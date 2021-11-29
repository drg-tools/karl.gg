import Form from 'vform'
import axios from 'axios'
import swal from 'sweetalert2'

export default {
  data: () => ({
    tab: 'general',
    form: new Form({})
  }),

  created () {
    const hash = window.location.hash

    if (hash.length > 2) {
      this.tab = hash.replace('#', '')
    }
  },

  methods: {
    /**
     * Change the active tab.
     *
     * @param {String} tab
     */
    changeTab (tab) {
      this.tab = tab
      this.form.clear()
    },

    /**
     * Save the settings.
     */
    save () {
      [
        ...this.$el.querySelectorAll('input, textarea, select')
      ].forEach(input => {
        this.form[input.name] = input.value
      })

      this.form.post('/comments/admin/settings')
    },

    /**
     * Reset the settings to their default values.
     */
    reset () {
      swal({
        title: this.$t('reset_settings_modal_title'),
        text: this.$t('reset_settings_modal_text'),
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: this.$t('reset_settings_modal_confirm'),
        cancelButtonText: this.$t('reset_settings_modal_cancel')
      }).then(() => {
        axios.delete('/comments/admin/settings')
          .then(() => window.location.reload())
      })
      .catch(swal.noop)
    }
  }
}
