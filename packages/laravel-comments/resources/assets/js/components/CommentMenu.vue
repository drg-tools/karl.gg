<template>
  <div class="comment__menu float-end">
    <button @click="collapse" type="button" class="comment__collapse btn btn-link">
      <icon :type="comment.collapsed ? 'plus' : 'minus'"></icon>
    </button>

    <dropdown v-if="loggedIn && (comment.can_update || comment.can_delete || !comment.reported)" class="ms-1 d-inline">
      <template slot="toggle">
        <icon type="menu"></icon>
      </template>

      <template slot="items">
        <button v-if="comment.can_update" @click="edit" class="dropdown-item" type="button">
          {{ $t('edit_button') }}
        </button>
        <button v-if="comment.can_delete" @click="destroy" class="dropdown-item" type="button">
          {{ $t('delete_button') }}
        </button>
        <button v-if="!comment.reported" @click="report" class="dropdown-item" type="button">
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

  methods: {
    collapse () {
      this.$set(this.comment, 'collapsed', !this.comment.collapsed)
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
        }
      }, 10)
    }
  }
}
</script>
