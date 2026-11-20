<template>
  <p v-if="form.errors.has(field)" class="comments-error" role="alert">
    {{ form.errors.get(field) }}
  </p>
</template>

<script>
export default {
  name: 'field-error',

  props: {
    form: { type: Object, required: true },
    field: { type: String, required: true }
  }
}
</script>
