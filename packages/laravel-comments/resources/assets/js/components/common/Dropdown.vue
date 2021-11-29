<template>
  <div class="dropdown">
    <button class="dropdown-toggle btn btn-link p-0" data-bs-toggle="dropdown">
      <slot name="toggle"></slot>
    </button>

    <div class="dropdown-menu py-1">
      <slot name="items"></slot>
    </div>
  </div>
</template>

<script>
import Dropdown from 'bootstrap/js/dist/dropdown'

export default {
  name: 'dropdown',

  mounted () {
    new Dropdown(this.$el)
  }
}
</script>
