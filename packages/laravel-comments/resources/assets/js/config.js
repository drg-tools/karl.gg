const config = {}

if (!window.config) {
  throw new Error('[comments] The config variable is not defined.')
}

Object.assign(config, window.config)

export default config
