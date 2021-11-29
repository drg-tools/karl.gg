import Vue from 'vue'
import Vuex from 'vuex'
import api from './api'
import config from './config'
import { hashParam, setHash, hierarchical, findComment } from './util'

const [UPVOTE, DOWNVOTE] = ['up', 'down']

Vue.use(Vuex)

// State
const state = {
  order: config.defaultOrder,
  page: config.page || hashParam('page', 1),
  target: config.target || hashParam('comment'),

  total: 0,
  comments: [],
  loading: false,
  pagination: null
}

// Getters
const getters = {
  page: state => state.page,
  total: state => state.total,
  order: state => state.order,
  target: state => state.target,
  loading: state => state.loading,
  comments: state => state.comments,
  pagination: state => state.pagination
}

// Mutations
const mutations = {
  SET_TARGET (state, target) {
    state.target = target
    setHash(`comment=${target}`)
  },

  SET_COMMENTS (state, { comments, total, pagination }) {
    comments.forEach(comment => {
      comment.replies = hierarchical(comment.replies, comment.id)
    })

    state.total = total
    state.comments = comments
    state.pagination = pagination
    state.page = pagination.current_page
  },

  ADD_COMMENT (state, comment) {
    state.total += 1

    if (comment.parent_id) {
      const parent = findComment(state.comments, comment.parent_id)
      if (parent) {
        parent.replies.unshift(comment)
      }
    } else {
      state.comments.unshift(comment)
    }
  },

  UPDATE_COMMENT (state, { comment, data }) {
    const props = [
      'content', 'content_html', 'status',
      'updated_at', 'can_update', 'can_delete'
    ]

    props.forEach(prop => {
      if (data[prop] !== undefined) {
        comment[prop] = data[prop]
      }
    })
  },

  DELETE_COMMENT (state, comment) {
    let comments = state.comments

    if (comment.parent_id) {
      const parent = findComment(state.comments, comment.parent_id)

      if (parent) {
        comments = parent.replies
      }
    }

    const index = comments.findIndex(({ id }) => comment.id === id)

    if (index > -1) {
      comments.splice(index, 1)
    }
  },

  REPORT_COMMENT (state, comment) {
    Vue.set(comment, 'reported', true)
  },

  UPVOTE (state, comment) {
    if (comment.voted === UPVOTE) {
      comment.voted = null
      comment.upvotes -= 1
    } else {
      if (comment.voted === DOWNVOTE) {
        comment.downvotes -= 1
      }

      comment.voted = UPVOTE
      comment.upvotes += 1
    }
  },

  DOWNVOTE (state, comment) {
    if (comment.voted === DOWNVOTE) {
      comment.voted = null
      comment.downvotes -= 1
    } else {
      if (comment.voted === UPVOTE) {
        comment.upvotes -= 1
      }

      comment.voted = DOWNVOTE
      comment.downvotes += 1
    }
  }
}

// Actions
const actions = {
  fetch ({ state, commit, dispatch }, payload = {}) {
    state.target = null
    state.loading = true

    if (payload.page) {
      state.page = payload.page
      setHash(`page=${state.page}`)
    }

    if (payload.order) {
      state.order = payload.order
    }

    const params = {
      page: state.page,
      order: state.order,
      target: state.target,
      page_id: config.pageId,
      commentable_id: config.commentableId,
      commentable_type: config.commentableType
    }

    return api.fetch(params)
      .then(({ data }) => {
        state.loading = false

        if (data.comments.length === 0 && data.total > 0) {
          dispatch('fetch', { page: state.page - 1 })
        } else {
          commit('SET_COMMENTS', data)
        }
      })
  },

  upvote ({ commit }, comment) {
    if (comment.voted === UPVOTE) {
      api.removeVote(comment.id)
    } else {
      api.upvote(comment.id)
    }

    commit('UPVOTE', comment)
  },

  downvote ({ commit }, comment) {
    if (comment.voted === DOWNVOTE) {
      api.removeVote(comment.id)
    } else {
      api.downvote(comment.id)
    }

    commit('DOWNVOTE', comment)
  },

  destroy ({ commit, dispatch }, comment) {
    return new Promise((resolve, reject) => {
      api.destroy(comment.id)
        .then(() => {
          commit('DELETE_COMMENT', comment)

          dispatch('fetch')

          resolve()
        })
        .catch(reject)
    })
  },

  report ({ commit }, comment) {
    api.report(comment.id)

    commit('REPORT_COMMENT', comment)
  }
}

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations
})
