import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { useAuthStore } from './store/authStore'
import { I18nProvider } from './contexts/I18nContext'
import { ThemeProvider } from './contexts/ThemeContext'
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
import Lessons from './pages/lessons/Lessons'
import LessonDetail from './pages/lessons/LessonDetail'
import SessionHistory from './pages/history/SessionHistory'
import ProgressDashboard from './pages/dashboard/ProgressDashboard'
import SupportGroups from './pages/groups/SupportGroups'
import GroupChat from './pages/groups/GroupChat'
import FollowUpSurvey from './pages/surveys/FollowUpSurvey'
import AvailabilityManager from './pages/professional/AvailabilityManager'
import SafetyPlan from './pages/phr/SafetyPlan'
import Goals from './pages/phr/Goals'
import Medications from './pages/phr/Medications'
import Caseload from './pages/professional/Caseload'
import CaseloadPatient from './pages/professional/CaseloadPatient'
import Payouts from './pages/professional/Payouts'
import GuidedExercises from './pages/tools/GuidedExercises'
import ReferralPromo from './pages/profile/ReferralPromo'
import Journal from './pages/phr/Journal'
import SessionTemplates from './pages/professional/SessionTemplates'
import ProgressCertificate from './pages/tools/ProgressCertificate'
import EAPDashboard from './pages/corporate/EAPDashboard'
import FAQ from './pages/legal/FAQ'
import TermsOfService from './pages/legal/TermsOfService'
import PrivacyPolicy from './pages/legal/PrivacyPolicy'
import RefundPolicy from './pages/legal/RefundPolicy'

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
    <ThemeProvider>
    <I18nProvider>
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/faq" element={<FAQ />} />
        <Route path="/terms" element={<TermsOfService />} />
        <Route path="/privacy" element={<PrivacyPolicy />} />
        <Route path="/refund-policy" element={<RefundPolicy />} />
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
          <Route path="/lessons" element={<Lessons />} />
          <Route path="/lessons/:slug" element={<LessonDetail />} />
          <Route path="/history" element={<SessionHistory />} />
          <Route path="/progress" element={<ProgressDashboard />} />
          <Route path="/groups" element={<SupportGroups />} />
          <Route path="/groups/:id" element={<GroupChat />} />
          <Route path="/surveys" element={<FollowUpSurvey />} />
          <Route path="/availability" element={<AvailabilityManager />} />
          <Route path="/safety-plan" element={<SafetyPlan />} />
          <Route path="/goals" element={<Goals />} />
          <Route path="/medications" element={<Medications />} />
          <Route path="/caseload" element={<Caseload />} />
          <Route path="/caseload/:id" element={<CaseloadPatient />} />
          <Route path="/payouts" element={<Payouts />} />
          <Route path="/exercises" element={<GuidedExercises />} />
          <Route path="/referral" element={<ReferralPromo />} />
          <Route path="/journal" element={<Journal />} />
          <Route path="/templates" element={<SessionTemplates />} />
          <Route path="/certificate" element={<ProgressCertificate />} />
          <Route path="/eap-dashboard" element={<EAPDashboard />} />
        </Route>
      </Routes>
    </BrowserRouter>
    </I18nProvider>
    </ThemeProvider>
  )
}
