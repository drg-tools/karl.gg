<template>
  <div class="modSelection">
    <!-- TODO: Selected items for each mod matrix -->
    <!-- TODO: Overclocks and selected OC's -->
    <!-- TODO: Props for Mods and OC's? -->

    <!-- Tier Row Start -->
    <div
      v-for="(mod, modTier) in modMatrixTiers"
      class="tierContainer"
      :key="modTier"
    >
      <h2 class="text-white">
        Tier {{ modTier }}
        <!-- If we're not on the first tier, show the level indicator -->
        <p v-if="modTier > 1" class="levelIndicator text-gray-500">
          Level {{ modTier * 4 }}
        </p>
      </h2>

      <!-- Mod Sub Container Start -->
      <!-- TODO: Call Stats here -->
      <div
        class="tierSubContainer"
        :class="[
          mod.length === 1
            ? ''
            : mod.length === 2
            ? 'tierBackgroundGradientHalf'
            : 'tierBackgroundGradient',
        ]"
      >
        <!-- Each Mod start -->
        <div
          v-for="(modData, modIndex) in mod"
          :key="modIndex"
          class="modDisplay"
        >
          <svg viewBox="0 0 80 50" height="100%" class="mod modBackground">
            <path
              d="M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826 L 0.3679663,25"
            />
            <g>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                y="10%"
                viewBox="0 0 80 50"
                class="modIcon"
                height="80%"
                preserveAspectRatio="xMidYMid meet"
                v-html="getIconPath(modData.icon)"
              ></svg>
            </g>
          </svg>
        </div>
        <!-- End individual mod -->
        <!-- If we don't have 3 mods, put a fake one on the end -->
        <div v-if="mod.length === 2" class="pseudoModDisplay"></div>
      </div>
      <!-- End Mod Sub Container -->
    </div>
    <!-- End Tier Row -->
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
    },
    getIconPath(iconName) {
      return this.$store.getters.getIconByName(iconName);
    },
  },
  computed: {
    modMatrixTiers() {
      return _.groupBy(this.flameThrowerMods, "mod_tier");
    },
  },
};
</script>

<style scoped>
h2 {
  width: 11rem;
}

.displayNone {
  display: none !important;
}

.levelIndicator {
  margin: 0;
  font-size: 1rem;
  font-style: normal;
  font-weight: normal;
}

.modSelection {
  flex: 1;
  min-height: 800px; /* Prevent jumping of page layout */
  height: 100%;
  width: 100%;
  padding-left: 1rem;
}

.modificationName {
  font-size: 1.2rem;
}

.modSelectionTitle {
  width: 100%;
  /*background-color: #282117;*/
  color: #fffbff;
  font-size: 1.2rem;
  text-align: center;
}

.tierContainer {
  height: 4.5rem;
  display: flex;
  /*padding-top: 0.5rem;
		padding-bottom: 0.5rem;*/
  justify-content: flex-start;
  align-items: center;
  color: #282117;
}

.overclockContainer {
  display: flex;
  /*padding-top: 0.5rem;
		padding-bottom: 0.5rem;*/
  justify-content: flex-start;
  align-items: center;
  color: #282117;
}

.overclockGrid {
  width: auto !important;
  display: grid;
  grid-gap: 10px;
  grid-template-columns: 6rem 6rem 6rem;
  grid-template-rows: 6rem 6rem 6rem;
  justify-items: center;
  background-color: rgba(91, 64, 45, 0.75);
  border-color: rgb(255, 255, 255);
  border-style: solid;
  padding: 10px 5px 10px 5px;
}

.tierSubContainer {
  width: 100%;
  display: flex;
  justify-content: space-between;
}

.tierBackgroundGradient {
  background: linear-gradient(
      to bottom,
      rgba(0, 0, 0, 0) 40%,
      rgba(40, 33, 23, 1) 40%,
      rgba(40, 33, 23, 1) 60%,
      rgba(0, 0, 0, 0) 60%
    )
    no-repeat;
  background-size: 100% 100%;
}

.tierBackgroundGradientHalf {
  background: linear-gradient(
      to bottom,
      rgba(0, 0, 0, 0) 40%,
      rgba(40, 33, 23, 1) 40%,
      rgba(40, 33, 23, 1) 60%,
      rgba(0, 0, 0, 0) 60%
    )
    no-repeat;
  background-size: 50% 100%;
}

.overclockBackground {
  fill: #000000;
  stroke: #000000;
}

.modBackground {
  fill: #111927;
  stroke: #fc9e00;
  stroke-width: 2px;
}

.modBackgroundActive {
  fill: #fc9e00;
  stroke: #fffbff;
  stroke-width: 2px;
}

.overclockBackground {
  fill: #000000;
  stroke: none;
  stroke-width: 2px;
}

.overclockBackgroundActive {
  fill: #fc9e00;
  /*stroke: #fffbff;*/
  stroke-width: 2px;
}

.modBackgroundActiveNoStroke {
  fill: #fc9e00;
  stroke-width: 0px;
}

.modIcon {
  fill: #ada194;
  stroke: none;
}

.modIcon.selectedOverclock {
  fill: #ada195;
}

.modIconActive {
  fill: #000000;
  stroke: none;
}

.overclockIconActive {
  fill: #ada195;
  stroke: none;
}

.modPadding {
  padding-left: 0.5rem;
  padding-right: 0.5rem;
}

.modDisplay {
  cursor: pointer;
  height: 4rem;
  width: 6rem;
}

.overclockDisplay {
  cursor: pointer;
  height: 6rem;
  width: 100%;
}

.hundredPercent {
  width: 100%;
}

.pseudoModDisplay {
  height: 4rem;
  width: 6rem;
}

.modDisplay:hover .modBackground,
.modDisplay:hover .overclockBackground,
.modDisplay:hover .overclockBackgroundActive,
.overclockDisplay:hover .overclockBackground {
  stroke: #fffbff;
}

.modDisplay:hover .modBackgroundActive {
  fill: #fccc00;
}

.modTextBox {
  min-height: 11rem;
  padding-top: 1rem;
  color: #fffbff;
}

.modTextBoxIcon {
  height: 5rem;
}

.modTextBoxHeader {
  display: flex;
  padding-bottom: 1rem;
}

.modTextBoxTitle p {
  margin: 0;
}

.increaseOverBase {
  color: rgba(255, 251, 255, 0.4);
  padding-top: 1rem;
}

@media (max-width: 770px) {
  .modSelection {
    flex: 0 0 100%;
    order: 1;
    padding: 0;
  }

  .tierSubContainer {
    max-width: 30rem;
  }
}

@media (max-width: 550px) {
  .modDisplay {
    height: 3rem;
    width: 4.8rem;
  }

  .pseudoModDisplay {
    height: 3rem;
    width: 4.8rem;
  }

  h2 {
    width: 20vw;
    margin: 0;
  }

  .modificationName {
    font-size: 1.2rem;
  }
}

@media (max-width: 400px) {
  .overclockGrid {
    left: 5% !important;
    width: 90% !important;
  }
}
</style>
