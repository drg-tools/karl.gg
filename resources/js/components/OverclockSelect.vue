<template>
  <!-- TODO: Add overclock selection  -->
  <!-- TODO: Selected overclock status -->

  <!-- TODO: Encapsulation of border state
    encapsulate the icon/border selection when selected
    encapsulate the icon/border select in the popover -->
  <div class="overclockContainer">
    <h2 class="text-white">Overclock</h2>
    <div
      v-if="this.$store.state.selectedPrimaryOverclock != ''"
      class="overclockDisplay"
      v-popover:overclocks.top
    >
      <OverclockIcon
        :icon="selectedOverclockIcon"
        :ocType="selectedOverclockType"
      />
    </div>
    <div class="overclockDisplay" v-else>
      <!--todo: show name of selected overclock on hover?-->
      <svg
        v-popover:overclocks.top
        viewBox="0 0 80 80"
        height="100%"
        class="mod overclockBackground hundredPercent"
      >
        <g display="inherit">
          <g>
            <!-- TODO: Encapsulate this more. This is not ideal currently -->
            <!-- background layer -->
            <path
              style="
                fill: #379c5d;
                stroke: #000000;
                stroke-width: 0.26458332px;
                stroke-linecap: butt;
                stroke-linejoin: miter;
                stroke-opacity: 1;
                fill-opacity: 1;
              "
              d="M 16.645549,6.8857899 63.523153,6.92059 79.999998,30.39618 52.538582,73.114198 26.69254,73.0896 2.5e-7,30.232339 Z"
              id="path817"
            />
          </g>
          <g transform="translate(0,-217)">
            <path
              style="fill: #000000; stroke-width: 0.32307553"
              d="M 14.900282,271.17622 3.1080334,252.26279 1.5540136,249.74756 2.5e-7,247.23234 8.3227717,235.55906 l 8.3227773,-11.67327 23.438794,0.0174 23.438811,0.0174 8.238423,11.6389 L 80,247.19838 v 0.0989 0.0989 l -13.730705,21.35901 -13.730713,21.35901 -12.923021,-0.0123 -12.92302,-0.0123 z m 40.513633,11.25399 3.844557,-6.04021 -1.016278,-1.83457 -1.016287,-1.83457 3.937786,-6.63632 3.937787,-6.6363 2.57636,-0.0928 2.57636,-0.0928 3.822907,-5.9508 3.822909,-5.95081 v -0.24646 -0.24647 l -7.533474,-10.60265 -7.533473,-10.60266 -6.15636,-0.0876 -6.156359,-0.0876 -1.347607,2.26837 -1.347615,2.26836 h -7.69994 -7.699939 l -1.255394,-2.18076 -1.255403,-2.18076 -6.189658,-0.0877 -6.189668,-0.0876 -7.6350455,10.71246 -7.6350361,10.71247 0.1854321,0.48323 0.1854322,0.48323 3.6035723,5.77266 3.6035723,5.77266 2.6093837,0.013 2.609385,0.013 3.715374,6.33241 3.715364,6.33242 0.128735,0.34077 0.128734,0.34076 -1.060593,1.96527 -1.060593,1.9653 3.677999,5.88452 3.678009,5.88452 11.792249,-0.0128 11.792258,-0.0128 3.844558,-6.0402 z m -30.453253,-0.32014 -3.347257,-5.40143 1.08499,-2.01551 1.084991,-2.0155 -0.339123,-0.56909 -0.339122,-0.56909 -3.85954,-6.54229 -3.859539,-6.54228 h -2.591517 -2.591516 l -3.3691817,-5.41152 -3.3691721,-5.41151 -0.00183,-0.48462 -0.00183,-0.48461 7.2025468,-10.0961 7.202557,-10.09611 h 5.778086 5.778095 l 1.207809,2.09998 1.207809,2.09999 h 8.322916 8.322915 l 1.207809,-2.09999 1.207809,-2.09998 h 5.730281 5.730273 l 6.367763,8.96535 6.367765,8.96534 0.964653,1.36218 0.964654,1.36218 -3.579725,5.58394 -3.579725,5.58394 -2.578649,0.0934 -2.57864,0.0934 -4.214726,7.10173 -4.214726,7.10174 1.002092,1.85099 1.002092,1.85097 -3.550592,5.49229 -3.550585,5.49228 -11.436788,0.0707 -11.436788,0.0707 z m 28.198477,-0.0313 2.70689,-4.16064 0.402047,-0.75124 0.402057,-0.75123 -1.096548,-2.03382 -1.096548,-2.03382 -0.161539,0.0143 -0.161538,0.0143 -2.90768,4.65716 -2.90768,4.65716 -8.29643,0.0127 -8.29643,0.0127 -2.997356,-4.6846 -2.997355,-4.68459 H 25.564205 25.37739 l -1.035904,2.10069 -1.035903,2.1007 2.98578,4.83323 2.985789,4.83326 10.587544,0.0122 10.587545,0.0122 z m -36.943038,-25.84143 -0.32229,-0.60221 -2.374636,-3.84006 -2.374635,-3.84008 -0.21309,-0.35839 -0.21308,-0.35838 5.249017,-7.39544 5.249026,-7.39542 4.569719,-0.16154 4.569728,-0.16155 0.222467,-0.22336 0.222458,-0.22337 -1.1942,-1.96585 -1.1942,-1.96588 -4.702474,0.0893 -4.702465,0.0892 -5.936823,8.39996 -5.9368314,8.39996 -0.840379,1.20445 -0.840379,1.20445 3.0241336,4.85322 3.0241338,4.85322 h 2.518545 2.518554 z m 55.709708,-4.24808 3.138728,-4.8503 -0.3088,-0.39967 -0.308791,-0.39969 -6.542801,-9.29688 -6.542802,-9.29688 -4.712145,0.0892 -4.712145,0.0892 -1.059733,1.7769 -1.059723,1.77692 -0.01255,0.40384 -0.01255,0.40386 h 4.670686 4.670687 l 5.236964,7.33508 5.236965,7.33508 -0.0109,0.33796 -0.0109,0.33796 -2.789138,4.3557 -2.789147,4.35568 v 0.24815 0.24813 h 2.389664 2.389655 l 3.138728,-4.85031 z m -51.381551,22.44558 0.859245,-1.46619 -0.101618,-0.57766 -0.101627,-0.57768 -3.392296,-5.7018 -3.392287,-5.7018 -2.375103,-0.008 -2.3751025,-0.008 0.7597245,1.25046 0.759724,1.25046 4.038442,6.49148 4.038442,6.49148 0.211615,0.0119 0.211606,0.0119 0.859235,-1.46619 z m 44.909678,-6.20734 4.692263,-7.51197 v -0.17522 -0.17523 l -2.333287,0.0949 -2.333297,0.0949 -3.53202,5.95933 -3.53203,5.95931 0.903368,1.72541 0.903369,1.72542 0.269685,-0.0925 0.269687,-0.0925 z m -17.263046,-41.3532 0.968473,-1.69615 h -8.891118 -8.891108 v 0.16136 0.16137 l 0.888459,1.61573 0.888459,1.6157 7.034177,-0.081 7.034194,-0.081 z"
              id="path838"
            />
          </g>
        </g>
      </svg>
    </div>
    <popover name="overclocks" class="overclockGrid">
      <div
        v-for="(overclock, overclockIndex) in flameThrowerOverclocks"
        :key="overclockIndex"
        v-on:click="selectOverclock(overclock.id)"
        class="tooltip-target modDisplay"
      >
        <OverclockIcon
          :icon="getIconPath(overclock.icon)"
          :ocType="overclock.overclock_type"
        />
      </div>
    </popover>
  </div>
</template>

<!--todo: reset button-->
<script>
import OverclockIcon from "./OverclockIcon.vue";
export default {
  name: "OverclockSelect",
  components: { OverclockIcon },
  data() {
    return {
      flameThrowerOverclocks: "",
      selectedOverclockIcon: "",
      selectedOverclockType: "",
    };
  },
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    this.hydrateData();
    // TODO: Make this part of our hydrate data?
    // Happens if you navigate away and come back
    this.selectedOc();
  },
  methods: {
    hydrateData() {
      this.flameThrowerOverclocks =
        this.$store.state.loadoutClassData.guns[0].overclocks;
    },
    getIconPath(iconName) {
      return this.$store.getters.getIconByName(iconName);
    },
    selectOverclock(overclockId) {
      this.$store.commit("clearSelectedPrimaryOverclock");
      this.$store.commit("setSelectedPrimaryOverclock", overclockId);
      this.selectedOc();
    },
    selectedOc() {
      // TODO: Make this a store getter?
      let selectedObject = this.flameThrowerOverclocks.filter(
        (oc) => oc.id === this.$store.state.selectedPrimaryOverclock
      );
      if(selectedObject.length === 0) {
        return;
      } else {
        this.selectedOverclockIcon = this.getIconPath(selectedObject[0].icon);
        this.selectedOverclockType = selectedObject[0].overclock_type;
      }
      
    },
  },
};
</script>

