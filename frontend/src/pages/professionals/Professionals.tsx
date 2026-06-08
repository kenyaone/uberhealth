import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import type { Professional } from '../../types'
import { Star, Search, Filter } from 'lucide-react'

export default function Professionals() {
  const [professionals, setProfessionals] = useState<Professional[]>([])
  const [loading, setLoading] = useState(true)
  const [search, setSearch] = useState('')
  const [specialization, setSpecialization] = useState('')
  const [maxRate, setMaxRate] = useState('')

  useEffect(() => {
    const params = new URLSearchParams()
    if (search) params.set('search', search)
    if (specialization) params.set('specialization', specialization)
    if (maxRate) params.set('max_rate', maxRate)

    setLoading(true)
    api.get(`/professionals/?${params.toString()}`)
      .then(r => setProfessionals(r.data.results || r.data))
      .finally(() => setLoading(false))
  }, [search, specialization, maxRate])

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Find a Therapist</h1>
        <p className="text-gray-500 mt-1">All professionals are KMPDC-verified.</p>
      </div>

      {/* Filters */}
      <div className="card">
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div className="relative">
            <Search size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              value={search}
              onChange={e => setSearch(e.target.value)}
              className="input-field pl-9"
              placeholder="Search by name, issue..."
            />
          </div>
          <select
            value={specialization}
            onChange={e => setSpecialization(e.target.value)}
            className="input-field"
          >
            <option value="">All Specializations</option>
            <option value="depression">Depression</option>
            <option value="anxiety">Anxiety</option>
            <option value="substance-use">Substance Use</option>
            <option value="alcohol">Alcohol Recovery</option>
            <option value="gambling">Gambling</option>
            <option value="trauma">Trauma & PTSD</option>
          </select>
          <select value={maxRate} onChange={e => setMaxRate(e.target.value)} className="input-field">
            <option value="">Any Rate</option>
            <option value="2000">Up to KES 2,000</option>
            <option value="3000">Up to KES 3,000</option>
            <option value="5000">Up to KES 5,000</option>
          </select>
        </div>
      </div>

      {loading ? (
        <div className="text-center py-10 text-gray-400">Loading professionals...</div>
      ) : professionals.length === 0 ? (
        <div className="text-center py-10 text-gray-400">
          No professionals found. Try adjusting your filters.
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
          {professionals.map((pro) => (
            <div key={pro.id} className="card hover:shadow-md transition-shadow">
              <div className="flex items-start gap-4">
                <div className="w-14 h-14 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xl flex-shrink-0">
                  {pro.display_name.charAt(0).toUpperCase()}
                </div>
                <div className="flex-1 min-w-0">
                  <div className="font-semibold text-gray-900">{pro.display_name}</div>
                  <div className="flex items-center gap-1 text-sm text-amber-500 mt-0.5">
                    <Star size={14} fill="currentColor" />
                    <span>{pro.rating || '—'}</span>
                    <span className="text-gray-400">({pro.total_reviews} reviews)</span>
                  </div>
                  <div className="text-sm text-gray-500 mt-1">
                    {pro.years_experience} yrs exp · {pro.languages.map(l => l.name).join(', ')}
                  </div>
                </div>
                <div className="text-right flex-shrink-0">
                  <div className="font-bold text-primary-700">KES {Number(pro.rate_per_hour).toLocaleString()}</div>
                  <div className="text-xs text-gray-400">per hour</div>
                </div>
              </div>

              {pro.specializations.length > 0 && (
                <div className="flex flex-wrap gap-1.5 mt-3">
                  {pro.specializations.slice(0, 4).map(s => (
                    <span key={s.id} className="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full">
                      {s.name}
                    </span>
                  ))}
                </div>
              )}

              <p className="text-sm text-gray-600 mt-3 line-clamp-2">{pro.bio}</p>

              <div className="flex gap-2 mt-4">
                <Link to={`/professionals/${pro.id}`} className="btn-secondary flex-1 text-center text-sm py-2">
                  View Profile
                </Link>
                <Link to={`/book/${pro.id}`} className="btn-primary flex-1 text-center text-sm py-2">
                  Book Session
                </Link>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
