import { useState } from 'react'
import { useNavigate, useSearchParams } from 'react-router-dom'
import { ArrowLeft, Phone, Send } from 'lucide-react'
import api from '../../api/axios'

export default function ParentalConsent() {
  const navigate = useNavigate()
  const [searchParams] = useSearchParams()
  const consultationId = searchParams.get('consultation_id')

  const [step, setStep] = useState<'request' | 'verify'>('request')
  const [guardianPhone, setGuardianPhone] = useState('')
  const [otp, setOtp] = useState('')
  const [loading, setLoading] = useState(false)
  const [message, setMessage] = useState('')

  const handleRequestOTP = async () => {
    setLoading(true)
    setMessage('')
    try {
      await api.post('/parental-consent/request-otp', {
        guardian_phone: guardianPhone,
        consultation_id: consultationId,
      })
      setMessage('OTP sent to guardian phone')
      setStep('verify')
    } catch (error: any) {
      setMessage(error.response?.data?.message || 'Failed to send OTP')
    } finally {
      setLoading(false)
    }
  }

  const handleVerifyOTP = async () => {
    setLoading(true)
    setMessage('')
    try {
      await api.post('/parental-consent/verify-otp', {
        guardian_phone: guardianPhone,
        otp,
        consultation_id: consultationId,
      })
      // Consent verified, redirect to booking confirmation
      navigate('/consultations')
    } catch (error: any) {
      setMessage(error.response?.data?.message || 'Invalid OTP')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div className="max-w-2xl mx-auto px-5 py-4 flex items-center gap-3">
          <button onClick={() => navigate(-1)} className="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium">
            <ArrowLeft size={18} />
          </button>
          <h1 className="text-lg font-bold text-gray-900">Guardian Consent Required</h1>
        </div>
      </div>

      <div className="max-w-2xl mx-auto px-5 py-8">
        <div className="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6">
          <div className="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 className="text-lg font-bold text-blue-900 mb-2">Parent/Guardian Verification Required</h2>
            <p className="text-blue-800 text-sm leading-relaxed">
              Since you're under 18, we need your parent or guardian to verify this appointment. They will receive an SMS with a 4-digit code.
            </p>
          </div>

          {step === 'request' ? (
            <>
              <div className="mb-6">
                <h3 className="text-lg font-bold text-gray-900 mb-4">Guardian's Phone Number</h3>
                <p className="text-gray-500 text-sm mb-4">Enter the phone number of your parent or guardian. They'll receive an OTP via SMS.</p>

                <input
                  type="tel"
                  placeholder="+254 712 345 678"
                  value={guardianPhone}
                  onChange={(e) => setGuardianPhone(e.target.value)}
                  className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
                />
              </div>

              {message && (
                <div className={`p-4 rounded-lg mb-6 text-sm ${message.includes('sent') ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'}`}>
                  {message}
                </div>
              )}

              <button
                onClick={handleRequestOTP}
                disabled={!guardianPhone || loading}
                className="w-full py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
              >
                <Phone size={18} />
                {loading ? 'Sending OTP...' : 'Send OTP to Guardian'}
              </button>
            </>
          ) : (
            <>
              <div className="mb-6">
                <h3 className="text-lg font-bold text-gray-900 mb-2">Enter OTP</h3>
                <p className="text-gray-500 text-sm mb-4">Your guardian should have received a 4-digit code. Ask them to share it with you.</p>

                <div className="flex gap-2 mb-4">
                  {[0, 1, 2, 3].map((_, i) => (
                    <input
                      key={i}
                      type="text"
                      maxLength={1}
                      value={otp[i] || ''}
                      onChange={(e) => {
                        const newOtp = otp.split('')
                        newOtp[i] = e.target.value.replace(/\D/g, '')
                        setOtp(newOtp.join(''))
                        if (e.target.value && i < 3) {
                          (e.target.nextElementSibling as HTMLInputElement)?.focus()
                        }
                      }}
                      className="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    />
                  ))}
                </div>

                <button
                  onClick={() => setStep('request')}
                  className="text-blue-600 text-sm font-semibold hover:underline"
                >
                  Change guardian phone
                </button>
              </div>

              {message && (
                <div className={`p-4 rounded-lg mb-6 text-sm ${message.includes('Invalid') ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`}>
                  {message}
                </div>
              )}

              <button
                onClick={handleVerifyOTP}
                disabled={otp.length !== 4 || loading}
                className="w-full py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2"
              >
                <Send size={18} />
                {loading ? 'Verifying...' : 'Verify OTP & Continue'}
              </button>
            </>
          )}
        </div>

        <div className="bg-gray-50 rounded-xl p-6 text-sm text-gray-600">
          <p className="font-semibold text-gray-900 mb-2">Why we need this:</p>
          <ul className="space-y-1 text-xs">
            <li>✓ Legal requirement for minors</li>
            <li>✓ Ensures parental awareness</li>
            <li>✓ Protects your privacy & safety</li>
          </ul>
        </div>
      </div>
    </div>
  )
}
