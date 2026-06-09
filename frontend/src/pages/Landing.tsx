import { Link } from 'react-router-dom'
import { Shield, Video, Heart, Star, Phone, Brain, Lock, Users, CheckCircle, Sparkles, Activity, Award, ArrowRight, BookOpen, Zap } from 'lucide-react'

const FEATURES = [
  { icon: Sparkles, title: 'AI-Powered Matching', desc: 'Pairs you with the right therapist based on your condition, language, and gender — in seconds.' },
  { icon: Brain, title: '5 Clinical Assessments', desc: 'PHQ-9, GAD-7, AUDIT, PGSI & FTND — WHO-validated tools covering depression, anxiety, alcohol, gambling and nicotine.' },
  { icon: Lock, title: 'No WhatsApp. No Meta.', desc: 'Fully pseudonymous. No phone number for video. We use Jitsi — not Facebook or Google infrastructure.' },
  { icon: Video, title: 'Encrypted Video Sessions', desc: 'Private Jitsi rooms generated per session. No recordings without your consent.' },
  { icon: Activity, title: 'Track Your Recovery', desc: 'Daily mood logs, sobriety streaks, craving journals, and progress charts — all in one place.' },
  { icon: BookOpen, title: 'Recovery Skills Library', desc: 'Refusal skills, coping strategies, and branching lessons — unlocked after your first paid session.' },
]

const STATS = [
  { value: 'KES 2,000', label: 'Per session' },
  { value: '3 Areas', label: 'Mental health, addiction & gambling' },
  { value: '5 Tools', label: 'WHO clinical assessments' },
  { value: '0 Meta', label: 'No WhatsApp or Facebook' },
]

const STEPS = [
  { step: '01', title: 'Take a Free Assessment', desc: 'Answer clinically-validated questions. Get your score and severity rating instantly — no sign-up needed.' },
  { step: '02', title: 'AI Matches You', desc: 'Based on your results, language, gender preference, and availability, AI recommends the best-fit therapist.' },
  { step: '03', title: 'Book & Connect Privately', desc: 'Pay via M-Pesa. Join your encrypted Jitsi session. No one knows who you are unless you choose to share.' },
]

export default function Landing() {
  return (
    <div className="min-h-screen font-sans">

      {/* Nav */}
      <nav className="bg-slate-900 bg-opacity-95 backdrop-blur sticky top-0 z-50 border-b border-slate-800">
        <div className="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
          <div className="flex items-center gap-2">
            <span className="text-lg font-bold text-white">🌿 Afya Yako Siri Yako</span>
            <span className="hidden md:inline text-xs text-slate-400 font-normal">Your Health, Your Secret</span>
          </div>
          <div className="hidden md:flex items-center gap-6 text-sm text-slate-300">
            <a href="#how-it-works" className="hover:text-white transition-colors">How it works</a>
            <a href="#for-companies" className="hover:text-white transition-colors">For Companies</a>
          </div>
          <div className="flex gap-2">
            <Link to="/login" className="px-4 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors text-sm">Login</Link>
            <Link to="/signup" className="px-4 py-2 bg-orange-500 hover:bg-orange-400 rounded-lg font-semibold text-sm text-white transition-colors">Get Started Free</Link>
          </div>
        </div>
      </nav>

      {/* ── HERO ─────────────────────────────────────────────── */}
      <section className="bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900 text-white overflow-hidden">
        <div className="max-w-7xl mx-auto px-6 py-16 md:py-20 grid md:grid-cols-2 gap-12 items-center">
          {/* Left text */}
          <div>
            <div className="inline-flex items-center gap-2 bg-teal-900 border border-teal-700 text-teal-200 text-xs px-3 py-1.5 rounded-full mb-6">
              🇰🇪 Kenya's only platform for mental health, addiction & gambling recovery
            </div>
            <h1 className="text-4xl md:text-5xl font-black mb-5 leading-tight tracking-tight">
              Real Help.<br />
              Real Privacy.<br />
              <span className="text-orange-400">Without Judgment.</span>
            </h1>
            <p className="text-slate-300 text-lg mb-3 leading-relaxed">
              AI-powered matching with <strong className="text-white">KMPDC-verified therapists</strong> for depression, anxiety, alcohol, drug use, and gambling addiction.
            </p>
            <p className="text-slate-400 text-sm mb-8">
              No WhatsApp. No Meta. No phone number required. Private encrypted sessions from <strong className="text-white">KES 2,000</strong>.
            </p>
            <div className="flex gap-3 flex-wrap">
              <Link to="/signup" className="flex items-center gap-2 px-7 py-3.5 bg-orange-500 hover:bg-orange-400 rounded-xl font-bold text-base transition-colors shadow-lg shadow-orange-900/40">
                Take Free Assessment <ArrowRight size={18} />
              </Link>
              <Link to="/login" className="px-7 py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl font-semibold text-base transition-colors">
                Sign In
              </Link>
            </div>
            <div className="mt-6 flex items-center gap-2 text-sm text-slate-400">
              <Shield size={14} className="text-teal-400" />
              Free assessment · No credit card · Cancel anytime
            </div>
          </div>

          {/* Right image */}
          <div className="relative hidden md:block">
            <div className="absolute inset-0 bg-gradient-to-l from-transparent via-transparent to-slate-900/60 z-10 rounded-3xl" />
            <img
              src="/stress.jpg"
              alt="Mental health support"
              className="rounded-3xl w-full h-96 object-cover shadow-2xl shadow-teal-900/50 border border-white/10"
            />
            {/* Floating badge */}
            <div className="absolute bottom-5 left-5 z-20 bg-white/95 backdrop-blur rounded-2xl px-4 py-3 shadow-xl">
              <div className="flex items-center gap-2">
                <div className="w-9 h-9 bg-teal-600 rounded-xl flex items-center justify-center">
                  <Sparkles size={16} className="text-white" />
                </div>
                <div>
                  <div className="text-xs font-bold text-gray-900">AI Match Found</div>
                  <div className="text-xs text-gray-500">94% match · Dr. Kamau</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── STATS BAR ────────────────────────────────────────── */}
      <section className="bg-white border-b border-gray-100 shadow-sm">
        <div className="max-w-5xl mx-auto px-6 py-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
          {STATS.map(({ value, label }) => (
            <div key={label}>
              <div className="text-2xl font-black text-teal-700">{value}</div>
              <div className="text-gray-500 text-xs mt-1">{label}</div>
            </div>
          ))}
        </div>
      </section>

      {/* ── 3 PILLARS ─────────────────────────────────────────── */}
      <section className="bg-gray-50 py-20 px-6">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-black text-gray-900 mb-3">We Go Beyond Mental Health</h2>
            <p className="text-gray-500 max-w-xl mx-auto">Most platforms only handle depression and anxiety. We cover everything Kenyans actually struggle with.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">

            {/* Mental Health */}
            <div className="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 hover:shadow-lg transition-shadow">
              <div className="bg-gradient-to-br from-teal-500 to-emerald-600 p-6 text-white">
                <div className="text-3xl mb-2">🧠</div>
                <h3 className="text-xl font-bold">Mental Health</h3>
                <p className="text-teal-100 text-sm mt-1">Depression, anxiety, trauma & more</p>
              </div>
              <ul className="p-5 space-y-2">
                {['Depression & Low Mood', 'Anxiety & Panic Attacks', 'Trauma & PTSD', 'Stress & Burnout', 'Grief & Loss', 'Relationship Issues'].map(c => (
                  <li key={c} className="flex items-center gap-2 text-gray-700 text-sm">
                    <CheckCircle size={13} className="text-teal-500 flex-shrink-0" /> {c}
                  </li>
                ))}
              </ul>
            </div>

            {/* Addiction — with drunkard image */}
            <div className="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 hover:shadow-lg transition-shadow">
              <div className="relative h-40 overflow-hidden">
                <img src="/drunkard.jpg" alt="Addiction recovery" className="w-full h-full object-cover" />
                <div className="absolute inset-0 bg-gradient-to-t from-orange-900/80 to-orange-600/30" />
                <div className="absolute bottom-4 left-4 text-white">
                  <div className="text-3xl mb-1">🍺</div>
                  <h3 className="text-xl font-bold">Addiction Recovery</h3>
                  <p className="text-orange-200 text-xs">Alcohol, drugs, tobacco & more</p>
                </div>
              </div>
              <ul className="p-5 space-y-2">
                {['Alcohol Dependence (AUDIT)', 'Drug & Substance Use', 'Tobacco & Miraa', 'Cannabis Dependence', 'Prescription Drug Misuse', 'Recovery Planning'].map(c => (
                  <li key={c} className="flex items-center gap-2 text-gray-700 text-sm">
                    <CheckCircle size={13} className="text-orange-500 flex-shrink-0" /> {c}
                  </li>
                ))}
              </ul>
            </div>

            {/* Gambling */}
            <div className="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 hover:shadow-lg transition-shadow">
              <div className="bg-gradient-to-br from-purple-600 to-violet-700 p-6 text-white">
                <div className="text-3xl mb-2">🎰</div>
                <h3 className="text-xl font-bold">Gambling Recovery</h3>
                <p className="text-purple-200 text-sm mt-1">SportPesa, betting & financial recovery</p>
              </div>
              <ul className="p-5 space-y-2">
                {['SportPesa & Betway Addiction', 'Online Betting Disorder', 'Casino Problem Gambling', 'Financial Recovery', 'Family Impact Counseling', 'PGSI Screening Tool'].map(c => (
                  <li key={c} className="flex items-center gap-2 text-gray-700 text-sm">
                    <CheckCircle size={13} className="text-purple-500 flex-shrink-0" /> {c}
                  </li>
                ))}
              </ul>
            </div>

          </div>
        </div>
      </section>

      {/* ── HOW IT WORKS ─────────────────────────────────────── */}
      <section id="how-it-works" className="bg-slate-900 text-white py-20 px-6">
        <div className="max-w-5xl mx-auto">
          <h2 className="text-3xl font-black text-center mb-14">How It Works</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {STEPS.map(({ step, title, desc }) => (
              <div key={step} className="relative">
                <div className="text-6xl font-black text-teal-800 mb-3 leading-none">{step}</div>
                <h3 className="font-bold text-lg mb-2 text-white">{title}</h3>
                <p className="text-slate-400 text-sm leading-relaxed">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── AI HIGHLIGHT ─────────────────────────────────────── */}
      <section className="bg-gradient-to-r from-teal-600 to-emerald-700 text-white py-16 px-6">
        <div className="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-8">
          <div className="flex-shrink-0 w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center">
            <Sparkles size={32} className="text-white" />
          </div>
          <div className="flex-1 text-center md:text-left">
            <h2 className="text-2xl font-black mb-2">AI That Understands Kenya</h2>
            <p className="text-teal-100 mb-4 leading-relaxed">
              Our AI reads your clinical assessment results and matches you with the therapist best suited for your specific condition — filtering by specialization, language (English or Kiswahili), gender preference, and availability.
            </p>
            <div className="flex flex-wrap gap-2 justify-center md:justify-start">
              {['Condition-aware matching', 'Kiswahili support', 'Availability filtering', 'Anonymous by default'].map(tag => (
                <span key={tag} className="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">{tag}</span>
              ))}
            </div>
          </div>
          <Link to="/signup" className="flex-shrink-0 px-6 py-3 bg-white text-teal-700 hover:bg-teal-50 rounded-xl font-bold transition-colors whitespace-nowrap shadow-lg">
            Try It Free
          </Link>
        </div>
      </section>

      {/* ── FEATURES GRID ────────────────────────────────────── */}
      <section className="bg-white py-20 px-6">
        <div className="max-w-6xl mx-auto">
          <h2 className="text-3xl font-black text-gray-900 text-center mb-12">Everything You Need</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            {FEATURES.map(({ icon: Icon, title, desc }) => (
              <div key={title} className="p-5 rounded-2xl border border-gray-200 hover:border-teal-300 hover:shadow-md transition-all">
                <div className="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center mb-3">
                  <Icon size={20} className="text-teal-600" />
                </div>
                <h3 className="font-bold text-gray-900 mb-1.5">{title}</h3>
                <p className="text-gray-500 text-sm leading-relaxed">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── RECOVERY SKILLS PROMO ─────────────────────────── */}
      <section className="bg-gradient-to-br from-amber-50 to-orange-50 border-y border-orange-100 py-16 px-6">
        <div className="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-10">
          <div className="flex-shrink-0 relative">
            <div className="w-24 h-24 bg-orange-100 rounded-3xl flex items-center justify-center">
              <BookOpen size={44} className="text-orange-600" />
            </div>
            <div className="absolute -top-2 -right-2 w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
              <Zap size={12} className="text-white" />
            </div>
          </div>
          <div className="flex-1 text-center md:text-left">
            <div className="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full mb-3 font-medium">
              🔓 Unlocks After First Session
            </div>
            <h2 className="text-2xl font-black text-gray-900 mb-2">Recovery Skills Library</h2>
            <p className="text-gray-600 mb-4 leading-relaxed">
              Gain access to our full library of recovery lessons — including <strong>refusal skills</strong>, <strong>branching coping techniques</strong>, relapse prevention, and mindfulness exercises — after your first paid session. Not free, because recovery is a commitment.
            </p>
            <div className="flex flex-wrap gap-2 justify-center md:justify-start">
              {['Refusal Skills', 'Coping Strategies', 'Relapse Prevention', 'Mindfulness', 'Branching Techniques', 'Self-care Plans'].map(tag => (
                <span key={tag} className="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full">{tag}</span>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── FOR PROFESSIONALS ────────────────────────────────── */}
      <section className="bg-slate-900 text-white py-20 px-6">
        <div className="max-w-5xl mx-auto">
          <div className="flex flex-col md:flex-row items-center gap-10">
            <div className="flex-1">
              <div className="inline-flex items-center gap-2 bg-teal-900 border border-teal-700 text-teal-200 text-xs px-3 py-1 rounded-full mb-5">
                🩺 Mental Health Professionals
              </div>
              <h2 className="text-3xl font-black mb-3">Join as a Therapist or Counselor</h2>
              <p className="text-slate-300 mb-5 leading-relaxed">
                KMPDC-licensed professionals earn <strong className="text-white">KES 2,000 per session</strong> (80% of KES 2,500). Paid directly to M-Pesa within 24 hours. Work from anywhere.
              </p>
              <div className="grid grid-cols-2 gap-3 mb-6">
                {['Set your own schedule', 'M-Pesa payouts in 24hrs', 'AI matches you with patients', 'Encrypted video sessions'].map(f => (
                  <div key={f} className="flex items-center gap-2 text-sm text-slate-300">
                    <CheckCircle size={13} className="text-teal-400 flex-shrink-0" /> {f}
                  </div>
                ))}
              </div>
              <Link to="/signup" state={{ role: 'professional' }} className="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 hover:bg-orange-400 rounded-xl font-bold transition-colors">
                Apply to Join <ArrowRight size={16} />
              </Link>
            </div>
            <div className="grid grid-cols-2 gap-3 text-center flex-shrink-0">
              {[{ value: '80%', label: 'Your share' }, { value: '24hrs', label: 'M-Pesa payout' }, { value: 'KES 2,000', label: 'Per session' }, { value: 'KMPDC', label: 'Verified only' }].map(({ value, label }) => (
                <div key={label} className="bg-slate-800 border border-slate-700 rounded-2xl px-6 py-4">
                  <div className="text-orange-400 font-black text-xl">{value}</div>
                  <div className="text-slate-400 text-xs mt-1">{label}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── FOR COMPANIES ────────────────────────────────────── */}
      <section id="for-companies" className="bg-white py-20 px-6">
        <div className="max-w-5xl mx-auto text-center">
          <Users size={40} className="text-teal-600 mx-auto mb-4" />
          <h2 className="text-3xl font-black text-gray-900 mb-3">Employee Assistance Programme (EAP)</h2>
          <p className="text-gray-500 max-w-2xl mx-auto mb-8 leading-relaxed">
            Give your team access to confidential therapy, addiction support, and wellness tools. Gambling and alcohol problems are costing Kenyan companies billions in lost productivity.
          </p>
          <div className="flex flex-wrap justify-center gap-4 mb-8">
            {['Confidential for employees', 'Usage analytics for HR', 'M-Pesa billing', 'Scalable from 10 to 10,000 staff'].map(f => (
              <div key={f} className="flex items-center gap-2 text-sm text-gray-600 bg-teal-50 border border-teal-100 px-3 py-2 rounded-lg">
                <CheckCircle size={14} className="text-teal-600" /> {f}
              </div>
            ))}
          </div>
          <Link to="/corporate" className="inline-flex items-center gap-2 px-8 py-3.5 bg-teal-600 hover:bg-teal-500 text-white rounded-xl font-bold transition-colors">
            Get a Quote <ArrowRight size={16} />
          </Link>
        </div>
      </section>

      {/* ── COMPARISON TABLE ─────────────────────────────────── */}
      <section className="bg-gray-50 py-16 px-6">
        <div className="max-w-3xl mx-auto">
          <h2 className="text-2xl font-black text-gray-900 text-center mb-8">Why Afya Yako Siri Yako?</h2>
          <div className="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table className="w-full text-sm">
              <thead className="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th className="text-left py-3 px-5 text-gray-500 font-medium">Feature</th>
                  <th className="py-3 px-4 text-gray-400 font-medium text-center">Others</th>
                  <th className="py-3 px-4 text-teal-600 font-bold text-center bg-teal-50">Afya Yako</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {[
                  ['Mental health support', '✅', '✅'],
                  ['Addiction recovery', '❌', '✅'],
                  ['Gambling recovery', '❌', '✅'],
                  ['AI therapist matching', '❌', '✅'],
                  ['Clinical assessments (5 tools)', '❌', '✅'],
                  ['Recovery skills library', '❌', '✅'],
                  ['No WhatsApp / Meta', '❌', '✅'],
                  ['Kiswahili support', 'Partial', '✅'],
                  ['Price per session', 'KES 2,500+', 'KES 2,000'],
                ].map(([feature, others, us]) => (
                  <tr key={feature} className="hover:bg-gray-50">
                    <td className="py-3 px-5 text-gray-700">{feature}</td>
                    <td className="py-3 px-4 text-center text-gray-400">{others}</td>
                    <td className="py-3 px-4 text-center font-semibold text-teal-700 bg-teal-50/50">{us}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </section>

      {/* ── FINAL CTA ────────────────────────────────────────── */}
      <section className="bg-gradient-to-br from-teal-700 to-emerald-800 text-white py-20 px-6 text-center">
        <h2 className="text-4xl font-black mb-4">Ready to Start?</h2>
        <p className="text-teal-100 text-lg mb-8 max-w-lg mx-auto">Free assessment. No credit card. Your identity stays yours.</p>
        <Link to="/signup" className="inline-flex items-center gap-2 px-10 py-4 bg-orange-500 hover:bg-orange-400 rounded-2xl font-black text-lg transition-colors shadow-xl shadow-orange-900/30">
          Take Free Assessment <ArrowRight size={20} />
        </Link>
        <div className="mt-6 flex items-center justify-center gap-2 text-teal-300 text-sm">
          <Star size={14} className="fill-teal-400 text-teal-400" />
          KMPDC-Verified Therapists Only
        </div>
      </section>

      {/* Crisis Banner */}
      <div className="bg-red-700 text-white text-center py-4 px-6 text-sm">
        <Phone size={13} className="inline mr-2" />
        <strong>In crisis right now?</strong>{' '}
        Befrienders Kenya: <a href="tel:0800723253" className="underline font-bold">0800 723 253</a>
        {' | '}NACADA: <a href="tel:1192" className="underline font-bold">1192</a>
        {' | '}Kenya Red Cross: <a href="tel:1199" className="underline font-bold">1199</a>
        {' '}— Free, 24/7
      </div>
    </div>
  )
}
