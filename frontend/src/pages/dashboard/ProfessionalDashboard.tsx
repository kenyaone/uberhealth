import { useEffect, useRef, useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import {
  Calendar, DollarSign, Star, Clock, CheckCircle,
  AlertCircle, Users, TrendingUp, Settings, PlayCircle, Plus, X, Loader2,
  Wifi, WifiOff
} from 'lucide-react'

interface Consultation {
  id: number
  scheduled_at: string
  duration_minutes: number
  status: string
  amount: number
  user: { id: number; display_name: string; username?: string; avatar?: string }
}

interface ProfData {
  professional: {
    id: number
    verification_status: string
    rate_per_hour: number
    rating: number
    total_sessions: number
    total_reviews: number
    is_available_online: boolean
    is_accepting_new_patients: boolean
    specializations: { id: number; name: string }[]
    languages: { id: number; name: string }[]
  }
  upcoming_consultations: Consultation[]
  pending_payouts: number
}

const STATUS_COLOR: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
}

function fmt(iso: string) {
  const d = new Date(iso)
  return d.toLocaleDateString('en-KE', { weekday: 'short', month: 'short', day: 'numeric' }) +
    ' · ' + d.toLocaleTimeString('en-KE', { hour: '2-digit', minute: '2-digit' })
}

interface DirectBookForm {
  patient_username: string
  scheduled_at: string
  duration_minutes: string
  agreed_amount: string
  notes: string
}

export default function ProfessionalDashboard() {
  const user = useAuthStore(s => s.user)
  const navigate = useNavigate()
  const [data, setData] = useState<ProfData | null>(null)
  const [loading, setLoading] = useState(true)
  const [showDirectBook, setShowDirectBook] = useState(false)
  const [directForm, setDirectForm] = useState<DirectBookForm>({ patient_username: '', scheduled_at: '', duration_minutes: '60', agreed_amount: '', notes: '' })
  const [directLoading, setDirectLoading] = useState(false)
  const [directError, setDirectError] = useState('')
  const [isOnline, setIsOnline] = useState(false)
  const [onlineLoading, setOnlineLoading] = useState(false)
  const [activeTab, setActiveTab] = useState<'sessions' | 'treatment-plans' | 'referrals'>('sessions')
  const heartbeatRef = useRef<ReturnType<typeof setInterval> | null>(null)

  const fetchDashboard = () =>
    api.get('/professionals/me/dashboard')
      .then(r => {
        setData(r.data)
        setIsOnline(r.data.professional?.is_available_online ?? false)
      })
      .catch(() => {})

  const startHeartbeat = () => {
    if (heartbeatRef.current) return
    heartbeatRef.current = setInterval(() => {
      api.post('/presence/heartbeat', { page: '/dashboard' }).catch(() => {})
    }, 30_000)
  }

  const stopHeartbeat = () => {
    if (heartbeatRef.current) { clearInterval(heartbeatRef.current); heartbeatRef.current = null }
  }

  const toggleOnline = async () => {
    setOnlineLoading(true)
    try {
      const r = await api.post('/professional/set-online', { online: !isOnline })
      const next = r.data.is_available_online
      setIsOnline(next)
      if (next) {
        api.post('/presence/heartbeat', { page: '/dashboard' }).catch(() => {})
        startHeartbeat()
      } else {
        stopHeartbeat()
      }
    } catch { /* ignore */ } finally {
      setOnlineLoading(false)
    }
  }

  useEffect(() => {
    fetchDashboard().finally(() => setLoading(false))
    const poll = setInterval(fetchDashboard, 10_000)
    return () => clearInterval(poll)
  }, [])

  // Heartbeat while online; cleanup on unmount
  useEffect(() => {
    if (isOnline) startHeartbeat()
    else stopHeartbeat()
    return () => stopHeartbeat()
  }, [isOnline])

  // Go offline on tab close
  useEffect(() => {
    const goOffline = () => {
      navigator.sendBeacon('/api/professional/set-online', JSON.stringify({ online: false }))
    }
    window.addEventListener('beforeunload', goOffline)
    return () => window.removeEventListener('beforeunload', goOffline)
  }, [])

  const submitDirectBook = async () => {
    setDirectError('')
    if (!directForm.patient_username || !directForm.scheduled_at || !directForm.agreed_amount) {
      setDirectError('Please fill in patient, date/time, and agreed amount.')
      return
    }
    setDirectLoading(true)
    try {
      const r = await api.post('/consultations/direct-book', {
        patient_username: directForm.patient_username,
        scheduled_at: new Date(directForm.scheduled_at).toISOString(),
        duration_minutes: parseInt(directForm.duration_minutes),
        agreed_amount: parseFloat(directForm.agreed_amount),
        notes: directForm.notes || undefined,
      })
      setShowDirectBook(false)
      setDirectForm({ patient_username: '', scheduled_at: '', duration_minutes: '60', agreed_amount: '', notes: '' })
      navigate(r.data.join_url)
    } catch (e: any) {
      setDirectError(e.response?.data?.error ?? 'Booking failed. Please try again.')
    } finally {
      setDirectLoading(false)
    }
  }

  if (loading) {
    return <div className="text-gray-400 py-16 text-center">Loading your dashboard…</div>
  }

  if (!data) {
    return (
      <div className="max-w-lg mx-auto mt-16 card text-center py-12">
        <AlertCircle size={48} className="text-accent-500 mx-auto mb-4" />
        <h2 className="text-xl font-bold text-gray-900 mb-2">No Professional Profile Yet</h2>
        <p className="text-gray-500 mb-6">Submit your application to get verified and start accepting patients.</p>
        <Link to="/apply" className="btn-primary">Submit Application</Link>
      </div>
    )
  }

  const { professional: prof, upcoming_consultations, pending_payouts } = data
  const isVerified = prof.verification_status === 'verified'
  const isPending = prof.verification_status === 'pending'

  return (
    <div className="space-y-6">

      {/* Header */}
      <div className="flex items-start justify-between gap-4 flex-wrap">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Hello, {user?.display_name} 👋</h1>
          <p className="text-gray-500 mt-1">Your professional dashboard</p>
        </div>
        <div className="flex items-center gap-3 flex-wrap">
          {/* Online / Offline toggle */}
          {isVerified && (
            <button
              onClick={toggleOnline}
              disabled={onlineLoading}
              className={`flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm transition-all border-2 ${
                isOnline
                  ? 'bg-green-500 border-green-500 text-white shadow-md shadow-green-200'
                  : 'bg-white border-gray-300 text-gray-600 hover:border-gray-400'
              }`}
            >
              {onlineLoading
                ? <Loader2 size={15} className="animate-spin" />
                : isOnline
                  ? <><span className="w-2 h-2 rounded-full bg-white animate-pulse" /><Wifi size={15} /></>
                  : <WifiOff size={15} />
              }
              {isOnline ? 'Online — accepting patients' : 'Go Online'}
            </button>
          )}
          <span className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium ${
            isVerified ? 'bg-green-100 text-green-800' :
            isPending ? 'bg-yellow-100 text-yellow-800' :
            'bg-red-100 text-red-800'
          }`}>
            {isVerified ? <CheckCircle size={14} /> : <Clock size={14} />}
            {isVerified ? 'KMPDC Verified' : isPending ? 'Verification Pending' : prof.verification_status}
          </span>
        </div>
      </div>

      {/* Direct Booking Modal */}
      {showDirectBook && (
        <div className="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
            <div className="flex items-center justify-between">
              <h2 className="font-bold text-gray-900 flex items-center gap-2"><Plus size={16} className="text-blue-600" /> Book Session Directly</h2>
              <button onClick={() => setShowDirectBook(false)} className="text-gray-400 hover:text-gray-600"><X size={18} /></button>
            </div>
            <p className="text-sm text-gray-500">Creates a confirmed session without online payment. Use for cash payments, EAP sessions, or testing.</p>
            <div className="space-y-3">
              <div>
                <label className="label">Patient username or email</label>
                <input className="input-field" placeholder="e.g. john_doe or john@email.com"
                  value={directForm.patient_username} onChange={e => setDirectForm(f => ({ ...f, patient_username: e.target.value }))} />
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="label">Date &amp; Time</label>
                  <input type="datetime-local" className="input-field" value={directForm.scheduled_at}
                    onChange={e => setDirectForm(f => ({ ...f, scheduled_at: e.target.value }))} />
                </div>
                <div>
                  <label className="label">Duration</label>
                  <select className="input-field" value={directForm.duration_minutes}
                    onChange={e => setDirectForm(f => ({ ...f, duration_minutes: e.target.value }))}>
                    <option value="30">30 min</option>
                    <option value="60">60 min</option>
                    <option value="90">90 min</option>
                  </select>
                </div>
              </div>
              <div>
                <label className="label">Agreed amount (KES) — enter 0 for test/free sessions</label>
                <input type="number" className="input-field" placeholder="0" min="0"
                  value={directForm.agreed_amount} onChange={e => setDirectForm(f => ({ ...f, agreed_amount: e.target.value }))} />
              </div>
              <div>
                <label className="label">Internal notes (optional)</label>
                <input className="input-field" placeholder="e.g. EAP session, cash paid"
                  value={directForm.notes} onChange={e => setDirectForm(f => ({ ...f, notes: e.target.value }))} />
              </div>
              {directError && <p className="text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2">{directError}</p>}
            </div>
            <div className="flex gap-3">
              <button onClick={submitDirectBook} disabled={directLoading}
                className="btn-primary flex-1 flex items-center justify-center gap-2">
                {directLoading && <Loader2 size={14} className="animate-spin" />}
                {directLoading ? 'Creating…' : 'Create & Start Session'}
              </button>
              <button onClick={() => setShowDirectBook(false)} className="btn-secondary flex-1">Cancel</button>
            </div>
          </div>
        </div>
      )}

      {/* Pending verification notice */}
      {isPending && (
        <div className="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex gap-3 text-sm text-yellow-800">
          <Clock size={18} className="flex-shrink-0 mt-0.5 text-yellow-600" />
          <div>
            <strong>Application under review.</strong> Our team is verifying your KMPDC license.
            This typically takes 24–48 hours. You will be notified by email once approved.
          </div>
        </div>
      )}

      {/* Stats grid */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="card text-center">
          <Calendar size={22} className="text-primary-600 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">{upcoming_consultations.length}</div>
          <div className="text-xs text-gray-500">Upcoming Sessions</div>
        </div>
        <div className="card text-center">
          <TrendingUp size={22} className="text-green-500 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">{prof.total_sessions}</div>
          <div className="text-xs text-gray-500">Total Sessions</div>
        </div>
        <div className="card text-center">
          <Star size={22} className="text-yellow-500 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">
            {prof.rating ? prof.rating.toFixed(1) : '—'}
          </div>
          <div className="text-xs text-gray-500">Rating ({prof.total_reviews} reviews)</div>
        </div>
        <div className="card text-center">
          <DollarSign size={22} className="text-accent-600 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">
            {pending_payouts > 0 ? `KES ${Number(pending_payouts).toLocaleString()}` : '—'}
          </div>
          <div className="text-xs text-gray-500">Pending Payout</div>
        </div>
      </div>

      {/* Quick actions */}
      <div>
        <h2 className="text-lg font-semibold text-gray-900 mb-3">Quick Actions</h2>
        <div className="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
          <Link to="/consultations" className="card hover:border-primary-300 transition-colors group">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                <Calendar size={20} className="text-primary-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">My Sessions</div>
                <div className="text-xs text-gray-500">View all consultations</div>
              </div>
            </div>
          </Link>

          <button onClick={() => setShowDirectBook(true)} className="card hover:border-blue-300 transition-colors group text-left">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <Plus size={20} className="text-blue-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">Book Direct Session</div>
                <div className="text-xs text-gray-500">Cash, EAP, or testing</div>
              </div>
            </div>
          </button>

          <Link to="/availability" className="card hover:border-primary-300 transition-colors group">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <Settings size={20} className="text-blue-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">Set Availability</div>
                <div className="text-xs text-gray-500">Manage your schedule</div>
              </div>
            </div>
          </Link>

          <Link to="/profile" className="card hover:border-primary-300 transition-colors group">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                <Users size={20} className="text-green-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">My Profile</div>
                <div className="text-xs text-gray-500">Edit your public listing</div>
              </div>
            </div>
          </Link>
        </div>
      </div>

      {/* Tabs: Sessions / Treatment Plans / Referrals */}
      <div>
        <div className="border-b border-gray-200 mb-6">
          <div className="flex gap-6">
            <button
              onClick={() => setActiveTab('sessions')}
              className={`pb-3 px-2 font-medium text-sm border-b-2 transition-colors ${
                activeTab === 'sessions'
                  ? 'border-primary-600 text-primary-600'
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              }`}
            >
              Upcoming Sessions
            </button>
            <button
              onClick={() => setActiveTab('treatment-plans')}
              className={`pb-3 px-2 font-medium text-sm border-b-2 transition-colors ${
                activeTab === 'treatment-plans'
                  ? 'border-primary-600 text-primary-600'
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              }`}
            >
              Treatment Plans
            </button>
            <button
              onClick={() => setActiveTab('referrals')}
              className={`pb-3 px-2 font-medium text-sm border-b-2 transition-colors ${
                activeTab === 'referrals'
                  ? 'border-primary-600 text-primary-600'
                  : 'border-transparent text-gray-600 hover:text-gray-900'
              }`}
            >
              Pending Referrals
            </button>
          </div>
        </div>

        {/* Sessions Tab */}
        {activeTab === 'sessions' && (
        <>
        <div className="flex items-center justify-between mb-3">
          <h2 className="text-lg font-semibold text-gray-900">Upcoming Sessions</h2>
          <Link to="/consultations" className="text-sm text-primary-600 hover:underline">View all</Link>
        </div>
        {upcoming_consultations.length === 0 ? (
          <div className="card text-center py-8 text-gray-400">
            <Calendar size={32} className="mx-auto mb-2 opacity-40" />
            <p className="text-sm">No upcoming sessions. {isVerified ? 'Your profile is live — patients can book you.' : 'Awaiting verification.'}</p>
          </div>
        ) : (
          <div className="space-y-3">
            {upcoming_consultations.map(c => (
              <div key={c.id} className="card flex items-center justify-between gap-4">
                <div className="flex items-center gap-3">
                  <div className="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-sm">
                    {(c.user.display_name || c.user.username || 'P').charAt(0).toUpperCase()}
                  </div>
                  <div>
                    <div className="font-medium text-gray-900">{c.user.display_name || c.user.username || 'Patient'}</div>
                    <div className="text-xs text-gray-500">{fmt(c.scheduled_at)} · {c.duration_minutes} min</div>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <span className="text-sm font-medium text-gray-700">KES {Number(c.amount).toLocaleString()}</span>
                  <span className={`text-xs px-2 py-0.5 rounded-full font-medium ${STATUS_COLOR[c.status] ?? 'bg-gray-100 text-gray-600'}`}>
                    {c.status}
                  </span>
                  {['confirmed', 'in_progress'].includes(c.status) && (
                    <Link to={`/consultations`} className="flex items-center gap-1 text-xs bg-blue-600 text-white px-2.5 py-1 rounded-lg hover:bg-blue-700 font-medium">
                      <PlayCircle size={11} /> Go to sessions
                    </Link>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
        </>
        )}

        {/* Treatment Plans Tab */}
        {activeTab === 'treatment-plans' && (
          <div className="card">
            <h3 className="font-semibold text-gray-900 mb-4">Treatment Plans</h3>
            <p className="text-gray-500 text-sm mb-4">View and manage treatment plans you've prescribed to patients.</p>
            <div className="space-y-3">
              <div className="p-4 border border-gray-200 rounded-lg hover:border-primary-300 transition-colors cursor-pointer">
                <div className="flex items-start justify-between">
                  <div>
                    <h4 className="font-medium text-gray-900">Patient Recovery Plan</h4>
                    <p className="text-xs text-gray-500 mt-1">Created 2 days ago · 8 sessions planned</p>
                  </div>
                  <span className="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                </div>
              </div>
              <div className="p-4 border border-gray-200 rounded-lg hover:border-primary-300 transition-colors cursor-pointer">
                <div className="flex items-start justify-between">
                  <div>
                    <h4 className="font-medium text-gray-900">Behavioral Intervention Plan</h4>
                    <p className="text-xs text-gray-500 mt-1">Created 1 week ago · 5 sessions planned</p>
                  </div>
                  <span className="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">In Progress</span>
                </div>
              </div>
            </div>
            <p className="text-xs text-gray-400 text-center mt-6">Full treatment plan management coming soon</p>
          </div>
        )}

        {/* Referrals Tab */}
        {activeTab === 'referrals' && (
          <div className="card">
            <h3 className="font-semibold text-gray-900 mb-4">Pending Referral Approvals</h3>
            <p className="text-gray-500 text-sm mb-4">Review and approve referrals from supervisors and colleagues.</p>
            <div className="space-y-3">
              <div className="p-4 border border-amber-200 bg-amber-50 rounded-lg">
                <div className="flex items-start justify-between">
                  <div>
                    <h4 className="font-medium text-gray-900">Referral from Dr. Sarah M.</h4>
                    <p className="text-xs text-gray-600 mt-1">Patient: John Doe • Reason: Severe anxiety</p>
                    <p className="text-xs text-gray-500 mt-2">Requires your approval to proceed</p>
                  </div>
                  <span className="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full">Pending</span>
                </div>
                <div className="flex gap-2 mt-4">
                  <button className="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg">
                    Approve
                  </button>
                  <button className="flex-1 px-3 py-2 border border-gray-300 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50">
                    Decline
                  </button>
                </div>
              </div>
              <div className="p-4 border border-green-200 bg-green-50 rounded-lg">
                <div className="flex items-start justify-between">
                  <div>
                    <h4 className="font-medium text-gray-900">Referral from Dr. James K.</h4>
                    <p className="text-xs text-gray-600 mt-1">Patient: Jane Smith • Reason: Trauma therapy</p>
                    <p className="text-xs text-gray-500 mt-2">Approved 3 hours ago</p>
                  </div>
                  <span className="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Approved</span>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Specializations & Languages */}
      {isVerified && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div className="card">
            <h3 className="font-semibold text-gray-900 mb-3">Your Specializations</h3>
            <div className="flex flex-wrap gap-2">
              {prof.specializations.map(s => (
                <span key={s.id} className="bg-primary-50 text-primary-800 text-xs px-2.5 py-1 rounded-full border border-primary-200">{s.name}</span>
              ))}
            </div>
          </div>
          <div className="card">
            <h3 className="font-semibold text-gray-900 mb-3">Session Languages</h3>
            <div className="flex flex-wrap gap-2">
              {prof.languages.map(l => (
                <span key={l.id} className="bg-blue-50 text-blue-800 text-xs px-2.5 py-1 rounded-full border border-blue-200">{l.name}</span>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* Earnings info */}
      <div className="card bg-primary-50 border-primary-200">
        <h3 className="font-semibold text-primary-900 mb-3">Earnings Structure</h3>
        <div className="grid grid-cols-3 gap-4 text-center text-sm">
          {[
            { label: 'Patient pays', value: `KES ${Number(prof.rate_per_hour).toLocaleString()}` },
            { label: 'You receive (80%)', value: `KES ${Math.round(prof.rate_per_hour * 0.8).toLocaleString()}`, bold: true },
            { label: 'Platform fee (20%)', value: `KES ${Math.round(prof.rate_per_hour * 0.2).toLocaleString()}` },
          ].map(({ label, value, bold }) => (
            <div key={label} className="p-3 bg-white rounded-lg border border-primary-100">
              <div className={`${bold ? 'text-green-600 font-bold text-lg' : 'text-gray-700'}`}>{value}</div>
              <div className="text-gray-400 text-xs mt-0.5">{label}</div>
            </div>
          ))}
        </div>
        <p className="text-xs text-primary-600 mt-3 text-center">Paid directly to your mobile money within 24 hours of each completed session.</p>
      </div>

      {/* Crisis reminder */}
      <div className="bg-red-50 border border-red-200 rounded-xl p-4">
        <div className="font-medium text-red-800 mb-1 flex items-center gap-2 text-sm">
          <AlertCircle size={14} /> Patient Crisis Protocol
        </div>
        <div className="text-xs text-red-700">
          If a patient expresses suicidal ideation during a session, refer them to: Befrienders Kenya <strong>0800 723 253</strong> · NACADA <strong>1192</strong> · Emergency <strong>999</strong>
        </div>
      </div>
    </div>
  )
}
