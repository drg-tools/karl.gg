import axios from 'axios'
import { queryString } from './util'

export default {
  /**
   * Fetch comments.
   *
   * @param  {Object} args
   * @return {Promise}
   */
  fetch: args => axios.get('/comments?' + queryString(args)),

  /**
   * Create a new comment.
   *
   * @param  {Object} data
   * @return {Promise}
   */
  store: data => axios.post(`/comments`, data),

  /**
   * Update the given comment.
   *
   * @param  {Number} cid
   * @param  {Object} data
   * @return {Promise}
   */
  update: (cid, data) => axios.patch(`/comments/${cid}`, data),

  /**
   * Delete the given comment.
   *
   * @param  {Number} cid
   * @return {Promise}
   */
  destroy: cid => axios.delete(`/comments/${cid}`),

  /**
   * Upvote the given comment.
   *
   * @param  {Number} cid
   * @return {Promise}
   */
  upvote: cid => axios.post(`/comments/${cid}/upvote`),

  /**
   * Downvote the given comment.
   *
   * @param  {Number} cid
   * @return {Promise}
   */
  downvote: cid => axios.post(`/comments/${cid}/downvote`),

  /**
   * Remove the vote for the given comment.
   *
   * @param  {Number} cid
   * @return {Promise}
   */
  removeVote: cid => axios.delete(`/comments/${cid}/remove-vote`),

  /**
   * Report the given comment.
   *
   * @param  {Number} cid
   * @return {Promise}
   */
  report: cid => axios.put(`/comments/${cid}/report`),

  /**
   * Render the comment as HTML.
   *
   * @param  {String} content
   * @return {Promise}
   */
  preview: content => axios.post('/comments/preview', { content })
}
