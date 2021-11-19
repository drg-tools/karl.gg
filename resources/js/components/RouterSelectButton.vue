<template>
    <router-link
        :to="routeTo"
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
            bg-blue-500
            hover:bg-blue-700
            focus:outline-none
            focus:ring-2
            focus:ring-offset-2
            focus:ring-blue-500
            w-full
            md:w-auto
        "
    >
        Select
    </router-link>
</template>

<script>
export default {
    name: "RouterSelectButton",
    props: ["routeTo"],
};
</script>

<style scoped></style>
