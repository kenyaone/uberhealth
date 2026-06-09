import { Outlet } from 'react-router-dom'
import Sidebar from './Sidebar'
import Navbar from './Navbar'
import AiChat from '../AiChat'
import OfflineBanner from '../OfflineBanner'
import { usePresenceHeartbeat } from '../../hooks/usePresence'

export default function Layout() {
  usePresenceHeartbeat()

  return (
    <>
      <OfflineBanner />
      <div className="flex h-screen bg-gray-50 dark:bg-gray-900">
        <Sidebar />
        <div className="flex-1 flex flex-col overflow-hidden">
          <Navbar />
          <main className="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
            <Outlet />
          </main>
        </div>
        <AiChat />
      </div>
    </>
  )
}
