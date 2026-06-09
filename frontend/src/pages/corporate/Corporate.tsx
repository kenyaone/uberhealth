import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'
import api from '../../api/axios'
import { Building2, CheckCircle, Users, BarChart3, Shield } from 'lucide-react'

interface EAPTier { id: number; name: string; min_employees: number; max_employees: number; price_kes_annual: string; sessions_per_employee: number; features: string[] }
interface FormData {
  company_name: string; contact_name: string; contact_email: string; contact_phone: string
  industry: string; employee_count: number; kra_pin: string; tier_id: number; phone: string
}

export default function Corporate() {
  const [tiers, setTiers] = useState<EAPTier[]>([])
  const [selectedTier, setSelectedTier] = useState<EAPTier | null>(null)
  const [submitted, setSubmitted] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const { register, handleSubmit, watch, setValue, formState: { errors } } = useForm<FormData>()
  const employeeCount = watch('employee_count')

  useEffect(() => {
    api.get('/corporate/tiers').then(r => { setTiers(r.data); setSelectedTier(r.data[0]) })
  }, [])

  useEffect(() => {
    if (employeeCount && tiers.length) {
      const matching = tiers.find(t => Number(employeeCount) >= t.min_employees && Number(employeeCount) <= t.max_employees)
      if (matching) { setSelectedTier(matching); setValue('tier_id', matching.id) }
    }
  }, [employeeCount, tiers])

  const onSubmit = async (data: FormData) => {
    if (!selectedTier) { setError('Please select a tier.'); return }
    setLoading(true)
    setError('')
    try {
      await api.post('/corporate/apply', { ...data, tier_id: selectedTier.id })
      setSubmitted(true)
    } catch (err: any) {
      setError(err.response?.data?.error || 'Submission failed. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  if (submitted) return (
    <div className="max-w-lg mx-auto card text-center py-14 mt-8">
      <Building2 size={52} className="text-blue-500 mx-auto mb-4" />
      <h2 className="text-2xl font-bold text-gray-900 mb-3">Application Received!</h2>
      <p className="text-gray-600 mb-2">Check your phone for the M-Pesa payment request.</p>
      <p className="text-gray-500 text-sm mb-6">Once payment is confirmed, your company account will be activated and your HR contact will receive onboarding instructions.</p>
      <div className="bg-blue-50 rounded-lg p-4 text-sm text-blue-800 text-left space-y-1">
        <div>✅ Account activated within 1 business day</div>
        <div>✅ Employee access codes sent to HR contact</div>
        <div>✅ Onboarding call scheduled</div>
      </div>
    </div>
  )

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Corporate EAP Packages</h1>
        <p className="text-gray-500 mt-2">Give your employees private, professional mental health support. Improve retention, reduce burnout, boost productivity.</p>
      </div>

      {/* Why EAP */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {[
          { icon: Users, title: 'Anonymous Access', desc: 'Employees access support privately — HR only sees aggregate stats, never individual data.' },
          { icon: BarChart3, title: 'HR Dashboard', desc: 'Track utilisation, trending issues, and ROI. Monthly reports delivered automatically.' },
          { icon: Shield, title: 'KMPDC Verified', desc: 'All therapists are licensed professionals. Sessions are encrypted end-to-end.' },
        ].map(({ icon: Icon, title, desc }) => (
          <div key={title} className="card text-center">
            <Icon size={28} className="text-blue-500 mx-auto mb-2" />
            <div className="font-semibold text-gray-900 mb-1">{title}</div>
            <p className="text-xs text-gray-500">{desc}</p>
          </div>
        ))}
      </div>

      {/* Tier selector */}
      <div>
        <h2 className="font-semibold text-gray-900 mb-3">Select Your Package</h2>
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          {tiers.map(tier => (
            <button
              key={tier.id}
              onClick={() => { setSelectedTier(tier); setValue('tier_id', tier.id) }}
              className={`card text-left border-2 transition-all ${selectedTier?.id === tier.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'}`}
            >
              <div className="font-semibold text-gray-900">{tier.name}</div>
              <div className="text-xs text-gray-500 mb-2">{tier.min_employees}–{tier.max_employees} employees</div>
              <div className="text-xl font-bold text-blue-700">KES {Number(tier.price_kes_annual).toLocaleString()}<span className="text-sm text-gray-400">/yr</span></div>
              <div className="text-xs text-gray-500 mt-1">{tier.sessions_per_employee} sessions per employee</div>
              {selectedTier?.id === tier.id && <CheckCircle size={16} className="text-blue-500 mt-2" />}
            </button>
          ))}
        </div>
      </div>

      {error && <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{error}</div>}

      {/* Application form */}
      <form onSubmit={handleSubmit(onSubmit)} className="card space-y-5">
        <h2 className="font-semibold text-gray-900 text-lg">Company Details</h2>

        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Company Name <span className="text-red-500">*</span></label>
            <input {...register('company_name', { required: true })} className="input-field" placeholder="Acme Kenya Ltd" />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Industry</label>
            <input {...register('industry')} className="input-field" placeholder="Banking, Tech, Healthcare..." />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Number of Employees <span className="text-red-500">*</span></label>
            <input {...register('employee_count', { required: true, valueAsNumber: true, min: 5 })} type="number" min={5} className="input-field" placeholder="50" />
            {selectedTier && <p className="text-xs text-blue-600 mt-1">→ Recommended: {selectedTier.name} package</p>}
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">KRA PIN (optional)</label>
            <input {...register('kra_pin')} className="input-field" placeholder="P051234567A" />
          </div>
        </div>

        <h2 className="font-semibold text-gray-900">HR Contact</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Contact Name <span className="text-red-500">*</span></label>
            <input {...register('contact_name', { required: true })} className="input-field" placeholder="Jane Wanjiku" />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Contact Email <span className="text-red-500">*</span></label>
            <input {...register('contact_email', { required: true })} type="email" className="input-field" placeholder="hr@company.co.ke" />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Contact Phone <span className="text-red-500">*</span></label>
            <input {...register('contact_phone', { required: true })} className="input-field" placeholder="0712345678" />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">M-Pesa Payment Number <span className="text-red-500">*</span></label>
            <input {...register('phone', { required: true })} className="input-field" placeholder="0712345678" />
          </div>
        </div>

        {selectedTier && (
          <div className="bg-blue-50 rounded-xl p-4">
            <div className="font-medium text-blue-900 mb-2">Order Summary</div>
            <div className="flex justify-between text-sm text-blue-800">
              <span>{selectedTier.name} EAP Package (1 year)</span>
              <span className="font-bold">KES {Number(selectedTier.price_kes_annual).toLocaleString()}</span>
            </div>
            <div className="text-xs text-blue-600 mt-1">{watch('employee_count') || selectedTier.max_employees} employees × {selectedTier.sessions_per_employee} sessions each</div>
          </div>
        )}

        <input type="hidden" {...register('tier_id')} value={selectedTier?.id} />

        <button type="submit" disabled={loading || !selectedTier} className="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors disabled:opacity-50">
          {loading ? 'Submitting...' : `Apply & Pay KES ${selectedTier ? Number(selectedTier.price_kes_annual).toLocaleString() : '—'}`}
        </button>
      </form>
    </div>
  )
}
