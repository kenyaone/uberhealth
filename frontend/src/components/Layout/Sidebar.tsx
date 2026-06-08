import { NavLink } from 'react-router-dom'
import {
  LayoutDashboard, ClipboardList, Users, Calendar,
  Heart, TrendingUp, User, Leaf, ShieldCheck, Stethoscope,
  DollarSign, Tag
} from 'lucide-react'
import { useAuthStore } from '../../store/authStore'

export default function Sidebar() {
  const user = useAuthStore(s => s.user)
  const isAdmin = user?.role === 'admin'
  const isProfessional = user?.role === 'professional'

  const userLinks = [
    { to: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
    { to: '/assessments', icon: ClipboardList, label: 'Assessments' },
    { to: '/professionals', icon: Users, label: 'Find Therapist' },
    { to: '/consultations', icon: Calendar, label: 'My Sessions' },
    { to: '/mood', icon: Heart, label: 'Mood Tracker' },
    { to: '/sobriety', icon: TrendingUp, label: 'Sobriety' },
    { to: '/profile', icon: User, label: 'Profile' },
  ]

  const professionalLinks = [
    { to: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
    { to: '/consultations', icon: Calendar, label: 'My Sessions' },
    { to: '/profile', icon: User, label: 'Profile' },
    { to: '/apply', icon: Stethoscope, label: 'My Application' },
  ]

  const adminLinks = [
    { to: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
    { to: '/admin', icon: ShieldCheck, label: 'Verify Professionals' },
    { to: '/admin/revenue', icon: DollarSign, label: 'Revenue' },
    { to: '/professionals', icon: Users, label: 'All Professionals' },
    { to: '/profile', icon: User, label: 'Profile' },
  ]

  // Add Pricing link for regular users
  const userLinksWithPricing = [
    ...userLinks.slice(0, -1),
    { to: '/pricing', icon: Tag, label: 'Upgrade Plan' },
    userLinks[userLinks.length - 1],
  ]

  const links = isAdmin ? adminLinks : isProfessional ? professionalLinks : userLinksWithPricing

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
