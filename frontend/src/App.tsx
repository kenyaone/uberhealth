import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { useAuthStore } from './store/authStore'
import Layout from './components/Layout/Layout'
import Login from './pages/auth/Login'
import Signup from './pages/auth/Signup'
import Dashboard from './pages/dashboard/Dashboard'
import Assessments from './pages/assessments/Assessments'
import TakeAssessment from './pages/assessments/TakeAssessment'
import Professionals from './pages/professionals/Professionals'
import ProfessionalDetail from './pages/professionals/ProfessionalDetail'
import BookConsultation from './pages/consultation/BookConsultation'
import MyConsultations from './pages/consultation/MyConsultations'
import JoinSession from './pages/consultation/JoinSession'
import MoodTracker from './pages/phr/MoodTracker'
import SobrietyTracker from './pages/phr/SobrietyTracker'
import Profile from './pages/Profile'
import Landing from './pages/Landing'
import ApplyAsProfessional from './pages/apply/ApplyAsProfessional'
import AdminDashboard from './pages/admin/AdminDashboard'
import RevenueDashboard from './pages/admin/RevenueDashboard'
import Pricing from './pages/pricing/Pricing'
import Subscribe from './pages/subscribe/Subscribe'
import Corporate from './pages/corporate/Corporate'

function PrivateRoute({ children }: { children: React.ReactNode }) {
  const isAuthenticated = useAuthStore((s) => s.isAuthenticated)
  return isAuthenticated ? <>{children}</> : <Navigate to="/login" replace />
}

function PublicRoute({ children }: { children: React.ReactNode }) {
  const isAuthenticated = useAuthStore((s) => s.isAuthenticated)
  return !isAuthenticated ? <>{children}</> : <Navigate to="/dashboard" replace />
}

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/login" element={<PublicRoute><Login /></PublicRoute>} />
        <Route path="/signup" element={<PublicRoute><Signup /></PublicRoute>} />

        <Route element={<PrivateRoute><Layout /></PrivateRoute>}>
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/assessments" element={<Assessments />} />
          <Route path="/assessments/:type" element={<TakeAssessment />} />
          <Route path="/professionals" element={<Professionals />} />
          <Route path="/professionals/:id" element={<ProfessionalDetail />} />
          <Route path="/book/:professionalId" element={<BookConsultation />} />
          <Route path="/consultations" element={<MyConsultations />} />
          <Route path="/session/:consultationId" element={<JoinSession />} />
          <Route path="/mood" element={<MoodTracker />} />
          <Route path="/sobriety" element={<SobrietyTracker />} />
          <Route path="/profile" element={<Profile />} />
          <Route path="/apply" element={<ApplyAsProfessional />} />
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/revenue" element={<RevenueDashboard />} />
          <Route path="/pricing" element={<Pricing />} />
          <Route path="/subscribe" element={<Subscribe />} />
          <Route path="/corporate" element={<Corporate />} />
        </Route>
      </Routes>
    </BrowserRouter>
  )
}
