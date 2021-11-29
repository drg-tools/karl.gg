import scrollTo from './util/scrollTo'
import hashParam from './util/hashParam'
import queryString from './util/queryString'
import iFrameResize from 'iframe-resizer/js/iframeResizer'

class Comments {
  /**
   * @param {Object} options
   */
  constructor ({ el, ...options }) {
    this.el = el

    const defaults = {
      url: '/comments'
    }

    this.options = { ...defaults, ...options }

    if (typeof this.el === 'string') {
      this.el = document.querySelector(this.el)
    }

    if (!this.el) {
      throw new Error('[comments] Element not found.')
    }

    this.el.innerHTML = ''

    this.initSpinner()
    this.initIframe()
  }

  /**
   * Initialize the spinner.
   */
  initSpinner () {
    this.spinner = document.createElement('div')
    this.spinner.classList.add('comments__spinner')
    this.el.appendChild(this.spinner)
  }

  /**
   * Initialize the iframe.
   */
  initIframe () {
    this.iframe = document.createElement('iframe')
    this.iframe.scrolling = 'no'
    this.iframe.src = this.buildUrl()
    this.iframe.classList.add('comments__iframe')

    this.iframe.onload = () => {
      this.spinner.style.display = 'none'
      this.iframe.style.opacity = 1
    }

    this.el.appendChild(this.iframe)

    iFrameResize({
      onMessage: ({ iframe, message: { type, position }}) => {
        if (type === 'scroll') {
          const rect = iframe.getBoundingClientRect()
          const scrollTop = window.pageYOffset || document.documentElement.scrollTop

          setTimeout(() => {
            scrollTo(position + rect.top + scrollTop)
          }, 50)
        }
      }
    }, this.iframe)
  }

  /**
   * Build the url.
   *
   * @return {String}
   */
  buildUrl () {
    const o = this.options
    const data = o.data ? (typeof o.data === 'function' ? o.data() : o.data) : {}
    const origin = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '')
    const permalink = o.permalink || ((location.origin || origin) + location.pathname + location.search)

    const params = {
      ...data,
      permalink,
      order: o.order,
      page_id: o.pageId,
      per_page: o.perPage,
      page: hashParam('page'),
      target: hashParam('comment'),
      commentable_id: o.commentableId,
      commentable_type: o.commentableType
    }

    return o.url + (o.url.indexOf('?') > -1 ? '&' : '?') + queryString(params)
  }
}

export default Comments
