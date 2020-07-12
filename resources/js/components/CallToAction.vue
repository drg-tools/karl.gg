<template>
    <div class="callToActionContainer">
        <img class="wideHero" src="../assets/img/karl-hero-wide.png" alt="hero image" usemap="#wideHeroMap" width="100%" height="250">
        <img class="highHero" src="../assets/img/karl-hero-high.png" alt="hero image" usemap="#wideHeroMap" width="100%" height="250">
        <!-- todo: use svg for clickable areas. http://thenewcode.com/760/Create-A-Responsive-Imagemap-With-SVG -->
        <map name="wideHeroMap">
            <area shape="rect" coords="500, 100, 570, 250" alt="random" href="/build/D">
            <area shape="rect" coords="580, 100, 640, 250" alt="random" href="/build/E">
            <area shape="rect" coords="655, 100, 725, 250" alt="random" href="/build/G">
            <area shape="rect" coords="740, 100, 795, 250" alt="random" href="/build/S">
            <area shape="rect" coords="400, 80, 450, 110" alt="random" href="/build/R">
        </map>
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
</style>
