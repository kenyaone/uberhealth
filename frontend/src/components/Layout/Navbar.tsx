import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'
import { LogOut } from 'lucide-react'
import NotificationBell from '../NotificationBell'

export default function Navbar() {
  const { user, logout } = useAuthStore()
  const navigate = useNavigate()

  const handleLogout = () => {
    logout()
    navigate('/login')
  }

  return (
    <header className="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
      <div className="text-sm text-gray-500">
        Welcome back, <span className="font-medium text-gray-900">{user?.display_name}</span>
      </div>
      <div className="flex items-center gap-3">
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
