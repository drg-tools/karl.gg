<template>
  <div
    class="modTextBox"
   v-if="this.$store.state.lastSelectedItemId"
  >
    <div class="modTextBoxHeader">
      <div class="modTextBoxIcon">
        <svg
          viewBox="0 0 80 50"
          height="100%"
          class="modPadding modBackgroundActiveNoStroke"
        >
          <path
            d="M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826 L 0.3679663,25"
          />
          <g>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              y="10%"
              viewBox="0 0 80 50"
              class="modIconActive"
              height="80%"
              preserveAspectRatio="xMidYMid meet"
              v-html="getIconPath(selectedModObject.icon)"
            ></svg>
          </g>
        </svg>
      </div>
      <div class="modTextBoxTitle">
        <p class="allCaps">
          {{ selectedModObject.mod_type || selectedModObject.overclock_type || selectedModObject.eq_type }}
        </p>
        <p class="allCaps modificationName">
          {{ selectedModObject.mod_name || selectedModObject.overclock_name || selectedModObject.name }}
        </p>
        <p class="costList flex flex-wrap">
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.credits_cost > 0"
          >
            <img
              src="/assets/img/20px-Credit.png"
              alt="Credits"
              title="Credits"
            />
            <span>{{ selectedModObject.credits_cost }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.bismor_cost > 0"
          >
            <img
              src="/assets/img/Bismor_icon.png"
              alt="Bismor"
              title="Bismor"
            />
            <span>{{ selectedModObject.bismor_cost }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.croppa_cost > 0"
          >
            <img
              src="/assets/img/Croppa_icon.png"
              alt="Croppa"
              title="Croppa"
            />
            <span>{{ selectedModObject.croppa_cost }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.enor_pearl_cost > 0"
          >
            <img
              src="/assets/img/Enor_pearl_icon.png"
              alt="Enor Pearl"
              title="Enor Pearl"
            />
            <span>{{ selectedModObject.enor_pearl_cost }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.jadiz_cost > 0"
          >
            <img src="/assets/img/Jadiz_icon.png" alt="Jadiz" title="Jadiz" />
            <span>{{ selectedModObject.jadiz_cost }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.magnite_cost > 0"
          >
            <img
              src="/assets/img/Magnite_icon.png"
              alt="Magnite"
              title="Magnite"
            />
            <span>{{ selectedModObject.magnite_cost }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="selectedModObject.umanite_cost > 0"
          >
            <img
              src="/assets/img/Umanite_icon.png"
              alt="Umanite"
              title="Umanite"
            />
            <span>{{ selectedModObject.umanite_cost }}</span>
          </span>
        </p>
      </div>
    </div>
    <div>
      {{ selectedModObject.text_description || selectedModObject.description }}
    </div>
  </div>
</template>

<script>
export default {
  name: "SelectedItemDisplay",
    methods: {
      getIconPath(iconName) {
            return this.$store.getters.getIconByName(iconName);
        },
    },
    computed: {
      selectedModObject() {
        return this.$store.state.lastSelectedItemObject;
      }
    }

};
</script>