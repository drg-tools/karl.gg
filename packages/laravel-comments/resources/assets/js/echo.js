import axios from 'axios'
import config from './config'
import Echo from 'laravel-echo'

let echo = null

if (config.broadcast) {
  const { broadcasting: { driver, pusher }} = config

  if (driver !== 'socket.io' && driver !== 'pusher') {
    throw new Error('[comments] The broadcasting driver must be "redis" or "pusher".')
  }

  try {
    echo = new Echo({
      broadcaster: driver,
      host: location.hostname + ':6001',
      namespace: 'Hazzard.Comments.Events',
      ...pusher
    })
  } catch (e) {
    console.warn('[comments] Could not connect to the socket server.', e)
  }

  axios.interceptors.request.use(config => {
    if (echo) {
      config.headers['X-Socket-Id'] = echo.socketId()
    }

    return config
  })
}

export default echo
