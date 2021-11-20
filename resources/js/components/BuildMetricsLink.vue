<template>
    <div class="underline text-sm text-right text-karl-orange ml-2 sm:ml-0">
        <a :href="`/asv/${gunId}/${combo}`" target="_blank">See more advanced stats</a>
    </div>
</template>

<script>
export default {
    name: "BuildMetricsLink",
    props: ["gunId", "combo"]
}
</script>

<style scoped>

</style>

