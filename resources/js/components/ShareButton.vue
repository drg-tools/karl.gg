<template>
    <div>
        <input type="hidden" v-model="this.pageUrl" />
        <button
            class="
                clip-btn
                inline-flex
                items-center
                text-center
                px-4
                py-2
                mr-3
                border border-transparent
                text-sm
                font-medium
                rounded-md
                shadow-sm
                text-white
                bg-orange-500
                hover:bg-orange-700
                focus:outline-none
                focus:ring-2
                focus:ring-offset-2
                focus:ring-orange-500
                w-full
                md:w-auto
            "
            v-clipboard:copy="pageUrl"
            v-clipboard:success="onCopy"
            v-clipboard:error="onError"
        >
            SHARE
        </button>
    </div>
</template> 


<script>
export default {
    name: "ShareButton",
    props: ["pageUrl"],
    methods: {
        onCopy: function (e) {
            this.$toasted.info("You just copied: " + this.pageUrl, {
                position: "top-center",
                duration: 5000,
            });
        },
        onError: function (e) {
            this.$toasted.error("Failed to copy URL", {
                position: "top-center",
                duration: 5000,
            });
        },
    },
};
</script>
