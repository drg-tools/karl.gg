<template>
    <div class="callToActionContainer">
        <!-- todo: only download the images needed for resolution -->
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 900 363" preserveAspectRatio="xMinYMin meet" class="wideHero">
            <image width="900" height="363" xlink:href="/assets/img/home-base.png">
            </image>
            <image width="900" height="363" xlink:href="/assets/img/home-logo.png">
            </image>
            <image width="900" height="363" :class="[classHovered.D ? 'classVisible' : 'classInvisible']"
                   xlink:href="/assets/img/home-driller_alpha.png">
            </image>
            <image width="900" height="363" :class="[classHovered.E ? 'classVisible' : 'classInvisible']"
                   xlink:href="/assets/img/home-engineer_alpha.png">
            </image>
            <image width="900" height="363" :class="[classHovered.G ? 'classVisible' : 'classInvisible']"
                   xlink:href="/assets/img/home-gunner_alpha.png">
            </image>
            <image width="900" height="363" :class="[classHovered.S ? 'classVisible' : 'classInvisible']"
                   xlink:href="/assets/img/home-scout_alpha.png">
            </image>
            <image width="900" height="363" :class="[classHovered.R ? 'classVisible' : 'classInvisible']"
                   xlink:href="/assets/img/home-dice_alpha.png">
            </image>
            <a xlink:href="/build/D" class="hoverEffect" v-on:mouseover="showChar('D')" v-on:mouseout="hideChar('D')">
                <!-- todo: use dwarf outline path instead of rects -->
                <rect x="390" y="91" fill-opacity="0" stroke-opacity="0" width="110" height="180"></rect>
            </a>
            <a xlink:href="/build/E" class="hoverEffect" v-on:mouseover="showChar('E')" v-on:mouseout="hideChar('E')">
                <rect x="500" y="95" fill-opacity="0" stroke-opacity="0" width="108" height="180"></rect>
            </a>
            <a xlink:href="/build/G" class="hoverEffect" v-on:mouseover="showChar('G')" v-on:mouseout="hideChar('G')">
                <rect x="608" y="98" fill-opacity="0" stroke-opacity="0" width="107" height="170"></rect>
            </a>
            <a xlink:href="/build/S" class="hoverEffect" v-on:mouseover="showChar('S')" v-on:mouseout="hideChar('S')">
                <rect x="715" y="100" fill-opacity="0" stroke-opacity="0" width="108" height="170"></rect>
            </a>
            <a xlink:href="/build/R" class="hoverEffect" v-on:mouseover="showChar('R')" v-on:mouseout="hideChar('R')">
                <circle cx="423" cy="74" r="7" fill-opacity="0" stroke-opacity="0"></circle>
            </a>
        </svg>
        <!-- todo: mobile CTA -->
        <!--<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 450 500" preserveAspectRatio="xMinYMin meet" class="highHero">
            <image width="450" height="500" xlink:href="/assets/img/karl-hero-high.png">
            </image>
            <image width="450" height="500" :class="[classHovered.D ? 'classVisible' : 'classInvisible']" xlink:href="/assets/img/karl-hero-high_overlay-D.png">
            </image>
            <image width="450" height="500" :class="[classHovered.E ? 'classVisible' : 'classInvisible']" xlink:href="/assets/img/karl-hero-high_overlay-E.png">
            </image>
            <image width="450" height="500" :class="[classHovered.G ? 'classVisible' : 'classInvisible']" xlink:href="/assets/img/karl-hero-high_overlay-G.png">
            </image>
            <image width="450" height="500" :class="[classHovered.S ? 'classVisible' : 'classInvisible']" xlink:href="/assets/img/karl-hero-high_overlay-S.png">
            </image>
            <a xlink:href="/build/D" class="hoverEffect" v-on:mouseover="showChar('D')" v-on:mouseout="hideChar('D')">
                <rect x="125" y="340" fill-opacity="0" stroke-opacity="1" stroke-width="4" width="70" height="150"></rect>
            </a>
            <a xlink:href="/build/E" class="hoverEffect" v-on:mouseover="showChar('E')" v-on:mouseout="hideChar('E')">
                <rect x="213" y="319" fill-opacity="0" stroke-opacity="1" width="70" height="150"></rect>
            </a>
            <a xlink:href="/build/G" class="hoverEffect" v-on:mouseover="showChar('G')" v-on:mouseout="hideChar('G')">
                <rect x="296" y="328" fill-opacity="0" stroke-opacity="1" width="70" height="150"></rect>
            </a>
            <a xlink:href="/build/S" class="hoverEffect" v-on:mouseover="showChar('S')" v-on:mouseout="hideChar('S')">
                <rect x="367" y="319" fill-opacity="0" stroke-opacity="1" width="70" height="150"></rect>
            </a>
            <a xlink:href="/build/R" class="hoverEffect">
                <rect x="26" y="323" fill-opacity="0" stroke-opacity="0" width="70" height="150"></rect>
            </a>
        </svg>-->
    </div>
</template>

<script>
    export default {
        name: 'CallToAction',
        components: {},
        computed: {},
        data() {
            return {
                classHovered: {
                    D: false,
                    E: false,
                    G: false,
                    S: false,
                    R: false
                }
            };
        },
        methods: {
            showChar: function (char) {
                Vue.set(this.classHovered, char, true);
            },
            hideChar: function (char) {
                Vue.set(this.classHovered, char, false);
            }
        },
        apollo: {},
        mounted: function () {
        }
    };
</script>

<style>
    .callToActionContainer {
        width: 100%;
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .textContainer {
        width: 80%;
        white-space: pre-line;
        text-align: center;
    }

    .textContainer h1 {
        margin-top: 2rem;
    }

    .textContainer h2 {
        margin-bottom: 1rem;
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

    .classVisible {
        display: inline
    }

    .classInvisible {
        display: none
    }
</style>
