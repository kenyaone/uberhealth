import { useEffect, useState, useRef } from 'react'
import { useParams, useNavigate, useLocation } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import api from '../../api/axios'
import type { Professional } from '../../types'
import { Shield, Phone, CheckCircle, Loader2, Clock, Video } from 'lucide-react'

type Step = 'details' | 'paying' | 'polling' | 'success' | 'failed' | 'payment_retry'

interface BookForm {
  scheduled_at: string
  duration_minutes: number
  share_assessments: boolean
  share_mood_logs: boolean
  phone: string
}

export default function BookConsultation() {
  const { professionalId } = useParams()
  const navigate = useNavigate()
  const location = useLocation()
  const followUpState = (location.state as any)
  const [pro, setPro] = useState<Professional | null>(null)
  const [step, setStep] = useState<Step>('details')
  const [consultationId, setConsultationId] = useState<string | null>(null)
  const [numericId, setNumericId] = useState<number | null>(null)
  const [amount, setAmount] = useState(0)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const pollRef = useRef<ReturnType<typeof setInterval> | null>(null)
  const pollCount = useRef(0)

  const [retryPhone, setRetryPhone] = useState('')
  const [retryLoading, setRetryLoading] = useState(false)

  const { register, handleSubmit, watch, formState: { errors } } = useForm<BookForm>({
    defaultValues: { duration_minutes: 60, share_assessments: false, share_mood_logs: false }
  })
  const duration = watch('duration_minutes')

  useEffect(() => {
    api.get(`/professionals/${professionalId}`).then(r => setPro(r.data.professional ?? r.data))
    return () => { if (pollRef.current) clearInterval(pollRef.current) }
  }, [professionalId])

  const computedAmount = pro ? Number(pro.rate_per_hour) * (Number(duration) / 60) : 0

  const onSubmit = async (data: BookForm) => {
    setLoading(true)
    setError('')
    try {
      // Step 1: Create consultation (or follow-up)
      const endpoint = followUpState?.is_follow_up && followUpState?.parent_id
        ? `/consultations/${followUpState.parent_id}/follow-up`
        : '/consultations'

      const bookRes = await api.post(endpoint, {
        professional_id: Number(professionalId),
        scheduled_at: data.scheduled_at,
        duration_minutes: Number(data.duration_minutes),
        share_assessments: data.share_assessments,
        share_mood_logs: data.share_mood_logs,
      })
      const c = bookRes.data.consultation
      setConsultationId(c.consultation_id)
      setNumericId(c.id)
      setAmount(c.amount)
      setStep('paying')

      // Step 2: Initiate M-Pesa STK Push
      try {
        await api.post('/payments/initiate', {
          consultation_id: c.consultation_id,
          phone: data.phone,
        })
        setStep('polling')
        startPolling(c.id)
      } catch (payErr: any) {
        // Booking exists but payment failed — go to retry screen (not back to start)
        const msg = payErr.response?.data?.error || 'M-Pesa prompt could not be sent.'
        setError(msg)
        setStep('payment_retry')
      }
    } catch (err: any) {
      const msg = err.response?.data?.error || 'Booking failed. Please try again.'
      setError(msg)
      setStep('details')
    } finally {
      setLoading(false)
    }
  }

  const startPolling = (id: number) => {
    pollCount.current = 0
    pollRef.current = setInterval(async () => {
      pollCount.current += 1
      if (pollCount.current > 20) {
        clearInterval(pollRef.current!)
        setStep('failed')
        return
      }
      try {
        const r = await api.get(`/consultations/${id}`)
        const status = r.data.consultation?.status ?? r.data.status
        if (status === 'confirmed') {
          clearInterval(pollRef.current!)
          setStep('success')
        } else if (status === 'cancelled') {
          clearInterval(pollRef.current!)
          setStep('failed')
        }
      } catch {}
    }, 4000)
  }

  const retryPayment = async () => {
    if (!consultationId || !numericId) return
    setRetryLoading(true)
    setError('')
    try {
      await api.post('/payments/initiate', { consultation_id: consultationId, phone: retryPhone })
      setStep('polling')
      startPolling(numericId)
    } catch (err: any) {
      setError(err.response?.data?.error || 'M-Pesa prompt could not be sent.')
    } finally {
      setRetryLoading(false)
    }
  }

  if (!pro) return <div className="text-center py-10 text-gray-400">Loading…</div>

  if (step === 'payment_retry') {
    return (
      <div className="max-w-lg mx-auto mt-10 card space-y-5">
        <div className="text-center">
          <CheckCircle size={40} className="text-green-500 mx-auto mb-3" />
          <h2 className="text-xl font-bold text-gray-900 mb-1">Session Booked!</h2>
          <p className="text-gray-500 text-sm">Session ID: <strong>{consultationId}</strong></p>
          <p className="text-gray-500 text-sm mt-1">Now send the M-Pesa payment to confirm.</p>
        </div>
        {error && <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{error}</div>}
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number</label>
          <input
            value={retryPhone}
            onChange={e => setRetryPhone(e.target.value)}
            className="input-field"
            placeholder="0712345678"
          />
        </div>
        <div className="bg-gray-50 rounded-lg p-3 text-sm flex justify-between font-bold text-gray-900">
          <span>Total</span><span>KES {amount.toLocaleString()}</span>
        </div>
        <button
          onClick={retryPayment}
          disabled={!retryPhone || retryLoading}
          className="btn-primary w-full py-3 text-base"
        >
          {retryLoading ? <span className="flex items-center justify-center gap-2"><Loader2 size={16} className="animate-spin" />Sending…</span> : 'Send M-Pesa Prompt'}
        </button>
        <button onClick={() => navigate('/consultations')} className="btn-secondary w-full text-sm">
          View My Sessions
        </button>
      </div>
    )
  }

  if (step === 'polling') {
    return (
      <div className="max-w-lg mx-auto mt-16 card text-center py-12">
        <Loader2 size={48} className="text-primary-600 mx-auto mb-4 animate-spin" />
        <h2 className="text-xl font-bold text-gray-900 mb-2">Waiting for Payment</h2>
        <p className="text-gray-600 mb-1">An M-Pesa STK Push has been sent to your phone.</p>
        <p className="text-gray-500 text-sm">Enter your M-Pesa PIN to confirm payment of <strong>KES {amount.toLocaleString()}</strong>.</p>
        <p className="text-xs text-gray-400 mt-4">Checking status… ({pollCount.current * 4}s elapsed)</p>
      </div>
    )
  }

  if (step === 'success') {
    return (
      <div className="max-w-lg mx-auto mt-16 card text-center py-12">
        <CheckCircle size={56} className="text-green-500 mx-auto mb-4" />
        <h2 className="text-xl font-bold text-gray-900 mb-2">Booking Confirmed!</h2>
        <p className="text-gray-600 mb-1">Payment of <strong>KES {amount.toLocaleString()}</strong> received.</p>
        <p className="text-sm text-gray-500 mb-6">Session ID: <strong>{consultationId}</strong></p>
        <div className="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-6 text-sm text-primary-800 text-left">
          <div className="flex items-center gap-2 mb-1">
            <Video size={14} />
            <strong>How to join your session:</strong>
          </div>
          Go to <strong>My Sessions</strong> → click <strong>Join Session</strong> at the scheduled time. A private Jitsi video room will open.
        </div>
        <div className="flex gap-3">
          <button onClick={() => navigate('/consultations')} className="btn-primary flex-1">View My Sessions</button>
          <button onClick={() => navigate('/dashboard')} className="btn-secondary flex-1">Dashboard</button>
        </div>
      </div>
    )
  }

  if (step === 'failed') {
    return (
      <div className="max-w-lg mx-auto mt-16 card text-center py-10">
        <Clock size={48} className="text-yellow-500 mx-auto mb-4" />
        <h2 className="text-xl font-bold text-gray-900 mb-2">Payment Not Confirmed</h2>
        <p className="text-gray-600 mb-4">The M-Pesa payment was not confirmed in time. Your booking is pending — if you paid, it will confirm automatically.</p>
        <div className="flex gap-3">
          <button onClick={() => navigate('/consultations')} className="btn-primary flex-1">Check My Sessions</button>
          <button onClick={() => { setStep('details'); setError('') }} className="btn-secondary flex-1">Try Again</button>
        </div>
      </div>
    )
  }

  return (
    <div className="max-w-2xl mx-auto space-y-5">
      <h1 className="text-2xl font-bold text-gray-900">
        {followUpState?.is_follow_up ? 'Book a Follow-up Session' : 'Book a Session'}
      </h1>
      {followUpState?.is_follow_up && (
        <p className="text-sm text-primary-700 bg-primary-50 border border-primary-200 rounded-lg px-3 py-2">
          This is a follow-up to your previous session with this therapist.
        </p>
      )}

      {/* Professional card */}
      <div className="card flex items-center gap-4">
        <div className="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl flex-shrink-0">
          {pro.display_name?.charAt(0).toUpperCase()}
        </div>
        <div className="flex-1">
          <div className="font-semibold text-gray-900">{pro.display_name}</div>
          <div className="text-sm text-gray-500">{pro.specializations?.map(s => s.name).join(', ')}</div>
        </div>
        <div className="text-right">
          <div className="font-bold text-primary-700">KES {Number(pro.rate_per_hour).toLocaleString()}/hr</div>
          <div className="text-xs text-gray-400">{pro.years_experience} yrs exp</div>
        </div>
      </div>

      {error && (
        <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{error}</div>
      )}

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-5">
        {/* Session details */}
        <div className="card space-y-4">
          <h2 className="font-semibold text-gray-900">Session Details</h2>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
            <input
              {...register('scheduled_at', { required: 'Please select a date and time' })}
              type="datetime-local"
              min={new Date(Date.now() + 60 * 60 * 1000).toISOString().slice(0, 16)}
              className="input-field"
            />
            {errors.scheduled_at && <p className="text-red-500 text-xs mt-1">{errors.scheduled_at.message}</p>}
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Duration</label>
            <select {...register('duration_minutes', { valueAsNumber: true })} className="input-field">
              <option value={30}>30 minutes — KES {Math.round(Number(pro.rate_per_hour) * 0.5).toLocaleString()}</option>
              <option value={60}>60 minutes — KES {Number(pro.rate_per_hour).toLocaleString()} (recommended)</option>
              <option value={90}>90 minutes — KES {Math.round(Number(pro.rate_per_hour) * 1.5).toLocaleString()}</option>
            </select>
          </div>
        </div>

        {/* Privacy */}
        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">Privacy Settings</h2>
          {[
            { name: 'share_assessments', label: 'Share my assessment results with therapist', desc: 'Helps them understand your condition better' },
            { name: 'share_mood_logs', label: 'Share my mood logs with therapist', desc: 'Shows your recent emotional patterns' },
          ].map(({ name, label, desc }) => (
            <label key={name} className="flex items-start gap-3 cursor-pointer">
              <input {...register(name as keyof BookForm)} type="checkbox" className="mt-1 rounded border-gray-300 text-primary-600" />
              <div>
                <div className="text-sm font-medium text-gray-900">{label}</div>
                <div className="text-xs text-gray-500">{desc}</div>
              </div>
            </label>
          ))}
        </div>

        {/* Payment */}
        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">M-Pesa Payment</h2>
          <div className="flex items-start gap-2 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3">
            <Shield size={14} className="flex-shrink-0 mt-0.5 text-blue-600" />
            <span>Your phone number is used only to send the M-Pesa prompt and is not stored in your profile.</span>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              <Phone size={13} className="inline mr-1" />M-Pesa Phone Number
            </label>
            <input
              {...register('phone', {
                required: 'Phone number required for payment',
                pattern: { value: /^(0[17]\d{8}|2547\d{8}|2541\d{8})$/, message: 'Enter a valid Kenyan number e.g. 0712345678' }
              })}
              className="input-field"
              placeholder="0712345678"
            />
            {errors.phone && <p className="text-red-500 text-xs mt-1">{errors.phone.message}</p>}
          </div>
          <div className="bg-gray-50 rounded-lg p-3 space-y-1.5 text-sm">
            <div className="flex justify-between text-gray-600">
              <span>Session ({duration} min)</span>
              <span>KES {computedAmount.toLocaleString()}</span>
            </div>
            <div className="flex justify-between font-bold text-gray-900 border-t border-gray-200 pt-1.5">
              <span>Total to pay</span>
              <span>KES {computedAmount.toLocaleString()}</span>
            </div>
          </div>
        </div>

        <button type="submit" disabled={loading} className="btn-primary w-full py-3 text-base">
          {loading
            ? <span className="flex items-center justify-center gap-2"><Loader2 size={16} className="animate-spin" /> Processing…</span>
            : `Pay KES ${computedAmount.toLocaleString()} via M-Pesa`
          }
        </button>
      </form>
    </div>
  )
}
