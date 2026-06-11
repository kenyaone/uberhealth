import { useEffect, useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import { CheckCircle, Zap, Building2, Stethoscope } from 'lucide-react'

interface Plan {
  id: number; name: string; tier: string; price_kes: string
  interval: string; features: string[]; assessment_limit: number; lesson_limit: number
}
interface EAPTier {
  id: number; name: string; min_employees: number; max_employees: number
  price_kes_annual: string; sessions_per_employee: number; features: string[]
}

export default function Pricing() {
  const [plans, setPlans] = useState<Plan[]>([])
  const [eapTiers, setEapTiers] = useState<EAPTier[]>([])
  const user = useAuthStore(s => s.user)
  const navigate = useNavigate()

  useEffect(() => {
    api.get('/subscriptions/plans').then(r => setPlans(r.data.plans ?? r.data))
    api.get('/corporate/tiers').then(r => setEapTiers(r.data))
  }, [])

  const userPlans = plans.filter(p => p.tier !== 'pro')
  const proPlans = plans.filter(p => p.tier === 'pro')

  const tierStyle: Record<string, string> = {
    free: 'border-gray-200',
    premium: 'border-primary-500 ring-2 ring-primary-500',
    pro: 'border-accent-500 ring-2 ring-accent-500',
  }

  return (
    <div className="space-y-16">
      <div className="text-center">
        <h1 className="text-3xl font-bold text-gray-900">Simple, Transparent Pricing</h1>
        <p className="text-gray-500 mt-2">Start free. Upgrade when you're ready.</p>
      </div>

      {/* User Plans */}
      <div>
        <h2 className="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
          <Zap size={20} className="text-primary-600" /> Personal Plans
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {userPlans.map(plan => (
            <div key={plan.id} className={`card border-2 ${tierStyle[plan.tier]} relative flex flex-col`}>
              {plan.tier === 'premium' && plan.interval === 'monthly' && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                  MOST POPULAR
                </div>
              )}
              <div className="mb-4">
                <h3 className="text-lg font-bold text-gray-900">{plan.name}</h3>
                <div className="mt-2">
                  {Number(plan.price_kes) === 0 ? (
                    <span className="text-3xl font-bold text-gray-900">Free</span>
                  ) : (
                    <>
                      <span className="text-3xl font-bold text-primary-700">KES {Number(plan.price_kes).toLocaleString()}</span>
                      <span className="text-gray-500 text-sm">/{plan.interval === 'monthly' ? 'month' : 'year'}</span>
                    </>
                  )}
                </div>
              </div>
              <ul className="space-y-2 flex-1 mb-6">
                {plan.features.map(f => (
                  <li key={f} className="flex items-start gap-2 text-sm text-gray-600">
                    <CheckCircle size={15} className="text-primary-500 flex-shrink-0 mt-0.5" />
                    {f}
                  </li>
                ))}
              </ul>
              {Number(plan.price_kes) === 0 ? (
                <Link to={user ? '/dashboard' : '/signup'} className="btn-secondary text-center text-sm py-2.5">
                  {user ? 'Current Plan' : 'Get Started Free'}
                </Link>
              ) : (
                <button
                  onClick={() => navigate('/subscribe', { state: { plan } })}
                  className={`${plan.tier === 'premium' ? 'btn-primary' : 'btn-accent'} text-center text-sm py-2.5`}
                >
                  Upgrade to {plan.name}
                </button>
              )}
            </div>
          ))}
        </div>
      </div>

      {/* Professional Pro Plan */}
      {proPlans.length > 0 && (
        <div>
          <h2 className="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <Stethoscope size={20} className="text-accent-600" /> For Mental Health Professionals
          </h2>
          <div className="max-w-md">
            {proPlans.map(plan => (
              <div key={plan.id} className={`card border-2 ${tierStyle['pro']}`}>
                <div className="flex items-center justify-between mb-4">
                  <div>
                    <h3 className="text-lg font-bold text-gray-900">{plan.name}</h3>
                    <div className="mt-1">
                      <span className="text-3xl font-bold text-accent-600">KES {Number(plan.price_kes).toLocaleString()}</span>
                      <span className="text-gray-500 text-sm">/month</span>
                    </div>
                  </div>
                  <Stethoscope size={32} className="text-accent-500" />
                </div>
                <ul className="space-y-2 mb-6">
                  {plan.features.map(f => (
                    <li key={f} className="flex items-start gap-2 text-sm text-gray-600">
                      <CheckCircle size={15} className="text-accent-500 flex-shrink-0 mt-0.5" />
                      {f}
                    </li>
                  ))}
                </ul>
                <button
                  onClick={() => navigate('/subscribe', { state: { plan } })}
                  className="btn-accent w-full text-sm py-2.5"
                >
                  Upgrade to Professional Pro
                </button>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* EAP Corporate */}
      <div>
        <h2 className="text-xl font-semibold text-gray-800 mb-2 flex items-center gap-2">
          <Building2 size={20} className="text-blue-600" /> Corporate EAP Packages
        </h2>
        <p className="text-gray-500 text-sm mb-6">
          Employee Assistance Programme — give your team private, confidential mental health support.
        </p>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {eapTiers.map((tier, i) => (
            <div key={tier.id} className={`card border-2 ${i === 1 ? 'border-blue-500 ring-2 ring-blue-400' : 'border-gray-200'} flex flex-col`}>
              {i === 1 && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                  POPULAR
                </div>
              )}
              <div className="mb-4">
                <h3 className="text-lg font-bold text-gray-900">{tier.name}</h3>
                <p className="text-xs text-gray-500">{tier.min_employees}–{tier.max_employees} employees</p>
                <div className="mt-2">
                  <span className="text-3xl font-bold text-blue-700">KES {Number(tier.price_kes_annual).toLocaleString()}</span>
                  <span className="text-gray-500 text-sm">/year</span>
                </div>
                <p className="text-xs text-gray-500 mt-1">
                  {tier.sessions_per_employee} sessions/employee · KES {Math.round(Number(tier.price_kes_annual) / (tier.max_employees * 12)).toLocaleString()}/employee/month
                </p>
              </div>
              <ul className="space-y-2 flex-1 mb-6">
                {tier.features.map(f => (
                  <li key={f} className="flex items-start gap-2 text-sm text-gray-600">
                    <CheckCircle size={15} className="text-blue-500 flex-shrink-0 mt-0.5" />
                    {f}
                  </li>
                ))}
              </ul>
              <Link
                to="/corporate"
                className="block text-center py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors"
              >
                Apply for {tier.name} Package
              </Link>
            </div>
          ))}
        </div>
        <div className="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-800">
          <strong>Need a custom quote?</strong> Contact us at <a href="mailto:corporate@uberhealth.co.ke" className="underline">corporate@uberhealth.co.ke</a> for organisations with 500+ employees or special requirements.
        </div>
      </div>

      {/* Revenue comparison */}
      <div className="card bg-gray-50 border-gray-200">
        <h2 className="font-semibold text-gray-800 mb-3">How it works for professionals</h2>
        <div className="grid grid-cols-3 gap-4 text-center text-sm">
          {[
            { label: 'Patient pays', value: 'KES 2,500', color: 'text-gray-700' },
            { label: 'You receive (80%)', value: 'KES 2,000', color: 'text-green-600 font-bold text-lg' },
            { label: 'Platform (20%)', value: 'KES 500', color: 'text-gray-500' },
          ].map(({ label, value, color }) => (
            <div key={label} className="p-3 bg-white rounded-lg border border-gray-100">
              <div className={`${color} text-base`}>{value}</div>
              <div className="text-gray-400 text-xs mt-0.5">{label}</div>
            </div>
          ))}
        </div>
        <p className="text-xs text-gray-400 mt-3 text-center">Paid directly to your M-Pesa within 24 hours of each session.</p>
      </div>
    </div>
  )
}
