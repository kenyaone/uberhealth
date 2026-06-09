import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import type { Consultation } from '../../types'
import { format } from 'date-fns'
import { Video, Clock, CheckCircle, XCircle, AlertCircle, FileText, Calendar, PlayCircle } from 'lucide-react'

const STATUS_CONFIG: Record<string, { label: string; color: string; icon: any }> = {
  pending: { label: 'Awaiting Payment', color: 'text-yellow-700 bg-yellow-50', icon: Clock },
  confirmed: { label: 'Confirmed', color: 'text-blue-700 bg-blue-50', icon: CheckCircle },
  in_progress: { label: 'In Progress', color: 'text-green-700 bg-green-50', icon: Video },
  completed: { label: 'Completed', color: 'text-gray-600 bg-gray-50', icon: CheckCircle },
  cancelled: { label: 'Cancelled', color: 'text-red-700 bg-red-50', icon: XCircle },
}

export default function MyConsultations() {
  const user = useAuthStore(s => s.user)
  const isProfessional = user?.role === 'professional'
  const [consultations, setConsultations] = useState<Consultation[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    const endpoint = isProfessional ? '/consultations/professional/list' : '/consultations'
    api.get(endpoint)
      .then(r => {
        const data = r.data.data ?? r.data.results ?? r.data
        setConsultations(Array.isArray(data) ? data : [])
      })
      .catch(() => setError('Could not load sessions.'))
      .finally(() => setLoading(false))
  }, [isProfessional])

  if (loading) return <div className="text-center py-10 text-gray-400">Loading sessions...</div>

  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">{isProfessional ? 'My Patient Sessions' : 'My Sessions'}</h1>
        {!isProfessional && <Link to="/professionals" className="btn-primary text-sm">Book New Session</Link>}
      </div>

      {error && <div className="text-sm text-red-600 bg-red-50 p-3 rounded-lg">{error}</div>}

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
            const statusLabel = (isProfessional && c.status === 'in_progress') ? 'Live' : statusConf.label

            return (
              <div key={c.id} className="card">
                <div className="flex items-start justify-between">
                  <div>
                    <div className="font-semibold text-gray-900">
                      {isProfessional
                        ? (c.user_display_name || 'Patient')
                        : (c.professional_detail?.display_name || 'Therapist')}
                    </div>
                    <div className="text-sm text-gray-500 mt-0.5">
                      {format(new Date(c.scheduled_at), 'EEE, MMM d · h:mm a')}
                      {' · '}{c.duration_minutes} min
                    </div>
                    <div className="text-sm text-gray-500">
                      KES {Number(c.amount).toLocaleString()} · {c.consultation_id}
                    </div>
                  </div>
                  <span className={`flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full ${isProfessional && c.status === 'in_progress' ? 'text-green-700 bg-green-100 ring-1 ring-green-400' : statusConf.color}`}>
                    <StatusIcon size={12} />
                    {statusLabel}
                  </span>
                </div>

                {c.professional_notes && (
                  <div className="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-700">
                    <span className="font-medium">Therapist's note: </span>{c.professional_notes}
                  </div>
                )}

                <div className="flex flex-wrap gap-2 mt-4">
                  {canJoin && (
                    <Link
                      to={`/session/${c.consultation_id}`}
                      className={`flex-1 text-center text-sm py-2 rounded-lg font-medium flex items-center justify-center gap-1.5 transition-colors ${
                        isProfessional
                          ? 'bg-blue-600 hover:bg-blue-700 text-white'
                          : 'btn-primary'
                      }`}
                    >
                      {isProfessional ? (
                        <><PlayCircle size={14} /> Start Session</>
                      ) : (
                        <><Video size={14} /> Join Session</>
                      )}
                    </Link>
                  )}
                  {c.status === 'completed' && !c.user_rating && (
                    <RateButton consultationId={c.id} onRated={() => {
                      setConsultations(prev => prev.map(x => x.id === c.id ? { ...x, user_rating: 5 } : x))
                    }} />
                  )}
                  {c.status === 'completed' && c.user_rating && (
                    <span className="text-sm text-amber-500 self-center">
                      {'⭐'.repeat(c.user_rating)} Rated
                    </span>
                  )}
                  {c.status === 'completed' && !isProfessional && (
                    <>
                      <RequestNotesButton consultationId={c.id} alreadyRequested={!!c.notes_requested_at} />
                      <ReceiptButton consultationId={(c as any).consultation_id} />
                      <Link
                        to={`/book/${c.professional_id ?? (typeof c.professional === 'object' ? (c.professional as any).id : c.professional)}`}
                        state={{ is_follow_up: true, parent_id: c.id }}
                        className="btn-secondary text-xs py-1.5 px-3 flex items-center gap-1"
                      >
                        <Calendar size={13} /> Follow-up
                      </Link>
                    </>
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

function RequestNotesButton({ consultationId, alreadyRequested }: { consultationId: number; alreadyRequested: boolean }) {
  const [requested, setRequested] = useState(alreadyRequested)

  const handleRequest = async () => {
    try {
      await api.post(`/consultations/${consultationId}/notes-request`)
      setRequested(true)
    } catch {}
  }

  return requested ? (
    <span className="text-xs text-gray-500 self-center flex items-center gap-1">
      <FileText size={12} /> Notes requested
    </span>
  ) : (
    <button onClick={handleRequest} className="btn-secondary text-xs py-1.5 px-3 flex items-center gap-1">
      <FileText size={13} /> Request Notes
    </button>
  )
}

function ReceiptButton({ consultationId }: { consultationId: string }) {
  const [loading, setLoading] = useState(false)

  const download = async () => {
    if (!consultationId) return
    setLoading(true)
    try {
      const r = await api.get(`/consultations/${consultationId}/receipt`)
      const d = r.data.receipt
      const html = `<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Clinical Receipt ${d.receipt_number}</title>
<style>body{font-family:sans-serif;max-width:600px;margin:40px auto;padding:20px;color:#111}
h1{color:#0d9488;font-size:20px}table{width:100%;border-collapse:collapse;margin:16px 0}
td{padding:8px 10px;border:1px solid #e5e7eb;font-size:13px}td:first-child{background:#f9fafb;font-weight:600;width:40%}
.header{border-bottom:2px solid #0d9488;padding-bottom:12px;margin-bottom:20px}
.footer{margin-top:24px;font-size:11px;color:#9ca3af;text-align:center}</style></head>
<body><div class="header"><h1>Afya Yako Siri Yako — Clinical Receipt</h1>
<p style="margin:4px 0;font-size:13px;color:#6b7280">${d.platform_url} · For insurance reimbursement</p></div>
<table>
<tr><td>Receipt No.</td><td>${d.receipt_number}</td></tr>
<tr><td>Session ID</td><td>${d.consultation_id}</td></tr>
<tr><td>Issue Date</td><td>${d.issue_date}</td></tr>
<tr><td>Session Date &amp; Time</td><td>${d.session_date} (EAT)</td></tr>
<tr><td>Duration</td><td>${d.duration_minutes} minutes</td></tr>
<tr><td>Patient</td><td>${d.patient_name}</td></tr>
<tr><td>Therapist</td><td>${d.therapist_name}</td></tr>
<tr><td>KMPDC License No.</td><td>${d.kmpdc_license}</td></tr>
<tr><td>Service</td><td>${d.service_type}</td></tr>
<tr><td>ICD-10 Code</td><td>${d.icd10_code} — ${d.icd10_description}</td></tr>
<tr><td><strong>Amount (KES)</strong></td><td><strong>${d.amount_kes}</strong></td></tr>
</table>
<p style="font-size:12px;color:#4b5563">This receipt is issued for insurance reimbursement purposes. The session was conducted via secure encrypted telehealth video (Jitsi Meet). For insurer queries contact: billing@mhapke.com</p>
<div class="footer">Afya Yako Siri Yako · mhapke.com · Nairobi, Kenya</div></body></html>`
      const blob = new Blob([html], { type: 'text/html' })
      const a = document.createElement('a')
      a.href = URL.createObjectURL(blob)
      a.download = `receipt-${d.receipt_number}.html`
      a.click()
    } catch {}
    finally { setLoading(false) }
  }

  return (
    <button onClick={download} disabled={loading} className="btn-secondary text-xs py-1.5 px-3 flex items-center gap-1">
      <FileText size={13} /> {loading ? '…' : 'Receipt'}
    </button>
  )
}

function RateButton({ consultationId, onRated }: { consultationId: number; onRated: () => void }) {
  const [rating, setRating] = useState(0)
  const [showing, setShowing] = useState(false)

  const submit = async () => {
    await api.post(`/consultations/${consultationId}/rate`, { rating })
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
