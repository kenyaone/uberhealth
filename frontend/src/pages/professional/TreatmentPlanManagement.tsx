import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import axios from 'axios'
import { Plus, FileText, Trash2, Edit2, Eye, Loader2, CheckCircle } from 'lucide-react'

interface TreatmentPlan {
  id: number
  consultation_id: number
  user_id: number
  description: string
  duration_weeks: number
  sessions_per_week: number
  cost_per_session: number
  total_cost: number
  status: 'draft' | 'active' | 'completed' | 'cancelled'
  schedule_details?: any
  created_at: string
  updated_at: string
  user?: { id: number; display_name: string }
  professional?: { user?: { display_name: string; cpb_license?: string }; has_cpb?: boolean }
}

export default function TreatmentPlanManagement() {
  const [plans, setPlans] = useState<TreatmentPlan[]>([])
  const [loading, setLoading] = useState(true)
  const [filter, setFilter] = useState<'all' | 'draft' | 'active' | 'completed'>('all')
  const [selectedPlan, setSelectedPlan] = useState<TreatmentPlan | null>(null)

  useEffect(() => {
    fetchPlans()
  }, [])

  const fetchPlans = async () => {
    setLoading(true)
    try {
      const res = await axios.get('/api/treatment-plans/my-prescribed')
      setPlans(res.data.plans || [])
    } catch (err) {
      console.error('Failed to load treatment plans', err)
    } finally {
      setLoading(false)
    }
  }

  const filteredPlans = filter === 'all'
    ? plans
    : plans.filter(p => p.status === filter)

  const stats = {
    total: plans.length,
    draft: plans.filter(p => p.status === 'draft').length,
    active: plans.filter(p => p.status === 'active').length,
    completed: plans.filter(p => p.status === 'completed').length,
    totalValue: plans.reduce((sum, p) => sum + p.total_cost, 0),
  }

  const handleDelete = async (id: number) => {
    if (!confirm('Delete this treatment plan?')) return
    try {
      await axios.delete(`/api/treatment-plans/${id}`)
      setPlans(plans.filter(p => p.id !== id))
    } catch (err) {
      console.error('Failed to delete plan', err)
    }
  }

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'draft':
        return 'bg-yellow-100 text-yellow-800'
      case 'active':
        return 'bg-green-100 text-green-800'
      case 'completed':
        return 'bg-blue-100 text-blue-800'
      case 'cancelled':
        return 'bg-red-100 text-red-800'
      default:
        return 'bg-gray-100 text-gray-800'
    }
  }

  if (loading) {
    return <div className="flex items-center justify-center py-16"><Loader2 className="animate-spin" /></div>
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between gap-4 flex-wrap">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Treatment Plans</h1>
          <p className="text-gray-500 text-sm mt-1">Create and manage treatment plans for your patients</p>
        </div>
        <Link
          to="/treatment-plan/new"
          className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
        >
          <Plus size={18} /> New Plan
        </Link>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div className="bg-white rounded-lg p-4 shadow border-l-4 border-blue-500">
          <p className="text-gray-500 text-xs uppercase">Total Plans</p>
          <p className="text-2xl font-bold text-gray-900 mt-1">{stats.total}</p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow border-l-4 border-yellow-500">
          <p className="text-gray-500 text-xs uppercase">Draft</p>
          <p className="text-2xl font-bold text-gray-900 mt-1">{stats.draft}</p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow border-l-4 border-green-500">
          <p className="text-gray-500 text-xs uppercase">Active</p>
          <p className="text-2xl font-bold text-gray-900 mt-1">{stats.active}</p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow border-l-4 border-blue-600">
          <p className="text-gray-500 text-xs uppercase">Completed</p>
          <p className="text-2xl font-bold text-gray-900 mt-1">{stats.completed}</p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow border-l-4 border-purple-500">
          <p className="text-gray-500 text-xs uppercase">Total Value</p>
          <p className="text-lg font-bold text-gray-900 mt-1">KES {(stats.totalValue / 1000).toFixed(0)}k</p>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-lg shadow p-4">
        <div className="flex gap-2 flex-wrap">
          {(['all', 'draft', 'active', 'completed'] as const).map(status => (
            <button
              key={status}
              onClick={() => setFilter(status)}
              className={`px-4 py-2 rounded-lg font-medium text-sm transition-colors ${
                filter === status
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              }`}
            >
              {status.charAt(0).toUpperCase() + status.slice(1)}
            </button>
          ))}
        </div>
      </div>

      {/* Plans List */}
      {filteredPlans.length === 0 ? (
        <div className="bg-white rounded-lg shadow p-12 text-center">
          <FileText size={48} className="mx-auto text-gray-300 mb-4" />
          <h3 className="text-lg font-semibold text-gray-900 mb-2">No treatment plans yet</h3>
          <p className="text-gray-500 mb-6">Create your first treatment plan to get started</p>
          <Link
            to="/treatment-plan/new"
            className="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
          >
            <Plus size={18} /> Create Plan
          </Link>
        </div>
      ) : (
        <div className="space-y-3">
          {filteredPlans.map(plan => (
            <div key={plan.id} className="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-6">
              <div className="flex items-start justify-between gap-4 flex-wrap">
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2 mb-2 flex-wrap">
                    <h3 className="text-lg font-semibold text-gray-900">
                      {plan.user?.display_name || 'Unknown Patient'}
                    </h3>
                    <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${getStatusColor(plan.status)}`}>
                      {plan.status}
                    </span>
                    {plan.professional?.has_cpb && (
                      <span className="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold flex items-center gap-1">
                        <CheckCircle size={12} /> CPB Verified
                      </span>
                    )}
                  </div>
                  <p className="text-gray-600 text-sm line-clamp-2 mb-3">{plan.description}</p>

                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                      <span className="text-gray-500">Duration</span>
                      <p className="font-semibold text-gray-900">{plan.duration_weeks} weeks</p>
                    </div>
                    <div>
                      <span className="text-gray-500">Sessions/Week</span>
                      <p className="font-semibold text-gray-900">{plan.sessions_per_week}</p>
                    </div>
                    <div>
                      <span className="text-gray-500">Cost/Session</span>
                      <p className="font-semibold text-gray-900">KES {plan.cost_per_session.toLocaleString()}</p>
                    </div>
                    <div>
                      <span className="text-gray-500">Total Cost</span>
                      <p className="font-semibold text-gray-900">KES {plan.total_cost.toLocaleString()}</p>
                    </div>
                  </div>

                  {plan.schedule_details && plan.schedule_details.sessions && (
                    <div className="mt-3 pt-3 border-t border-gray-200">
                      <p className="text-xs font-semibold text-gray-600 mb-2">Scheduled Sessions:</p>
                      <div className="flex flex-wrap gap-2">
                        {plan.schedule_details.sessions.slice(0, 3).map((session: any, idx: number) => (
                          <span key={idx} className="text-xs bg-gray-50 text-gray-700 px-2 py-1 rounded">
                            {new Date(session.date).toLocaleDateString()} {session.time}
                          </span>
                        ))}
                        {plan.schedule_details.sessions.length > 3 && (
                          <span className="text-xs text-gray-500">+{plan.schedule_details.sessions.length - 3} more</span>
                        )}
                      </div>
                    </div>
                  )}

                  <p className="text-xs text-gray-400 mt-3">
                    Created {new Date(plan.created_at).toLocaleDateString()}
                  </p>
                </div>

                <div className="flex gap-2">
                  <button
                    onClick={() => setSelectedPlan(plan)}
                    className="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
                    title="View details"
                  >
                    <Eye size={18} />
                  </button>
                  {plan.status === 'draft' && (
                    <>
                      <Link
                        to={`/treatment-plan/${plan.consultation_id}`}
                        className="p-2 text-green-600 hover:bg-green-50 rounded-lg"
                        title="Edit draft"
                      >
                        <Edit2 size={18} />
                      </Link>
                      <button
                        onClick={() => handleDelete(plan.id)}
                        className="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                        title="Delete draft"
                      >
                        <Trash2 size={18} />
                      </button>
                    </>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Detail Modal */}
      {selectedPlan && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
              <h2 className="text-lg font-bold text-gray-900">Treatment Plan Details</h2>
              <button
                onClick={() => setSelectedPlan(null)}
                className="text-gray-400 hover:text-gray-600"
              >
                ✕
              </button>
            </div>

            <div className="p-6 space-y-6">
              {/* Header */}
              <div>
                <div className="flex items-center gap-3 mb-3 flex-wrap">
                  <h3 className="text-xl font-bold text-gray-900">{selectedPlan.user?.display_name}</h3>
                  {selectedPlan.professional?.has_cpb && (
                    <span className="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-bold flex items-center gap-1">
                      <CheckCircle size={14} /> CPB Verified
                    </span>
                  )}
                </div>
                <span className={`text-sm px-3 py-1 rounded-full font-medium ${getStatusColor(selectedPlan.status)}`}>
                  {selectedPlan.status}
                </span>
              </div>

              {/* Description */}
              <div>
                <label className="text-sm font-semibold text-gray-700">Description</label>
                <p className="mt-2 text-gray-600 leading-relaxed">{selectedPlan.description}</p>
              </div>

              {/* Plan Details */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="text-sm font-semibold text-gray-700">Duration</label>
                  <p className="mt-1 text-2xl font-bold text-gray-900">{selectedPlan.duration_weeks}</p>
                  <p className="text-xs text-gray-500">weeks</p>
                </div>
                <div>
                  <label className="text-sm font-semibold text-gray-700">Sessions Per Week</label>
                  <p className="mt-1 text-2xl font-bold text-gray-900">{selectedPlan.sessions_per_week}</p>
                </div>
                <div>
                  <label className="text-sm font-semibold text-gray-700">Cost Per Session</label>
                  <p className="mt-1 text-2xl font-bold text-gray-900">
                    KES {selectedPlan.cost_per_session.toLocaleString()}
                  </p>
                </div>
                <div>
                  <label className="text-sm font-semibold text-gray-700">Total Cost</label>
                  <p className="mt-1 text-2xl font-bold text-green-600">
                    KES {selectedPlan.total_cost.toLocaleString()}
                  </p>
                </div>
              </div>

              {/* Scheduled Sessions */}
              {selectedPlan.schedule_details?.sessions && (
                <div>
                  <label className="text-sm font-semibold text-gray-700 block mb-3">Scheduled Sessions</label>
                  <div className="space-y-2">
                    {selectedPlan.schedule_details.sessions.map((session: any, idx: number) => (
                      <div key={idx} className="bg-gray-50 rounded-lg p-3 flex items-start gap-3">
                        <div className="text-sm">
                          <p className="font-medium text-gray-900">
                            {new Date(session.date).toLocaleDateString('en-KE', {
                              weekday: 'short',
                              month: 'short',
                              day: 'numeric',
                            })} at {session.time}
                          </p>
                          {session.notes && <p className="text-gray-600 text-xs mt-1">{session.notes}</p>}
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {/* Dates */}
              <div className="pt-4 border-t border-gray-200 text-xs text-gray-500 space-y-1">
                <p>Created: {new Date(selectedPlan.created_at).toLocaleString()}</p>
                <p>Updated: {new Date(selectedPlan.updated_at).toLocaleString()}</p>
              </div>
            </div>

            <div className="bg-gray-50 px-6 py-4 flex gap-3">
              <button
                onClick={() => setSelectedPlan(null)}
                className="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-medium"
              >
                Close
              </button>
              {selectedPlan.status === 'draft' && (
                <Link
                  to={`/treatment-plan/${selectedPlan.consultation_id}`}
                  className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-center"
                >
                  Edit
                </Link>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
