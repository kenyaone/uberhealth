import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import { Video, Shield, ExternalLink } from 'lucide-react'

export default function JoinSession() {
  const { consultationId } = useParams()
  const navigate = useNavigate()
  const user = useAuthStore(s => s.user)
  const [session, setSession] = useState<any>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [joined, setJoined] = useState(false)

  useEffect(() => {
    api.post(`/consultations/${consultationId}/join/`)
      .then(r => setSession(r.data))
      .catch(e => setError(e.response?.data?.error || 'Session not found or not ready.'))
      .finally(() => setLoading(false))
  }, [consultationId])

  const handleJoin = () => {
    if (session?.jitsi_url) {
      window.open(session.jitsi_url + `#userInfo.displayName="${user?.display_name}"`, '_blank')
      setJoined(true)
    }
  }

  if (loading) return <div className="text-center py-10 text-gray-400">Preparing your session...</div>
  if (error) return (
    <div className="max-w-lg mx-auto card text-center py-10">
      <div className="text-red-500 mb-3 text-lg font-medium">{error}</div>
      <button onClick={() => navigate('/consultations')} className="btn-secondary">Back to Sessions</button>
    </div>
  )

  return (
    <div className="max-w-2xl mx-auto space-y-5">
      <div>
        <h1 className="text-2xl font-bold text-gray-900">Your Session</h1>
        <p className="text-gray-500 text-sm mt-1">Session with {session?.consultation?.professional_detail?.display_name}</p>
      </div>

      <div className="card">
        <div className="flex items-start gap-3 mb-5">
          <Shield size={20} className="text-primary-600 flex-shrink-0 mt-0.5" />
          <div className="text-sm text-gray-700">
            <strong>Privacy note:</strong> This session uses Jitsi — your call is encrypted and NOT routed through WhatsApp or Meta.
            Your identity appears only as <strong>"{user?.display_name}"</strong> to the therapist.
          </div>
        </div>

        <div className="bg-gray-50 rounded-xl p-5 text-center mb-5">
          <Video size={48} className="text-primary-600 mx-auto mb-3" />
          <h2 className="font-semibold text-gray-900 mb-1">Video Session Ready</h2>
          <p className="text-sm text-gray-500 mb-1">Room: <code className="bg-gray-200 px-1.5 py-0.5 rounded text-xs">{session?.room}</code></p>
          <p className="text-xs text-gray-400">Encrypted · meet.jit.si</p>
        </div>

        <div className="text-sm text-gray-600 space-y-2 mb-5">
          <h3 className="font-medium text-gray-900">Before you join:</h3>
          <div className="flex items-center gap-2">✅ Find a quiet, private space</div>
          <div className="flex items-center gap-2">✅ Test your camera and microphone</div>
          <div className="flex items-center gap-2">✅ Ensure stable internet connection</div>
          <div className="flex items-center gap-2">✅ Use headphones for better audio</div>
        </div>

        <button onClick={handleJoin} className="btn-primary w-full py-3 text-base flex items-center justify-center gap-2">
          <Video size={20} />
          Join Video Session
          <ExternalLink size={16} />
        </button>

        {joined && (
          <div className="mt-4 text-center text-sm text-gray-500">
            Session opened in new tab. When done,{' '}
            <button onClick={() => navigate('/consultations')} className="text-primary-600 underline">
              return to your sessions
            </button>.
          </div>
        )}
      </div>
    </div>
  )
}
