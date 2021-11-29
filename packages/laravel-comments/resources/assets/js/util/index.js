import Prism from '@/vendor/prism'
export { default as scrollTo } from './scrollTo'
export { default as hashParam } from './hashParam'
export { default as queryString } from './queryString'
export { default as authorRecaller } from './authorRecaller'

/**
 * Highlight code blocks.
 *
 * @param {Element} el
 */
export function highlight (el) {
  [...el.querySelectorAll('pre code')].forEach(block => {
    Prism.highlightElement(block)
  })
}

/**
 * Replace emoji with actual images.
 *
 * @param  {String} text
 * @return {String}
 */
export function emojify (text) {
  return window.twemoji ? window.twemoji.parse(text) : text
}

/**
 * Set location hash.
 *
 * @param {String} hash
 */
export function setHash (hash) {
  (window.parent || window)['location'].hash = `!${hash}`
}

/**
 * Find comment by id.
 *
 * @param  {Array} comments
 * @param  {Number} id
 * @return {Object}
 */
export function findComment (comments, id) {
  for (let i = 0; i < comments.length; i++) {
    if (comments[i].id === id) {
      return comments[i]
    }

    const comment = findComment(comments[i].replies, id)

    if (comment) {
      return comment
    }
  }
}

/**
 * @param  {Array}  comments
 * @param  {Number} parentId
 * @return {Array}
 */
export function hierarchical (comments, parentId) {
  const result = []

  comments.forEach(comment => {
    if (comment.parent_id === parentId) {
      comment.replies = hierarchical(comments, comment.id)
      result.push(comment)
    }
  })

  return result
}
