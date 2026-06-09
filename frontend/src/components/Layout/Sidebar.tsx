import { NavLink } from 'react-router-dom'
import {
  LayoutDashboard, ClipboardList, Users, Calendar,
  Heart, TrendingUp, User, Leaf, ShieldCheck, Stethoscope,
  DollarSign, Tag, BookOpen, MessageCircle, History, Clock, BarChart2,
  Shield, Target, Pill, Wind, Gift
} from 'lucide-react'
import { useAuthStore } from '../../store/authStore'
import { useT } from '../../contexts/I18nContext'

export default function Sidebar() {
  const user = useAuthStore(s => s.user)
  const { t } = useT()
  const isAdmin = user?.role === 'admin'
  const isProfessional = user?.role === 'professional'

  const userLinks = [
    { to: '/dashboard', icon: LayoutDashboard, label: t('dashboard') },
    { to: '/assessments', icon: ClipboardList, label: t('assessments') },
    { to: '/professionals', icon: Users, label: t('findTherapist') },
    { to: '/consultations', icon: Calendar, label: t('mySessions') },
    { to: '/history', icon: History, label: t('sessionHistory') },
    { to: '/progress', icon: BarChart2, label: 'My Progress' },
    { to: '/mood', icon: Heart, label: t('moodTracker') },
    { to: '/sobriety', icon: TrendingUp, label: t('sobriety') },
    { to: '/safety-plan', icon: Shield, label: 'Safety Plan' },
    { to: '/goals', icon: Target, label: 'My Goals' },
    { to: '/medications', icon: Pill, label: 'Medications' },
    { to: '/exercises', icon: Wind, label: 'Exercises' },
    { to: '/lessons', icon: BookOpen, label: t('recoveryLibrary') },
    { to: '/groups', icon: MessageCircle, label: t('supportGroups') },
    { to: '/referral', icon: Gift, label: 'Referral & Promo' },
    { to: '/profile', icon: User, label: t('profile') },
  ]

  const professionalLinks = [
    { to: '/dashboard', icon: LayoutDashboard, label: t('dashboard') },
    { to: '/consultations', icon: Calendar, label: t('mySessions') },
    { to: '/caseload', icon: Users, label: 'My Patients' },
    { to: '/payouts', icon: DollarSign, label: 'Payouts' },
    { to: '/availability', icon: Clock, label: t('availability') },
    { to: '/profile', icon: User, label: t('profile') },
    { to: '/apply', icon: Stethoscope, label: t('myApplication') },
  ]

  const adminLinks = [
    { to: '/dashboard', icon: LayoutDashboard, label: t('dashboard') },
    { to: '/admin', icon: ShieldCheck, label: 'Verify Professionals' },
    { to: '/admin/revenue', icon: DollarSign, label: 'Revenue' },
    { to: '/professionals', icon: Users, label: 'All Professionals' },
    { to: '/profile', icon: User, label: t('profile') },
  ]

  // Add Pricing link for regular users
  const userLinksWithPricing = [
    ...userLinks.slice(0, -1),
    { to: '/pricing', icon: Tag, label: t('upgradePlan') },
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
            <div className="font-bold text-base leading-tight">Afya Yako Siri Yako</div>
            <div className="text-primary-300 text-xs">Your Health, Your Secret</div>
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
