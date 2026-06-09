import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'
import { LogOut } from 'lucide-react'
import NotificationBell from '../NotificationBell'
import { useT } from '../../contexts/I18nContext'

export default function Navbar() {
  const { user, logout } = useAuthStore()
  const { lang, setLang, t } = useT()
  const navigate = useNavigate()

  const handleLogout = () => {
    logout()
    navigate('/login')
  }

  return (
    <header className="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
      <div className="text-sm text-gray-500">
        {t('welcomeBack')}, <span className="font-medium text-gray-900">{user?.display_name}</span>
      </div>
      <div className="flex items-center gap-3">
        {/* Language toggle */}
        <button
          onClick={() => setLang(lang === 'en' ? 'sw' : 'en')}
          className="text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg px-2.5 py-1.5 transition-colors"
          title="Switch language / Badilisha lugha"
        >
          {lang === 'en' ? '🇰🇪 SW' : '🇬🇧 EN'}
        </button>
        <NotificationBell />
        <div className="w-8 h-8 rounded-full bg-primary-700 text-white flex items-center justify-center text-sm font-bold">
          {user?.display_name?.charAt(0).toUpperCase()}
        </div>
        <button
          onClick={handleLogout}
          className="flex items-center gap-2 text-sm text-gray-500 hover:text-red-600 transition-colors"
        >
          <LogOut size={16} />
          Logout
        </button>
      </div>
    </header>
  )
}
