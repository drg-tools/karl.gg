<template>
  <div class="recaptcha"></div>
</template>

<script>
import config from '@/config'
import recaptcha from '@/util/recaptcha'

export default {
  data: () => ({
    widgetId: null
  }),

  created () {
    if (!config.recaptchaKey) {
      throw new Error('[comments] You must set the "recaptcha" keys in config/services.php.')
    }

    recaptcha.key = config.recaptchaKey
    recaptcha.checkLoad()
  },

  mounted () {
    recaptcha.render(this.$el)
      .then(id => { this.widgetId = id })
  },

  methods: {
    getResponse () {
      return recaptcha.getResponse(this.widgetId)
    },

    reset () {
      recaptcha.reset(this.widgetId)
    }
  }
}
</script>
