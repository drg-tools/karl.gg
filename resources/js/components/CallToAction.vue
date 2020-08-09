<template>
    <div class="callToActionContainer">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 900 250" preserveAspectRatio="xMinYMin meet">
            <image width="900" height="250" xlink:href="../assets/img/karl-hero-wide.png">
            </image>
            <a xlink:href="/build/D" class="hoverEffect">
                <rect x="500" y="100" fill="#fff" fill-opacity="0" stroke="#000" stroke-opacity="0"  stroke-width="5" stroke-linecap="butt" width="70" height="150" ></rect>
            </a>
        </svg>
    </div>
</template>

<script>
    export default {
        name: 'CallToAction',
        components: {},
        computed: {},
        methods: {},
        apollo: {},
        mounted: function () {
            console.log("mounted call to action")
        }
    };
</script>

<style>
    .callToActionContainer {
        width: 100%;
        margin-bottom: 1rem;
        background-color: rgb(196, 196, 196);
    }

    .wideHero {
        display: inherit;
    }
    @media (max-width: 770px) {
        .wideHero {
            display: none;
        }
    }
    .highHero {
        display: none;
    }
    @media (max-width: 770px) {
        .highHero {
            display: inherit;
        }
    }

    .hoverEffect:hover > rect {
        stroke-opacity: 1;
    }
</style>
