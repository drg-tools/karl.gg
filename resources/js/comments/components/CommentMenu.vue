<template>
  <div class="comment__menu">
    <button @click="collapse" type="button" class="comment__collapse"
      :aria-label="comment.collapsed ? 'Expand' : 'Collapse'" :aria-expanded="!comment.collapsed">
      <icon :type="comment.collapsed ? 'plus' : 'minus'"></icon>
    </button>

    <dropdown v-if="loggedIn && (comment.can_update || comment.can_delete || canReport)" label="Comment actions">
      <template #toggle>
        <icon type="menu"></icon>
      </template>

      <template #items>
        <button v-if="comment.can_update" @click="edit" class="comments-dropdown__item" role="menuitem" type="button">
          {{ $t('edit_button') }}
        </button>
        <button v-if="comment.can_delete" @click="destroy" class="comments-dropdown__item" role="menuitem" type="button">
          {{ $t('delete_button') }}
        </button>
        <button v-if="canReport" @click="report" class="comments-dropdown__item" role="menuitem" type="button">
          {{ $t('report_button') }}
        </button>
      </template>
    </dropdown>
  </div>
</template>

<script>
import config from '@/config'

export default {
  props: ['comment'],

  data: () => ({
    loggedIn: config.loggedIn
  }),

  computed: {
    canReport () {
      return this.comment.status === 'approved' && !this.comment.reported
    }
  },

  methods: {
    collapse () {
      this.comment.collapsed = !this.comment.collapsed
    },

    edit () {
      this.$parent.editComment = this.comment
    },

    destroy () {
      setTimeout(() => {
        if (confirm(this.$t('confirm_delete'))) {
          this.$store.dispatch('destroy', this.comment)
            .catch(error => {
              if (error.response.status === 403) {
                this.$store.commit('UPDATE_COMMENT', {
                  comment: this.comment,
                  data: { can_delete: false }
                })

                alert(this.$t('error_delete'))
              } else {
                throw error
              }
            })
        }
      }, 10)
    },

    report () {
      setTimeout(() => {
        if (confirm(this.$t('confirm_report'))) {
          this.$store.dispatch('report', this.comment)
            .catch(error => alert(this.$t(error.response?.status === 403 ? 'error_403' : 'error_500')))
        }
      }, 10)
    }
  }
}
</script>
