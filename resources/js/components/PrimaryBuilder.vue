<template>
  <div class="flex flex-row justify-between my-5">

    <h2>Primary Builder: -selected weapon name- / select weapon</h2>
    Weapon selector has to be here
    then mod matrix
    then cost metrics
      
  </div>



</template>

<script>
export default {
  name: "PrimaryBuilder",
  
  methods: {
    goBack() {
      window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/')
    }
  },
};
</script>