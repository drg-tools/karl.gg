const files = require.context('.', false, /\.vue$/)
const modules = {}
files.keys().forEach((key) => {
     if (key === './index.js') return
     modules[key.replace(/(\.\/|\.vue)/g, '')] = files(key)
})
export default modules