<template>
    <div>
        <button
            @click="this.share"
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
        >
            SHARE
        </button>
    </div>
</template>


<script>
export default {
    name: "ShareButton",
    props: ["loadout"],
    methods: {
        share: function () {
            if (navigator.share) {
                navigator.share({
                    title: this.loadout.name,
                    text: 'Check out this Deep Rock Galactic build.',
                    url: document.location.href,
                })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(document.location.href);

                this.$toasted.info("You just copied: " + document.location.href, {
                    position: "top-center",
                    duration: 5000,
                });
            }
        },
    },
};
</script>
