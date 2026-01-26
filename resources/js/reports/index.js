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

const formatNumber = (value) => {
  const safeValue = Number.isFinite(Number(value)) ? Number(value) : 0
  return new Intl.NumberFormat('es-ES', { maximumFractionDigits: 0 }).format(safeValue)
}

const formatUpdated = (value) => {
  if (!value) {
    return 'Sin actualizaciones recientes'
  }

  const parsed = new Date(value)
  if (Number.isNaN(parsed.getTime())) {
    return 'Sin actualizaciones recientes'
  }

  return `Actualizado el ${new Intl.DateTimeFormat('es-ES', {
    dateStyle: 'medium',
    timeStyle: 'short',
  }).format(parsed)}`
}

const getThemeColors = () => {
  const computed = getComputedStyle(document.documentElement)
  return {
    loans: computed.getPropertyValue('--accent').trim() || '#1D4ED8',
    reservations: computed.getPropertyValue('--success').trim() || '#16A34A',
  }
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

const renderSummary = (payload, summaryContainer, updatedEl, errorEl) => {
  const payloadCards = payload?.cards?.length ? payload.cards : BASE_SUMMARY_CARDS
  summaryContainer.innerHTML = payloadCards.map(createCardMarkup).join('')
  if (updatedEl) {
    updatedEl.textContent = formatUpdated(payload?.updated_at)
  }
  errorEl?.classList.add('hidden')
}

const renderActivity = (payload, chartEl, updatedEl, loaderEl, emptyEl, errorEl, chartState) => {
  if (!chartEl) {
    loaderEl?.classList.add('hidden')
    return
  }

  const days = mergeActivityDays(payload?.days)
  const totalActivity = days.some((day) => day.loans > 0 || day.reservations > 0)
  emptyEl?.classList.toggle('hidden', totalActivity)
  errorEl?.classList.add('hidden')

  const colors = getThemeColors()
  const isDark = document.documentElement.classList.contains('dark')
  const options = {
    chart: {
      type: 'bar',
      height: '100%',
      toolbar: { show: false },
      animations: { easing: 'easeinout', speed: 400 },
    },
    theme: { mode: isDark ? 'dark' : 'light' },
    colors: [colors.loans, colors.reservations],
    plotOptions: {
      bar: {
        columnWidth: '48%',
        borderRadius: 8,
        endingShape: 'rounded',
      },
    },
    dataLabels: { enabled: false },
    legend: {
      show: true,
      position: 'top',
      labels: { colors: 'var(--text)' },
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent'],
    },
    tooltip: {
      theme: isDark ? 'dark' : 'light',
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
    series: [
      { name: 'Préstamos', data: days.map((day) => day.loans) },
      { name: 'Reservas', data: days.map((day) => day.reservations) },
    ],
  }

  const renderChart = () => {
    if (!window.ApexCharts) {
      errorEl?.classList.remove('hidden')
      errorEl.textContent = 'ApexCharts no se pudo cargar.'
      loaderEl?.classList.add('hidden')
      return
    }

    if (!chartState.instance) {
      chartState.instance = new window.ApexCharts(chartEl, options)
      chartState.instance.render().finally(() => loaderEl?.classList.add('hidden'))
    } else {
      chartState.instance.updateOptions(options).finally(() => loaderEl?.classList.add('hidden'))
    }
  }

  renderChart()

  if (updatedEl) {
    updatedEl.textContent = formatUpdated(payload?.updated_at)
  }
}

const inventoryRenderers = {
  'low-stock': (item) => {
    const li = document.createElement('li')
    li.className = 'flex items-start justify-between gap-3'

    const info = document.createElement('div')
    const name = document.createElement('p')
    name.className = 'text-sm font-medium text-[var(--text)]'
    name.textContent = item.name ?? '—'

    const meta = document.createElement('p')
    meta.className = 'text-xs text-[var(--text-muted)]'
    meta.textContent = `Stock ${item.stock ?? 0} · Mín ${item.min ?? 0}`

    info.append(name, meta)

    const badge = document.createElement('span')
    badge.className = 'text-xs font-semibold text-[var(--text-muted)]'
    badge.textContent = `#${item.id ?? '—'}`

    li.append(info, badge)
    return li
  },
  overdue: (item) => {
    const li = document.createElement('li')
    li.className = 'flex items-start justify-between gap-3'

    const info = document.createElement('div')
    const title = document.createElement('p')
    title.className = 'text-sm font-medium text-[var(--text)]'
    title.textContent = item.code ? `Préstamo ${item.code}` : 'Préstamo pendiente'

    const meta = document.createElement('p')
    meta.className = 'text-xs text-[var(--text-muted)]'
    meta.textContent = `${item.who ?? 'Sin usuario'} · vence ${item.due ?? '—'}`

    info.append(title, meta)

    const badge = document.createElement('span')
    badge.className = 'text-xs font-semibold text-amber-600'
    badge.textContent = item.due ?? 'Sin fecha'

    li.append(info, badge)
    return li
  },
  'top-materials': (item) => {
    const li = document.createElement('li')
    li.className = 'flex items-start justify-between gap-3'

    const info = document.createElement('div')
    const name = document.createElement('p')
    name.className = 'text-sm font-medium text-[var(--text)]'
    name.textContent = item.name ?? '—'

    const meta = document.createElement('p')
    meta.className = 'text-xs text-[var(--text-muted)]'
    meta.textContent = `Solicitudes ${item.qty ?? 0}`

    info.append(name, meta)

    const badge = document.createElement('span')
    badge.className = 'text-xs font-semibold text-[var(--text-muted)]'
    badge.textContent = `#${item.id ?? '—'}`

    li.append(info, badge)
    return li
  },
}

const renderInventory = (payload, listMap, countMap, emptyMap, updatedEl, errorEl) => {
  const data = payload ?? { low_stock: [], overdue: [], top_materials: [], updated_at: null }
  errorEl?.classList.add('hidden')

  INVENTORY_KEYS.forEach((key) => {
    const listEl = listMap[key]
    const countEl = countMap[key]
    const emptyEl = emptyMap[key]
    const items = (() => {
      if (key === 'low-stock') {
        return Array.isArray(data.low_stock) ? data.low_stock : []
      }
      if (key === 'overdue') {
        return Array.isArray(data.overdue) ? data.overdue : []
      }
      return Array.isArray(data.top_materials) ? data.top_materials : []
    })()

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

  if (updatedEl) {
    updatedEl.textContent = formatUpdated(data.updated_at)
  }
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
  const activityUpdated = root.querySelector('[data-activity-updated]')
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
      renderSummary(null, summaryContainer, summaryUpdated, summaryError)
    }

    if (activityResult.status === 'fulfilled') {
      renderActivity(
        activityResult.value,
        activityChartBar,
        activityUpdated,
        activityLoader,
        activityEmpty,
        activityError,
        chartState,
      )
    } else {
      showError(activityError, 'No se pudo cargar la gráfica de actividad.')
      activityLoader?.classList.add('hidden')
      renderActivity(null, activityChartBar, activityUpdated, activityLoader, activityEmpty, activityError, chartState)
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
      renderInventory(null, inventoryLists, inventoryCounts, inventoryEmptyNodes, inventoryUpdated, inventoryError)
    }
  }

  loadReports()

  window.addEventListener('theme:changed', () => {
    if (!activityChartBar || !chartState.instance) {
      return
    }

    const colors = getThemeColors()
    const isDark = document.documentElement.classList.contains('dark')

    chartState.instance.updateOptions({
      theme: { mode: isDark ? 'dark' : 'light' },
      colors: [colors.loans, colors.reservations],
      tooltip: { theme: isDark ? 'dark' : 'light' },
    })
  })
})
