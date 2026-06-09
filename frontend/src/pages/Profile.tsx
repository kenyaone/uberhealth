import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'
import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../store/authStore'
import api from '../api/axios'
import { Shield, Save, Zap, CheckCircle, Crown, Calendar, ArrowRight, RefreshCw } from 'lucide-react'

interface ProfileForm {
  display_name: string
  email: string
  is_anonymous_mode: boolean
}

interface Subscription {
  id: number
  status: string
  expires_at: string | null
  plan: {
    id: number
    name: string
    tier: string
    price_kes: string
    interval: string
    features: string[]
    assessment_limit: number
    lesson_limit: number
  }
}

const TIER_BADGE: Record<string, string> = {
  free:    'bg-gray-100 text-gray-700 border-gray-200',
  premium: 'bg-teal-50 text-teal-800 border-teal-200',
  pro:     'bg-purple-50 text-purple-800 border-purple-200',
}

export default function Profile() {
  const { user, updateUser } = useAuthStore()
  const navigate = useNavigate()
  const [saving, setSaving]     = useState(false)
  const [saved, setSaved]       = useState(false)
  const [subscription, setSub]  = useState<Subscription | null>(null)
  const [subLoading, setSubLoad] = useState(true)

  const { register, handleSubmit } = useForm<ProfileForm>({
    defaultValues: {
      display_name:     user?.display_name || '',
      email:            user?.email || '',
      is_anonymous_mode: user?.is_anonymous_mode ?? true,
    }
  })

  useEffect(() => {
    api.get('/subscriptions/current')
      .then(r => setSub(r.data.subscription ?? null))
      .catch(() => {})
      .finally(() => setSubLoad(false))
  }, [])

  const onSubmit = async (data: ProfileForm) => {
    setSaving(true)
    try {
      const res = await api.put('/auth/me', data)
      updateUser(res.data)
      setSaved(true)
      setTimeout(() => setSaved(false), 3000)
    } finally {
      setSaving(false)
    }
  }

  const tier     = subscription?.plan?.tier ?? 'free'
  const planName = subscription?.plan?.name ?? 'Free'
  const isExpiring = subscription?.expires_at
    ? new Date(subscription.expires_at).getTime() - Date.now() < 7 * 24 * 60 * 60 * 1000
    : false

  return (
    <div className="max-w-xl mx-auto space-y-5">
      <h1 className="text-2xl font-bold text-gray-900">Your Profile</h1>

      {/* ── Identity card ── */}
      <div className="card">
        <div className="flex items-center gap-4 mb-5">
          <div className="w-16 h-16 rounded-2xl bg-primary-700 text-white flex items-center justify-center text-2xl font-bold">
            {user?.display_name?.charAt(0).toUpperCase()}
          </div>
          <div>
            <div className="font-semibold text-gray-900 text-lg">{user?.display_name}</div>
            <div className="text-sm text-gray-500">@{user?.username}</div>
            <div className="flex items-center gap-2 mt-1">
              <span className={`inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full border font-medium ${TIER_BADGE[tier]}`}>
                <Crown size={10} /> {planName}
              </span>
              <span className="text-xs text-gray-400 capitalize">{user?.role}</span>
            </div>
          </div>
        </div>

        <div className="bg-primary-50 border border-primary-200 rounded-lg p-3 flex gap-2 text-sm text-primary-800 mb-5">
          <Shield size={16} className="flex-shrink-0 mt-0.5" />
          <span>Your username <strong>@{user?.username}</strong> is never shown to professionals.</span>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Display Name <span className="text-gray-400 text-xs">(shown to therapists)</span>
            </label>
            <input {...register('display_name', { required: true })} className="input-field" />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Email <span className="text-gray-400 text-xs">(optional, for reminders)</span>
            </label>
            <input {...register('email')} type="email" className="input-field" placeholder="optional@email.com" />
          </div>

          <label className="flex items-center gap-3 cursor-pointer">
            <input {...register('is_anonymous_mode')} type="checkbox" className="rounded border-gray-300 text-primary-600" />
            <div>
              <div className="text-sm font-medium text-gray-900">Anonymous mode</div>
              <div className="text-xs text-gray-500">Keep your username hidden in all interactions</div>
            </div>
          </label>

          <button type="submit" disabled={saving} className="btn-primary w-full flex items-center justify-center gap-2">
            <Save size={16} />
            {saving ? 'Saving...' : saved ? '✓ Saved!' : 'Save Changes'}
          </button>
        </form>
      </div>

      {/* ── Subscription card ── */}
      <div className="card">
        <div className="flex items-center justify-between mb-4">
          <h2 className="font-semibold text-gray-900 flex items-center gap-2">
            <Crown size={16} className="text-teal-600" /> Your Plan
          </h2>
          {!subLoading && (
            <span className={`text-xs px-2 py-0.5 rounded-full border font-medium ${TIER_BADGE[tier]}`}>
              {planName}
            </span>
          )}
        </div>

        {subLoading ? (
          <div className="flex items-center gap-2 text-gray-400 text-sm py-4">
            <RefreshCw size={14} className="animate-spin" /> Loading…
          </div>

        ) : subscription ? (
          <>
            {/* Active plan summary */}
            <div className="bg-gray-50 rounded-xl p-4 mb-4">
              <div className="flex items-center justify-between mb-3">
                <div className="text-sm font-semibold text-gray-800">{subscription.plan.name}</div>
                {subscription.expires_at && (
                  <div className={`flex items-center gap-1 text-xs ${isExpiring ? 'text-amber-600 font-medium' : 'text-gray-400'}`}>
                    <Calendar size={11} />
                    {new Date(subscription.expires_at).toLocaleDateString('en-KE', { day: 'numeric', month: 'short', year: 'numeric' })}
                  </div>
                )}
              </div>

              {/* Limit counters */}
              <div className="grid grid-cols-2 gap-2 mb-3">
                <div className="bg-white rounded-lg px-3 py-2 text-center border border-gray-100">
                  <div className="text-xl font-bold text-teal-700">
                    {subscription.plan.assessment_limit === -1 ? '∞' : subscription.plan.assessment_limit}
                  </div>
                  <div className="text-xs text-gray-400">Assessments</div>
                </div>
                <div className="bg-white rounded-lg px-3 py-2 text-center border border-gray-100">
                  <div className="text-xl font-bold text-teal-700">
                    {subscription.plan.lesson_limit === -1 ? '∞' : subscription.plan.lesson_limit}
                  </div>
                  <div className="text-xs text-gray-400">Lessons</div>
                </div>
              </div>

              {/* Features */}
              <ul className="space-y-1.5">
                {subscription.plan.features.slice(0, 5).map(f => (
                  <li key={f} className="flex items-start gap-2 text-xs text-gray-600">
                    <CheckCircle size={12} className="text-teal-500 shrink-0 mt-0.5" /> {f}
                  </li>
                ))}
              </ul>
            </div>

            {/* Expiry warning */}
            {isExpiring && (
              <div className="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800 flex items-start gap-2 mb-4">
                <Calendar size={15} className="shrink-0 mt-0.5 text-amber-500" />
                <div>
                  Plan expiring soon.{' '}
                  <button onClick={() => navigate('/pricing')} className="underline font-semibold">Renew now</button>
                  {' '}to keep your sessions and lesson access.
                </div>
              </div>
            )}

            {/* Upgrade nudge for non-pro */}
            {tier !== 'pro' && (
              <div className="rounded-xl border border-teal-200 bg-gradient-to-br from-teal-50 to-emerald-50 p-4">
                <div className="flex items-start gap-3">
                  <div className="w-9 h-9 bg-teal-600 rounded-xl flex items-center justify-center shrink-0">
                    <Zap size={16} className="text-white" />
                  </div>
                  <div className="flex-1">
                    <div className="font-semibold text-teal-900 text-sm mb-0.5">
                      {tier === 'free' ? 'Unlock Full Recovery Support' : 'Upgrade to Annual — Save 20%'}
                    </div>
                    <div className="text-xs text-teal-700 mb-3">
                      {tier === 'free'
                        ? 'Unlimited sessions, full lessons library, peer groups, and AI progress insights.'
                        : 'Switch to annual billing and save KES 2,400 a year.'}
                    </div>
                    <button
                      onClick={() => navigate('/pricing')}
                      className="flex items-center gap-1.5 text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition-colors"
                    >
                      See Upgrade Options <ArrowRight size={12} />
                    </button>
                  </div>
                </div>
              </div>
            )}
          </>

        ) : (
          /* No subscription — free user upgrade pitch */
          <div>
            <p className="text-sm text-gray-500 mb-4">
              You are on the <strong>Free plan</strong> — free assessments only. No sessions or lessons included.
            </p>

            <div className="rounded-xl border-2 border-teal-400 bg-gradient-to-br from-teal-600 to-emerald-700 p-5 text-white">
              <div className="text-xs font-bold uppercase tracking-wider text-teal-200 mb-2">Most Popular</div>
              <div className="text-lg font-black mb-0.5">Premium Plan</div>
              <div className="flex items-end gap-1 mb-4">
                <span className="text-3xl font-black">KES 2,500</span>
                <span className="text-teal-200 text-sm mb-1">/month</span>
              </div>
              <ul className="space-y-1.5 mb-5">
                {[
                  'Unlimited therapy sessions',
                  'Full recovery skills library (11 lessons)',
                  'Peer support groups',
                  'AI progress insights & SOAP notes',
                  'Mood, sobriety & craving tracking',
                  'KMPDC-verified therapists only',
                ].map(f => (
                  <li key={f} className="flex items-center gap-2 text-sm text-teal-100">
                    <CheckCircle size={13} className="text-teal-300 shrink-0" /> {f}
                  </li>
                ))}
              </ul>
              <button
                onClick={() => navigate('/pricing')}
                className="w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-colors"
              >
                Upgrade Now <ArrowRight size={16} />
              </button>
            </div>

            <p className="text-xs text-center text-gray-400 mt-3">Pay via M-Pesa · Cancel anytime · No card needed</p>
          </div>
        )}
      </div>

      {/* ── Account Security ── */}
      <div className="card">
        <h2 className="font-semibold text-gray-900 mb-3">Account Security</h2>
        <div className="space-y-2.5 text-sm text-gray-600">
          {[
            ['Username', `@${user?.username}`],
            ['Account type', user?.role ?? ''],
            ['Data encryption', '✓ AES-256'],
            ['WhatsApp required', '✗ Never'],
          ].map(([label, value]) => (
            <div key={label} className="flex items-center justify-between">
              <span>{label}</span>
              <span className={`font-medium ${value?.startsWith('✓') ? 'text-green-600' : value?.startsWith('✗') ? 'text-green-600' : 'text-gray-900'} capitalize`}>
                {value}
              </span>
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}
