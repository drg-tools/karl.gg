<template>
  <svg class="svg-icon align-middle" width="0.750em" height="0.750em">
    <use :xlink:href="`#${type}`"/>
  </svg>
</template>

<script>
const files = require.context('!svg-sprite-loader!./../../../icons', false, /.*\.svg$/)

files.keys().forEach(files)

export default {
  name: 'icon',

  props: {
    type: {
      type: String,
      required: true
    }
  }
}
</script>
