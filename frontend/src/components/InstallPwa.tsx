import { useEffect, useState } from 'react'
import { X } from 'lucide-react'

interface BeforeInstallPromptEvent extends Event {
  prompt: () => Promise<void>
  userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>
}

export default function InstallPwa() {
  const [prompt, setPrompt] = useState<BeforeInstallPromptEvent | null>(null)
  const [dismissed, setDismissed] = useState(false)
  const [isIos, setIsIos] = useState(false)
  const [isStandalone, setIsStandalone] = useState(false)

  useEffect(() => {
    const standalone = window.matchMedia('(display-mode: standalone)').matches
      || (navigator as any).standalone === true
    setIsStandalone(standalone)

    const ios = /iphone|ipad|ipod/i.test(navigator.userAgent) && !(window as any).MSStream
    setIsIos(ios)

    const handler = (e: Event) => {
      e.preventDefault()
      setPrompt(e as BeforeInstallPromptEvent)
    }
    window.addEventListener('beforeinstallprompt', handler)
    return () => window.removeEventListener('beforeinstallprompt', handler)
  }, [])

  if (isStandalone || dismissed) return null
  if (!prompt && !isIos) return null
  if (localStorage.getItem('pwa_install_dismissed')) return null

  const handleInstall = async () => {
    if (!prompt) return
    await prompt.prompt()
    const { outcome } = await prompt.userChoice
    if (outcome === 'accepted') setDismissed(true)
  }

  const handleDismiss = () => {
    localStorage.setItem('pwa_install_dismissed', '1')
    setDismissed(true)
  }

  return (
    <div className="fixed bottom-0 left-0 right-0 z-50 bg-slate-900 border-t border-slate-700 px-4 py-3 flex items-center gap-3 shadow-2xl">
      <img src="/icon-192.png" alt="" className="w-10 h-10 rounded-xl flex-shrink-0" />
      <div className="flex-1 min-w-0">
        <p className="text-white text-sm font-semibold leading-tight">Install Afya Yako</p>
        {isIos ? (
          <p className="text-slate-400 text-xs">
            Tap <strong className="text-slate-300">Share</strong> then <strong className="text-slate-300">Add to Home Screen</strong>
          </p>
        ) : (
          <p className="text-slate-400 text-xs">Works offline · Faster access · No app store needed</p>
        )}
      </div>
      {!isIos && (
        <button
          onClick={handleInstall}
          className="bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors flex-shrink-0"
        >
          Install
        </button>
      )}
      <button onClick={handleDismiss} className="text-slate-400 hover:text-white flex-shrink-0" aria-label="Dismiss">
        <X size={18} />
      </button>
    </div>
  )
}
