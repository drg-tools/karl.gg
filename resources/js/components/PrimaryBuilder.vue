<template>
  <div class="flex flex-row justify-between my-5">
    <router-link to="/"
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
      >Back
    </router-link>
    <div class="weaponSelectContainer" v-on:click="selectEquipment()">
        <div class="flexboxWeaponSelect" :class="[getSelected ? 'equipmentActive' : 'equipment']">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 180 90"
                 :class="[getSelected ? 'equipmentIconActive' : 'equipmentIcon']"
                 height="50%"
                 preserveAspectRatio="xMidYMid meet"
                 v-html="getIconFromPath"></svg>
        </div>
        <div :class="[getSelected ? 'equipmentTextActive' : 'equipmentText']"><!-- v-if="equipmentNameVisible"-->
            <h4>{{ name }}</h4>
        </div>
    </div>


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
    
  },
};
</script>

<style scoped>

</style>