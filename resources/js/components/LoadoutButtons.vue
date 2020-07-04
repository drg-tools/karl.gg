<template>
	<div class="buttonContainer">
        <div class="button" v-on:click="onSaveClick">
            <h3 class="buttonText">save</h3>
        </div>
        <div class="button" v-on:click="onShareClick">
            <h3 class="buttonText">share</h3>
        </div>
    </div>
</template>

<script>
	export default {
		name: 'LoadoutButtons',
        methods: {
            onSaveClick() {
                console.log("save loadout to backend")
            },
            onShareClick() {
                console.log("generate share link without saving")
            }
        }
	};
</script>

<style scoped>
    .buttonContainer {
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }

    .button {
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0.5rem 0 0.5rem 1rem;
        width: 8rem;
        height: 2.2rem;
        background: linear-gradient(90deg, #fc9e00 4%, rgba(0,0,0,0) 4%, rgba(0,0,0,0) 8%, #fc9e00 8%, #fc9e00 92%, rgba(0,0,0,0) 92%, rgba(0,0,0,0) 96%, #fc9e00 96%);
    }

    /* todo: base all hover effects on this one, like buttons ingame */
    .button:hover {
        background: linear-gradient(90deg, #ffd200 4%, rgba(0,0,0,0) 4%, rgba(0,0,0,0) 8%, #ffd200 8%, #ffd200 92%, rgba(0,0,0,0) 92%, rgba(0,0,0,0) 96%, #ffd200 96%);
    }

    .buttonText {
        font-size: 1.6rem;
        letter-spacing: 0.05rem;
        font-weight: bold;
        font-family: BebasNeue, sans-serif;
    }
</style>
