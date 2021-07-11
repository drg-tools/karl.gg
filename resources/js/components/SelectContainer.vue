<template>
  <div class="flex flex-row justify-between my-5">
  <!-- TODO: Conditially show/hide these if a primary OR secondary is selected ONLY -->
  <!-- TODO: Conditially show/hide equipment if all equipment is selected ONLY -->
  <div class="flex flex-col">
      <h2>Select a Primary</h2>
      <router-link to="/primary-builder"
      class="
          inline-flex
          items-center
          text-center
          px-4
          py-2
          border border-transparent
          text-sm
          font-medium
          rounded-md
          shadow-sm
          text-white
          bg-orange-500
          hover:bg-orange-700
          focus:outline-none
          focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
          w-full
          md:w-auto
        "
      >
      Select
      </router-link>
    </div>

<div class="flex flex-col">
      <h2>Select a Secondary</h2>
      <a
        href="#"
        class="
          inline-flex
          items-center
          text-center
          px-4
          py-2
          border border-transparent
          text-sm
          font-medium
          rounded-md
          shadow-sm
          text-white
          bg-orange-500
          hover:bg-orange-700
          focus:outline-none
          focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
          w-full
          md:w-auto
        "
      >
      Select
      </a>
    </div>


    <div class="flex flex-col">
      <h2>Select Equipment</h2>
      <a
        href="#"
        class="
          inline-flex
          items-center
          text-center
          px-4
          py-2
          border border-transparent
          text-sm
          font-medium
          rounded-md
          shadow-sm
          text-white
          bg-orange-500
          hover:bg-orange-700
          focus:outline-none
          focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
          w-full
          md:w-auto
        "
      >
      Select
      </a>
    </div>

  </div>

</template>

<script>
export default {
  name: "SelectContainer",
  
  methods: {
    goBack() {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    }
  },
};
</script>