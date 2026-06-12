import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import { ChevronRight, CheckCircle, Loader2, Lock, Calendar, ArrowLeft } from 'lucide-react'

interface Lesson {
  id: number
  title: string
  slug: string
  summary?: string
  category: string
  level: string
  duration_minutes: number
  thumbnail_emoji?: string
  user_progress?: { completed: boolean; progress_pct: number }
}

const CATEGORIES = [
  { key: 'refusal',    label: 'Refusal Skills',    emoji: '🛡️', bg: 'bg-amber-50',  border: 'border-amber-200',  text: 'text-amber-900'  },
  { key: 'alcohol',    label: 'Alcohol Recovery',  emoji: '🌿', bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-900' },
  { key: 'gambling',   label: 'Gambling Recovery', emoji: '🎯', bg: 'bg-purple-50', border: 'border-purple-200', text: 'text-purple-900' },
  { key: 'depression', label: 'Depression',        emoji: '🌤️', bg: 'bg-blue-50',   border: 'border-blue-200',   text: 'text-blue-900'   },
  { key: 'anxiety',    label: 'Anxiety',           emoji: '🌊', bg: 'bg-teal-50',   border: 'border-teal-200',   text: 'text-teal-900'   },
  { key: 'trauma',     label: 'Trauma',            emoji: '🌱', bg: 'bg-rose-50',   border: 'border-rose-200',   text: 'text-rose-900'   },
  { key: 'wellness',   label: 'Wellness',          emoji: '✨', bg: 'bg-green-50',  border: 'border-green-200',  text: 'text-green-900'  },
]

function isScenario(title: string) {
  const t = title.toLowerCase()
  return t.startsWith('scenario:') || t.startsWith('branching in practice') || t.startsWith('branching after')
}

function cleanTitle(title: string) {
  return title
    .replace(/^Scenario:\s*/i, '')
    .replace(/^Branching in Practice:\s*/i, '')
    .replace(/^Branching After a Relapse:\s*/i, 'After a Relapse: ')
}

export default function RecoveryLibrary() {
  const [lessons, setLessons]   = useState<Lesson[]>([])
  const [loading, setLoading]   = useState(true)
  const [locked, setLocked]     = useState(false)
  const [activeCat, setActiveCat] = useState<string | null>(null)

  useEffect(() => {
    api.get('/lessons')
      .then(r => {
        const raw = r.data.data ?? r.data.results ?? r.data
        setLessons(Array.isArray(raw) ? raw : [])
      })
      .catch(e => {
        if (e.response?.status === 403) setLocked(true)
        setLessons([])
      })
      .finally(() => setLoading(false))
  }, [])

  const activeCfg = CATEGORIES.find(c => c.key === activeCat)
  const catLessons = activeCat ? lessons.filter(l => l.category === activeCat) : []
  const foundations = catLessons.filter(l => !isScenario(l.title))
  const scenarios   = catLessons.filter(l =>  isScenario(l.title))

  return (
    <div className="max-w-2xl space-y-5">

      {/* Header */}
      <div className="flex items-center gap-3">
        {activeCat && (
          <button onClick={() => setActiveCat(null)} className="text-gray-400 hover:text-gray-700 transition-colors">
            <ArrowLeft size={20} />
          </button>
        )}
        <div>
          <h1 className="text-xl font-bold text-gray-900">
            {activeCfg ? `${activeCfg.emoji} ${activeCfg.label}` : 'Recovery Library'}
          </h1>
          {!activeCat && <p className="text-gray-500 text-sm mt-0.5">Choose a topic to get started.</p>}
        </div>
      </div>

      {loading ? (
        <div className="flex justify-center py-16"><Loader2 size={28} className="animate-spin text-primary-500" /></div>
      ) : locked ? (
        <div className="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-4">
          <Lock size={32} className="text-amber-500 flex-shrink-0" />
          <div className="flex-1">
            <p className="font-semibold text-amber-900">Lessons unlock after your first session</p>
            <p className="text-sm text-amber-700 mt-1">Book a paid session to access the full library.</p>
          </div>
          <Link to="/professionals" className="btn-primary text-sm px-5 py-2.5 flex items-center gap-2 flex-shrink-0">
            <Calendar size={14} /> Book a Session
          </Link>
        </div>
      ) : !activeCat ? (

        /* ── Category grid ── */
        <div className="grid grid-cols-2 gap-3">
          {CATEGORIES.filter(c => lessons.some(l => l.category === c.key)).map(cat => {
            const total = lessons.filter(l => l.category === cat.key).length
            const done  = lessons.filter(l => l.category === cat.key && l.user_progress?.completed).length
            return (
              <button key={cat.key} onClick={() => setActiveCat(cat.key)}
                className={`${cat.bg} ${cat.border} border rounded-2xl p-4 text-left hover:shadow-md transition-all group`}>
                <span className="text-3xl leading-none block mb-2">{cat.emoji}</span>
                <p className={`font-bold text-sm ${cat.text}`}>{cat.label}</p>
                <p className="text-xs text-gray-500 mt-1">{total} lessons{done > 0 ? ` · ${done} done` : ''}</p>
              </button>
            )
          })}
        </div>

      ) : (

        /* ── Lesson list ── */
        <div className="space-y-5">
          {foundations.length > 0 && (
            <div>
              <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Foundation</p>
              <div className="space-y-2">
                {foundations.map((l, i) => <LessonCard key={l.id} lesson={l} index={i + 1} />)}
              </div>
            </div>
          )}
          {scenarios.length > 0 && (
            <div>
              <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Scenarios</p>
              <div className="space-y-2">
                {scenarios.map((l, i) => <LessonCard key={l.id} lesson={l} index={i + 1} scenario />)}
              </div>
            </div>
          )}
        </div>

      )}
    </div>
  )
}

function LessonCard({ lesson, index, scenario }: { lesson: Lesson; index: number; scenario?: boolean }) {
  const done = lesson.user_progress?.completed
  return (
    <Link to={`/lessons/${lesson.slug}`}
      className={`flex items-center gap-3 rounded-xl border px-4 py-3 transition-all hover:shadow-sm group ${
        done ? 'bg-green-50 border-green-200' : scenario ? 'bg-gray-50 border-gray-200' : 'bg-white border-gray-200 hover:border-primary-200'
      }`}>
      <div className={`w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-sm font-bold ${
        done ? 'bg-green-100 text-green-700' : scenario ? 'bg-gray-200 text-gray-500' : 'bg-primary-50 text-primary-700'
      }`}>
        {done
          ? <CheckCircle size={15} className="text-green-600" />
          : lesson.thumbnail_emoji
            ? <span className="text-base leading-none">{lesson.thumbnail_emoji}</span>
            : <span className="text-xs">{index}</span>
        }
      </div>
      <div className="flex-1 min-w-0">
        <p className="font-medium text-gray-900 group-hover:text-primary-700 text-sm leading-snug">
          {cleanTitle(lesson.title)}
        </p>
        <div className="flex items-center gap-2 mt-0.5">
          <span className={`text-xs capitalize ${
            lesson.level === 'beginner' ? 'text-green-600' : lesson.level === 'intermediate' ? 'text-yellow-600' : 'text-red-500'
          }`}>{lesson.level}</span>
          {lesson.duration_minutes ? <span className="text-xs text-gray-400">{lesson.duration_minutes} min</span> : null}
        </div>
      </div>
      <ChevronRight size={14} className="text-gray-300 group-hover:text-primary-400 flex-shrink-0" />
    </Link>
  )
}
