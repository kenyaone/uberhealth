import { Link } from 'react-router-dom'
import { Shield, Video, Heart, Star, Phone } from 'lucide-react'

export default function Landing() {
  return (
    <div className="min-h-screen bg-gradient-to-b from-primary-900 to-primary-700 text-white">
      {/* Nav */}
      <nav className="flex items-center justify-between px-8 py-5">
        <div className="flex items-center gap-2 text-xl font-bold">
          🌿 MHAP Kenya
        </div>
        <div className="flex gap-3">
          <Link to="/login" className="px-4 py-2 rounded-lg hover:bg-primary-800 transition-colors">Login</Link>
          <Link to="/signup" className="px-4 py-2 bg-accent-600 rounded-lg hover:bg-accent-500 transition-colors font-medium">Get Started Free</Link>
        </div>
      </nav>

      {/* Hero */}
      <div className="text-center px-6 py-20">
        <div className="inline-block bg-primary-800 text-primary-200 text-sm px-4 py-1.5 rounded-full mb-6">
          🇰🇪 Built for Kenya — Private, Secure, Affordable
        </div>
        <h1 className="text-5xl font-bold mb-6 leading-tight">
          Mental Health Support<br />Without Judgment
        </h1>
        <p className="text-primary-200 text-xl mb-10 max-w-2xl mx-auto">
          Connect with KMPDC-verified therapists for depression, anxiety, alcohol recovery,
          gambling, and addiction — privately, affordably, and on your terms.
        </p>
        <div className="flex gap-4 justify-center flex-wrap">
          <Link to="/signup" className="px-8 py-3 bg-accent-600 hover:bg-accent-500 rounded-xl font-semibold text-lg transition-colors">
            Start Your Journey →
          </Link>
          <Link to="/login" className="px-8 py-3 bg-primary-800 hover:bg-primary-700 rounded-xl font-semibold text-lg transition-colors">
            I Already Have Account
          </Link>
        </div>
      </div>

      {/* Features */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 px-8 pb-16 max-w-6xl mx-auto">
        {[
          { icon: Shield, title: 'Your Privacy Protected', desc: 'Pseudonymous login. No WhatsApp. No Meta. Fully encrypted.' },
          { icon: Video, title: 'Jitsi Video Sessions', desc: 'Encrypted video therapy — no phone number required.' },
          { icon: Heart, title: 'Track Your Progress', desc: 'Mood logs, sobriety streaks, assessment history.' },
          { icon: Star, title: 'Verified Professionals', desc: 'KMPDC-licensed therapists. KES 2,000–3,000/session.' },
        ].map(({ icon: Icon, title, desc }) => (
          <div key={title} className="bg-primary-800 bg-opacity-50 rounded-xl p-5">
            <Icon size={24} className="text-accent-600 mb-3" />
            <h3 className="font-semibold mb-2">{title}</h3>
            <p className="text-primary-300 text-sm">{desc}</p>
          </div>
        ))}
      </div>

      {/* Crisis Banner */}
      <div className="bg-red-600 text-white text-center py-4 px-6">
        <Phone size={16} className="inline mr-2" />
        <strong>In crisis?</strong> Befrienders Kenya: <a href="tel:0800723253" className="underline font-bold">0800 723 253</a>
        {' | '}NACADA: <a href="tel:1192" className="underline font-bold">1192</a>
        {' '}— Available 24/7
      </div>
    </div>
  )
}
