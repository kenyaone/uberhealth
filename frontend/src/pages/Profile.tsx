import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { useAuthStore } from '../store/authStore'
import api from '../api/axios'
import { User, Shield, Save } from 'lucide-react'

interface ProfileForm {
  display_name: string
  email: string
  is_anonymous_mode: boolean
}

export default function Profile() {
  const { user, updateUser } = useAuthStore()
  const [saving, setSaving] = useState(false)
  const [saved, setSaved] = useState(false)
  const { register, handleSubmit } = useForm<ProfileForm>({
    defaultValues: {
      display_name: user?.display_name || '',
      email: user?.email || '',
      is_anonymous_mode: user?.is_anonymous_mode ?? true,
    }
  })

  const onSubmit = async (data: ProfileForm) => {
    setSaving(true)
    try {
      const res = await api.patch('/auth/profile/', data)
      updateUser(res.data)
      setSaved(true)
      setTimeout(() => setSaved(false), 3000)
    } finally {
      setSaving(false)
    }
  }

  return (
    <div className="max-w-xl mx-auto space-y-5">
      <h1 className="text-2xl font-bold text-gray-900">Your Profile</h1>

      <div className="card">
        <div className="flex items-center gap-4 mb-5">
          <div className="w-16 h-16 rounded-2xl bg-primary-700 text-white flex items-center justify-center text-2xl font-bold">
            {user?.display_name?.charAt(0).toUpperCase()}
          </div>
          <div>
            <div className="font-semibold text-gray-900 text-lg">{user?.display_name}</div>
            <div className="text-sm text-gray-500">@{user?.username}</div>
            <div className="text-xs text-primary-600 mt-0.5 capitalize">{user?.role}</div>
          </div>
        </div>

        <div className="bg-primary-50 border border-primary-200 rounded-lg p-3 flex gap-2 text-sm text-primary-800 mb-5">
          <Shield size={16} className="flex-shrink-0 mt-0.5" />
          <span>Your username <strong>@{user?.username}</strong> is never shown to professionals.</span>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Display Name <span className="text-gray-400 text-xs">(shown to therapists)</span>
            </label>
            <input {...register('display_name', { required: true })} className="input-field" />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Email <span className="text-gray-400 text-xs">(optional, for reminders)</span>
            </label>
            <input {...register('email')} type="email" className="input-field" placeholder="optional@email.com" />
          </div>

          <label className="flex items-center gap-3 cursor-pointer">
            <input {...register('is_anonymous_mode')} type="checkbox" className="rounded border-gray-300 text-primary-600" />
            <div>
              <div className="text-sm font-medium text-gray-900">Anonymous mode</div>
              <div className="text-xs text-gray-500">Keep your username hidden in all interactions</div>
            </div>
          </label>

          <button type="submit" disabled={saving} className="btn-primary w-full flex items-center justify-center gap-2">
            <Save size={16} />
            {saving ? 'Saving...' : saved ? '✓ Saved!' : 'Save Changes'}
          </button>
        </form>
      </div>

      <div className="card">
        <h2 className="font-semibold text-gray-900 mb-3">Account Security</h2>
        <div className="space-y-2 text-sm text-gray-600">
          <div className="flex items-center justify-between">
            <span>Username</span>
            <span className="font-medium text-gray-900">@{user?.username}</span>
          </div>
          <div className="flex items-center justify-between">
            <span>Account type</span>
            <span className="font-medium text-gray-900 capitalize">{user?.role}</span>
          </div>
          <div className="flex items-center justify-between">
            <span>Data encryption</span>
            <span className="text-green-600 font-medium">✓ AES-256</span>
          </div>
          <div className="flex items-center justify-between">
            <span>WhatsApp required</span>
            <span className="text-green-600 font-medium">✗ Never</span>
          </div>
        </div>
      </div>
    </div>
  )
}
