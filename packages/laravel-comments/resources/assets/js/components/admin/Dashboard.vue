<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4 pb-2">
        {{ $t('dashboard_title') }}

        <a v-if="args.page_id || (args.commentable_id && args.commentable_type)"
          :href="`?status=${args.status}`" class="float-end">
          <small>{{ $t('clear_page_filter') }}</small>
        </a>
      </h4>

      <!-- Status Filter -->
      <ul class="status__filter">
        <li v-for="(status, index) in statuses" :class="{ active: status === args.status }" :key="index">
          <a :href="statusPage(status)">
            {{ $t(`status_${status}`) }}
            <span v-if="status !== 'all'" class="status__count">
              ({{ statusCount[status] || 0 }})
            </span>
          </a>
          <span v-if="index < 4" class="status__filter-sep">|</span>
        </li>
      </ul>

      <div class="row my-2">
        <!-- Bulk Actions -->
        <div class="col-12 col-sm-8 col-lg-10">
          <div class="bulk__actions">
            <select v-model="bulkAction" class="form-select form-select-sm">
              <option value="">{{ $t('select_bulk_action') }}</option>
              <option v-if="args.status !== 'approved'" value="approved">{{ $t('bulk_action_approve') }}</option>
              <option v-if="args.status === 'all' || args.status === 'approved'" value="pending">{{ $t('bulk_action_unapprove') }}</option>
              <option v-if="args.status !== 'spam' && args.status !== 'trash'" value="spam">{{ $t('bulk_action_spam') }}</option>
              <option v-if="args.status === 'spam' || args.status === 'trash'" value="delete">{{ $t('bulk_action_delete') }}</option>
              <option v-if="args.status !== 'spam' && args.status !== 'trash'" value="trash">{{ $t('bulk_action_trash') }}</option>
            </select>
            <button @click="applyBulkAction" type="button" class="btn btn-primary btn-sm">
              {{ $t('apply_bulk_action') }}
            </button>
          </div>
        </div>

        <!-- Order Dropdown -->
        <div class="col-12 col-sm-4 col-lg-2">
          <select v-model="order" class="form-select form-select-sm">
            <option value="latest">{{ $t('order_latest') }}</option>
            <option value="oldest">{{ $t('order_oldest') }}</option>
          </select>
        </div>
      </div>

      <!-- Comments -->
      <div class="table-responsive">
        <table class="table table-striped comments__table">
          <thead>
            <tr>
              <th class="column__checkbox">
                <input type="checkbox" @click="toggleCheckbox" ref="toggleall" class="form-check-input">
              </th>
              <th class="column__author">{{ $t('column_author') }}</th>
              <th class="column__comment">{{ $t('column_comment') }}</th>
              <th class="column__in-response">{{ $t('column_in_response') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="comment in comments" :key="comment.id" class="comment__item"
              :class="{ 'table-warning': comment.status === 'pending' && args.status !== 'pending' }">
              <td class="column__checkbox">
                <input type="checkbox" @click="toggleCheckbox($event, comment)"
                  :checked="selected.includes(comment.id)" class="form-check-input">
              </td>
              <td class="column__author">
                <div>
                  <img :src="comment.author_avatar">
                  <b>{{ comment.author_name }}</b>
                  <small>{{ comment.user_id ? '(user)' : '' }}</small>
                </div>
                <div>
                  <a :href="`mailto:${comment.author_email}`">
                    {{ comment.author_email }}
                  </a>
                </div>
                <div v-if="comment.author_url">
                  <a :href="comment.author_url" target="_blank">
                    {{ comment.author_url }}
                  </a>
                </div>
                <a :href="`http://whatismyipaddress.com/ip/${comment.author_ip}`"
                  target="_blank" :title="comment.user_agent">
                  {{ comment.author_ip }}
                </a>
              </td>
              <td class="column__comment">
                <div class="comment__submitted-on mb-1">
                  {{ $t('submitted_on') }}
                  <a :href="comment.url" :title="comment.created_at.timestamp" target="_blank">
                    <timeago :since="comment.created_at" :title="comment.created_at"></timeago>
                  </a>
                  <span v-if="comment.parent">
                    {{ $t('in_reply_to') }}
                    <a :href="comment.parent.url" target="_blank">
                      {{ comment.parent.author_name }}
                    </a>
                  </span>
                </div>

                <div class="comment__content">
                  {{ comment.content }}
                </div>

                <div class="comment__actions mt-2">
                  <span v-if="comment.status !== 'trash' && comment.status !== 'spam'">
                    <span v-if="comment.status !== 'approved'">
                      <a href="#" class="comment__action-approve" @click.prevent="updateStatus($event, comment, 'approved')">
                        {{ $t('action_approve') }}
                      </a> |
                    </span>
                    <span v-if="comment.status !== 'pending'">
                      <a href="#" class="comment__action-unapprove" @click.prevent="updateStatus($event, comment, 'pending')">
                        {{ $t('action_unapprove') }}
                      </a> |
                    </span>

                    <a :href="`#!comment=${comment.id}`" @click="edit(comment)">
                      {{ $t('action_edit') }}
                    </a> |

                    <a href="#" class="comment__action-spam" @click.prevent="updateStatus($event, comment, 'spam')">
                      {{ $t('action_spam') }}
                    </a>
                  </span>

                  <a v-if="comment.status === 'trash' || comment.status === 'spam'" href="#"
                    class="comment__action-approve" @click.prevent="updateStatus($event, comment, 'approved')">
                    {{ $t('action_approve') }}
                  </a>

                  <span v-if="comment.status === 'trash' || comment.status === 'spam'">
                    | <a href="#" class="comment__action-delete" @click.prevent="destroy($event, comment)">
                      {{ $t('action_delete') }}
                    </a>
                  </span>

                  <span v-if="comment.status !== 'trash' && comment.status !== 'spam'">
                    | <a href="#" class="comment__action-trash" @click.prevent="updateStatus($event, comment, 'trash')">
                      {{ $t('action_trash') }}
                    </a>
                  </span>
                </div>
              </td>
              <td class="column__in-response">
                <a :href="commentPage(comment)" class="page-comment-count" :title="$t('filter_by_page')">
                  <span class="badge bg-primary me-2">
                    {{ commentCount(comment) }}
                  </span>
                </a>
                <a :href="comment.permalink" target="_blank">
                  {{ $t('view_page') }}
                </a>
              </td>
            </tr>

            <tr v-if="comments.length === 0">
              <td colspan="4" class="text-center">
                {{ $t('no_comments') }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Count & Per Page -->
      <div class="row">
        <div class="col-6">
          <span v-if="pagination.total > 0">
            {{ $tc('comments_count', pagination.total === 1 ? 1 : 2, { count: pagination.total }) }}
          </span>
        </div>
        <div class="col-6">
          <div class="float-end">
            <select v-model="perPage" class="form-control form-control-sm custom-select" style="max-width: 70px;">
              <option v-for="value in [15, 25, 50, 100]" :value="value">{{ value }}</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <pagination
        :pagination="pagination"
        :page-id="args.page_id"
        :commentable-id="args.commentable_id"
        :commentable-type="args.commentable_type"
      ></pagination>
    </div>

    <!-- Edit Modal -->
    <edit-modal ref="editmodal"></edit-modal>
  </div>
</template>

<script>
import api from '@/api'
import axios from 'axios'
import swal from 'sweetalert2'
import config from '@/config'
import { queryString, hashParam } from '@/util'
import Pagination from './Pagination.vue'
import EditModal from './EditModal.vue'

export default {
  components: {
    Pagination,
    EditModal
  },

  data: () => ({
    args: config.data.args,
    comments: [],
    pagination: {},
    statusCount: {},
    pageCount: {},
    commentableCount: {},

    page: 1,
    loading: true,
    redirected: false,
    perPage: 15,
    selected: [],
    bulkAction: '',
    order: 'latest',
    statuses: ['all', 'pending', 'approved', 'spam', 'trash']
  }),

  watch: {
    order () { this.fetch() },
    perPage () { this.fetch() }
  },

  created () {
    this.setComments(config.data.comments)
  },

  mounted () {
    this.$refs.editmodal.$on('updated', () => this.fetch())

    const id = hashParam('comment')
    if (id) {
      api.fetch({ id })
        .then(({ data }) => this.edit(data))
        .catch(() => { window.location.hash = '' })
    }
  },

  methods: {
    /**
     * Set the comments data.
     *
     * @param {Object} data
     */
    setComments (data) {
      if (data.comments.length === 0 && this.page > 1) {
        this.page = this.redirected ? 1 : this.page - 1
        this.redirected = true
        return this.fetch()
      }

      this.comments = data.comments
      this.pageCount = data.page_count
      this.commentableCount = data.commentable_count
      this.pagination = data.pagination
      this.statusCount = data.status_count
    },

    /**
     * Fetch comments from the server.
     */
    fetch () {
      this.loading = true

      axios.get('/comments/admin?' + queryString({
        page: this.page,
        order: this.order,
        per_page: this.perPage,
        status: this.args.status,
        page_id: this.args.page_id || undefined,
        commentable_id: this.args.commentable_id,
        commentable_type: this.args.commentable_type
      }))
        .then(({ data }) => {
          this.loading = false
          this.setComments(data)
        })
    },

    /**
     * Toggle checkbox.
     *
     * @param {Event} e
     * @param {Object|null} comment
     */
    toggleCheckbox (e, comment = null) {
      e.stopPropagation()

      // Toggle all checkboxes.
      if (!comment) {
        this.selected = e.target.checked ? this.comments.map(c => c.id) : []
        e.target.checked = (this.selected.length === this.comments.length)
        return
      }

      if (e.target.checked) {
        if (!this.selected.includes(comment.id)) {
          this.selected.push(comment.id)
        }
      } else {
        this.selected.splice(this.selected.indexOf(comment.id), 1)
      }

      const [total, selected] = [this.comments.length, this.selected.length]

      this.$refs.toggleall.checked = (selected === total)
      this.$refs.toggleall.indeterminate = (selected > 0 && selected !== total)
    },

    /**
     * Apply a bulk action on the selected comments.
     */
    applyBulkAction () {
      if (!this.bulkAction || this.selected.length === 0) {
        return
      }

      const action = () => axios.post('/comments/bulk-update', {
        ids: this.selected,
        status: this.bulkAction
      }).then(() => {
        this.selected = []
        this.bulkAction = ''
        this.$refs.toggleall.checked = false
        this.$refs.toggleall.indeterminate = false

        this.fetch()
      })

      if (this.bulkAction === 'delete') {
        swal({
          title: this.$t('delete_modal_title'),
          html: this.$t('delete_modal_text', { count: this.selected.length }),
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: this.$t('delete_modal_confirm'),
          cancelButtonText: this.$t('delete_modal_cancel')
        })
          .then(action)
          .catch(() => { this.bulkAction = '' })
      } else {
        action()
      }
    },

    /**
     * Open edit modal.
     *
     * @param {Object} comment
     */
    edit (comment) {
      this.$refs.editmodal.show(comment)
    },

    /**
     * Update the comment status.
     *
     * @param {Event} e
     * @param {Object} comment
     * @param {String} status
     */
    updateStatus (e, { id }, status) {
      this.highlight(e, status)

      api.update(id, { status })
        .then(() => this.fetch())
    },

    /**
     * Delete the comment.
     *
     * @param {Event} e
     * @param {Object} comment
     */
    destroy (e, comment) {
      this.highlight(e, 'trash')

      api.destroy(comment.id)
        .then(() => this.fetch())
    },

    /**
     * Highlight the comment item table row.
     *
     * @param {Event} e
     * @param {String} status
     */
    highlight (e, status) {
      const row = e.target.closest('.comment__item')

      if (status === 'spam' || status === 'trash') {
        row.classList.add('table-danger')
      } else if (status === 'pending') {
        row.classList.add('table-warning')
      } else if (status === 'approved') {
        row.classList.remove('table-warning')
        row.classList.add('table-success')
      }
    },

    /**
     * @param  {Object} comment
     * @return {Number}
     */
    commentCount (comment) {
      if (comment.page_id) {
        return this.pageCount[comment.page_id] || 0
      }

      return this.commentableCount[`${comment.commentable_id}|${comment.commentable_type}`] || 0
    },

    /**
     * @param  {Object} comment
     * @return {Number}
     */
    commentPage (comment) {
      if (comment.page_id) {
        return `?page_id=${comment.page_id}`
      }

      return `?commentable_id=${comment.commentable_id}&commentable_type=${comment.commentable_type}`
    },

    /**
     * @param  {Object} comment
     * @return {Number}
     */
    statusPage (status) {
      let url = `?status=${status}`

      if (this.args.page_id) {
        url += `&page_id=${this.args.page_id}`
      } else if (this.args.commentable_id && this.args.commentable_type) {
        url += `&commentable_id=${this.args.commentable_id}&commentable_type=${this.args.commentable_type}`
      }

      return url
    }
  }
}
</script>
