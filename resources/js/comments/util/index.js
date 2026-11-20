import Prism from 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'
import 'prismjs/components/prism-bash'
import 'prismjs/components/prism-c'
import 'prismjs/components/prism-cpp'
import 'prismjs/components/prism-csharp'
import 'prismjs/components/prism-java'
import 'prismjs/components/prism-less'
import 'prismjs/components/prism-markup-templating'
import 'prismjs/components/prism-php'
import 'prismjs/components/prism-php-extras'
import 'prismjs/components/prism-sql'
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
 * Set the location hash without adding a history entry.
 *
 * @param {String} hash
 */
export function setHash (hash) {
  history.replaceState(null, '', `#!${hash}`)
}

/**
 * @param  {Array} comments
 * @param  {Number} id
 * @return {Object|undefined}
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
 * Nest a thread's flat reply list under each reply's parent.
 *
 * @param  {Array} comments
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
