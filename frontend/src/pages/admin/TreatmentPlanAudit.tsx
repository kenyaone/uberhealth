import { useState, useEffect } from 'react'
import axios from 'axios'
import { FileText, User, DollarSign, Calendar, AlertCircle, Filter } from 'lucide-react'

interface TreatmentPlan {
  id: number
  professional_id: number
  user_id: number
  description: string
  total_cost: number
  status: string
  created_at: string
  updated_at: string
  professional?: { user: { display_name: string } }
  user?: { display_name: string }
}

interface AuditReport {
  professional: string
  professional_id: number
  total_plans: number
  active_plans: number
  draft_plans: number
  total_value_kes: number
  last_updated: string
}

export default function TreatmentPlanAudit() {
  const [audit, setAudit] = useState<AuditReport[]>([])
  const [plans, setPlans] = useState<TreatmentPlan[]>([])
  const [loading, setLoading] = useState(true)
  const [view, setView] = useState<'summary' | 'details'>('summary')
  const [filterStatus, setFilterStatus] = useState('')
  const [filterProfessional, setFilterProfessional] = useState('')

  useEffect(() => {
    fetchAudit()
  }, [])

  const fetchAudit = async () => {
    setLoading(true)
    try {
      const res = await axios.get('/api/admin/treatment-plans-audit')
      setAudit(res.data.by_professional || [])
      setPlans(res.data.all_plans || [])
    } catch (err) {
      console.error('Failed to load audit data', err)
    } finally {
      setLoading(false)
    }
  }

  const filteredPlans = plans.filter(p => {
    if (filterStatus && p.status !== filterStatus) return false
    if (filterProfessional && p.professional_id !== parseInt(filterProfessional)) return false
    return true
  })

  const totalValue = audit.reduce((sum, p) => sum + p.total_value_kes, 0)
  const totalPlans = plans.length

  if (loading) return <div className="text-center py-8">Loading audit data...</div>

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Treatment Plan Accountability</h1>
          <p className="text-gray-500 text-sm mt-1">Audit and monitor all prescribed treatment plans</p>
        </div>
        <button
          onClick={() => setView(view === 'summary' ? 'details' : 'summary')}
          className="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"
        >
          {view === 'summary' ? 'View Details' : 'View Summary'}
        </button>
      </div>

      {/* Overview Cards */}
      {view === 'summary' && (
        <>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div className="bg-white rounded-lg p-6 shadow border-l-4 border-blue-500">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-gray-500 text-sm">Total Plans</p>
                  <p className="text-3xl font-bold text-gray-900 mt-1">{totalPlans}</p>
                </div>
                <FileText size={32} className="text-blue-500 opacity-20" />
              </div>
            </div>

            <div className="bg-white rounded-lg p-6 shadow border-l-4 border-green-500">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-gray-500 text-sm">Total Value</p>
                  <p className="text-3xl font-bold text-gray-900 mt-1">
                    KES {(totalValue / 1000).toFixed(0)}k
                  </p>
                </div>
                <DollarSign size={32} className="text-green-500 opacity-20" />
              </div>
            </div>

            <div className="bg-white rounded-lg p-6 shadow border-l-4 border-purple-500">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-gray-500 text-sm">Professionals</p>
                  <p className="text-3xl font-bold text-gray-900 mt-1">{audit.length}</p>
                </div>
                <User size={32} className="text-purple-500 opacity-20" />
              </div>
            </div>
          </div>

          {/* Professional Summary Table */}
          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-200">
              <h2 className="text-lg font-semibold text-gray-900">By Professional</h2>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Professional</th>
                    <th className="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase">Total Plans</th>
                    <th className="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase">Active</th>
                    <th className="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase">Draft</th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Total Value</th>
                    <th className="px-6 py-3 text-xs font-medium text-gray-700 uppercase">Last Updated</th>
                  </tr>
                </thead>
                <tbody>
                  {audit.map((prof) => (
                    <tr key={prof.professional_id} className="border-b border-gray-200 hover:bg-gray-50">
                      <td className="px-6 py-4 text-sm font-medium text-gray-900">{prof.professional}</td>
                      <td className="px-6 py-4 text-sm text-center text-gray-600">{prof.total_plans}</td>
                      <td className="px-6 py-4 text-sm text-center">
                        <span className="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                          {prof.active_plans}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-sm text-center">
                        <span className="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-medium">
                          {prof.draft_plans}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-sm font-semibold text-right text-gray-900">
                        KES {prof.total_value_kes.toLocaleString()}
                      </td>
                      <td className="px-6 py-4 text-sm text-gray-500">
                        {new Date(prof.last_updated).toLocaleDateString()}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </>
      )}

      {/* Detailed Plans View */}
      {view === 'details' && (
        <>
          <div className="bg-white rounded-lg shadow p-6 space-y-4">
            <h2 className="text-lg font-semibold text-gray-900 flex items-center gap-2">
              <Filter size={20} /> Filters
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select
                  value={filterStatus}
                  onChange={(e) => setFilterStatus(e.target.value)}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">All Statuses</option>
                  <option value="draft">Draft</option>
                  <option value="active">Active</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Professional</label>
                <select
                  value={filterProfessional}
                  onChange={(e) => setFilterProfessional(e.target.value)}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">All Professionals</option>
                  {Array.from(new Set(plans.map(p => p.professional_id))).map(profId => {
                    const prof = plans.find(p => p.professional_id === profId)
                    return (
                      <option key={profId} value={profId}>
                        {prof?.professional?.user?.display_name}
                      </option>
                    )
                  })}
                </select>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
              <h2 className="text-lg font-semibold text-gray-900">All Treatment Plans ({filteredPlans.length})</h2>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead className="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th className="px-6 py-3 text-left font-medium text-gray-700">ID</th>
                    <th className="px-6 py-3 text-left font-medium text-gray-700">Professional</th>
                    <th className="px-6 py-3 text-left font-medium text-gray-700">Patient</th>
                    <th className="px-6 py-3 text-left font-medium text-gray-700">Description</th>
                    <th className="px-6 py-3 text-right font-medium text-gray-700">Cost (KES)</th>
                    <th className="px-6 py-3 text-center font-medium text-gray-700">Status</th>
                    <th className="px-6 py-3 text-left font-medium text-gray-700">Created</th>
                  </tr>
                </thead>
                <tbody>
                  {filteredPlans.map((plan) => (
                    <tr key={plan.id} className="border-b border-gray-200 hover:bg-gray-50">
                      <td className="px-6 py-4 text-gray-900 font-medium">#{plan.id}</td>
                      <td className="px-6 py-4 text-gray-900">{plan.professional?.user?.display_name}</td>
                      <td className="px-6 py-4 text-gray-600">{plan.user?.display_name}</td>
                      <td className="px-6 py-4 text-gray-600 truncate max-w-xs">{plan.description}</td>
                      <td className="px-6 py-4 text-right font-semibold text-gray-900">
                        {plan.total_cost.toLocaleString()}
                      </td>
                      <td className="px-6 py-4 text-center">
                        <span
                          className={`px-3 py-1 rounded-full text-xs font-medium ${
                            plan.status === 'active'
                              ? 'bg-green-100 text-green-800'
                              : plan.status === 'draft'
                                ? 'bg-yellow-100 text-yellow-800'
                                : plan.status === 'completed'
                                  ? 'bg-blue-100 text-blue-800'
                                  : 'bg-red-100 text-red-800'
                          }`}
                        >
                          {plan.status}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-gray-500 text-xs">
                        {new Date(plan.created_at).toLocaleDateString()}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </>
      )}
    </div>
  )
}
