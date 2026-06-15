import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { ArrowLeft, ArrowRight, CheckCircle, Clock, Video, MapPin, Shield, AlertCircle, CreditCard, Heart, Banknote } from 'lucide-react'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import type { Professional } from '../../types'
import ConsentForm from '../../components/ConsentForm'
import SuitabilityAssessment from '../../components/SuitabilityAssessment'
import EmergencyContacts from '../../components/EmergencyContacts'

type BookStep = 'mode' | 'consent' | 'fee' | 'confirm' | 'processing' | 'success'
type SessionMode = 'virtual' | 'physical'
type PayMethod = 'paystack' | 'pesapal' | 'insurance' | 'cash'

interface BookingData {
  mode: SessionMode
  scheduled_at: string
  duration_minutes: number
  share_assessments: boolean
  is_user_minor?: boolean
}

export default function BookConsultation() {
  const user = useAuthStore(s => s.user)
  const { professionalId } = useParams()
  const navigate = useNavigate()

  const [pro, setPro] = useState<Professional | null>(null)
  const [step, setStep] = useState<BookStep>('mode')
  const [booking, setBooking] = useState<BookingData>({
    mode: 'virtual',
    scheduled_at: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString().slice(0, 16),
    duration_minutes: 60,
    share_assessments: false,
  })
  const [consentOpen, setConsentOpen] = useState(false)
  const [showSuitabilityAssessment, setShowSuitabilityAssessment] = useState(true)
  const [showEmergencyContacts, setShowEmergencyContacts] = useState(false)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const [consultationId, setConsultationId] = useState<string | null>(null)
  const [payMethod, setPayMethod] = useState<PayMethod>('pesapal')
  const BOOKING_FEE = 500 // KES

  useEffect(() => {
    api.get(`/professionals/${professionalId}`)
      .then(r => setPro(r.data.professional ?? r.data))
      .catch(() => navigate('/professionals'))
  }, [professionalId, navigate])

  const handleNext = () => {
    console.log('[DEBUG] handleNext: current step =', step)
    if (step === 'mode') {
      // Validate mode is selected
      if (!booking.mode) {
        setError('Please select a session mode')
        return
      }
      setStep('consent')
    } else if (step === 'consent') {
      setStep('fee')
    } else if (step === 'fee') {
      console.log('[DEBUG] Setting step to confirm')
      setStep('confirm')
    }
  }

  const handleConsent = (consented: boolean) => {
    if (consented) {
      setStep('fee')
    }
  }

  const handleConfirm = async () => {
    setLoading(true)
    setError('')
    try {
      // Create consultation with all details
      const res = await api.post('/consultations', {
        professional_id: Number(professionalId),
        mode: booking.mode,
        scheduled_at: new Date(booking.scheduled_at).toISOString(),
        duration_minutes: booking.duration_minutes,
        share_assessments: booking.share_assessments,
        share_mood_logs: booking.share_assessments,
        consent_accepted: true,
        payment_method: payMethod,
      })

      const consultationId = res.data.consultation_id
      setConsultationId(consultationId)
      setStep('processing')

      // Handle payment based on selected method
      if (payMethod === 'pesapal') {
        // Initiate PesaPal payment
        const payRes = await api.post('/payments/pesapal/initiate', {
          consultation_id: consultationId,
          phone: (user as any)?.phone_number,
          email: (user as any)?.email,
        })
        if (payRes.data.redirect_url) {
          window.location.href = payRes.data.redirect_url
        }
      } else if (payMethod === 'paystack') {
        // Initiate Paystack payment
        const payRes = await api.post('/payments/paystack/initialize', {
          consultation_id: consultationId,
        })
        const { access_code } = payRes.data
        if (!(window as any).PaystackPop) {
          await new Promise<void>((resolve) => {
            const s = document.createElement('script')
            s.src = 'https://js.paystack.co/v1/inline.js'
            s.onload = () => resolve()
            document.head.appendChild(s)
          })
        }
        const handler = (window as any).PaystackPop.setup({
          key: import.meta.env.VITE_PAYSTACK_PUBLIC_KEY || 'pk_test_placeholder',
          email: (user as any)?.email || `user@uberhealth.co.ke`,
          access_code,
          ref: `ps-${consultationId}-${Date.now()}`,
          currency: 'KES',
          callback: async (response: { reference: string }) => {
            try {
              await api.post('/payments/paystack/verify', { reference: response.reference, consultation_id: consultationId })
              setStep('success')
            } catch {
              setError('Payment verification failed')
              setStep('confirm')
            }
          },
          onClose: () => {
            setStep('confirm')
          },
        })
        handler.openIframe()
      } else if (payMethod === 'cash') {
        // Payment at session - just mark as success
        setTimeout(() => setStep('success'), 1500)
      } else if (payMethod === 'insurance') {
        // Insurance flow - just mark as pending
        setTimeout(() => setStep('success'), 1500)
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Booking failed. Please try again.')
      setStep('confirm')
    } finally {
      setLoading(false)
    }
  }

  if (!pro) {
    return <div className="flex items-center justify-center min-h-screen text-gray-500">Loading professional info...</div>
  }

  const rate = Number(pro.rate_per_hour || 0)
  const sessionCost = rate * (booking.duration_minutes / 60)
  const totalCost = sessionCost + BOOKING_FEE

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div className="max-w-2xl mx-auto px-5 py-4 flex items-center justify-between">
          <button onClick={() => navigate(-1)} className="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <ArrowLeft size={18} />
          </button>
          <h1 className="text-lg font-bold text-gray-900">Book Session with {pro.display_name}</h1>
          <div className="w-16" />
        </div>
      </div>

      <div className="max-w-2xl mx-auto px-5 py-8">
        {/* Progress */}
        <div className="flex items-center gap-2 mb-8">
          {(['mode', 'consent', 'fee', 'confirm'] as const).map((s, i) => (
            <div key={s} className="flex items-center">
              <div className={`w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs ${
                (['mode', 'consent', 'fee', 'confirm'].indexOf(step) >= i)
                  ? 'bg-teal-600 text-white'
                  : 'bg-gray-200 text-gray-600'
              }`}>
                {i + 1}
              </div>
              {i < 3 && <div className={`h-1 flex-1 mx-2 ${
                (['mode', 'consent', 'fee', 'confirm'].indexOf(step) > i) ? 'bg-teal-600' : 'bg-gray-200'
              }`} />}
            </div>
          ))}
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex gap-3">
            <AlertCircle size={18} className="text-red-600 flex-shrink-0 mt-0.5" />
            <p className="text-red-700 text-sm">{error}</p>
          </div>
        )}

        {/* Step 1: Mode Selection */}
        {step === 'mode' && (
          <div className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">How would you like to connect?</h2>
            <div className="space-y-4">
              <button
                onClick={() => setBooking(prev => ({ ...prev, mode: 'virtual' }))}
                className={`w-full p-6 border-2 rounded-xl text-left transition-all ${
                  booking.mode === 'virtual'
                    ? 'border-teal-600 bg-teal-50'
                    : 'border-gray-200 bg-white hover:border-gray-300'
                }`}
              >
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 flex-shrink-0">
                    <Video size={24} />
                  </div>
                  <div className="flex-1">
                    <h3 className="font-bold text-gray-900 mb-1">Video Call</h3>
                    <p className="text-sm text-gray-500">Private encrypted video session</p>
                  </div>
                  {booking.mode === 'virtual' && <CheckCircle size={24} className="text-teal-600 flex-shrink-0" />}
                </div>
              </button>

              <button
                onClick={() => setBooking(prev => ({ ...prev, mode: 'physical' }))}
                className={`w-full p-6 border-2 rounded-xl text-left transition-all ${
                  booking.mode === 'physical'
                    ? 'border-teal-600 bg-teal-50'
                    : 'border-gray-200 bg-white hover:border-gray-300'
                }`}
              >
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                    <MapPin size={24} />
                  </div>
                  <div className="flex-1">
                    <h3 className="font-bold text-gray-900 mb-1">In-Person</h3>
                    <p className="text-sm text-gray-500">Meet with therapist face-to-face</p>
                  </div>
                  {booking.mode === 'physical' && <CheckCircle size={24} className="text-teal-600 flex-shrink-0" />}
                </div>
              </button>
            </div>

            <div className="mt-8 flex gap-3">
              <button
                onClick={() => navigate(-1)}
                className="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                onClick={handleNext}
                className="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2"
              >
                Continue <ArrowRight size={18} />
              </button>
            </div>
          </div>
        )}

        {/* Step 2: Consent */}
        {step === 'consent' && (
          <div className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6">
            <h2 className="text-2xl font-bold text-gray-900 mb-4">Session Consent</h2>
            <p className="text-gray-600 mb-6">Before we proceed, please review and agree to our session terms.</p>

            <div className="mb-8">
              <button
                onClick={() => setConsentOpen(true)}
                className="w-full flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50"
              >
                <div className="text-left">
                  <p className="font-semibold text-gray-900">Review Session Terms</p>
                  <p className="text-sm text-gray-500">Privacy, confidentiality, and your rights</p>
                </div>
                <ArrowRight size={18} className="text-gray-400" />
              </button>
            </div>

            <div className="mt-8 flex gap-3">
              <button
                onClick={() => setStep('mode')}
                className="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50"
              >
                Back
              </button>
              <button
                onClick={handleNext}
                className="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2"
              >
                I Agree & Continue <ArrowRight size={18} />
              </button>
            </div>
          </div>
        )}

        {/* Step 3: Booking Fee */}
        {step === 'fee' && (
          <div className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">Booking Summary</h2>

            <div className="space-y-4 mb-8 pb-8 border-b border-gray-200">
              <div className="flex justify-between">
                <span className="text-gray-600">Professional:</span>
                <span className="font-semibold text-gray-900">{pro.display_name}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Session Type:</span>
                <span className="font-semibold text-gray-900">{booking.mode === 'virtual' ? 'Video Call' : 'In-Person'}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Session Time:</span>
                <span className="font-semibold text-gray-900">{new Date(booking.scheduled_at).toLocaleString()}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Duration:</span>
                <span className="font-semibold text-gray-900">{booking.duration_minutes} minutes</span>
              </div>
            </div>

            <div className="space-y-3 mb-8 bg-blue-50 rounded-lg p-6 border border-blue-100">
              <div className="flex justify-between items-center">
                <span className="text-gray-700">Session Rate:</span>
                <span className="font-semibold">KES {sessionCost.toFixed(2)}</span>
              </div>
              <div className="flex justify-between items-center">
                <span className="text-gray-700">Booking Fee:</span>
                <span className="font-semibold">KES {BOOKING_FEE.toFixed(2)}</span>
              </div>
              <div className="border-t border-blue-200 pt-3 flex justify-between items-center text-lg">
                <span className="font-bold text-gray-900">Total Amount:</span>
                <span className="font-black text-teal-600">KES {totalCost.toFixed(2)}</span>
              </div>
            </div>

            <div className="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8 flex gap-3">
              <AlertCircle size={18} className="text-amber-600 flex-shrink-0 mt-0.5" />
              <p className="text-sm text-amber-800">
                The <strong>KES 500 booking fee is deducted from your final session cost.</strong> You'll only pay the difference if the session rate exceeds this amount.
              </p>
            </div>

            <div className="mb-8">
              <h3 className="font-semibold text-gray-900 mb-4">How will you pay?</h3>
              <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                <button
                  type="button"
                  onClick={() => setPayMethod('paystack')}
                  className={`flex items-center gap-2 p-3 rounded-lg border-2 transition-all text-left text-sm ${
                    payMethod === 'paystack'
                      ? 'border-blue-500 bg-blue-50'
                      : 'border-gray-200 bg-white hover:border-gray-300'
                  }`}
                >
                  <div className={`w-6 h-6 rounded-lg flex items-center justify-center ${payMethod === 'paystack' ? 'bg-blue-600' : 'bg-gray-100'}`}>
                    <CreditCard size={14} className={payMethod === 'paystack' ? 'text-white' : 'text-gray-500'} />
                  </div>
                  <span className={`font-semibold ${payMethod === 'paystack' ? 'text-blue-900' : 'text-gray-700'}`}>Paystack</span>
                </button>

                <button
                  type="button"
                  onClick={() => setPayMethod('pesapal')}
                  className={`flex items-center gap-2 p-3 rounded-lg border-2 transition-all text-left text-sm ${
                    payMethod === 'pesapal'
                      ? 'border-purple-500 bg-purple-50'
                      : 'border-gray-200 bg-white hover:border-gray-300'
                  }`}
                >
                  <div className={`w-6 h-6 rounded-lg flex items-center justify-center ${payMethod === 'pesapal' ? 'bg-purple-600' : 'bg-gray-100'}`}>
                    <Banknote size={14} className={payMethod === 'pesapal' ? 'text-white' : 'text-gray-500'} />
                  </div>
                  <span className={`font-semibold ${payMethod === 'pesapal' ? 'text-purple-900' : 'text-gray-700'}`}>PesaPal</span>
                </button>

                <button
                  type="button"
                  onClick={() => setPayMethod('insurance')}
                  className={`flex items-center gap-2 p-3 rounded-lg border-2 transition-all text-left text-sm ${
                    payMethod === 'insurance'
                      ? 'border-teal-500 bg-teal-50'
                      : 'border-gray-200 bg-white hover:border-gray-300'
                  }`}
                >
                  <div className={`w-6 h-6 rounded-lg flex items-center justify-center ${payMethod === 'insurance' ? 'bg-teal-600' : 'bg-gray-100'}`}>
                    <Heart size={14} className={payMethod === 'insurance' ? 'text-white' : 'text-gray-500'} />
                  </div>
                  <span className={`font-semibold ${payMethod === 'insurance' ? 'text-teal-900' : 'text-gray-700'}`}>Insurance</span>
                </button>

                <button
                  type="button"
                  onClick={() => setPayMethod('cash')}
                  className={`flex items-center gap-2 p-3 rounded-lg border-2 transition-all text-left text-sm ${
                    payMethod === 'cash'
                      ? 'border-green-500 bg-green-50'
                      : 'border-gray-200 bg-white hover:border-gray-300'
                  }`}
                >
                  <div className={`w-6 h-6 rounded-lg flex items-center justify-center ${payMethod === 'cash' ? 'bg-green-600' : 'bg-gray-100'}`}>
                    <Banknote size={14} className={payMethod === 'cash' ? 'text-white' : 'text-gray-500'} />
                  </div>
                  <span className={`font-semibold ${payMethod === 'cash' ? 'text-green-900' : 'text-gray-700'}`}>At Session</span>
                </button>
              </div>
            </div>

            <div className="mt-8 flex gap-3">
              <button
                onClick={() => setStep('consent')}
                className="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50"
              >
                Back
              </button>
              <button
                onClick={handleNext}
                className="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2"
              >
                Continue to Confirmation <ArrowRight size={18} />
              </button>
            </div>
          </div>
        )}

        {/* Step 4: Confirm & Book */}
        {(step === 'confirm' || step === 'processing') && (
          <div className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">Confirm Booking</h2>

            <div className="bg-teal-50 border border-teal-200 rounded-lg p-6 mb-8">
              <h3 className="font-bold text-teal-900 mb-4">Ready to book?</h3>
              <ul className="space-y-2 text-sm text-teal-800">
                <li className="flex gap-2">
                  <CheckCircle size={16} className="text-teal-600 flex-shrink-0 mt-0.5" />
                  Session with {pro.display_name}
                </li>
                <li className="flex gap-2">
                  <CheckCircle size={16} className="text-teal-600 flex-shrink-0 mt-0.5" />
                  {booking.mode === 'virtual' ? 'Encrypted video call' : 'In-person appointment'}
                </li>
                <li className="flex gap-2">
                  <CheckCircle size={16} className="text-teal-600 flex-shrink-0 mt-0.5" />
                  Total: KES {totalCost.toFixed(2)}
                </li>
              </ul>
            </div>

            <div className="mt-8 flex gap-3">
              <button
                onClick={() => setStep('fee')}
                disabled={step === 'processing'}
                className="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 disabled:opacity-50"
              >
                Back
              </button>
              <button
                onClick={handleConfirm}
                disabled={loading || step === 'processing'}
                className="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 text-white font-semibold rounded-lg flex items-center justify-center gap-2"
              >
                {step === 'processing' ? 'Processing...' : 'Confirm & Book'}
              </button>
            </div>
          </div>
        )}

        {/* Success State */}
        {step === 'success' && (
          <div className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6 text-center">
            <div className="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
              <CheckCircle size={32} className="text-green-600" />
            </div>
            <h2 className="text-2xl font-bold text-gray-900 mb-2">Booking Confirmed!</h2>
            <p className="text-gray-600 mb-6">Your session has been scheduled. Check your email for details.</p>
            <button
              onClick={() => navigate('/consultations')}
              className="px-8 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg"
            >
              View My Sessions
            </button>
          </div>
        )}
      </div>

      <ConsentForm
        isOpen={consentOpen}
        onClose={() => setConsentOpen(false)}
        onConsent={handleConsent}
        title="Session Consent & Terms"
        description="Please review our session policies, your rights, and privacy protections."
        type={booking.mode}
      />

      <SuitabilityAssessment
        isOpen={showSuitabilityAssessment}
        onComplete={(suitable) => {
          setShowSuitabilityAssessment(false)
          if (!suitable) {
            navigate(-1)
          }
        }}
        onEmergency={() => {
          setShowSuitabilityAssessment(false)
          setShowEmergencyContacts(true)
        }}
      />

      <EmergencyContacts
        isOpen={showEmergencyContacts}
        onClose={() => setShowEmergencyContacts(false)}
      />
    </div>
  )
}
