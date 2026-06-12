import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import {
  BookOpen, Wind, MessageCircle, HandHeart, Heart, TrendingUp,
  Shield, Target, BookMarked, ChevronRight, CheckCircle, Search,
  Loader2, Lock, Calendar, Zap, Brain, Play, BookText, Clapperboard,
  ChevronDown, ArrowLeft, Users, Flame
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

// ── Config ────────────────────────────────────────────────────────────────

const CATEGORY_CONFIG: Record<string, {
  label: string
  emoji: string
  desc: string
  color: string        // tailwind bg for card
  accent: string       // tailwind text / border for badges
  border: string
}> = {
  refusal:    { label: 'Refusal Skills',     emoji: '🛡️', desc: 'Learn to say no confidently and handle pressure from others.',         color: 'bg-amber-50',  accent: 'text-amber-700',  border: 'border-amber-200' },
  alcohol:    { label: 'Alcohol Recovery',   emoji: '🌿', desc: 'Tools and real stories for staying sober across everyday situations.', color: 'bg-orange-50', accent: 'text-orange-700', border: 'border-orange-200' },
  gambling:   { label: 'Gambling Recovery',  emoji: '🎯', desc: 'Understand urges, triggers, and how to rebuild financial safety.',      color: 'bg-purple-50', accent: 'text-purple-700', border: 'border-purple-200' },
  depression: { label: 'Depression',         emoji: '🌤️', desc: 'Evidence-based skills for managing low mood and negative thinking.',   color: 'bg-blue-50',   accent: 'text-blue-700',   border: 'border-blue-200'   },
  anxiety:    { label: 'Anxiety',            emoji: '🌊', desc: 'Breathing, grounding, and thought techniques for anxious moments.',     color: 'bg-teal-50',   accent: 'text-teal-700',   border: 'border-teal-200'   },
  trauma:     { label: 'Trauma',             emoji: '🌱', desc: 'Gentle approaches to understanding and processing past experiences.',   color: 'bg-rose-50',   accent: 'text-rose-700',   border: 'border-rose-200'   },
  wellness:   { label: 'Wellness',           emoji: '✨', desc: 'Daily habits and practices that support long-term recovery.',           color: 'bg-green-50',  accent: 'text-green-700',  border: 'border-green-200'  },
}

const EXERCISES = [
  { id: 'box',         name: 'Box Breathing',        icon: Wind,  color: 'teal',   desc: '4-4-4-4 for instant calm' },
  { id: '478',         name: '4-7-8 Breathing',       icon: Wind,  color: 'blue',   desc: 'Best before sleep' },
  { id: 'grounding',   name: '5-4-3-2-1 Grounding',  icon: Zap,   color: 'green',  desc: 'Anchor to the present' },
  { id: 'progressive', name: 'Muscle Relaxation',     icon: Brain, color: 'purple', desc: 'Release physical tension' },
]

const SELF_CARE = [
  { to: '/mood',        icon: Heart,      label: 'Mood Tracker',     desc: 'Log how you feel daily',         color: 'pink'   },
  { to: '/sobriety',    icon: TrendingUp, label: 'Sobriety Tracker', desc: 'Track your clean days',          color: 'teal'   },
  { to: '/safety-plan', icon: Shield,     label: 'Safety Plan',      desc: 'Your personal crisis protocol',  color: 'blue'   },
  { to: '/goals',       icon: Target,     label: 'Recovery Goals',   desc: 'Set and track your milestones',  color: 'orange' },
  { to: '/journal',     icon: BookMarked, label: 'My Journal',       desc: 'Private reflective writing',     color: 'green'  },
]

const COLOR_MAP: Record<string, { bg: string; icon: string; badge: string }> = {
  teal:   { bg: 'bg-teal-50',   icon: 'text-teal-600',   badge: 'bg-teal-100'   },
  blue:   { bg: 'bg-blue-50',   icon: 'text-blue-600',   badge: 'bg-blue-100'   },
  green:  { bg: 'bg-green-50',  icon: 'text-green-600',  badge: 'bg-green-100'  },
  purple: { bg: 'bg-purple-50', icon: 'text-purple-600', badge: 'bg-purple-100' },
  pink:   { bg: 'bg-pink-50',   icon: 'text-pink-600',   badge: 'bg-pink-100'   },
  orange: { bg: 'bg-orange-50', icon: 'text-orange-600', badge: 'bg-orange-100' },
}

// Detect whether a lesson is a scenario or a foundation/instructional lesson
function lessonType(title: string): 'scenario' | 'learn' {
  const t = title.toLowerCase()
  if (t.startsWith('scenario:') || t.startsWith('branching in practice') || t.startsWith('branching after')) return 'scenario'
  return 'learn'
}

// ── Component ─────────────────────────────────────────────────────────────

export default function RecoveryLibrary() {
  const [lessons, setLessons]           = useState<Lesson[]>([])
  const [loading, setLoading]           = useState(true)
  const [locked, setLocked]             = useState(false)
  const [search, setSearch]             = useState('')
  const [activeCategory, setActiveCategory] = useState<string | null>(null)

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

  const allCategories = [...new Set(lessons.map(l => l.category).filter(Boolean))]
  const completedCount = lessons.filter(l => l.user_progress?.completed).length

  // When a category is active, filter + split lessons for that category
  const activeLessons = activeCategory
    ? lessons.filter(l => l.category === activeCategory)
    : []

  const searchResults = search
    ? lessons.filter(l =>
        l.title.toLowerCase().includes(search.toLowerCase()) ||
        (l.summary ?? l.description ?? '').toLowerCase().includes(search.toLowerCase())
      )
    : []

  return (
    <div className="space-y-8 max-w-4xl">

      {/* Header */}
      <div className="flex items-start justify-between flex-wrap gap-3">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Recovery Library</h1>
          <p className="text-gray-500 text-sm mt-1">Skills, exercises, and tools for your recovery journey.</p>
        </div>
        {completedCount > 0 && (
          <div className="bg-teal-50 border border-teal-200 rounded-xl px-4 py-2 text-sm text-teal-800 font-medium">
            <CheckCircle size={14} className="inline mr-1.5 text-teal-600" />
            {completedCount} of {lessons.length} lessons completed
          </div>
        )}
      </div>

      {/* Guided Exercises */}
      <section>
        <div className="flex items-center justify-between mb-3">
          <h2 className="font-semibold text-gray-800 flex items-center gap-2">
            <Wind size={16} className="text-teal-600" /> Guided Exercises
          </h2>
          <Link to="/exercises" className="text-xs text-teal-700 font-medium hover:underline flex items-center gap-1">
            Open exercises <ChevronRight size={13} />
          </Link>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
          {EXERCISES.map(ex => {
            const c = COLOR_MAP[ex.color]
            const Icon = ex.icon
            return (
              <Link key={ex.id} to="/exercises"
                className={`${c.bg} rounded-2xl p-4 hover:shadow-md transition-all group border border-transparent hover:border-gray-200`}>
                <div className={`w-9 h-9 ${c.badge} rounded-xl flex items-center justify-center mb-3`}>
                  <Icon size={18} className={c.icon} />
                </div>
                <p className="font-semibold text-gray-900 text-sm leading-tight">{ex.name}</p>
                <p className="text-xs text-gray-500 mt-1">{ex.desc}</p>
                <div className={`mt-3 flex items-center gap-1 text-xs font-medium ${c.icon}`}>
                  <Play size={11} /> Start
                </div>
              </Link>
            )
          })}
        </div>
      </section>

      {/* Lessons section */}
      <section>
        <div className="flex items-center justify-between mb-4">
          <h2 className="font-semibold text-gray-800 flex items-center gap-2">
            <BookOpen size={16} className="text-teal-600" />
            {activeCategory
              ? (CATEGORY_CONFIG[activeCategory]?.label ?? activeCategory)
              : 'Psychoeducation Lessons'}
          </h2>
          {activeCategory && (
            <button onClick={() => setActiveCategory(null)}
              className="flex items-center gap-1 text-xs text-gray-500 hover:text-gray-800 transition-colors">
              <ArrowLeft size={13} /> All topics
            </button>
          )}
        </div>

        {loading ? (
          <div className="flex items-center justify-center py-12">
            <Loader2 size={28} className="animate-spin text-primary-500" />
          </div>
        ) : locked ? (
          <LockedState />
        ) : lessons.length === 0 ? (
          <div className="card text-center py-10 text-gray-400">
            <BookOpen size={32} className="mx-auto mb-2 text-gray-300" />
            No lessons available yet.
          </div>
        ) : search ? (
          /* ── Global search results ── */
          <SearchResults results={searchResults} search={search} />
        ) : activeCategory ? (
          /* ── Single category expanded ── */
          <CategoryDetail lessons={activeLessons} category={activeCategory} />
        ) : (
          /* ── Category overview grid ── */
          <>
            {/* Search bar */}
            <div className="relative mb-5">
              <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input value={search} onChange={e => setSearch(e.target.value)}
                placeholder="Search all lessons…"
                className="input-field pl-9 text-sm w-full" />
            </div>

            <CategoryGrid
              categories={allCategories}
              lessons={lessons}
              onSelect={setActiveCategory}
            />
          </>
        )}
      </section>

      {/* Community */}
      <section>
        <h2 className="font-semibold text-gray-800 flex items-center gap-2 mb-3">
          <MessageCircle size={16} className="text-teal-600" /> Community & Support
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <Link to="/groups"
            className="card flex items-center gap-4 hover:shadow-md transition-all group border-l-4 border-l-teal-400">
            <div className="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0">
              <Users size={20} className="text-teal-600" />
            </div>
            <div className="flex-1 min-w-0">
              <p className="font-semibold text-gray-900 group-hover:text-teal-700 transition-colors">Support Groups</p>
              <p className="text-sm text-gray-500 mt-0.5">Join peer-led groups moderated by professionals</p>
            </div>
            <ChevronRight size={16} className="text-gray-300 group-hover:text-teal-400 flex-shrink-0" />
          </Link>
          <Link to="/peer-mentors"
            className="card flex items-center gap-4 hover:shadow-md transition-all group border-l-4 border-l-orange-400">
            <div className="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center flex-shrink-0">
              <HandHeart size={20} className="text-orange-500" />
            </div>
            <div className="flex-1 min-w-0">
              <p className="font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Peer Mentors</p>
              <p className="text-sm text-gray-500 mt-0.5">Connect with someone who has walked the same path</p>
            </div>
            <ChevronRight size={16} className="text-gray-300 group-hover:text-orange-400 flex-shrink-0" />
          </Link>
        </div>
      </section>

      {/* Self-care tools */}
      <section>
        <h2 className="font-semibold text-gray-800 flex items-center gap-2 mb-3">
          <Heart size={16} className="text-teal-600" /> Self-Care Tools
        </h2>
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
          {SELF_CARE.map(item => {
            const c = COLOR_MAP[item.color]
            const Icon = item.icon
            return (
              <Link key={item.to} to={item.to}
                className={`${c.bg} rounded-2xl p-4 hover:shadow-md transition-all group border border-transparent hover:border-gray-200`}>
                <div className={`w-9 h-9 ${c.badge} rounded-xl flex items-center justify-center mb-3`}>
                  <Icon size={17} className={c.icon} />
                </div>
                <p className="font-semibold text-gray-900 text-sm leading-tight">{item.label}</p>
                <p className="text-xs text-gray-500 mt-1 leading-snug">{item.desc}</p>
              </Link>
            )
          })}
        </div>
      </section>

    </div>
  )
}

// ── Category overview grid ─────────────────────────────────────────────────

function CategoryGrid({ categories, lessons, onSelect }: {
  categories: string[]
  lessons: Lesson[]
  onSelect: (cat: string) => void
}) {
  const ordered = ['refusal', 'alcohol', 'gambling', 'depression', 'anxiety', 'trauma', 'wellness']
  const sorted = [...ordered.filter(c => categories.includes(c)), ...categories.filter(c => !ordered.includes(c))]

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
      {sorted.map(cat => {
        const cfg = CATEGORY_CONFIG[cat]
        const catLessons = lessons.filter(l => l.category === cat)
        const foundations = catLessons.filter(l => lessonType(l.title) === 'learn')
        const scenarios   = catLessons.filter(l => lessonType(l.title) === 'scenario')
        const done        = catLessons.filter(l => l.user_progress?.completed).length
        const pct         = catLessons.length ? Math.round((done / catLessons.length) * 100) : 0

        if (!cfg) return null
        return (
          <button key={cat} onClick={() => onSelect(cat)}
            className={`${cfg.color} border ${cfg.border} rounded-2xl p-5 text-left hover:shadow-md transition-all group`}>
            <div className="flex items-start justify-between mb-3">
              <span className="text-3xl leading-none">{cfg.emoji}</span>
              {pct > 0 && (
                <span className={`text-xs font-semibold px-2 py-0.5 rounded-full bg-white/70 ${cfg.accent}`}>
                  {pct}% done
                </span>
              )}
            </div>
            <p className={`font-bold text-base text-gray-900 group-hover:${cfg.accent} transition-colors`}>{cfg.label}</p>
            <p className="text-xs text-gray-500 mt-1 leading-snug">{cfg.desc}</p>

            <div className="flex items-center gap-3 mt-4">
              {foundations.length > 0 && (
                <span className="flex items-center gap-1 text-xs text-gray-600">
                  <BookText size={12} /> {foundations.length} lesson{foundations.length !== 1 ? 's' : ''}
                </span>
              )}
              {scenarios.length > 0 && (
                <span className="flex items-center gap-1 text-xs text-gray-600">
                  <Clapperboard size={12} /> {scenarios.length} scenario{scenarios.length !== 1 ? 's' : ''}
                </span>
              )}
            </div>

            {pct > 0 && (
              <div className="mt-3 h-1.5 bg-white/60 rounded-full overflow-hidden">
                <div className="h-full bg-current rounded-full opacity-50 transition-all" style={{ width: `${pct}%` }} />
              </div>
            )}
          </button>
        )
      })}
    </div>
  )
}

// ── Single category detail ─────────────────────────────────────────────────

function CategoryDetail({ lessons, category }: { lessons: Lesson[]; category: string }) {
  const cfg = CATEGORY_CONFIG[category]
  const foundations = lessons.filter(l => lessonType(l.title) === 'learn')
  const scenarios   = lessons.filter(l => lessonType(l.title) === 'scenario')

  return (
    <div className="space-y-6">
      {/* Category banner */}
      {cfg && (
        <div className={`${cfg.color} border ${cfg.border} rounded-2xl px-5 py-4 flex items-center gap-4`}>
          <span className="text-4xl leading-none">{cfg.emoji}</span>
          <div>
            <p className="font-bold text-gray-900">{cfg.label}</p>
            <p className="text-sm text-gray-500 mt-0.5">{cfg.desc}</p>
          </div>
        </div>
      )}

      {/* Foundation lessons */}
      {foundations.length > 0 && (
        <div>
          <div className="flex items-center gap-2 mb-3">
            <BookText size={15} className="text-gray-500" />
            <span className="font-semibold text-gray-800 text-sm">Foundation Lessons</span>
            <span className="text-xs text-gray-400">— read these first</span>
          </div>
          <div className="space-y-2">
            {foundations.map((lesson, i) => (
              <LessonRow key={lesson.id} lesson={lesson} index={i + 1} type="learn" />
            ))}
          </div>
        </div>
      )}

      {/* Scenario lessons */}
      {scenarios.length > 0 && (
        <div>
          <div className="flex items-center gap-2 mb-3">
            <Clapperboard size={15} className="text-gray-500" />
            <span className="font-semibold text-gray-800 text-sm">Real-World Scenarios</span>
            <span className="text-xs text-gray-400">— see the skills in action</span>
          </div>
          <div className="space-y-2">
            {scenarios.map((lesson, i) => (
              <LessonRow key={lesson.id} lesson={lesson} index={i + 1} type="scenario" />
            ))}
          </div>
        </div>
      )}
    </div>
  )
}

// ── Search results ─────────────────────────────────────────────────────────

function SearchResults({ results, search }: { results: Lesson[]; search: string }) {
  if (results.length === 0) {
    return <p className="text-sm text-gray-400 text-center py-8">No lessons match &quot;{search}&quot;.</p>
  }
  return (
    <div className="space-y-2">
      {results.map(lesson => (
        <LessonRow key={lesson.id} lesson={lesson} type={lessonType(lesson.title)} showCategory />
      ))}
    </div>
  )
}

// ── Lesson row ─────────────────────────────────────────────────────────────

function LessonRow({ lesson, index, type, showCategory }: {
  lesson: Lesson
  index?: number
  type: 'learn' | 'scenario'
  showCategory?: boolean
}) {
  const done = lesson.user_progress?.completed
  const pct  = lesson.user_progress?.progress_pct ?? 0
  const cfg  = showCategory ? CATEGORY_CONFIG[lesson.category] : undefined

  const isScenario = type === 'scenario'

  // Strip the "Scenario:" prefix from the title for display
  const displayTitle = lesson.title.replace(/^Scenario:\s*/i, '').replace(/^Branching in Practice:\s*/i, '').replace(/^Branching After a Relapse:\s*/i, 'After a Relapse: ')

  return (
    <Link to={`/lessons/${lesson.slug}`}
      className={`flex items-center gap-3 rounded-2xl border transition-all hover:shadow-md cursor-pointer group px-4 py-3
        ${done
          ? 'bg-green-50 border-green-200'
          : isScenario
            ? 'bg-gray-50 border-gray-200 hover:border-gray-300'
            : 'bg-white border-gray-200 hover:border-primary-200'
        }`}>

      {/* Index or emoji */}
      <div className={`w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 text-sm font-bold
        ${done
          ? 'bg-green-100 text-green-700'
          : isScenario
            ? 'bg-gray-200 text-gray-500'
            : 'bg-primary-50 text-primary-700'
        }`}>
        {done
          ? <CheckCircle size={16} className="text-green-600" />
          : lesson.thumbnail_emoji
            ? <span className="text-base leading-none">{lesson.thumbnail_emoji}</span>
            : index
              ? <span>{index}</span>
              : isScenario
                ? <Clapperboard size={14} />
                : <BookText size={14} />
        }
      </div>

      <div className="flex-1 min-w-0">
        <div className="flex items-center gap-2 flex-wrap">
          {/* Type badge */}
          {isScenario ? (
            <span className="text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-600 font-medium flex items-center gap-1">
              <Clapperboard size={10} /> Scenario
            </span>
          ) : (
            <span className="text-xs px-2 py-0.5 rounded-full bg-primary-50 text-primary-700 font-medium flex items-center gap-1">
              <BookText size={10} /> Learn
            </span>
          )}
          {/* Category badge when in search */}
          {showCategory && cfg && (
            <span className={`text-xs px-2 py-0.5 rounded-full border ${cfg.color} ${cfg.accent} ${cfg.border}`}>
              {cfg.emoji} {cfg.label}
            </span>
          )}
        </div>

        <p className="font-medium text-gray-900 group-hover:text-primary-700 transition-colors text-sm mt-0.5 leading-snug">
          {displayTitle}
        </p>

        {(lesson.summary ?? lesson.description) && (
          <p className="text-xs text-gray-400 mt-0.5 line-clamp-1">{lesson.summary ?? lesson.description}</p>
        )}

        <div className="flex items-center gap-2 mt-1">
          {lesson.level && (
            <span className={`text-xs px-2 py-0.5 rounded-full capitalize ${
              lesson.level === 'beginner'     ? 'bg-green-100 text-green-700'
              : lesson.level === 'intermediate' ? 'bg-yellow-100 text-yellow-700'
              : 'bg-red-100 text-red-700'
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

      <ChevronRight size={15} className="text-gray-300 group-hover:text-primary-400 flex-shrink-0" />
    </Link>
  )
}

// ── Locked state ───────────────────────────────────────────────────────────

function LockedState() {
  return (
    <div className="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-5">
      <Lock size={36} className="text-amber-500 flex-shrink-0" />
      <div className="flex-1">
        <p className="font-semibold text-amber-900">Lessons unlock after your first paid session</p>
        <p className="text-sm text-amber-700 mt-1">This keeps content exclusive to committed members and funds clinical quality.</p>
        <div className="flex flex-wrap gap-2 mt-3">
          {['Refusal Skills', 'Relapse Prevention', 'Coping Techniques', 'Mindfulness', 'Grief Processing'].map(t => (
            <span key={t} className="text-xs bg-amber-100 text-amber-800 rounded-full px-2.5 py-1">{t}</span>
          ))}
        </div>
      </div>
      <Link to="/professionals" className="btn-primary flex items-center gap-2 flex-shrink-0 text-sm px-5 py-2.5">
        <Calendar size={14} /> Book First Session
      </Link>
    </div>
  )
}
