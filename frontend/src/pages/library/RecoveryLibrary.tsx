import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import {
  BookOpen, ChevronRight, CheckCircle, Search,
  Loader2, Lock, Calendar, BookText, Clapperboard
} from 'lucide-react'

interface Lesson {
  id: number
  title: string
  slug: string
  summary?: string
  description?: string
  category: string
  level: string
  duration_minutes: number
  thumbnail_emoji?: string
  user_progress?: { completed: boolean; progress_pct: number }
}

const CATEGORY_CONFIG: Record<string, { label: string; emoji: string; color: string; text: string }> = {
  refusal:    { label: 'Refusal Skills',    emoji: '🛡️', color: 'bg-amber-100',  text: 'text-amber-800'  },
  alcohol:    { label: 'Alcohol Recovery',  emoji: '🌿', color: 'bg-orange-100', text: 'text-orange-800' },
  gambling:   { label: 'Gambling Recovery', emoji: '🎯', color: 'bg-purple-100', text: 'text-purple-800' },
  depression: { label: 'Depression',        emoji: '🌤️', color: 'bg-blue-100',   text: 'text-blue-800'   },
  anxiety:    { label: 'Anxiety',           emoji: '🌊', color: 'bg-teal-100',   text: 'text-teal-800'   },
  trauma:     { label: 'Trauma',            emoji: '🌱', color: 'bg-rose-100',   text: 'text-rose-800'   },
  wellness:   { label: 'Wellness',          emoji: '✨', color: 'bg-green-100',  text: 'text-green-800'  },
}

const CAT_ORDER = ['refusal', 'alcohol', 'gambling', 'depression', 'anxiety', 'trauma', 'wellness']

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
  const [lessons, setLessons] = useState<Lesson[]>([])
  const [loading, setLoading] = useState(true)
  const [locked, setLocked]   = useState(false)
  const [search, setSearch]   = useState('')
  const [activeCat, setActiveCat] = useState('')

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

  const completedCount = lessons.filter(l => l.user_progress?.completed).length

  const filtered = lessons.filter(l => {
    const q = search.toLowerCase()
    const matchSearch = !q || l.title.toLowerCase().includes(q) || (l.summary ?? l.description ?? '').toLowerCase().includes(q)
    const matchCat = !activeCat || l.category === activeCat
    return matchSearch && matchCat
  })

  const cats = CAT_ORDER.filter(c => lessons.some(l => l.category === c))

  // Group filtered lessons by category, splitting each into learn + scenario
  const grouped = cats.reduce<Record<string, { learn: Lesson[]; scenario: Lesson[] }>>((acc, cat) => {
    const items = filtered.filter(l => l.category === cat)
    if (!items.length) return acc
    acc[cat] = {
      learn:    items.filter(l => !isScenario(l.title)),
      scenario: items.filter(l =>  isScenario(l.title)),
    }
    return acc
  }, {})

  return (
    <div className="max-w-3xl space-y-6">

      {/* Header */}
      <div className="flex items-center justify-between flex-wrap gap-3">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Recovery Library</h1>
          <p className="text-gray-500 text-sm mt-0.5">Lessons and real-world scenarios to support your recovery.</p>
        </div>
        {completedCount > 0 && (
          <span className="bg-teal-50 border border-teal-200 rounded-xl px-4 py-2 text-sm text-teal-800 font-medium flex items-center gap-1.5">
            <CheckCircle size={14} className="text-teal-600" />
            {completedCount} of {lessons.length} completed
          </span>
        )}
      </div>

      {loading ? (
        <div className="flex justify-center py-16">
          <Loader2 size={28} className="animate-spin text-primary-500" />
        </div>
      ) : locked ? (
        <div className="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-5">
          <Lock size={36} className="text-amber-500 flex-shrink-0" />
          <div className="flex-1">
            <p className="font-semibold text-amber-900">Lessons unlock after your first paid session</p>
            <p className="text-sm text-amber-700 mt-1">This keeps content exclusive to committed members and funds clinical quality.</p>
          </div>
          <Link to="/professionals" className="btn-primary flex items-center gap-2 flex-shrink-0 text-sm px-5 py-2.5">
            <Calendar size={14} /> Book a Session
          </Link>
        </div>
      ) : (
        <>
          {/* Search */}
          <div className="relative">
            <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              value={search}
              onChange={e => setSearch(e.target.value)}
              placeholder="Search lessons…"
              className="input-field pl-9 text-sm w-full"
            />
          </div>

          {/* Category filter pills */}
          <div className="flex gap-2 flex-wrap">
            <button
              onClick={() => setActiveCat('')}
              className={`text-xs px-3 py-1.5 rounded-full border font-medium transition-colors ${
                !activeCat ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'
              }`}
            >
              All topics
            </button>
            {cats.map(cat => {
              const cfg = CATEGORY_CONFIG[cat]
              if (!cfg) return null
              return (
                <button
                  key={cat}
                  onClick={() => setActiveCat(activeCat === cat ? '' : cat)}
                  className={`text-xs px-3 py-1.5 rounded-full border font-medium transition-colors flex items-center gap-1 ${
                    activeCat === cat
                      ? `${cfg.color} ${cfg.text} border-transparent`
                      : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'
                  }`}
                >
                  {cfg.emoji} {cfg.label}
                </button>
              )
            })}
          </div>

          {/* Lessons */}
          {Object.keys(grouped).length === 0 ? (
            <p className="text-sm text-gray-400 text-center py-12">No lessons match your search.</p>
          ) : (
            <div className="space-y-8">
              {Object.entries(grouped).map(([cat, { learn, scenario }]) => {
                const cfg = CATEGORY_CONFIG[cat]
                return (
                  <section key={cat}>
                    {/* Category header */}
                    <div className="flex items-center gap-2 mb-4">
                      <span className="text-xl leading-none">{cfg?.emoji}</span>
                      <h2 className="font-bold text-gray-900">{cfg?.label ?? cat}</h2>
                      <span className="text-xs text-gray-400 ml-1">
                        {learn.length + scenario.length} lessons
                      </span>
                    </div>

                    {/* Foundation lessons */}
                    {learn.length > 0 && (
                      <div className="mb-4">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                          <BookText size={11} /> Foundation
                        </p>
                        <div className="space-y-2">
                          {learn.map((lesson, i) => (
                            <LessonCard key={lesson.id} lesson={lesson} index={i + 1} type="learn" />
                          ))}
                        </div>
                      </div>
                    )}

                    {/* Scenario lessons */}
                    {scenario.length > 0 && (
                      <div>
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                          <Clapperboard size={11} /> Real-World Scenarios
                        </p>
                        <div className="space-y-2">
                          {scenario.map((lesson, i) => (
                            <LessonCard key={lesson.id} lesson={lesson} index={i + 1} type="scenario" />
                          ))}
                        </div>
                      </div>
                    )}
                  </section>
                )
              })}
            </div>
          )}
        </>
      )}
    </div>
  )
}

function LessonCard({ lesson, index, type }: {
  lesson: Lesson
  index: number
  type: 'learn' | 'scenario'
}) {
  const done    = lesson.user_progress?.completed
  const pct     = lesson.user_progress?.progress_pct ?? 0
  const scenario = type === 'scenario'

  return (
    <Link
      to={`/lessons/${lesson.slug}`}
      className={`flex items-center gap-3 rounded-xl border px-4 py-3 transition-all hover:shadow-sm cursor-pointer group ${
        done       ? 'bg-green-50 border-green-200' :
        scenario   ? 'bg-gray-50 border-gray-200 hover:border-gray-300' :
                     'bg-white border-gray-200 hover:border-primary-200 hover:bg-primary-50/30'
      }`}
    >
      {/* Number / emoji */}
      <div className={`w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-sm font-bold ${
        done     ? 'bg-green-100 text-green-700' :
        scenario ? 'bg-gray-200 text-gray-500' :
                   'bg-primary-50 text-primary-700'
      }`}>
        {done
          ? <CheckCircle size={16} className="text-green-600" />
          : lesson.thumbnail_emoji
            ? <span className="text-base leading-none">{lesson.thumbnail_emoji}</span>
            : <span className="text-xs">{index}</span>
        }
      </div>

      {/* Content */}
      <div className="flex-1 min-w-0">
        <p className="font-medium text-gray-900 group-hover:text-primary-700 transition-colors text-sm leading-snug">
          {cleanTitle(lesson.title)}
        </p>
        {(lesson.summary ?? lesson.description) && (
          <p className="text-xs text-gray-400 mt-0.5 line-clamp-1">{lesson.summary ?? lesson.description}</p>
        )}
        <div className="flex items-center gap-2 mt-1 flex-wrap">
          {lesson.level && (
            <span className={`text-xs px-1.5 py-0.5 rounded capitalize ${
              lesson.level === 'beginner' ? 'bg-green-100 text-green-700' :
              lesson.level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' :
              'bg-red-100 text-red-700'
            }`}>{lesson.level}</span>
          )}
          {lesson.duration_minutes ? (
            <span className="text-xs text-gray-400">{lesson.duration_minutes} min</span>
          ) : null}
        </div>
        {pct > 0 && !done && (
          <div className="mt-1.5 h-1 bg-gray-100 rounded-full w-24">
            <div className="h-full bg-primary-500 rounded-full" style={{ width: `${pct}%` }} />
          </div>
        )}
      </div>

      <ChevronRight size={14} className="text-gray-300 group-hover:text-primary-400 flex-shrink-0" />
    </Link>
  )
}
