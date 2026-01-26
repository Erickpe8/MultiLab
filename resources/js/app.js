import './bootstrap'
import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

console.debug && console.debug('[Toast] app.js loaded')

const TYPE_META = {
  success: {
    title: 'Éxito',
    borderClass: 'border-emerald-200',
    iconBg: 'bg-emerald-50',
    iconColor: 'text-emerald-600',
    progressColor: '#16a34a',
    iconPath: 'M5 13l4 4L19 7',
  },
  error: {
    title: 'Error',
    borderClass: 'border-[color-mix(in oklab, var(--primary) 40%, var(--border))]',
    iconBg: 'bg-[var(--primary-soft)]',
    iconColor: 'text-[var(--primary)]',
    progressColor: 'var(--primary)',
    iconPath: 'M6 18L18 6M6 6l12 12',
  },
  warning: {
    title: 'Alerta',
    borderClass: 'border-amber-200',
    iconBg: 'bg-amber-50',
    iconColor: 'text-amber-600',
    progressColor: '#f59e0b',
    iconPath: 'M12 9v2m0 4h.01',
  },
  info: {
    title: 'Info',
    borderClass: 'border-sky-200',
    iconBg: 'bg-sky-50',
    iconColor: 'text-sky-600',
    progressColor: '#2563eb',
    iconPath: 'M13 16h-1v-4h-1m1-4h.01',
  },
}

const MAX_ACTIVE_TOASTS = 4

const normalizeType = (value) => (TYPE_META[value] ? value : 'info')

const ToastManager = (() => {
  let activeToasts = []
  let rootElement = null

  const dispatchRemoval = (toast) => {
    if (!toast || toast.dataset.dismissed === 'true') {
      return
    }

    toast.dataset.dismissed = 'true'
    toast.classList.add('opacity-0', 'translate-y-5')
    toast.classList.remove('opacity-100', 'translate-y-0')

    clearTimeout(toast._timeoutId)

    window.setTimeout(() => {
      toast.remove()
      activeToasts = activeToasts.filter((t) => t !== toast)
    }, 250)
  }

  const createToast = ({ message, type = 'info', timeout = 5000 }) => {
    const safeType = normalizeType(type)
    const meta = TYPE_META[safeType]
    const toast = document.createElement('article')
    toast.setAttribute('role', 'status')
    toast.setAttribute('aria-live', 'polite')
    toast.className =
      'pointer-events-auto w-full rounded-2xl border bg-white/90 shadow-lg shadow-black/5 border-slate-200 px-4 py-3 text-sm text-slate-700 transition-all duration-300 ease-out opacity-0 translate-y-3'
    toast.classList.add(meta.borderClass)
    toast.dataset.type = safeType

    const layout = document.createElement('div')
    layout.className = 'flex items-start gap-3'

    const iconWrap = document.createElement('div')
    iconWrap.className =
      `inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full ${meta.iconBg}`
    const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg')
    icon.setAttribute('viewBox', '0 0 24 24')
    icon.setAttribute('fill', 'none')
    icon.setAttribute('stroke-width', '2')
    icon.setAttribute('stroke-linecap', 'round')
    icon.setAttribute('stroke-linejoin', 'round')
    icon.classList.add(meta.iconColor, 'h-5', 'w-5')

    const iconPath = document.createElementNS('http://www.w3.org/2000/svg', 'path')
    iconPath.setAttribute('d', meta.iconPath)
    icon.appendChild(iconPath)
    iconWrap.appendChild(icon)

    const body = document.createElement('div')
    body.className = 'flex-1 space-y-1'

    const header = document.createElement('div')
    header.className = 'flex items-center justify-between gap-2'

    const title = document.createElement('p')
    title.className = 'text-sm font-semibold text-slate-900'
    title.textContent = meta.title

    const closeBtn = document.createElement('button')
    closeBtn.setAttribute('type', 'button')
    closeBtn.setAttribute('aria-label', 'Cerrar toast')
    closeBtn.className =
      'inline-flex h-6 w-6 items-center justify-center rounded-full text-slate-500 hover:bg-slate-100'
    closeBtn.innerHTML = '<span aria-hidden="true">&times;</span>'
    closeBtn.addEventListener('click', () => dispatchRemoval(toast))

    header.appendChild(title)
    header.appendChild(closeBtn)

    const messageEl = document.createElement('p')
    messageEl.className = 'text-sm text-slate-600 leading-relaxed'
    messageEl.textContent = message

    body.appendChild(header)
    body.appendChild(messageEl)

    layout.appendChild(iconWrap)
    layout.appendChild(body)

    const progressTrack = document.createElement('div')
    progressTrack.className = 'mt-3 h-1 rounded-full bg-slate-100 overflow-hidden'

    const progress = document.createElement('div')
    progress.className = 'h-full rounded-full transition-all'
    progress.style.backgroundImage =
      `linear-gradient(90deg, ${meta.progressColor} 0%, ${meta.progressColor} 100%)`
    progress.style.width = '100%'
    progressTrack.appendChild(progress)

    toast.appendChild(layout)
    toast.appendChild(progressTrack)

    window.requestAnimationFrame(() => {
      toast.classList.remove('opacity-0', 'translate-y-3')
      toast.classList.add('opacity-100', 'translate-y-0')
    })

    const removeTimeout = window.setTimeout(() => dispatchRemoval(toast), Number(timeout) || 5000)
    toast._timeoutId = removeTimeout

    progress.style.transition = `width ${Number(timeout) || 5000}ms linear`
    window.setTimeout(() => {
      progress.style.width = '0%'
    }, 50)

    closeBtn.addEventListener('click', () => {
      progress.style.width = '0%'
    })

    return toast
  }

  const show = (payload) => {
    if (!payload || !payload.message) {
      return
    }

    const stack = rootElement

    if (!stack) {
      console.warn && console.warn('[Toast] cannot append toast, stack unavailable')
      return
    }

    const toast = createToast({
      ...payload,
      type: normalizeType(payload.type),
      timeout: Number(payload.timeout) || 5000,
    })

    stack.prepend(toast)
    activeToasts.unshift(toast)
    console.debug && console.debug('[Toast] toast appended', toast.dataset.type, toast.textContent)

    if (activeToasts.length > MAX_ACTIVE_TOASTS) {
      const oldest = activeToasts.pop()
      dispatchRemoval(oldest)
    }
  }

  const setRoot = (element) => {
    rootElement = element
  }

  return {
    show,
    setRoot,
  }
})()

const dispatchToastEvent = (detail) => {
  if (!detail?.message) return

  window.dispatchEvent(new CustomEvent('toast', { detail }))
}

const pendingToasts = []

const handleToastEvent = (event) => {
  const detail = event.detail ?? {}
  if (!detail.message) return

  if (!window.__toastBooted) {
    pendingToasts.push(detail)
    console.debug && console.debug('[Toast] event queued', detail)
    return
  }

  console.debug && console.debug('[Toast] event received', detail)
  ToastManager.show(detail)
}

window.addEventListener('toast', handleToastEvent)

const bootToast = () => {
  if (window.__toastBooted) {
    return
  }

  window.__toastBooted = true
  console.debug && console.debug('[Toast] manager booted')

  const stack = document.getElementById('toast-stack')
  if (!stack || window.getComputedStyle(stack).display === 'none') {
    console.warn && console.warn('[Toast] #toast-stack missing or hidden')
    return
  }

  ToastManager.setRoot(stack)

  pendingToasts.forEach((detail) => ToastManager.show(detail))
  pendingToasts.length = 0

  const smokeParam = new URL(window.location.href).searchParams.get('toast_smoke')
  const isLocal = import.meta.env.MODE === 'local' || import.meta.env.MODE === 'development'
  if (isLocal && smokeParam === '1') {
    dispatchToastEvent({
      message: 'Smoke test toast',
      type: 'info',
      timeout: 4000,
    })
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bootToast)
} else {
  bootToast()
}

window.showNotification = (message, type = 'info', timeout = 5000) => {
  dispatchToastEvent({
    message: message ?? '',
    type,
    timeout,
  })
}

window.notify = {
  show(message, type = 'info', timeout = 5000) {
    dispatchToastEvent({
      message: message ?? '',
      type,
      timeout,
    })
  },
}
