import { NavLink } from 'react-router-dom'
import {
  LayoutDashboard, ClipboardList, Users, Calendar,
  Heart, TrendingUp, User, Leaf
} from 'lucide-react'

const links = [
  { to: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
  { to: '/assessments', icon: ClipboardList, label: 'Assessments' },
  { to: '/professionals', icon: Users, label: 'Find Therapist' },
  { to: '/consultations', icon: Calendar, label: 'My Sessions' },
  { to: '/mood', icon: Heart, label: 'Mood Tracker' },
  { to: '/sobriety', icon: TrendingUp, label: 'Sobriety' },
  { to: '/profile', icon: User, label: 'Profile' },
]

export default function Sidebar() {
  return (
    <aside className="w-64 bg-primary-900 text-white flex flex-col">
      <div className="p-5 border-b border-primary-800">
        <div className="flex items-center gap-3">
          <div className="bg-accent-600 rounded-lg p-2">
            <Leaf size={20} className="text-white" />
          </div>
          <div>
            <div className="font-bold text-lg leading-tight">MHAP Kenya</div>
            <div className="text-primary-300 text-xs">Mental Health Platform</div>
          </div>
        </div>
      </div>

      <nav className="flex-1 p-3 space-y-1">
        {links.map(({ to, icon: Icon, label }) => (
          <NavLink
            key={to}
            to={to}
            className={({ isActive }) =>
              `flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${
                isActive
                  ? 'bg-primary-700 text-white'
                  : 'text-primary-200 hover:bg-primary-800 hover:text-white'
              }`
            }
          >
            <Icon size={18} />
            {label}
          </NavLink>
        ))}
      </nav>

      <div className="p-4 border-t border-primary-800">
        <div className="text-xs text-primary-400">
          🔒 Your data is private & encrypted
        </div>
      </div>
    </aside>
  )
}
