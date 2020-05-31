<template>
    <div class="classSelectItem" v-on:click="selectClass()">
        <div :class="[isSelected ? 'equipmentTextActive' : 'equipmentText']">
            <h4>{{ name }}</h4>
        </div>
    </div>
</template>

<script>
    import store from '../store';

    export default {
        name: 'ClassComponent',
        props: {
            name: String,
            classId: String
        },
        computed: {
            isSelected: function () {
                return store.state.selected.class === this.classId;
            }
        },
        methods: {
            selectClass() {
                store.commit('selectClass', {classID: this.classId});
            }
        }
    };
</script>

<style scoped>
    .classSelectItem {
        display: flex;
        cursor: pointer;
    }
</style>
