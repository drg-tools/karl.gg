<template>
    <div class="flex justify-evenly my-5">
        <div class="flex flex-row">
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
                    bg-orange-500
                    hover:bg-orange-700
                    focus:outline-none
                    focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                    w-full
                    md:w-auto
                    h-12
                "
                >Back
            </router-link>
        </div>
        <DrillerPrimaries />
    </div>
</template>

<script>
import DrillerPrimaries from "./driller/DrillerPrimaries.vue";

export default {
    name: "PrimaryBuilder",
    components: { DrillerPrimaries },
};
</script>
