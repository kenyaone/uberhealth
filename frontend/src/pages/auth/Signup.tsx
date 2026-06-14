import { useState } from 'react'
import { Link, useNavigate, useLocation } from 'react-router-dom'
import { useForm } from 'react-hook-form'
import api from '../../api/axios'
import { useAuthStore } from '../../store/authStore'
import { Shield, Eye, EyeOff } from 'lucide-react'

interface SignupForm {
  username: string
  display_name: string
  password: string
  password_confirm: string
  email?: string
  role: 'user' | 'professional'
  consent: boolean
}

export default function Signup() {
  const navigate = useNavigate()
  const location = useLocation()
  const presetRole = (location.state as any)?.role
  const { setAuth } = useAuthStore()
  const { register, handleSubmit, watch, formState: { errors } } = useForm<SignupForm>({
    defaultValues: { role: presetRole === 'professional' ? 'professional' : 'user' }
  })
  const [showPassword, setShowPassword] = useState(false)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const password = watch('password')

  const onSubmit = async (data: SignupForm) => {
    setLoading(true)
    setError('')
    try {
      const res = await api.post('/auth/signup', data)
      setAuth(res.data.user, res.data.access, res.data.refresh)
      navigate(res.data.user.role === 'professional' ? '/apply' : '/dashboard')
    } catch (err: any) {
      const errs = err.response?.data
      const messages = []
      if (errs?.username) messages.push(errs.username[0])
      if (errs?.password) messages.push(errs.password[0])
      if (errs?.email) messages.push(errs.email[0])
      if (errs?.display_name) messages.push(errs.display_name[0])
      if (errs?.error) messages.push(errs.error)
      setError(messages.length > 0 ? messages.join(' ') : 'Signup failed. Try again.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
      <div className="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <div className="text-center mb-6">
          <h1 className="text-2xl font-bold text-gray-900">Create Your Account</h1>
          <p className="text-gray-500 text-sm mt-1">No real name required. Stay anonymous.</p>
        </div>

        <div className="bg-primary-50 border border-primary-200 rounded-lg p-3 mb-6 flex gap-2 text-sm text-primary-800">
          <Shield size={16} className="flex-shrink-0 mt-0.5" />
          <span>Your username is private. Professionals only see your display name.</span>
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Username <span className="text-gray-400 text-xs">(private, never shown to others)</span>
            </label>
            <input
              {...register('username', {
                required: 'Username is required',
                minLength: { value: 5, message: 'At least 5 characters' },
                pattern: { value: /^[a-zA-Z0-9_]+$/, message: 'Letters, numbers, underscores only' }
              })}
              className="input-field"
              placeholder="StrengthSeeker101"
            />
            {errors.username && <p className="text-red-500 text-xs mt-1">{errors.username.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Display Name <span className="text-gray-400 text-xs">(shown to therapist — can be fake)</span>
            </label>
            <input
              {...register('display_name', { required: 'Display name is required' })}
              className="input-field"
              placeholder="John, Patient A, or any name"
            />
            {errors.display_name && <p className="text-red-500 text-xs mt-1">{errors.display_name.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">
              Email <span className="text-gray-400 text-xs">(optional — for reminders only)</span>
            </label>
            <input
              {...register('email')}
              type="email"
              className="input-field"
              placeholder="optional@email.com"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div className="relative">
              <input
                {...register('password', {
                  required: 'Password required',
                  minLength: { value: 8, message: 'At least 8 characters' }
                })}
                type={showPassword ? 'text' : 'password'}
                className="input-field pr-10"
                placeholder="••••••••"
              />
              <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
              </button>
            </div>
            {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input
              {...register('password_confirm', {
                required: 'Please confirm password',
                validate: (val) => val === password || 'Passwords do not match'
              })}
              type="password"
              className="input-field"
              placeholder="••••••••"
            />
            {errors.password_confirm && <p className="text-red-500 text-xs mt-1">{errors.password_confirm.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">I am signing up as</label>
            <select {...register('role')} className="input-field">
              <option value="user">A Person Seeking Support</option>
              <option value="professional">A Mental Health Professional</option>
            </select>
          </div>

          {/* DPA 2019 consent — required */}
          <div>
            <label className="flex items-start gap-3 cursor-pointer">
              <input
                type="checkbox"
                {...register('consent', { required: 'You must agree to continue' })}
                className="mt-0.5 w-4 h-4 accent-teal-600 flex-shrink-0"
              />
              <span className="text-sm text-gray-600 leading-relaxed">
                I have read and agree to the{' '}
                <Link to="/privacy" target="_blank" className="text-teal-700 font-medium hover:underline">Privacy Policy</Link>
                {' '}and{' '}
                <Link to="/terms" target="_blank" className="text-teal-700 font-medium hover:underline">Terms of Service</Link>.
                I understand my data is processed under the <strong>Kenya Data Protection Act 2019</strong>.
              </span>
            </label>
            {errors.consent && <p className="text-red-500 text-xs mt-1 ml-7">{errors.consent.message}</p>}
          </div>

          <button type="submit" disabled={loading} className="btn-primary w-full py-3 mt-2">
            {loading ? 'Creating account...' : 'Create Account Free'}
          </button>
        </form>

        <p className="text-center text-sm text-gray-500 mt-6">
          Already have an account?{' '}
          <Link to="/login" className="text-primary-700 font-medium hover:underline">Sign in</Link>
        </p>
      </div>
    </div>
  )
}
