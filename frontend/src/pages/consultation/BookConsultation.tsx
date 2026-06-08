import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import api from '../../api/axios'
import type { Professional } from '../../types'
import { Shield, Phone, CheckCircle } from 'lucide-react'

interface BookForm {
  scheduled_at: string
  duration_minutes: number
  share_assessments: boolean
  share_mood_logs: boolean
  recording_enabled: boolean
  phone: string
}

export default function BookConsultation() {
  const { professionalId } = useParams()
  const navigate = useNavigate()
  const [pro, setPro] = useState<Professional | null>(null)
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState<any>(null)
  const [error, setError] = useState('')
  const { register, handleSubmit, formState: { errors } } = useForm<BookForm>({
    defaultValues: { duration_minutes: 60, share_assessments: false, share_mood_logs: false, recording_enabled: true }
  })

  useEffect(() => {
    api.get(`/professionals/${professionalId}/`).then(r => setPro(r.data))
  }, [professionalId])

  const onSubmit = async (data: BookForm) => {
    setLoading(true)
    setError('')
    try {
      const res = await api.post('/consultations/book/', {
        ...data,
        professional_id: Number(professionalId),
      })
      setSuccess(res.data)
    } catch (err: any) {
      setError(err.response?.data?.error || 'Booking failed. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  if (!pro) return <div className="text-center py-10 text-gray-400">Loading...</div>

  if (success) {
    return (
      <div className="max-w-lg mx-auto card text-center py-10">
        <CheckCircle size={48} className="text-green-500 mx-auto mb-4" />
        <h2 className="text-xl font-bold text-gray-900 mb-2">Booking Confirmed!</h2>
        <p className="text-gray-600 mb-4">
          Check your phone for an M-Pesa payment request for{' '}
          <strong>KES {success.payment.amount.toLocaleString()}</strong>.
        </p>
        <p className="text-sm text-gray-500 mb-6">
          Session ID: <strong>{success.consultation.consultation_id}</strong>
        </p>
        <div className="flex gap-3">
          <button onClick={() => navigate('/consultations')} className="btn-primary flex-1">
            View My Sessions
          </button>
          <button onClick={() => navigate('/dashboard')} className="btn-secondary flex-1">
            Dashboard
          </button>
        </div>
      </div>
    )
  }

  return (
    <div className="max-w-2xl mx-auto space-y-5">
      <h1 className="text-2xl font-bold text-gray-900">Book a Session</h1>

      <div className="card">
        <div className="flex items-center gap-4">
          <div className="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl">
            {pro.display_name.charAt(0)}
          </div>
          <div>
            <div className="font-semibold text-gray-900">{pro.display_name}</div>
            <div className="text-sm text-gray-500">{pro.specializations.map(s => s.name).join(', ')}</div>
          </div>
          <div className="ml-auto text-right">
            <div className="font-bold text-primary-700">KES {Number(pro.rate_per_hour).toLocaleString()}</div>
            <div className="text-xs text-gray-400">per hour</div>
          </div>
        </div>
      </div>

      {error && (
        <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{error}</div>
      )}

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-5">
        <div className="card space-y-4">
          <h2 className="font-semibold text-gray-900">Session Details</h2>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
            <input
              {...register('scheduled_at', { required: 'Please select a date and time' })}
              type="datetime-local"
              className="input-field"
            />
            {errors.scheduled_at && <p className="text-red-500 text-xs mt-1">{errors.scheduled_at.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Duration</label>
            <select {...register('duration_minutes')} className="input-field">
              <option value={30}>30 minutes</option>
              <option value={60}>60 minutes (recommended)</option>
              <option value={90}>90 minutes</option>
            </select>
          </div>
        </div>

        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">Privacy Settings</h2>

          {[
            { name: 'share_assessments', label: 'Share my assessment results with therapist', desc: 'Helps them understand your situation better' },
            { name: 'share_mood_logs', label: 'Share my mood logs with therapist', desc: 'Shows your recent emotional patterns' },
            { name: 'recording_enabled', label: 'Record this session (encrypted)', desc: 'Both you and the therapist get a copy' },
          ].map(({ name, label, desc }) => (
            <label key={name} className="flex items-start gap-3 cursor-pointer">
              <input
                {...register(name as keyof BookForm)}
                type="checkbox"
                className="mt-1 rounded border-gray-300 text-primary-600"
              />
              <div>
                <div className="text-sm font-medium text-gray-900">{label}</div>
                <div className="text-xs text-gray-500">{desc}</div>
              </div>
            </label>
          ))}
        </div>

        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">M-Pesa Payment</h2>
          <div className="flex items-start gap-2 text-sm text-gray-600">
            <Shield size={14} className="flex-shrink-0 mt-0.5 text-primary-600" />
            <span>Your phone number is only used for payment and is deleted immediately after.</span>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              <Phone size={14} className="inline mr-1" />
              M-Pesa Phone Number
            </label>
            <input
              {...register('phone', {
                required: 'Phone number required for payment',
                pattern: { value: /^(07|01|2547|2541)\d{8}$|^0\d{9}$/, message: 'Enter a valid Kenyan number (e.g. 0712345678)' }
              })}
              className="input-field"
              placeholder="0712345678"
            />
            {errors.phone && <p className="text-red-500 text-xs mt-1">{errors.phone.message}</p>}
          </div>
          <div className="bg-gray-50 rounded-lg p-3 flex justify-between text-sm">
            <span className="text-gray-600">Session fee</span>
            <span className="font-bold text-gray-900">KES {Number(pro.rate_per_hour).toLocaleString()}</span>
          </div>
        </div>

        <button type="submit" disabled={loading} className="btn-primary w-full py-3 text-base">
          {loading ? 'Processing...' : `Pay KES ${Number(pro.rate_per_hour).toLocaleString()} & Book`}
        </button>
      </form>
    </div>
  )
}
