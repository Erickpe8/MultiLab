console.log('[reports] reports/index.js cargado');

const BASE_SUMMARY_CARDS = [
  {
    key: 'pending_users',
    label: 'Solicitudes pendientes',
    hint: 'Usuarios por aprobar',
    variant: 'warning',
    value: 0,
  },
  {
    key: 'active_loans',
    label: 'Préstamos activos',
    hint: 'En curso',
    variant: 'info',
    value: 0,
  },
  {
    key: 'overdue_loans',
    label: 'Préstamos vencidos',
    hint: 'Requieren acción',
    variant: 'danger',
    value: 0,
  },
  {
    key: 'active_reservations',
    label: 'Reservas activas',
    hint: 'Aula B201',
    variant: 'success',
    value: 0,
  },
]

const VARIANT_COLOR = {
  warning: 'text-amber-600',
  info: 'text-sky-600',
  danger: 'text-red-600',
  success: 'text-emerald-600',
}

const INVENTORY_KEYS = ['low-stock', 'overdue', 'top-materials']

const SERIES = [
  { key: 'loans', label: 'Préstamos', color: '#2563eb' },
  { key: 'reservations', label: 'Reservas', color: '#16a34a' },
]

const formatNumber = (value) => {
  const safeValue = Number.isFinite(Number(value)) ? Number(value) : 0
  return new Intl.NumberFormat('es-ES', { maximumFractionDigits: 0 }).format(safeValue)
}

const UPDATED_FORMATTER = new Intl.DateTimeFormat('es-CO', {
  dateStyle: 'medium',
  timeStyle: 'short',
  hourCycle: 'h23',
  timeZone: 'America/Bogota',
})

const formatUpdatedAt = (isoString) => {
  if (!isoString) {
    return null
  }

  const parsed = new Date(isoString)
  if (Number.isNaN(parsed.getTime())) {
    return null
  }

  return UPDATED_FORMATTER.format(parsed)
}

const buildTimestampText = (isoString, options = {}) => {
  if (options.error) {
    return 'No disponible'
  }

  const formatted = formatUpdatedAt(isoString)
  if (!formatted) {
    return 'Actualizando�'
  }

  return `Actualizado el ${formatted}`
}

const setUpdatedLabel = (element, isoString, options = {}) => {
  if (!element) {
    return
  }

  element.textContent = buildTimestampText(isoString, options)
}

const buildWeekDays = () => {
  const days = []
  const today = new Date()

  for (let offset = 13; offset >= 0; offset -= 1) {
    const snapshot = new Date(today)
    snapshot.setDate(today.getDate() - offset)
    const label = new Intl.DateTimeFormat('es-ES', {
      day: 'numeric',
      month: 'short',
    })
      .format(snapshot)
      .replace('.', '')
      .toLowerCase()

    days.push({
      date: snapshot.toISOString().split('T')[0],
      label,
    })
  }

  return days
}

const mergeActivityDays = (payloadDays) => {
  const baseDays = buildWeekDays()
  const provided = Array.isArray(payloadDays) ? payloadDays : []
  const lookup = provided.reduce((acc, current) => {
    if (current?.date) {
      acc[current.date] = current
    }
    return acc
  }, {})

  return baseDays.map((day) => {
    const match = lookup[day.date]
    return {
      date: day.date,
      label: day.label,
      loans: Number.isFinite(match?.loans) ? match.loans : 0,
      reservations: Number.isFinite(match?.reservations) ? match.reservations : 0,
    }
  })
}

const createCardMarkup = (card) => {
  const variantClass = VARIANT_COLOR[card.variant] ?? 'text-[var(--text)]'
  const formattedValue = formatNumber(card.value)

  return `
        <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 shadow-sm transition-all duration-200">
            <div class="flex items-end justify-between gap-4">
                <p class="text-3xl font-semibold ${variantClass}">${formattedValue}</p>
            </div>
            <p class="mt-4 text-sm font-semibold text-[var(--text)]">${card.label}</p>
            <p class="text-xs text-[var(--text-muted)]">${card.hint}</p>
        </article>
    `
}

const renderSummary = (payload, summaryContainer, updatedEl, errorEl, options = {}) => {
  const payloadCards = payload?.cards?.length ? payload.cards : BASE_SUMMARY_CARDS
  summaryContainer.innerHTML = payloadCards.map(createCardMarkup).join('')
  setUpdatedLabel(updatedEl, payload?.updated_at, options)
  errorEl?.classList.add('hidden')
}

const MS_PER_DAY = 86400000
const computeDueDiff = (due) => {
  if (!due) {
    return 0
  }
  const dueDate = new Date(due)
  const now = new Date()
  return Math.round((now - dueDate) / MS_PER_DAY)
}

const buildDueStatus = (due) => {
  const diff = computeDueDiff(due)
  const absDiff = Math.abs(diff)

  if (diff > 0) {
    return { label: 'Vencido', description: `Hace ${absDiff} ${absDiff === 1 ? 'día' : 'días'}` }
  }

  if (diff === 0) {
    return { label: 'Vence hoy', description: 'Hoy' }
  }

  return {
    label: 'Vence pronto',
    description: `Faltan ${absDiff} ${absDiff === 1 ? 'día' : 'días'}`,
  }
}

const renderActivityLegend = () => {
  const el = document.querySelector('[data-activity-legend]')
  if (!el) {
    console.warn('[reports] no existe data-activity-legend')
    return
  }

  console.log('[reports] renderActivityLegend() ejecutado')
  el.innerHTML = `
        <span class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--card)] px-3 py-1 text-[var(--text)]">
            <span class="h-2.5 w-2.5 rounded-full" style="background:#2563eb"></span>
            Préstamos
        </span>
        <span class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--card)] px-3 py-1 text-[var(--text)]">
            <span class="h-2.5 w-2.5 rounded-full" style="background:#16a34a"></span>
            Reservas
        </span>
    `
}

const updateActivityStats = (element, loans, reservations) => {
  if (!element) {
    return
  }
  element.innerHTML = `
        <span data-activity-loans class="text-[var(--text-muted)]">
            Préstamos: <span class="text-[var(--accent)]">${formatNumber(loans)}</span>
        </span>
        <span data-activity-reservations class="text-[var(--text-muted)]">
            Reservas: <span class="text-[var(--accent)]">${formatNumber(reservations)}</span>
        </span>
    `
}
const renderActivity = (
  payload,
  chartEl,
  chartWrapper,
  statsEl,
  updatedEl,
  loaderEl,
  emptyEl,
  errorEl,
  chartState,
  options = {},
) => {
  if (!chartEl) {
    loaderEl?.classList.add('hidden')
    return
  }

  const days = mergeActivityDays(payload?.days)
  const hasActivity = days.some((day) => day.loans > 0 || day.reservations > 0)
  errorEl?.classList.add('hidden')

  const totalLoans = days.reduce((sum, day) => sum + Number(day.loans ?? 0), 0)
  const totalReservations = days.reduce((sum, day) => sum + Number(day.reservations ?? 0), 0)
  updateActivityStats(statsEl, totalLoans, totalReservations)

  if (!hasActivity) {
    chartWrapper?.classList.add('hidden')
    emptyEl?.classList.remove('hidden')
    loaderEl?.classList.add('hidden')
    chartState.instance?.destroy()
    chartState.instance = null
    setUpdatedLabel(updatedEl, payload?.updated_at, options)
    return
  }

  chartWrapper?.classList.remove('hidden')
  emptyEl?.classList.add('hidden')

  const isDark = document.documentElement.classList.contains('dark')
  const tooltipBg = getComputedStyle(document.documentElement).getPropertyValue('--card').trim() || '#fff'
  const tooltipTextColor = getComputedStyle(document.documentElement).getPropertyValue('--text').trim() || '#111827'
  const tooltipBorderColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim() || 'rgba(15,23,42,0.2)'
  const config = {
    chart: {
      type: 'bar',
      height: '100%',
      toolbar: { show: false },
      animations: { easing: 'easeinout', speed: 400 },
    },
    theme: { mode: isDark ? 'dark' : 'light' },
    colors: SERIES.map((series) => series.color),
    plotOptions: {
      bar: {
        columnWidth: '48%',
        borderRadius: 8,
        endingShape: 'rounded',
      },
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent'],
    },
    tooltip: {
      enabled: true,
      shared: false,
      intersect: true,
      followCursor: true,
      theme: isDark ? 'dark' : 'light',
      x: { show: false },
      y: {
        formatter: (value) => `${value ?? 0}`,
      },
      custom: ({ series = [], seriesIndex, dataPointIndex, w }) => {
        const label =
          w?.config?.xaxis?.categories?.[dataPointIndex]
          ?? w?.globals?.categoryLabels?.[dataPointIndex]
          ?? ''
        const value = Number.isFinite(series?.[seriesIndex]?.[dataPointIndex])
          ? series[seriesIndex][dataPointIndex]
          : 0
        const name = w?.config?.series?.[seriesIndex]?.name ?? `Serie`

        return `
          <div style="background:${tooltipBg}; color:${tooltipTextColor}; border:1px solid ${tooltipBorderColor}; border-radius:0.65rem; padding:0.5rem 0.75rem; font-size:0.85rem; box-shadow:0 10px 30px rgba(15,23,42,0.25);">
            <div style="font-weight:600; margin-bottom:0.15rem;">${name}</div>
            <div>${label}: ${value ?? 0}</div>
          </div>
        `
      },
    },
    xaxis: {
      categories: days.map((day) => day.label),
      labels: {
        style: { colors: Array(days.length).fill('var(--text-secondary)') },
      },
      axisBorder: { show: false },
    },
    yaxis: {
      labels: {
        style: { colors: 'var(--text-secondary)' },
      },
    },
    grid: {
      strokeDashArray: 4,
      borderColor: 'var(--border)',
    },
    series: SERIES.map((series) => ({
      name: series.label,
      data: days.map((day) => day[series.key] ?? 0),
    })),
  }

  const renderChart = () => {
    if (!window.ApexCharts) {
      errorEl?.classList.remove('hidden')
      errorEl.textContent = 'ApexCharts no se pudo cargar.'
      loaderEl?.classList.add('hidden')
      return
    }

    if (!chartState.instance) {
      chartState.instance = new window.ApexCharts(chartEl, config)
      chartState.instance.render().finally(() => loaderEl?.classList.add('hidden'))
    } else {
      chartState.instance.updateOptions(config).finally(() => loaderEl?.classList.add('hidden'))
    }
  }

  renderChart()
  renderActivityLegend()
  setUpdatedLabel(updatedEl, payload?.updated_at, options)
}

const inventoryRenderers = {
  'low-stock': (item) => {
    const li = document.createElement('li')
    li.className = 'inventory-item flex items-start justify-between gap-3'

    const info = document.createElement('div')
    const name = document.createElement('p')
    name.className = 'text-sm font-medium text-[var(--text)]'
    name.textContent = item.name ?? '—'

    const meta = document.createElement('p')
    meta.className = 'text-xs text-[var(--text-muted)]'
    meta.textContent = `Stock ${item.stock ?? 0} · Mín ${item.min ?? 0}`

    info.append(name, meta)

    const metaStack = document.createElement('div')
    metaStack.className = 'meta-stack text-right text-[var(--text)]'
    const badge = document.createElement('span')
    badge.className = 'text-xs font-semibold text-[var(--text)]'
    badge.textContent = `#${item.id ?? '—'}`
    metaStack.append(badge)

    li.append(info, metaStack)
    return li
  },
  overdue: (item) => {
    const { description: statusDescription } = buildDueStatus(item.due)
    const li = document.createElement('li')
    li.className =
      'inventory-item flex items-start justify-between gap-3 rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-3 py-3 transition hover:bg-[var(--card)]'

    const info = document.createElement('div')
    info.className = 'flex-1 min-w-0 space-y-1'

    const title = document.createElement('p')
    title.className = 'text-sm font-semibold text-[var(--text)] break-words'
    title.textContent = item.code ? `Préstamo ${item.code}` : 'Préstamo sin código'

    const subtitle = document.createElement('p')
    subtitle.className = 'text-xs text-[var(--text-muted)] break-words'
    subtitle.textContent = `${item.who ? `Solicitante ${item.who}` : 'Solicitante desconocido'} · vence ${item.due ?? '—'}`

    info.append(title, subtitle)

    const metaStack = document.createElement('div')
    metaStack.className = 'w-[110px] shrink-0 text-right space-y-0.5 leading-snug'

    const dueDate = document.createElement('p')
    dueDate.className = 'text-sm font-semibold text-orange-600 whitespace-normal break-words'
    dueDate.textContent = item.due ?? 'Sin fecha'

    const relative = document.createElement('p')
    relative.className = 'text-xs text-[var(--text-muted)] whitespace-normal break-words'
    relative.textContent = statusDescription

    metaStack.append(dueDate, relative)

    li.append(info, metaStack)
    return li
  },
  'top-materials': (item) => {
    const li = document.createElement('li')
    li.className = 'inventory-item flex items-start justify-between gap-3'

    const info = document.createElement('div')
    const name = document.createElement('p')
    name.className = 'text-sm font-medium text-[var(--text)]'
    name.textContent = item.name ?? '—'

    const meta = document.createElement('p')
    meta.className = 'text-xs text-[var(--text-muted)]'
    meta.textContent = `Solicitudes ${item.qty ?? 0}`

    info.append(name, meta)

    const metaStack = document.createElement('div')
    metaStack.className = 'meta-stack text-right'
    const badge = document.createElement('span')
    badge.className = 'text-xs font-semibold text-[var(--text)]'
    badge.textContent = `#${item.id ?? '—'}`
    metaStack.append(badge)

    li.append(info, metaStack)
    return li
  },
}

const renderInventory = (
  payload,
  listMap,
  countMap,
  emptyMap,
  updatedEl,
  errorEl,
  options = {},
) => {
  const data = payload ?? { low_stock: [], overdue: [], top_materials: [], updated_at: null }
  errorEl?.classList.add('hidden')

  INVENTORY_KEYS.forEach((key) => {
    const listEl = listMap[key]
    const countEl = countMap[key]
    const emptyEl = emptyMap[key]
    let items = (() => {
      if (key === 'low-stock') {
        return Array.isArray(data.low_stock) ? data.low_stock : []
      }
      if (key === 'overdue') {
        return Array.isArray(data.overdue) ? data.overdue : []
      }
      return Array.isArray(data.top_materials) ? data.top_materials : []
    })()

    if (key === 'overdue' && items.length > 1) {
      items = items
        .slice()
        .sort((a, b) => computeDueDiff(b.due) - computeDueDiff(a.due))
    }

    if (listEl) {
      listEl.innerHTML = ''
      if (items.length === 0) {
        emptyEl?.classList.remove('hidden')
      } else {
        emptyEl?.classList.add('hidden')
        const renderer = inventoryRenderers[key]
        items.forEach((item) => {
          const node = renderer(item)
          listEl.append(node)
        })
      }
    }

    if (countEl) {
      countEl.textContent = String(items.length)
    }
  })

  setUpdatedLabel(updatedEl, data.updated_at, options)
}

const showError = (element, message) => {
  if (!element) {
    return
  }
  element.textContent = message
  element.classList.remove('hidden')
}

const fetchJson = async (url) => {
  const response = await fetch(url, { headers: { Accept: 'application/json' } })
  if (!response.ok) {
    throw new Error(`HTTP ${response.status}`)
  }
  return response.json()
}

document.addEventListener('DOMContentLoaded', () => {
  const root = document.querySelector('[data-reports-page]')
  if (!root) {
    return
  }

  const summaryContainer = root.querySelector('[data-reports-summary]')
  const summaryUpdated = root.querySelector('[data-summary-updated]')
  const summaryError = root.querySelector('[data-summary-error]')

  const activityChartBar = root.querySelector('[data-reports-chart]')
  const activityChartWrapper = root.querySelector('[data-activity-chart-wrapper]')
  const activityUpdated = root.querySelector('[data-activity-updated]')
  const activityStats = root.querySelector('[data-activity-stats]')
  const activityLoader = root.querySelector('[data-activity-loading]')
  const activityEmpty = root.querySelector('[data-activity-empty]')
  const activityError = root.querySelector('[data-activity-error]')

  const inventoryUpdated = root.querySelector('[data-inventory-updated]')
  const inventoryError = root.querySelector('[data-inventory-error]')

  const inventoryLists = {}
  const inventoryCounts = {}
  const inventoryEmptyNodes = {}

  INVENTORY_KEYS.forEach((key) => {
    inventoryLists[key] = root.querySelector(`[data-inventory-list="${key}"]`)
    inventoryCounts[key] = root.querySelector(`[data-inventory-count="${key}"]`)
  })

  root.querySelectorAll('[data-inventory-empty]').forEach((emptyNode) => {
    const target = emptyNode.getAttribute('data-inventory-target')
    if (target) {
      inventoryEmptyNodes[target] = emptyNode
    }
  })

  const chartState = { instance: null }
  renderActivityLegend()

  const loadReports = async () => {
    const summaryPromise = fetchJson('/reports/summary')
    const activityPromise = fetchJson('/reports/activity')
    const inventoryPromise = fetchJson('/reports/inventory')

    const [summaryResult, activityResult, inventoryResult] = await Promise.allSettled([
      summaryPromise,
      activityPromise,
      inventoryPromise,
    ])

    if (summaryResult.status === 'fulfilled') {
      renderSummary(summaryResult.value, summaryContainer, summaryUpdated, summaryError)
    } else {
      showError(summaryError, 'No se pudieron cargar los indicadores.')
      renderSummary(null, summaryContainer, summaryUpdated, summaryError, { error: true })
    }

    if (activityResult.status === 'fulfilled') {
      renderActivity(
        activityResult.value,
        activityChartBar,
        activityChartWrapper,
        activityStats,
        activityUpdated,
        activityLoader,
        activityEmpty,
        activityError,
        chartState,
      )
    } else {
      showError(activityError, 'No se pudo cargar la gráfica de actividad.')
      renderActivity(
        null,
        activityChartBar,
        activityChartWrapper,
        activityStats,
        activityUpdated,
        activityLoader,
        activityEmpty,
        activityError,
        chartState,
        { error: true },
      )
    }

    if (inventoryResult.status === 'fulfilled') {
      renderInventory(
        inventoryResult.value,
        inventoryLists,
        inventoryCounts,
        inventoryEmptyNodes,
        inventoryUpdated,
        inventoryError,
      )
    } else {
      showError(inventoryError, 'No se pudo cargar el inventario.')
      renderInventory(
        null,
        inventoryLists,
        inventoryCounts,
        inventoryEmptyNodes,
        inventoryUpdated,
        inventoryError,
        { error: true },
      )
    }
  }

  loadReports()

  window.addEventListener('theme:changed', () => {
    if (!activityChartBar || !chartState.instance) {
      return
    }

    const isDark = document.documentElement.classList.contains('dark')

    chartState.instance.updateOptions({
      theme: { mode: isDark ? 'dark' : 'light' },
      colors: SERIES.map((series) => series.color),
      tooltip: { theme: isDark ? 'dark' : 'light' },
    })
  })
})
