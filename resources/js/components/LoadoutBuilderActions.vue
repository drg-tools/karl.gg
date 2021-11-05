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
            <div>
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
        <div class="mx-auto">
            {{ messageTitle }} <br />
            {{ messageText }}
            <!-- todo: buttons for save anonymously / log in / cancel / ...? -->
            <div
                class="
                    mt-4
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
                v-on:click="onGuestSave"
            >
               SAVE AS GUEST
            </div>
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
                v-on:click="onCloseMessageModal"
            >
                CLOSE
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
            console.log(loggedInStatus);
            if (loggedInStatus) {
                // User is logged in & GQL will save them with the right ID
                this.$modal.show("loadingModal");
            } else {
                this.messageTitle = "Not logged in :(";
                this.messageText =
                    "You can save your loadout anonymously or log in first. PLEASE NOTE: You will not be able to edit your build later. Only registered users can edit their builds.";
                this.$modal.show("messageModal");
            }
        },
        onGuestSave() {
            this.$modal.hide("messageModal");
            this.$modal.show("loadingModal");
            this.onAcceptSave();
        },
        async onAcceptSave() {
            if (this.update) {
                // current user created the loadout, so let him update it instead of creating a new one
                // this.updateLoadout(loadoutData)
                //     .then(result => {
                //         let redirId = result.data.updateLoadout.id;
                //         this.$modal.hide('loadingModal');
                //         window.location.href = `/preview/${redirId}`;
                //     })
                //     .catch(e => {
                //         this.isError = true;
                //         this.$modal.hide('loadingModal');
                //         for (var key in e.graphQLErrors[0]?.extensions?.validation) {
                //             this.errors.push(e.graphQLErrors[0].extensions.validation[key][0])
                //         }
                //     });
            } else {
                // create fresh loadout
                let loadoutReturn = await this.$store
                    .dispatch("saveLoadout")
                    .then(result => {
                        // Get the new loadout id
                        let redirId = result.data.createLoadout.id;
                        this.$modal.hide("loadingModal");
                        window.location.href = `/preview/${redirId}`;
                    })
                    .catch((e) => {
                        this.$modal.hide("loadingModal");
                        console.log(e);
                    });
            }
        },
        onCloseMessageModal() {
            this.$modal.hide("messageModal");
        },
        getIsLoggedIn() {
            return this.$store.getters.isLoggedIn;
        },
    },
};
</script>
