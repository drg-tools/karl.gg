import zenscroll from '@/vendor/zenscroll'

/**
 * Scroll to the target element or Y position.
 *
 * @param {HTMLElement|Number} target
 * @param {Number} duration
 */
export default function scrollTo (target, duration = 200) {
  zenscroll[isNaN(target) ? 'to' : 'toY'](target, duration)
}
