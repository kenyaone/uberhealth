import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../../api/axios'
import {
  BookOpen, Wind, MessageCircle, HandHeart, Heart, TrendingUp,
  Shield, Target, BookMarked, ChevronRight, CheckCircle, Search,
  Loader2, Lock, Calendar, Zap, Brain, Play
} from 'lucide-react'

interface Lesson {
  id: number
  title: string
  slug: string
  description: string
  category: string
  level: string
  duration_minutes: number
  user_progress?: { completed: boolean; progress_pct: number }
}

const CATEGORY_LABELS: Record<string, string> = {
  refusal:    'Refusal Skills',
  alcohol:    'Alcohol Recovery',
  gambling:   'Gambling Recovery',
  depression: 'Depression',
  anxiety:    'Anxiety',
  trauma:     'Trauma',
  wellness:   'Wellness',
}

const CATEGORY_COLORS: Record<string, string> = {
  refusal:    'bg-amber-50 text-amber-700 border-amber-200',
  alcohol:    'bg-orange-50 text-orange-700 border-orange-200',
  gambling:   'bg-purple-50 text-purple-700 border-purple-200',
  depression: 'bg-blue-50 text-blue-700 border-blue-200',
  anxiety:    'bg-teal-50 text-teal-700 border-teal-200',
  trauma:     'bg-rose-50 text-rose-700 border-rose-200',
  wellness:   'bg-green-50 text-green-700 border-green-200',
}

const EXERCISES = [
  { id: 'box',        name: 'Box Breathing',          icon: Wind,  color: 'teal',   desc: '4-4-4-4 for instant calm' },
  { id: '478',        name: '4-7-8 Breathing',        icon: Wind,  color: 'blue',   desc: 'Best before sleep' },
  { id: 'grounding',  name: '5-4-3-2-1 Grounding',   icon: Zap,   color: 'green',  desc: 'Anchor to the present' },
  { id: 'progressive',name: 'Muscle Relaxation',      icon: Brain, color: 'purple', desc: 'Release physical tension' },
]

const SELF_CARE = [
  { to: '/mood',        icon: Heart,      label: 'Mood Tracker',    desc: 'Log how you feel daily',        color: 'pink' },
  { to: '/sobriety',    icon: TrendingUp, label: 'Sobriety Tracker',desc: 'Track your clean days',         color: 'teal' },
  { to: '/safety-plan', icon: Shield,     label: 'Safety Plan',     desc: 'Your personal crisis protocol', color: 'blue' },
  { to: '/goals',       icon: Target,     label: 'Recovery Goals',  desc: 'Set and track your milestones', color: 'orange' },
  { to: '/journal',     icon: BookMarked, label: 'My Journal',      desc: 'Private reflective writing',    color: 'green' },
]

const COLOR_MAP: Record<string, { bg: string; icon: string; badge: string }> = {
  teal:   { bg: 'bg-teal-50',   icon: 'text-teal-600',   badge: 'bg-teal-100' },
  blue:   { bg: 'bg-blue-50',   icon: 'text-blue-600',   badge: 'bg-blue-100' },
  green:  { bg: 'bg-green-50',  icon: 'text-green-600',  badge: 'bg-green-100' },
  purple: { bg: 'bg-purple-50', icon: 'text-purple-600', badge: 'bg-purple-100' },
  pink:   { bg: 'bg-pink-50',   icon: 'text-pink-600',   badge: 'bg-pink-100' },
  orange: { bg: 'bg-orange-50', icon: 'text-orange-600', badge: 'bg-orange-100' },
}

export default function RecoveryLibrary() {
  const [lessons, setLessons] = useState<Lesson[]>([])
  const [loading, setLoading] = useState(true)
  const [locked, setLocked] = useState(false)
  const [search, setSearch] = useState('')
  const [activeCategory, setActiveCategory] = useState('')

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

  const categories = [...new Set(lessons.map(l => l.category).filter(Boolean))]
  const completedCount = lessons.filter(l => l.user_progress?.completed).length

  const filtered = lessons.filter(l => {
    const matchesSearch = !search || l.title.toLowerCase().includes(search.toLowerCase()) || l.description?.toLowerCase().includes(search.toLowerCase())
    const matchesCat = !activeCategory || l.category === activeCategory
    return matchesSearch && matchesCat
  })

  const grouped = categories.reduce<Record<string, Lesson[]>>((acc, cat) => {
    const items = filtered.filter(l => l.category === cat)
    if (items.length) acc[cat] = items
    return acc
  }, {})

  // Lessons with no category
  const uncategorised = filtered.filter(l => !l.category)

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

      {/* Guided Exercises strip */}
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
              <Link
                key={ex.id}
                to="/exercises"
                className={`${c.bg} rounded-2xl p-4 hover:shadow-md transition-all group border border-transparent hover:border-gray-200`}
              >
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
        <h2 className="font-semibold text-gray-800 flex items-center gap-2 mb-3">
          <BookOpen size={16} className="text-teal-600" /> Psychoeducation Lessons
        </h2>

        {loading ? (
          <div className="flex items-center justify-center py-12">
            <Loader2 size={28} className="animate-spin text-primary-500" />
          </div>
        ) : locked ? (
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
        ) : lessons.length === 0 ? (
          <div className="card text-center py-10 text-gray-400">
            <BookOpen size={32} className="mx-auto mb-2 text-gray-300" />
            No lessons available yet.
          </div>
        ) : (
          <>
            {/* Search + filter */}
            <div className="flex flex-col sm:flex-row gap-3 mb-4">
              <div className="relative flex-1">
                <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  value={search}
                  onChange={e => setSearch(e.target.value)}
                  placeholder="Search lessons…"
                  className="input-field pl-9 text-sm"
                />
              </div>
              {categories.length > 1 && (
                <div className="flex gap-2 flex-wrap">
                  <button
                    onClick={() => setActiveCategory('')}
                    className={`text-xs px-3 py-1.5 rounded-full border font-medium transition-colors ${!activeCategory ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'}`}
                  >All</button>
                  {categories.map(cat => (
                    <button key={cat} onClick={() => setActiveCategory(cat === activeCategory ? '' : cat)}
                      className={`text-xs px-3 py-1.5 rounded-full border font-medium transition-colors ${activeCategory === cat ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'}`}>
                      {CATEGORY_LABELS[cat] ?? cat}
                    </button>
                  ))}
                </div>
              )}
            </div>

            {filtered.length === 0 ? (
              <p className="text-sm text-gray-400 text-center py-8">No lessons match your search.</p>
            ) : (
              <div className="space-y-6">
                {Object.entries(grouped).map(([cat, items]) => (
                  <div key={cat}>
                    <div className="flex items-center gap-2 mb-2">
                      <span className={`text-xs px-2.5 py-1 rounded-full border font-medium ${CATEGORY_COLORS[cat] ?? 'bg-gray-50 text-gray-600 border-gray-200'}`}>{CATEGORY_LABELS[cat] ?? cat}</span>
                      <span className="text-xs text-gray-400">{items.filter(l => l.user_progress?.completed).length}/{items.length} done</span>
                    </div>
                    <div className="space-y-2">
                      {items.map(lesson => <LessonRow key={lesson.id} lesson={lesson} />)}
                    </div>
                  </div>
                ))}
                {uncategorised.length > 0 && (
                  <div className="space-y-2">
                    {uncategorised.map(lesson => <LessonRow key={lesson.id} lesson={lesson} />)}
                  </div>
                )}
              </div>
            )}
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
              <MessageCircle size={20} className="text-teal-600" />
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
              <Link
                key={item.to}
                to={item.to}
                className={`${c.bg} rounded-2xl p-4 hover:shadow-md transition-all group border border-transparent hover:border-gray-200`}
              >
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

function LessonRow({ lesson }: { lesson: Lesson }) {
  const done = lesson.user_progress?.completed
  const pct = lesson.user_progress?.progress_pct ?? 0
  return (
    <Link
      to={`/lessons/${lesson.slug}`}
      className={`card flex items-center gap-4 hover:shadow-md transition-all cursor-pointer group py-3 ${done ? 'border-l-4 border-l-green-400' : ''}`}
    >
      <div className={`w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 ${done ? 'bg-green-100' : 'bg-primary-50'}`}>
        {done
          ? <CheckCircle size={18} className="text-green-600" />
          : <BookOpen size={18} className="text-primary-600" />
        }
      </div>
      <div className="flex-1 min-w-0">
        <p className="font-medium text-gray-900 group-hover:text-primary-700 transition-colors text-sm">{lesson.title}</p>
        {lesson.description && (
          <p className="text-xs text-gray-400 mt-0.5 truncate">{lesson.description}</p>
        )}
        <div className="flex items-center gap-2 mt-1 flex-wrap">
          {lesson.level && (
            <span className={`text-xs px-2 py-0.5 rounded-full capitalize ${lesson.level === 'beginner' ? 'bg-green-100 text-green-700' : lesson.level === 'intermediate' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'}`}>
              {lesson.level}
            </span>
          )}
          {lesson.duration_minutes ? <span className="text-xs text-gray-400">{lesson.duration_minutes} min</span> : null}
        </div>
        {pct > 0 && !done && (
          <div className="mt-1.5 h-1 bg-gray-100 rounded-full w-28">
            <div className="h-full bg-primary-500 rounded-full" style={{ width: `${pct}%` }} />
          </div>
        )}
      </div>
      <ChevronRight size={15} className="text-gray-300 group-hover:text-primary-400 flex-shrink-0" />
    </Link>
  )
}
