<template>
    <div class="flex justify-evenly my-5">
        <div class="flex flex-row">
            <div
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
                    focus:ring-2
                    focus:ring-offset-2
                    focus:ring-orange-500
                    w-full
                    md:w-auto
                "
                v-on:click="onSaveClick"
            >
                SAVE
            </div>
        </div>
        <modal
            name="loadingModal"
            class="loadoutModal"
            :adaptive="true"
            :height="250"
        >
            <div class="contentContainer">
                <h1>Saving Loadout...FOR KARL!</h1>
                <img src="/assets/img/karl-spinner-free.gif" alt="loading..." />
            </div>
        </modal>

        <modal
            name="messageModal"
            class="loadoutModal"
            :adaptive="true"
            :height="250"
        >
            <div class="contentContainer">
                <h1 class="modalTitle">{{ messageTitle }}</h1>
                <h2>{{ messageText }}</h2>
            </div>
            <!-- todo: buttons for save anonymously / log in / cancel / ...? -->
            <div class="buttonContainer">
                <div class="button guest-btn" v-on:click="onGuestSave">
                    <span class="buttonText">SAVE AS GUEST</span>
                </div>
                <div class="button" v-on:click="onCloseMessageModal">
                    <span class="buttonText">CLOSE</span>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    name: "LoadoutBuilderActions",
    data: () => {
        return {
            messageTitle: "",
            messageText: "",
            update: false,

        };
    },
    methods: {
        onSaveClick() {
            let loggedInStatus = this.getIsLoggedIn();
            this.$modal.show('loadingModal');
            // this.$store.dispatch("saveLoadout");
        },
        
        getIsLoggedIn() {
            return this.$store.getters.isLoggedIn;
        },
    },
};
</script>
