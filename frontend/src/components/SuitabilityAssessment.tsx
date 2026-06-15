import { useState } from 'react'
import { AlertCircle, CheckCircle, Phone } from 'lucide-react'

interface SuitabilityAssessmentProps {
  onComplete: (suitable: boolean) => void
  onEmergency?: () => void
}

export default function SuitabilityAssessment({ onComplete, onEmergency }: SuitabilityAssessmentProps) {
  const [currentQuestion, setCurrentQuestion] = useState(0)
  const [answers, setAnswers] = useState<boolean[]>([])
  const [showEmergencyWarning, setShowEmergencyWarning] = useState(false)

  const questions = [
    {
      text: "Are you currently in a safe location where you can talk privately?",
      helpText: "Tele-mental health requires a quiet, private space"
    },
    {
      text: "Do you have a stable internet connection for video calls?",
      helpText: "A reliable connection ensures session quality"
    },
    {
      text: "Are you currently experiencing thoughts of self-harm or suicide?",
      helpText: "If yes, please contact emergency services instead",
      isRed: true
    },
    {
      text: "Do you feel safe from immediate physical harm?",
      helpText: "Tele-mental health requires personal safety",
      isRed: true
    },
    {
      text: "Are you willing to keep session schedules and commitments?",
      helpText: "Consistency helps with therapeutic progress"
    },
  ]

  const handleAnswer = (answer: boolean) => {
    const newAnswers = [...answers, answer]
    setAnswers(newAnswers)

    // Emergency check: if yes to self-harm or no to safety
    if ((currentQuestion === 2 && answer) || (currentQuestion === 3 && !answer)) {
      setShowEmergencyWarning(true)
      return
    }

    // Move to next question or complete
    if (currentQuestion < questions.length - 1) {
      setCurrentQuestion(currentQuestion + 1)
    } else {
      // All questions answered - check suitability
      const suitable = newAnswers[0] && newAnswers[1] && !newAnswers[2] && newAnswers[3] && newAnswers[4]
      onComplete(suitable)
    }
  }

  if (showEmergencyWarning) {
    return (
      <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div className="bg-white rounded-2xl max-w-md w-full shadow-2xl p-8 text-center">
          <div className="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <AlertCircle size={32} className="text-red-600" />
          </div>
          <h2 className="text-2xl font-bold text-gray-900 mb-3">We're Concerned About Your Safety</h2>
          <p className="text-gray-600 mb-6">
            Based on your responses, tele-mental health may not be appropriate right now. Please reach out to emergency services or a crisis helpline immediately.
          </p>

          <div className="space-y-3 mb-8 bg-red-50 rounded-lg p-4">
            <div className="flex items-center gap-2">
              <Phone size={20} className="text-red-600" />
              <div className="text-left">
                <p className="font-semibold text-red-900">Emergency Services</p>
                <p className="text-sm text-red-700">Call 999 or 911</p>
              </div>
            </div>
            <div className="flex items-center gap-2">
              <Phone size={20} className="text-red-600" />
              <div className="text-left">
                <p className="font-semibold text-red-900">Kenya Red Cross</p>
                <p className="text-sm text-red-700">+254-719-241-214</p>
              </div>
            </div>
            <div className="flex items-center gap-2">
              <Phone size={20} className="text-red-600" />
              <div className="text-left">
                <p className="font-semibold text-red-900">Befrienders Kenya</p>
                <p className="text-sm text-red-700">+254-722-178-177</p>
              </div>
            </div>
          </div>

          <button
            onClick={() => onEmergency?.()}
            className="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg mb-2"
          >
            Call Emergency Services
          </button>
          <button
            onClick={() => {
              setShowEmergencyWarning(false)
              setAnswers([])
              setCurrentQuestion(0)
            }}
            className="w-full px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50"
          >
            Go Back
          </button>
        </div>
      </div>
    )
  }

  const question = questions[currentQuestion]
  const progress = ((currentQuestion + 1) / questions.length) * 100

  return (
    <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div className="bg-white rounded-2xl max-w-md w-full shadow-2xl p-8">
        <div className="mb-6">
          <div className="flex items-center justify-between mb-2">
            <h3 className="text-sm font-semibold text-gray-600">Suitability Assessment</h3>
            <span className="text-sm font-medium text-gray-500">{currentQuestion + 1}/{questions.length}</span>
          </div>
          <div className="w-full bg-gray-200 rounded-full h-2">
            <div
              className={`h-2 rounded-full transition-all ${question.isRed ? 'bg-red-500' : 'bg-teal-600'}`}
              style={{ width: `${progress}%` }}
            />
          </div>
        </div>

        <h2 className="text-lg font-bold text-gray-900 mb-3">{question.text}</h2>
        <p className="text-sm text-gray-600 mb-8">{question.helpText}</p>

        <div className="space-y-3">
          <button
            onClick={() => handleAnswer(true)}
            className="w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition-colors"
          >
            <CheckCircle size={18} />
            Yes
          </button>
          <button
            onClick={() => handleAnswer(false)}
            className="w-full px-4 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors"
          >
            No
          </button>
        </div>
      </div>
    </div>
  )
}
