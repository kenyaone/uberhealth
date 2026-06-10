import { useEffect, useState } from 'react'
import { useParams, Link } from 'react-router-dom'
import api from '../../api/axios'
import type { Professional } from '../../types'
import { Star, Clock, Globe, ChevronLeft, CheckCircle, Bell, BellOff } from 'lucide-react'

const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

export default function ProfessionalDetail() {
  const { id } = useParams()
  const [pro, setPro] = useState<Professional | null>(null)
  const [onWaitlist, setOnWaitlist] = useState(false)
  const [waitlistLoading, setWaitlistLoading] = useState(false)

  useEffect(() => {
    api.get(`/professionals/${id}`).then(r => setPro(r.data.professional ?? r.data))
  }, [id])

  const toggleWaitlist = async () => {
    if (!id) return
    setWaitlistLoading(true)
    try {
      if (onWaitlist) {
        await api.delete(`/professionals/${id}/waitlist`)
        setOnWaitlist(false)
      } else {
        await api.post(`/professionals/${id}/waitlist`, {})
        setOnWaitlist(true)
      }
    } catch {}
    finally { setWaitlistLoading(false) }
  }

  if (!pro) return <div className="text-center py-10 text-gray-400">Loading...</div>

  return (
    <div className="max-w-3xl mx-auto space-y-5">
      <Link to="/professionals" className="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700">
        <ChevronLeft size={16} /> Back to Professionals
      </Link>

      <div className="card">
        <div className="flex items-start gap-5">
          <div className="w-20 h-20 rounded-2xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-3xl flex-shrink-0">
            {pro.display_name.charAt(0).toUpperCase()}
          </div>
          <div className="flex-1">
            <h1 className="text-2xl font-bold text-gray-900">{pro.display_name}</h1>
            <div className="flex items-center gap-1 text-amber-500 mt-1">
              <Star size={16} fill="currentColor" />
              <span className="font-medium">{pro.rating || '—'}</span>
              <span className="text-gray-400 text-sm">({pro.total_reviews} reviews, {pro.total_sessions} sessions)</span>
            </div>
            <div className="flex flex-wrap gap-3 mt-2 text-sm text-gray-500">
              <span className="flex items-center gap-1"><Clock size={14} /> {pro.years_experience} years experience</span>
              <span className="flex items-center gap-1"><Globe size={14} /> {pro.languages.map(l => l.name).join(', ')}</span>
            </div>
          </div>
          <div className="text-right">
            <div className="text-sm font-medium text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1.5 rounded-xl">Rate discussed at booking</div>
          </div>
        </div>

        <div className="flex items-center gap-2 mt-4 text-sm">
          <CheckCircle size={14} className="text-green-500" />
          <span className="text-green-700">KMPDC Verified — License: {pro.kmpdc_license}</span>
        </div>
      </div>

      <div className="card">
        <h2 className="font-semibold text-gray-900 mb-2">About</h2>
        <p className="text-gray-700 text-sm leading-relaxed">{pro.bio}</p>
      </div>

      <div className="card">
        <h2 className="font-semibold text-gray-900 mb-3">Specializations</h2>
        <div className="flex flex-wrap gap-2">
          {pro.specializations.map(s => (
            <span key={s.id} className="bg-primary-50 text-primary-800 border border-primary-200 px-3 py-1 rounded-full text-sm">
              {s.name}
            </span>
          ))}
        </div>
      </div>

      {pro.availability.length > 0 && (
        <div className="card">
          <h2 className="font-semibold text-gray-900 mb-3">Availability</h2>
          <div className="space-y-2">
            {pro.availability.filter(a => a.is_active).map(a => (
              <div key={a.id} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{DAYS[a.day_of_week]}</span>
                <span className="text-gray-900 font-medium">{a.start_time} — {a.end_time}</span>
              </div>
            ))}
          </div>
        </div>
      )}

      <div className="flex gap-3">
        <Link to={`/book/${pro.id}`} className="btn-primary flex-1 text-center py-3 block text-base">
          Book a Session
        </Link>
        <button onClick={toggleWaitlist} disabled={waitlistLoading}
          title={onWaitlist ? 'Leave waitlist' : 'Join waitlist — get notified when a slot opens'}
          className={`flex items-center gap-2 px-4 py-3 rounded-lg border font-medium text-sm transition-colors ${
            onWaitlist
              ? 'bg-amber-50 border-amber-400 text-amber-700 hover:bg-amber-100'
              : 'bg-white border-gray-300 text-gray-600 hover:border-teal-400 hover:text-teal-700'
          }`}>
          {onWaitlist ? <BellOff size={16} /> : <Bell size={16} />}
          {onWaitlist ? 'On waitlist' : 'Waitlist'}
        </button>
      </div>
    </div>
  )
}
