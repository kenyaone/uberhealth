import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import api from '../../api/axios'
import { ChevronLeft, ChevronRight, CheckCircle, AlertCircle } from 'lucide-react'

interface Question { key: string; text: string; scale?: string[] }
interface AssessmentData {
  title: string
  description: string
  scale?: string[]
  questions: Question[]
}

export default function TakeAssessment() {
  const { type } = useParams<{ type: string }>()
  const navigate = useNavigate()
  const [data, setData] = useState<AssessmentData | null>(null)
  const [responses, setResponses] = useState<Record<string, number>>({})
  const [current, setCurrent] = useState(0)
  const [result, setResult] = useState<any>(null)
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    api.get(`/assessments/questions/${type}/`).then(r => setData(r.data))
  }, [type])

  if (!data) return <div className="flex justify-center py-20 text-gray-500">Loading assessment...</div>

  const questions = data.questions
  const question = questions[current]
  const scale = question.scale || data.scale || []
  const allAnswered = questions.every(q => responses[q.key] !== undefined)

  const handleAnswer = (val: number) => {
    setResponses(prev => ({ ...prev, [question.key]: val }))
    if (current < questions.length - 1) {
      setTimeout(() => setCurrent(c => c + 1), 300)
    }
  }

  const handleSubmit = async () => {
    setLoading(true)
    try {
      const res = await api.post('/assessments/submit/', { assessment_type: type, responses })
      setResult(res.data)
    } finally {
      setLoading(false)
    }
  }

  if (result) {
    return (
      <div className="max-w-2xl mx-auto">
        <div className="card">
          <div className="text-center mb-6">
            {result.is_crisis_flag ? (
              <AlertCircle size={48} className="text-red-500 mx-auto mb-3" />
            ) : (
              <CheckCircle size={48} className="text-primary-600 mx-auto mb-3" />
            )}
            <h2 className="text-xl font-bold text-gray-900">{data.title} — Complete</h2>
          </div>

          <div className="bg-gray-50 rounded-xl p-5 mb-5">
            <div className="flex items-center justify-between mb-3">
              <span className="text-gray-600">Your Score</span>
              <span className="text-3xl font-bold text-primary-700">{result.score}</span>
            </div>
            <div className="text-lg font-semibold text-gray-900 mb-2">{result.severity}</div>
            <p className="text-gray-700 text-sm">{result.interpretation}</p>
          </div>

          {result.is_crisis_flag && (
            <div className="bg-red-50 border border-red-300 rounded-xl p-4 mb-5">
              <div className="font-semibold text-red-800 mb-2">⚠️ We noticed something important</div>
              <p className="text-red-700 text-sm mb-3">
                Your response suggests you may be having thoughts of self-harm. You are not alone.
                Please reach out immediately.
              </p>
              <div className="space-y-1 text-sm text-red-800">
                <div>📞 <strong>Befrienders Kenya: 0800 723 253</strong> (Free, 24/7)</div>
                <div>📞 <strong>NACADA: 1192</strong> (Free, 24/7)</div>
              </div>
            </div>
          )}

          <div className="bg-primary-50 rounded-xl p-4 mb-5">
            <div className="font-medium text-primary-800 mb-2">Recommendations</div>
            <p className="text-primary-700 text-sm">{result.recommendations}</p>
          </div>

          <div className="flex gap-3">
            <button onClick={() => navigate('/professionals')} className="btn-primary flex-1">
              Find a Therapist
            </button>
            <button onClick={() => navigate('/assessments')} className="btn-secondary flex-1">
              Back to Assessments
            </button>
          </div>
        </div>
      </div>
    )
  }

  const progress = ((current) / questions.length) * 100

  return (
    <div className="max-w-2xl mx-auto">
      <div className="mb-6">
        <button onClick={() => navigate('/assessments')} className="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-3">
          <ChevronLeft size={16} /> Back
        </button>
        <h1 className="text-xl font-bold text-gray-900">{data.title}</h1>
        <p className="text-gray-500 text-sm mt-1">{data.description}</p>
        <div className="mt-3 h-1.5 bg-gray-200 rounded-full">
          <div className="h-full bg-primary-600 rounded-full transition-all" style={{ width: `${progress}%` }} />
        </div>
        <div className="text-xs text-gray-400 mt-1 text-right">{current + 1} of {questions.length}</div>
      </div>

      <div className="card">
        <h2 className="text-lg font-medium text-gray-900 mb-6">{question.text}</h2>
        <div className="space-y-2">
          {scale.map((label, i) => {
            const val = i
            const isSelected = responses[question.key] === val
            return (
              <button
                key={i}
                onClick={() => handleAnswer(val)}
                className={`w-full text-left px-4 py-3 rounded-lg border-2 transition-all text-sm ${
                  isSelected
                    ? 'border-primary-600 bg-primary-50 text-primary-800 font-medium'
                    : 'border-gray-200 hover:border-primary-300 hover:bg-gray-50'
                }`}
              >
                {label}
              </button>
            )
          })}
        </div>

        <div className="flex justify-between mt-6">
          <button
            onClick={() => setCurrent(c => Math.max(0, c - 1))}
            disabled={current === 0}
            className="btn-secondary disabled:opacity-40"
          >
            <ChevronLeft size={16} className="inline" /> Previous
          </button>

          {current === questions.length - 1 ? (
            <button
              onClick={handleSubmit}
              disabled={!allAnswered || loading}
              className="btn-primary disabled:opacity-50"
            >
              {loading ? 'Calculating...' : 'See Results'}
            </button>
          ) : (
            <button
              onClick={() => setCurrent(c => c + 1)}
              disabled={responses[question.key] === undefined}
              className="btn-primary disabled:opacity-50"
            >
              Next <ChevronRight size={16} className="inline" />
            </button>
          )}
        </div>
      </div>
    </div>
  )
}
