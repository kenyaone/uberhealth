import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import { CheckCircle, Shield, Clock, Upload } from 'lucide-react'
import type { Specialization, Language } from '../../types'

interface ApplyForm {
  kmpdc_license: string
  bio: string
  years_experience: number
  gender: string
  rate_per_hour: number
  mpesa_number: string
  specialization_ids: number[]
  language_ids: number[]
}

export default function ApplyAsProfessional() {
  const navigate = useNavigate()
  const user = useAuthStore(s => s.user)
  const [specializations, setSpecializations] = useState<Specialization[]>([])
  const [languages, setLanguages] = useState<Language[]>([])
  const [selectedSpecs, setSelectedSpecs] = useState<number[]>([])
  const [selectedLangs, setSelectedLangs] = useState<number[]>([])
  const [submitting, setSubmitting] = useState(false)
  const [submitted, setSubmitted] = useState(false)
  const [error, setError] = useState('')

  const { register, handleSubmit, formState: { errors } } = useForm<ApplyForm>({
    defaultValues: { years_experience: 1, rate_per_hour: 2000, gender: 'other' }
  })

  useEffect(() => {
    api.get('/professionals/specializations/').then(r => setSpecializations(r.data))
    api.get('/professionals/languages/').then(r => setLanguages(r.data))
  }, [])

  const toggleSpec = (id: number) =>
    setSelectedSpecs(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id])

  const toggleLang = (id: number) =>
    setSelectedLangs(prev => prev.includes(id) ? prev.filter(x => x !== id) : [...prev, id])

  const onSubmit = async (data: ApplyForm) => {
    if (selectedSpecs.length === 0) { setError('Select at least one specialization.'); return }
    if (selectedLangs.length === 0) { setError('Select at least one language.'); return }
    setSubmitting(true)
    setError('')
    try {
      await api.post('/professionals/register/', {
        ...data,
        specialization_ids: selectedSpecs,
        language_ids: selectedLangs,
      })
      setSubmitted(true)
    } catch (err: any) {
      const d = err.response?.data
      setError(d?.kmpdc_license?.[0] || d?.error || 'Submission failed. Please try again.')
    } finally {
      setSubmitting(false)
    }
  }

  if (submitted) {
    return (
      <div className="max-w-lg mx-auto mt-16 card text-center py-12">
        <Clock size={56} className="text-accent-600 mx-auto mb-4" />
        <h2 className="text-2xl font-bold text-gray-900 mb-3">Application Submitted!</h2>
        <p className="text-gray-600 mb-2">
          Thank you, <strong>{user?.display_name}</strong>. Your application is under review.
        </p>
        <p className="text-gray-500 text-sm mb-6">
          Our team will verify your KMPDC license and contact you within <strong>24–48 hours</strong>.
          You will be notified once approved.
        </p>
        <div className="bg-primary-50 border border-primary-200 rounded-lg p-4 text-sm text-primary-800 mb-6 text-left">
          <div className="font-medium mb-2">What happens next:</div>
          <div className="space-y-1.5">
            <div>✅ We verify your KMPDC license number</div>
            <div>✅ Admin reviews your profile & specializations</div>
            <div>✅ You receive approval notification</div>
            <div>✅ Your profile goes live for patients to book</div>
          </div>
        </div>
        <button onClick={() => navigate('/dashboard')} className="btn-primary w-full">
          Go to Dashboard
        </button>
      </div>
    )
  }

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Apply as a Mental Health Professional</h1>
        <p className="text-gray-500 mt-1">Fill in your details. Our team will verify and approve within 24–48 hours.</p>
      </div>

      <div className="bg-primary-50 border border-primary-200 rounded-xl p-4 flex gap-3 text-sm text-primary-800">
        <Shield size={18} className="flex-shrink-0 mt-0.5" />
        <div>
          <strong>KMPDC Verification Required.</strong> We check every license number with the Kenya Medical Practitioners
          and Dentists Council before approving any professional on the platform.
        </div>
      </div>

      {error && (
        <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{error}</div>
      )}

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-5">

        {/* License & Basic Info */}
        <div className="card space-y-4">
          <h2 className="font-semibold text-gray-900">Professional Details</h2>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              KMPDC License Number <span className="text-red-500">*</span>
            </label>
            <input
              {...register('kmpdc_license', { required: 'KMPDC license is required' })}
              className="input-field font-mono"
              placeholder="e.g. KP-2020-0012"
            />
            {errors.kmpdc_license && <p className="text-red-500 text-xs mt-1">{errors.kmpdc_license.message}</p>}
            <p className="text-xs text-gray-400 mt-1">This will be verified with KMPDC before approval.</p>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Years of Experience <span className="text-red-500">*</span></label>
              <input
                {...register('years_experience', { required: true, min: 0, max: 50, valueAsNumber: true })}
                type="number" min={0} max={50}
                className="input-field"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">Gender</label>
              <select {...register('gender')} className="input-field">
                <option value="female">Female</option>
                <option value="male">Male</option>
                <option value="other">Prefer not to say</option>
              </select>
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Session Rate (KES/hr) <span className="text-red-500">*</span>
              </label>
              <input
                {...register('rate_per_hour', { required: true, min: 500, valueAsNumber: true })}
                type="number" min={500}
                className="input-field"
                placeholder="2000"
              />
              <p className="text-xs text-gray-400 mt-1">Platform takes 20%. You receive 80%.</p>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                M-Pesa Number (for payouts) <span className="text-red-500">*</span>
              </label>
              <input
                {...register('mpesa_number', { required: 'M-Pesa number required for payouts' })}
                className="input-field"
                placeholder="0712345678"
              />
              {errors.mpesa_number && <p className="text-red-500 text-xs mt-1">{errors.mpesa_number.message}</p>}
            </div>
          </div>
        </div>

        {/* Bio */}
        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">Professional Bio</h2>
          <p className="text-xs text-gray-500">This is what patients read when choosing a therapist. Be warm, honest, and specific.</p>
          <textarea
            {...register('bio', {
              required: 'Bio is required',
              minLength: { value: 80, message: 'Please write at least 80 characters' }
            })}
            rows={5}
            className="input-field resize-none"
            placeholder="Describe your background, approach, and what types of clients you help. E.g. 'I am a clinical psychologist with 7 years helping individuals overcome alcohol dependency and depression...'"
          />
          {errors.bio && <p className="text-red-500 text-xs mt-1">{errors.bio.message}</p>}
        </div>

        {/* Specializations */}
        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">
            Specializations <span className="text-red-500">*</span>
            <span className="text-gray-400 font-normal text-sm ml-2">(select all that apply)</span>
          </h2>
          <div className="flex flex-wrap gap-2">
            {specializations.map(s => (
              <button
                key={s.id}
                type="button"
                onClick={() => toggleSpec(s.id)}
                className={`px-3 py-1.5 rounded-full text-sm border-2 transition-all ${
                  selectedSpecs.includes(s.id)
                    ? 'border-primary-600 bg-primary-50 text-primary-800 font-medium'
                    : 'border-gray-200 text-gray-600 hover:border-gray-300'
                }`}
              >
                {selectedSpecs.includes(s.id) && '✓ '}{s.name}
              </button>
            ))}
          </div>
          {selectedSpecs.length === 0 && <p className="text-xs text-gray-400">Select at least one.</p>}
        </div>

        {/* Languages */}
        <div className="card space-y-3">
          <h2 className="font-semibold text-gray-900">
            Languages <span className="text-red-500">*</span>
            <span className="text-gray-400 font-normal text-sm ml-2">(languages you can conduct sessions in)</span>
          </h2>
          <div className="flex flex-wrap gap-2">
            {languages.map(l => (
              <button
                key={l.id}
                type="button"
                onClick={() => toggleLang(l.id)}
                className={`px-3 py-1.5 rounded-full text-sm border-2 transition-all ${
                  selectedLangs.includes(l.id)
                    ? 'border-primary-600 bg-primary-50 text-primary-800 font-medium'
                    : 'border-gray-200 text-gray-600 hover:border-gray-300'
                }`}
              >
                {selectedLangs.includes(l.id) && '✓ '}{l.name}
              </button>
            ))}
          </div>
        </div>

        {/* Earnings preview */}
        <div className="card bg-primary-50 border-primary-200">
          <h2 className="font-semibold text-primary-900 mb-3">Earnings Estimate</h2>
          <div className="space-y-1 text-sm text-primary-800">
            <div className="flex justify-between">
              <span>10 sessions/week @ KES 2,000</span>
              <span className="font-medium">KES 16,000/week</span>
            </div>
            <div className="flex justify-between">
              <span>20 sessions/week @ KES 2,000</span>
              <span className="font-bold text-primary-900">KES 32,000/week</span>
            </div>
            <div className="text-xs text-primary-600 mt-2">Platform fee: 20% · Your share: 80% · Paid to M-Pesa within 24hrs</div>
          </div>
        </div>

        <button type="submit" disabled={submitting} className="btn-primary w-full py-3 text-base">
          {submitting ? 'Submitting Application...' : 'Submit Application for Review'}
        </button>
      </form>
    </div>
  )
}
