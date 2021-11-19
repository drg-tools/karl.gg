<template>
    <router-link
        to="/"
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
                    bg-orange-700
                    hover:bg-orange-900
                    focus:outline-none
                    focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                    w-full
                    md:w-auto
                    h-12
                "
    >
        <i class="fas fa-chevron-left"></i>
    </router-link>
</template>

<script>
export default {
    name: "BackButton"
}
</script>
