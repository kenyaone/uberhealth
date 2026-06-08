import { useState } from 'react'
import { useLocation, useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import api from '../../api/axios'
import { CheckCircle, Phone, Shield } from 'lucide-react'

export default function Subscribe() {
  const location = useLocation()
  const navigate = useNavigate()
  const plan = location.state?.plan
  const [success, setSuccess] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const { register, handleSubmit, formState: { errors } } = useForm<{ phone: string }>()

  if (!plan) return (
    <div className="text-center py-10">
      <p className="text-gray-500 mb-4">No plan selected.</p>
      <button onClick={() => navigate('/pricing')} className="btn-primary">View Plans</button>
    </div>
  )

  const onSubmit = async ({ phone }: { phone: string }) => {
    setLoading(true)
    setError('')
    try {
      await api.post('/subscriptions/subscribe/', { plan_id: plan.id, phone })
      setSuccess(true)
    } catch (err: any) {
      setError(err.response?.data?.error || 'Payment failed. Try again.')
    } finally {
      setLoading(false)
    }
  }

  if (success) return (
    <div className="max-w-md mx-auto card text-center py-12 mt-8">
      <CheckCircle size={52} className="text-green-500 mx-auto mb-4" />
      <h2 className="text-xl font-bold text-gray-900 mb-2">Check Your Phone!</h2>
      <p className="text-gray-600 text-sm mb-6">
        An M-Pesa payment request for <strong>KES {Number(plan.price_kes).toLocaleString()}</strong> has been sent.
        Enter your PIN to activate <strong>{plan.name}</strong>.
      </p>
      <button onClick={() => navigate('/dashboard')} className="btn-primary w-full">Go to Dashboard</button>
    </div>
  )

  return (
    <div className="max-w-md mx-auto mt-8 space-y-5">
      <h1 className="text-2xl font-bold text-gray-900">Upgrade to {plan.name}</h1>

      <div className="card border-2 border-primary-300 bg-primary-50">
        <div className="flex justify-between items-center">
          <div>
            <div className="font-semibold text-primary-900">{plan.name}</div>
            <div className="text-xs text-primary-600">{plan.interval === 'monthly' ? 'Billed monthly' : 'Billed annually'}</div>
          </div>
          <div className="text-2xl font-bold text-primary-700">
            KES {Number(plan.price_kes).toLocaleString()}
          </div>
        </div>
        <ul className="mt-3 space-y-1">
          {plan.features.slice(0, 4).map((f: string) => (
            <li key={f} className="text-xs text-primary-700 flex items-center gap-1.5">
              <CheckCircle size={12} /> {f}
            </li>
          ))}
        </ul>
      </div>

      {error && <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{error}</div>}

      <form onSubmit={handleSubmit(onSubmit)} className="card space-y-4">
        <h2 className="font-semibold text-gray-900">Pay via M-Pesa</h2>
        <div className="flex items-start gap-2 text-xs text-gray-500">
          <Shield size={14} className="flex-shrink-0 mt-0.5 text-primary-600" />
          Your phone number is only used for this payment and deleted immediately after.
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">
            <Phone size={14} className="inline mr-1" /> M-Pesa Number
          </label>
          <input
            {...register('phone', { required: 'Phone required', pattern: { value: /^0\d{9}$/, message: 'Enter valid Kenyan number' } })}
            className="input-field"
            placeholder="0712345678"
          />
          {errors.phone && <p className="text-red-500 text-xs mt-1">{errors.phone.message}</p>}
        </div>
        <button type="submit" disabled={loading} className="btn-primary w-full py-3">
          {loading ? 'Sending payment request...' : `Pay KES ${Number(plan.price_kes).toLocaleString()} via M-Pesa`}
        </button>
      </form>

      <button onClick={() => navigate('/pricing')} className="text-sm text-gray-400 hover:text-gray-600 w-full text-center">
        ← Back to pricing
      </button>
    </div>
  )
}
