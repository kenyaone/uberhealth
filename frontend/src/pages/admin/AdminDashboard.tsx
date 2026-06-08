import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import type { Professional } from '../../types'
import { CheckCircle, XCircle, Clock, Users, Star, ChevronDown, ChevronUp } from 'lucide-react'

type TabType = 'pending' | 'verified' | 'rejected'

export default function AdminDashboard() {
  const user = useAuthStore(s => s.user)
  const navigate = useNavigate()
  const [tab, setTab] = useState<TabType>('pending')
  const [professionals, setProfessionals] = useState<Professional[]>([])
  const [loading, setLoading] = useState(true)
  const [acting, setActing] = useState<number | null>(null)
  const [expanded, setExpanded] = useState<number | null>(null)

  useEffect(() => {
    if (user?.role !== 'admin') { navigate('/dashboard'); return }
    fetchProfessionals(tab)
  }, [tab])

  const fetchProfessionals = (statusFilter: string) => {
    setLoading(true)
    api.get(`/professionals/admin/all/?status=${statusFilter}`)
      .then(r => setProfessionals(r.data))
      .finally(() => setLoading(false))
  }

  const handleAction = async (id: number, action: 'approve' | 'reject') => {
    setActing(id)
    try {
      await api.post(`/professionals/admin/${id}/verify/`, { action })
      setProfessionals(prev => prev.filter(p => p.id !== id))
    } finally {
      setActing(null)
    }
  }

  const counts = { pending: 0, verified: 0, rejected: 0 }

  const TABS: { key: TabType; label: string; icon: any; color: string }[] = [
    { key: 'pending', label: 'Pending Review', icon: Clock, color: 'text-amber-600' },
    { key: 'verified', label: 'Approved', icon: CheckCircle, color: 'text-green-600' },
    { key: 'rejected', label: 'Rejected', icon: XCircle, color: 'text-red-500' },
  ]

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Admin — Professional Verification</h1>
        <p className="text-gray-500 text-sm mt-1">Review and approve therapist applications.</p>
      </div>

      {/* Tabs */}
      <div className="flex gap-2 border-b border-gray-200">
        {TABS.map(({ key, label, icon: Icon, color }) => (
          <button
            key={key}
            onClick={() => setTab(key)}
            className={`flex items-center gap-2 px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors ${
              tab === key
                ? 'border-primary-600 text-primary-700'
                : 'border-transparent text-gray-500 hover:text-gray-700'
            }`}
          >
            <Icon size={15} className={tab === key ? color : ''} />
            {label}
          </button>
        ))}
      </div>

      {loading ? (
        <div className="text-center py-10 text-gray-400">Loading...</div>
      ) : professionals.length === 0 ? (
        <div className="card text-center py-10 text-gray-400">
          <Users size={36} className="mx-auto mb-2 text-gray-300" />
          No {tab} applications.
        </div>
      ) : (
        <div className="space-y-4">
          {professionals.map(pro => (
            <div key={pro.id} className="card">
              {/* Header row */}
              <div className="flex items-start justify-between">
                <div className="flex items-start gap-4">
                  <div className="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl flex-shrink-0">
                    {pro.display_name.charAt(0).toUpperCase()}
                  </div>
                  <div>
                    <div className="font-semibold text-gray-900">{pro.display_name}</div>
                    <div className="text-sm text-gray-500">@{pro.user?.username} · {pro.user?.email || 'No email'}</div>
                    <div className="flex flex-wrap gap-2 mt-1.5">
                      <span className="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-mono">{pro.kmpdc_license}</span>
                      <span className="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{pro.years_experience} yrs exp</span>
                      <span className="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded capitalize">{pro.gender}</span>
                      <span className="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded font-medium">
                        KES {Number(pro.rate_per_hour).toLocaleString()}/hr
                      </span>
                    </div>
                  </div>
                </div>

                <div className="flex items-center gap-2 flex-shrink-0">
                  <button
                    onClick={() => setExpanded(expanded === pro.id ? null : pro.id)}
                    className="text-gray-400 hover:text-gray-600 p-1"
                  >
                    {expanded === pro.id ? <ChevronUp size={18} /> : <ChevronDown size={18} />}
                  </button>
                </div>
              </div>

              {/* Specializations + Languages */}
              <div className="mt-3 flex flex-wrap gap-1.5">
                {pro.specializations.map(s => (
                  <span key={s.id} className="text-xs bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full">{s.name}</span>
                ))}
                {pro.languages.map(l => (
                  <span key={l.id} className="text-xs bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded-full">{l.name}</span>
                ))}
              </div>

              {/* Expanded bio */}
              {expanded === pro.id && (
                <div className="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-700 leading-relaxed">
                  <div className="font-medium text-gray-500 text-xs mb-1 uppercase tracking-wide">Bio</div>
                  {pro.bio}
                </div>
              )}

              {/* Action buttons — only for pending */}
              {tab === 'pending' && (
                <div className="flex gap-3 mt-4 pt-4 border-t border-gray-100">
                  <button
                    onClick={() => handleAction(pro.id, 'approve')}
                    disabled={acting === pro.id}
                    className="flex-1 flex items-center justify-center gap-2 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
                  >
                    <CheckCircle size={16} />
                    {acting === pro.id ? 'Processing...' : 'Approve & Verify'}
                  </button>
                  <button
                    onClick={() => handleAction(pro.id, 'reject')}
                    disabled={acting === pro.id}
                    className="flex-1 flex items-center justify-center gap-2 py-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm font-medium transition-colors disabled:opacity-50"
                  >
                    <XCircle size={16} />
                    Reject
                  </button>
                </div>
              )}

              {/* Status badge for non-pending */}
              {tab !== 'pending' && (
                <div className={`mt-3 flex items-center gap-1.5 text-sm font-medium ${
                  tab === 'verified' ? 'text-green-600' : 'text-red-500'
                }`}>
                  {tab === 'verified' ? <CheckCircle size={15} /> : <XCircle size={15} />}
                  {tab === 'verified' ? 'Approved & Live on Platform' : 'Application Rejected'}
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
