<template>
    <div class="section">
        <div class="title" v-on:click="toggle">
            <span class="toggleIcon" id="toggleIcon">{{toggleIcon}}</span> Toggle This Section
        </div>
        <hr/>
        <!-- BIND v-show to the showSection data property to reflect changes -->
        <div class="body" v-show="showSection">
            <img v-bind:src="imageUrl">
        </div>
    </div>
</template>

<script>

    export default {
        name: 'NewLayout',
        components: {},
        methods: {
            toggle() {
                this.showSection = !this.showSection;
            }
        },
        data() {
            return {
                showSection: true, // Flag to show section
                imageUrl: '"../assets/img/full-D_image.png"',
                toggleIcon: '+'
            };
        }
    };
</script>

<style scoped>

</style>
