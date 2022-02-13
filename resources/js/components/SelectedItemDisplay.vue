<template>
<!-- Here we just need to use a getter to see if we have a selected item
Then we do a pull for the name, details, icon, and cost info
Stretch goal is OC's -->
  <div
    class="modTextBox"
    v-if="
      hoveredMod ? !!hoveredMod.mod_name || !!hoveredMod.overclock_name : false
    "
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
              v-html="getIconFromPath(hoveredMod.icon)"
            ></svg>
          </g>
        </svg>
      </div>
      <div class="modTextBoxTitle">
        <p class="allCaps">
          {{ hoveredMod.mod_type || hoveredMod.overclock_type }}
        </p>
        <p class="allCaps modificationName">
          {{ hoveredMod.mod_name || hoveredMod.overclock_name }}
        </p>
        <p class="costList flex flex-wrap">
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.credits > 0"
          >
            <img
              src="/assets/img/20px-Credit.png"
              alt="Credits"
              title="Credits"
            />
            <span>{{ hoveredMod.cost.credits }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.bismor > 0"
          >
            <img
              src="/assets/img/Bismor_icon.png"
              alt="Bismor"
              title="Bismor"
            />
            <span>{{ hoveredMod.cost.bismor }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.croppa > 0"
          >
            <img
              src="/assets/img/Croppa_icon.png"
              alt="Croppa"
              title="Croppa"
            />
            <span>{{ hoveredMod.cost.croppa }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.enorPearl > 0"
          >
            <img
              src="/assets/img/Enor_pearl_icon.png"
              alt="Enor Pearl"
              title="Enor Pearl"
            />
            <span>{{ hoveredMod.cost.enorPearl }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.jadiz > 0"
          >
            <img src="/assets/img/Jadiz_icon.png" alt="Jadiz" title="Jadiz" />
            <span>{{ hoveredMod.cost.jadiz }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.magnite > 0"
          >
            <img
              src="/assets/img/Magnite_icon.png"
              alt="Magnite"
              title="Magnite"
            />
            <span>{{ hoveredMod.cost.magnite }}</span>
          </span>
          <span
            class="costListItem flex items-center pr-2"
            v-if="hoveredMod.cost.umanite > 0"
          >
            <img
              src="/assets/img/Umanite_icon.png"
              alt="Umanite"
              title="Umanite"
            />
            <span>{{ hoveredMod.cost.umanite }}</span>
          </span>
        </p>
      </div>
    </div>
    <div>
      {{ hoveredMod.text_description || hoveredMod.description }}
    </div>
  </div>
</template>

<script>
export default {
  name: "SelectedItemDisplay",
    // Ok we need:
    //  selected item icon
    //  selected item name
    //  selected item type
    //  selected item credits_stuff
};
</script>