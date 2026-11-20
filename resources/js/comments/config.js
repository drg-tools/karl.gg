/**
 * Settings rendered by resources/views/comments/embed.blade.php onto the mount element.
 */
export const mountElement = document.querySelector('[data-comments]')

const config = {
  ...JSON.parse(mountElement?.dataset.comments ?? '{}'),
  permalink: window.location.origin + window.location.pathname,
  defaultOrder: 'best',
  orderOptions: ['best', 'latest', 'oldest']
}

export default config
