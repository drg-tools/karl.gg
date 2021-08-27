<template>
  <div>
    <!-- TODO: Add mod matrix stub -->
    <!-- TODO: Selected items for each mod matrix -->
    <!-- TODO: Overclocks and selected OC's -->
    <!-- TODO: Props for Mods and OC's? -->
    <!-- TODO: Export all mod icons and add those guys to store as well -->
    
    <div v-for="mod, modTier in modMatrixTiers">
        <h2>{{modTier}}</h2>
        <div v-for="item in mod" class="asvMod">
            <svg viewBox="0 0 80 50"
                         height="100%"
                         class="mod modBackground"
                         >
                        <path
                            d="M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826 L 0.3679663,25"/>
                        <g>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 y="10%"
                                 viewBox="0 0 80 50"
                                 class="modIcon"
                                 height="80%"
                                 preserveAspectRatio="xMidYMid meet"
                                 v-html="getIconPath(item.icon)"></svg>
                        </g>
                    </svg>
          
        </div>
    </div>
    </div>

</template>

<!--todo: reset button-->
<script>
export default {
  name: "ModMatrix",
  data() {
    return {
      flameThrowerMods: "",
    };
  },
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    this.checkMods();
  },
  methods: {
    checkMods() {
      this.flameThrowerMods = this.$store.state.loadoutClassData.guns[0].mods;
      console.log(this.flameThrowerMods);
    },
    getIconPath(iconName) {
        // TODO: Make this a getter in store
        console.log(this.$store.getters.getIconByName(iconName))
        return this.$store.getters.getIconByName(iconName);
    }
  },
  computed: {
    modMatrixTiers() {
      return _.groupBy(this.flameThrowerMods, "mod_tier");
    },
  },
};
</script>

