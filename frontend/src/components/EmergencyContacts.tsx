import { AlertCircle, X, Phone } from 'lucide-react'

interface EmergencyContactsProps {
  isOpen: boolean
  onClose: () => void
}

export default function EmergencyContacts({ isOpen, onClose }: EmergencyContactsProps) {
  const emergencyContacts = [
    {
      name: 'Emergency Services',
      number: '999 / 911',
      description: 'Police, Fire, Ambulance - Life-threatening emergencies',
      color: 'red',
    },
    {
      name: 'Kenya Red Cross Helpline',
      number: '+254-719-241-214',
      description: 'Mental health crisis support available 24/7',
      color: 'red',
    },
    {
      name: 'Befrienders Kenya',
      number: '+254-722-178-177',
      description: 'Suicide prevention and emotional support',
      color: 'red',
    },
    {
      name: 'Mental Health Kenya',
      number: '+254-711-037-999',
      description: 'Professional mental health guidance and referrals',
      color: 'orange',
    },
    {
      name: 'Kenya Psychological Association',
      number: '+254-722-619-449',
      description: 'Psychology professional services and referrals',
      color: 'blue',
    },
  ]

  if (!isOpen) return null

  return (
    <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div className="bg-white rounded-2xl max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <AlertCircle size={28} className="text-red-600" />
            <h2 className="text-2xl font-bold text-gray-900">Emergency Support</h2>
          </div>
          <button onClick={onClose} className="text-gray-400 hover:text-gray-600">
            <X size={24} />
          </button>
        </div>

        {/* Content */}
        <div className="px-6 py-8">
          <div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
            <p className="text-red-900 font-semibold">
              If you or someone else is in immediate danger, please contact emergency services right now.
            </p>
          </div>

          <h3 className="text-lg font-bold text-gray-900 mb-6">Available Support Lines</h3>

          <div className="space-y-4 mb-8">
            {emergencyContacts.map((contact) => (
              <div
                key={contact.number}
                className={`border-l-4 rounded-lg p-4 ${
                  contact.color === 'red'
                    ? 'border-red-500 bg-red-50'
                    : contact.color === 'orange'
                      ? 'border-orange-500 bg-orange-50'
                      : 'border-blue-500 bg-blue-50'
                }`}
              >
                <div className="flex items-start justify-between mb-2">
                  <div>
                    <h4 className="font-bold text-gray-900">{contact.name}</h4>
                    <p className="text-sm text-gray-600 mt-1">{contact.description}</p>
                  </div>
                </div>
                <a
                  href={`tel:${contact.number.replace(/\s/g, '')}`}
                  className={`inline-flex items-center gap-2 font-bold ${
                    contact.color === 'red'
                      ? 'text-red-700 hover:text-red-900'
                      : contact.color === 'orange'
                        ? 'text-orange-700 hover:text-orange-900'
                        : 'text-blue-700 hover:text-blue-900'
                  }`}
                >
                  <Phone size={18} />
                  {contact.number}
                </a>
              </div>
            ))}
          </div>

          <div className="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
            <h4 className="font-semibold text-teal-900 mb-2">If You're Not in Immediate Danger:</h4>
            <ul className="text-sm text-teal-800 space-y-1">
              <li>✓ Talk to a trusted friend or family member</li>
              <li>✓ Contact a mental health professional directly</li>
              <li>✓ Visit your nearest health facility</li>
              <li>✓ Use the "Get Help" resources on our platform</li>
            </ul>
          </div>

          <div className="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h4 className="font-semibold text-gray-900 mb-2">Important:</h4>
            <p className="text-sm text-gray-700">
              This information is provided as a resource. If you are experiencing a mental health crisis, please reach out to the support lines above or your local emergency services. Your safety is the priority.
            </p>
          </div>

          <button
            onClick={onClose}
            className="w-full mt-8 px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  )
}
