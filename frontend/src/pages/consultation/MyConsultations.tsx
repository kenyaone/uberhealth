import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import type { Consultation } from '../../types'
import { format } from 'date-fns'
import { Video, Clock, CheckCircle, XCircle, AlertCircle } from 'lucide-react'

const STATUS_CONFIG: Record<string, { label: string; color: string; icon: any }> = {
  pending: { label: 'Awaiting Payment', color: 'text-yellow-700 bg-yellow-50', icon: Clock },
  confirmed: { label: 'Confirmed', color: 'text-blue-700 bg-blue-50', icon: CheckCircle },
  in_progress: { label: 'In Progress', color: 'text-green-700 bg-green-50', icon: Video },
  completed: { label: 'Completed', color: 'text-gray-600 bg-gray-50', icon: CheckCircle },
  cancelled: { label: 'Cancelled', color: 'text-red-700 bg-red-50', icon: XCircle },
}

export default function MyConsultations() {
  const [consultations, setConsultations] = useState<Consultation[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/consultations/mine/').then(r => setConsultations(r.data)).finally(() => setLoading(false))
  }, [])

  if (loading) return <div className="text-center py-10 text-gray-400">Loading sessions...</div>

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">My Sessions</h1>
        <Link to="/professionals" className="btn-primary text-sm">Book New Session</Link>
      </div>

      {consultations.length === 0 ? (
        <div className="card text-center py-10">
          <Video size={40} className="text-gray-300 mx-auto mb-3" />
          <h3 className="font-medium text-gray-600 mb-2">No sessions yet</h3>
          <p className="text-sm text-gray-400 mb-4">Find a therapist and book your first session.</p>
          <Link to="/professionals" className="btn-primary">Find a Therapist</Link>
        </div>
      ) : (
        <div className="space-y-4">
          {consultations.map((c) => {
            const statusConf = STATUS_CONFIG[c.status] || STATUS_CONFIG['pending']
            const StatusIcon = statusConf.icon
            const canJoin = ['confirmed', 'in_progress'].includes(c.status)

            return (
              <div key={c.id} className="card">
                <div className="flex items-start justify-between">
                  <div>
                    <div className="font-semibold text-gray-900">
                      {c.professional_detail?.display_name || 'Therapist'}
                    </div>
                    <div className="text-sm text-gray-500 mt-0.5">
                      {format(new Date(c.scheduled_at), 'EEE, MMM d · h:mm a')}
                      {' · '}{c.duration_minutes} min
                    </div>
                    <div className="text-sm text-gray-500">
                      KES {Number(c.amount).toLocaleString()} · {c.consultation_id}
                    </div>
                  </div>
                  <span className={`flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full ${statusConf.color}`}>
                    <StatusIcon size={12} />
                    {statusConf.label}
                  </span>
                </div>

                {c.professional_notes && (
                  <div className="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-700">
                    <span className="font-medium">Therapist's note: </span>{c.professional_notes}
                  </div>
                )}

                <div className="flex gap-2 mt-4">
                  {canJoin && (
                    <Link to={`/session/${c.consultation_id}`} className="btn-primary flex-1 text-center text-sm py-2">
                      <Video size={14} className="inline mr-1" /> Join Session
                    </Link>
                  )}
                  {c.status === 'completed' && !c.user_rating && (
                    <RateButton consultationId={c.consultation_id} onRated={() => {
                      setConsultations(prev => prev.map(x => x.consultation_id === c.consultation_id ? { ...x, user_rating: 5 } : x))
                    }} />
                  )}
                  {c.status === 'completed' && c.user_rating && (
                    <span className="text-sm text-amber-500">
                      {'⭐'.repeat(c.user_rating)} Rated
                    </span>
                  )}
                </div>
              </div>
            )
          })}
        </div>
      )}
    </div>
  )
}

function RateButton({ consultationId, onRated }: { consultationId: string; onRated: () => void }) {
  const [rating, setRating] = useState(0)
  const [showing, setShowing] = useState(false)

  const submit = async () => {
    await api.post(`/consultations/${consultationId}/rate/`, { rating })
    onRated()
    setShowing(false)
  }

  if (!showing) return (
    <button onClick={() => setShowing(true)} className="btn-secondary text-sm py-2 flex-1">
      Rate Session
    </button>
  )

  return (
    <div className="flex items-center gap-2 flex-1">
      <div className="flex gap-1">
        {[1, 2, 3, 4, 5].map(n => (
          <button key={n} onClick={() => setRating(n)} className={`text-xl ${n <= rating ? 'text-amber-400' : 'text-gray-300'}`}>★</button>
        ))}
      </div>
      <button onClick={submit} disabled={!rating} className="btn-primary text-xs py-1 px-3">Submit</button>
    </div>
  )
}
