export default {
  prefix: 'comment_author_',

  /**
   * Save the author attributes into local storage.
   *
   * @param {Object]} attributes
   */
  put (attributes) {
    Object.keys(attributes).forEach(key => {
      localStorage.setItem(this.prefix + key, attributes[key])
    })
  },

  /**
   * Get author attribute from local storage.
   *
   * @param  {String} key
   * @return {String}
   */
  get (key) {
    return localStorage.getItem(this.prefix + key) || ''
  }
}
