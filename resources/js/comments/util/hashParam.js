/**
 * Get param from the hash string.
 *
 * @param  {String} name
 * @param  {Any} _default
 * @return {Any}
 */
export default function hashParam (name, _default = undefined) {
  const fragment = extract(window.location.search, '_escaped_fragment_')
  const str = fragment ? `#${fragment}` : window.location.hash.replace('#!', '#')

  return +extract(str, name, true) || _default
}

/**
 * @param  {String} str
 * @param  {String} name
 * @param  {Boolean} isHash
 * @return {String|undefined}
 */
function extract (str, name, isHash) {
  name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]')
  const regex = new RegExp((isHash ? '[\\#]' : '[\\?&]') + name + '=([^&#]*)')
  const results = regex.exec(str)

  return results ? decodeURIComponent(results[1].replace(/\+/g, ' ')) : undefined
}
