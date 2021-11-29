const promise = {}
let recaptcha = null

promise.promise = new Promise(resolve => {
  promise.resolve = resolve
})

window.recaptchaApiLoaded = () => {
  recaptcha = window.grecaptcha
  promise.resolve(recaptcha)
}

export default {
  key: '',

  getRecaptcha () {
    if (recaptcha !== null) {
      return Promise.resolve(recaptcha)
    }

    return promise.promise
  },

  getResponse (widgetId) {
    this.assertLoaded()

    if (widgetId === null) {
      return
    }

    return recaptcha.getResponse(widgetId)
  },

  reset (widgetId) {
    this.assertLoaded()

    if (widgetId === null) {
      return
    }

    this.getRecaptcha().then(recaptcha => {
      recaptcha.reset(widgetId)
    })
  },

  checkLoad () {
    if (window.hasOwnProperty('grecaptcha')) {
      window.recaptchaApiLoaded()
    }
  },

  assertLoaded () {
    if (recaptcha === null) {
      throw new Error('ReCAPTCHA has not been loaded')
    }
  },

  render (el) {
    return this.getRecaptcha().then(recaptcha => {
      return recaptcha.render(el, {
        sitekey: this.key
      })
    })
  }
}
