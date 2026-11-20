const encode = str => encodeURIComponent(str).replace(/[!'()*]/g, x => `%${x.charCodeAt(0).toString(16).toUpperCase()}`)

/**
 * Convert an object to a query string.
 *
 * @param  {Object} obj
 * @return {String}
 */
export default function queryString (obj) {
  return Object.keys(obj).map(key => {
    const value = obj[key]

    if (value === undefined) {
      return ''
    }

    if (value === null) {
      return encode(key)
    }

    return encode(key) + '=' + encode(value)
  }).filter(x => x.length > 0).join('&')
}
