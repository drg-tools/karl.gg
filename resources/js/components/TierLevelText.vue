<template>
  <h2 class="text-white">
    Tier {{ modTier }}
    <!-- If we're not on the first tier, show the level indicator -->
    <!-- [0/4/8/12/16] for Primary -->
    <div v-if="itemType === 'primary'">
      <p v-if="modTier == 2" class="levelIndicator text-gray-500">Level 4</p>
      <p v-if="modTier == 3" class="levelIndicator text-gray-500">Level 8</p>
      <p v-if="modTier == 4" class="levelIndicator text-gray-500">Level 12</p>
      <p v-if="modTier == 5" class="levelIndicator text-gray-500">Level 16</p>
    </div>
    <div v-if="itemType === 'secondary'">
      <p v-if="modTier == 2" class="levelIndicator text-gray-500">Level 6</p>
      <p v-if="modTier == 3" class="levelIndicator text-gray-500">Level 10</p>
      <p v-if="modTier == 4" class="levelIndicator text-gray-500">Level 14</p>
      <p v-if="modTier == 5" class="levelIndicator text-gray-500">Level 18</p>
    </div>
    <div v-if="itemType === 'equipment'">
      <p v-if="modTier == 2" class="levelIndicator text-gray-500">Level 5</p>
      <p v-if="modTier == 3" class="levelIndicator text-gray-500">Level 10</p>
      <p v-if="modTier == 4" class="levelIndicator text-gray-500">Level 15</p>
    </div>
  </h2>
</template>

<!--todo: reset button-->
<script>
export default {
  name: "TierLevelText",
  props: ["itemType", "modTier"],
};
</script>

