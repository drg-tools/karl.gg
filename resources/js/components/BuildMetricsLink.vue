<template>
    <a :href="`/asv/${gunId}/${combo}`" class="underline text-sm text-karl-orange" target="_blank">See more advanced
        stats</a>
</template>

<script>
export default {
    name: "BuildMetricsLink",
    props: ["gunId", "combo"]
}
</script>

<style scoped>

</style>

