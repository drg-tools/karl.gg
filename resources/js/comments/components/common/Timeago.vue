<template>
  <time :datetime="since">{{ relativeTime }}</time>
</template>

<script>
import config from '@/config'

const formatter = new Intl.RelativeTimeFormat(config.locale.replace('_', '-'), { numeric: 'auto' })
const units = [
  ['year', 31536000], ['month', 2592000], ['week', 604800],
  ['day', 86400], ['hour', 3600], ['minute', 60], ['second', 1]
]

export default {
  props: {
    since: { type: String, required: true }
  },

  computed: {
    relativeTime () {
      const seconds = (Date.parse(this.since) - Date.now()) / 1000
      if (!Number.isFinite(seconds)) {
        return this.since
      }

      const [unit, size] = units.find(([, size]) => Math.abs(seconds) >= size) ?? units.at(-1)
      return formatter.format(Math.trunc(seconds / size), unit)
    }
  }
}
</script>
