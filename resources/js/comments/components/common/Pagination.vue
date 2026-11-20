<template>
  <nav v-if="pagination && pagination.total > pagination.per_page">
    <ul class="comments__pagination">
      <li :class="{ disabled: pagination.current_page == 1 }" class="page-item">
        <a :href="getUrl(pagination.prev_page)" @click="onClick($event, pagination.prev_page)"
          class="page-link" :aria-label="$t('previous')">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">{{ $t('previous') }}</span>
        </a>
      </li>

      <li v-if="pagination.first_adjacent_page > 1" class="page-item">
        <a :href="getUrl(1)" @click="onClick($event, 1)" class="page-link">1</a>
      </li>

      <li v-if="pagination.first_adjacent_page > 2" class="page-item disabled">
        <a class="page-link">...</a>
      </li>

      <li v-for="page in adjacentPages" :key="page"
        :class="{ active: pagination.current_page === page }" class="page-item">
        <a :href="getUrl(page)" @click="onClick($event, page)" class="page-link">
          {{ page }}
        </a>
      </li>

      <li v-if="pagination.last_adjacent_page < pagination.last_page - 1" class="page-item disabled">
        <a class="page-link">...</a>
      </li>

      <li v-if="pagination.last_adjacent_page < pagination.last_page" class="page-item">
        <a :href="getUrl(pagination.last_page)" @click="onClick($event, pagination.last_page)" class="page-link">
          {{ pagination.last_page }}
        </a>
      </li>

      <li :class="{ disabled: pagination.current_page === pagination.last_page }" class="page-item" :aria-label="$t('next')">
        <a :href="getUrl(pagination.next_page)" @click="onClick($event, pagination.next_page)" class="page-link">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">{{ $t('next') }}</span>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  props: {
    pagination: Object,
    getUrl: { type: Function, required: true },
    onClick: { type: Function, default: () => {} }
  },

  computed: {
    adjacentPages () {
      const { first_adjacent_page: first, last_adjacent_page: last } = this.pagination
      return Array.from({ length: last - first + 1 }, (_, index) => first + index)
    }
  }
}
</script>
