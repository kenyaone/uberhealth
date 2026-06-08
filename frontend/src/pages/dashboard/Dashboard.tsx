import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'
import api from '../../api/axios'
import { ClipboardList, Users, Calendar, TrendingUp, Heart, AlertCircle } from 'lucide-react'
import type { Assessment, MoodLog, SobrietyTracker } from '../../types'

export default function Dashboard() {
  const user = useAuthStore((s) => s.user)
  const [assessments, setAssessments] = useState<Assessment[]>([])
  const [moodLogs, setMoodLogs] = useState<MoodLog[]>([])
  const [sobriety, setSobriety] = useState<SobrietyTracker[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    Promise.all([
      api.get('/assessments/history/?page_size=3'),
      api.get('/phr/mood/?days=7'),
      api.get('/phr/sobriety/'),
    ]).then(([a, m, s]) => {
      setAssessments(a.data.results || a.data)
      setMoodLogs(m.data.results || m.data)
      setSobriety(s.data.results || s.data)
    }).catch(() => {}).finally(() => setLoading(false))
  }, [])

  const latestMood = moodLogs[0]
  const avgMood = moodLogs.length
    ? Math.round(moodLogs.reduce((sum, l) => sum + l.mood_score, 0) / moodLogs.length * 10) / 10
    : null

  return (
    <div className="space-y-6">
      {/* Greeting */}
      <div>
        <h1 className="text-2xl font-bold text-gray-900">
          Hello, {user?.display_name} 👋
        </h1>
        <p className="text-gray-500 mt-1">How are you doing today?</p>
      </div>

      {/* Quick stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div className="card text-center">
          <ClipboardList size={24} className="text-primary-600 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">{assessments.length}</div>
          <div className="text-xs text-gray-500">Assessments</div>
        </div>
        <div className="card text-center">
          <Heart size={24} className="text-rose-500 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">{avgMood ?? '—'}</div>
          <div className="text-xs text-gray-500">Avg Mood (7d)</div>
        </div>
        <div className="card text-center">
          <TrendingUp size={24} className="text-green-500 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">
            {sobriety[0]?.current_streak ?? '—'}
          </div>
          <div className="text-xs text-gray-500">Day Streak</div>
        </div>
        <div className="card text-center">
          <Calendar size={24} className="text-blue-500 mx-auto mb-2" />
          <div className="text-2xl font-bold text-gray-900">0</div>
          <div className="text-xs text-gray-500">Upcoming Sessions</div>
        </div>
      </div>

      {/* Quick actions */}
      <div>
        <h2 className="text-lg font-semibold text-gray-900 mb-3">Quick Actions</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <Link to="/assessments" className="card hover:border-primary-300 transition-colors group">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                <ClipboardList size={20} className="text-primary-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">Take Assessment</div>
                <div className="text-xs text-gray-500">PHQ-9, AUDIT, PGSI & more</div>
              </div>
            </div>
          </Link>

          <Link to="/professionals" className="card hover:border-primary-300 transition-colors group">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                <Users size={20} className="text-blue-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">Find a Therapist</div>
                <div className="text-xs text-gray-500">Verified KMPDC professionals</div>
              </div>
            </div>
          </Link>

          <Link to="/mood" className="card hover:border-primary-300 transition-colors group">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center group-hover:bg-rose-200 transition-colors">
                <Heart size={20} className="text-rose-700" />
              </div>
              <div>
                <div className="font-medium text-gray-900">Log Your Mood</div>
                <div className="text-xs text-gray-500">Track how you're feeling</div>
              </div>
            </div>
          </Link>
        </div>
      </div>

      {/* Recent assessments */}
      {assessments.length > 0 && (
        <div>
          <div className="flex items-center justify-between mb-3">
            <h2 className="text-lg font-semibold text-gray-900">Recent Assessments</h2>
            <Link to="/assessments" className="text-sm text-primary-600 hover:underline">View all</Link>
          </div>
          <div className="space-y-3">
            {assessments.map((a) => (
              <div key={a.id} className="card flex items-center justify-between">
                <div>
                  <div className="font-medium text-gray-900">{a.assessment_type_display}</div>
                  <div className="text-sm text-gray-500">
                    Score: {a.score} — <span className={`font-medium ${
                      a.severity.includes('Severe') ? 'text-red-600' :
                      a.severity.includes('Moderate') ? 'text-yellow-600' : 'text-green-600'
                    }`}>{a.severity}</span>
                  </div>
                </div>
                {a.is_crisis_flag && (
                  <span className="flex items-center gap-1 text-red-600 text-xs font-medium bg-red-50 px-2 py-1 rounded">
                    <AlertCircle size={12} /> Crisis Flag
                  </span>
                )}
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Sobriety trackers */}
      {sobriety.length > 0 && (
        <div>
          <h2 className="text-lg font-semibold text-gray-900 mb-3">Sobriety Streaks</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {sobriety.filter(s => s.is_active).map((s) => (
              <div key={s.id} className="card">
                <div className="text-sm text-gray-500 capitalize mb-1">{s.substance}</div>
                <div className="text-3xl font-bold text-primary-700">{s.current_streak}</div>
                <div className="text-sm text-gray-500">days sober 🎯</div>
                <div className="text-xs text-gray-400 mt-1">Best: {s.longest_streak} days</div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Crisis hotlines */}
      <div className="bg-red-50 border border-red-200 rounded-xl p-4">
        <div className="font-medium text-red-800 mb-2 flex items-center gap-2">
          <AlertCircle size={16} />
          Need immediate help?
        </div>
        <div className="text-sm text-red-700 space-y-1">
          <div>📞 Befrienders Kenya: <strong>0800 723 253</strong> (Free, 24/7)</div>
          <div>📞 NACADA: <strong>1192</strong> (Free, 24/7)</div>
          <div>📞 Emergency: <strong>999</strong></div>
        </div>
      </div>
    </div>
  )
}
