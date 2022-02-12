<template>
    <div>
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
                        bg-green-600
                        hover:bg-green-900
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-green-500
                        w-full
                        md:w-auto
                        cursor-pointer
                    "
                    v-on:click="onSaveClick"
                >
                    Save Loadout
                </div>
            </div>

            <modal
                name="loadingModal"
                class="loadoutModal"
                :adaptive="true"
                :height="250"
            >
                <div>
                    <h2>Saving Loadout...FOR KARL!</h2>
                    <img
                        src="/assets/img/karl-spinner-free.gif"
                        alt="loading..."
                    />
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
        <div
            class="form-group"
            :class="{ 'form-group--error': $v.name.$error }"
        >
            <label class="form__label">Name</label>
            <input
                class="
                    text-gray-900
                    shadow-sm
                    outline-none
                    block
                    w-full
                    sm:text-sm
                    border-gray-300
                    rounded-md
                    p-2
                    mb-2
                "
                v-model="name"
                v-on:input="debounceInput"
                placeholder="Karl's amazing loadout"
            />
        </div>
        <div class="error" v-if="!$v.name.required">Field is required</div>
        <div class="error" v-if="!$v.name.maxLength">
            Max {{ $v.name.$params.maxLength.max }} characters.
        </div>
    </div>
</template>

<script>
import { required, maxLength } from "vuelidate/lib/validators";
import debounce from 'lodash-es/debounce';

export default {
    name: "LoadoutBuilderActions",
    data: () => {
        return {
            messageTitle: "",
            messageText: "",
            update: false,
            updateId: "",
            name: "",
            unsubscribe: "",
            editedLoadoutCreatorId: "",
        };
    },
    created() {
        // TODO: Find a more stable way to get LoadoutId...
        // This just grabs the id from /build/{id}
        // Could easily be fudged?
        let path = window.location.pathname.split("/");
        let loadoutId = path[path.length - 1];
        if (loadoutId !== "build") {
            // we are editing a build
            this.onLoadHydrate(loadoutId);
            this.loadoutCreatorId;
        }
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            // TODO: make this handle equipment as well as primary and secondary
            if (mutation.type === "setLoadoutName") {
                this.name = state.loadoutName;
            }
        });
    },
    beforeDestroy() {
        this.unsubscribe();
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255),
        },
    },
    methods: {
        debounceInput: debounce(function (e) {
            // this.name = e.target.value;
            this.$store.commit("setLoadoutName", e.target.value);
        }, 500),
        async onLoadHydrate(loadoutEditingId) {
            const response = await this.$store
                .dispatch("getLoadoutData", loadoutEditingId)
                .then((result) => {
                    if (result.creator != null) {
                        // loadout has creator id
                        this.editedLoadoutCreatorId = result.creator.id;
                        this.updateId = result.id;
                    }
                    // dispatch the store hydrator here
                    this.$store.dispatch("hydrateLoadoutEditData", result);
                })
                .catch((e) => {
                    console.log(e);
                });
        },
        onSaveClick() {
            this.$v.$touch();
            if (this.$v.$invalid) {
                // Vuelidate errors detected, do not submit.
                return;
            } else {
                this.$store.commit("setAuthUser", window.authUser);
                
                let loggedInStatus = this.getIsLoggedIn();
                if (loggedInStatus) {
                    // User is logged in & GQL will save them with the right ID
                    this.$modal.show("loadingModal");
                    if (this.editedLoadoutCreatorId == this.getLoggedInUserId()) {
                        this.update = true;
                    }
                    this.onAcceptSave();
                } else {
                    this.messageTitle = "Not logged in :(";
                    this.messageText =
                        "You can save your loadout anonymously or log in first. PLEASE NOTE: You will not be able to edit your build later. Only registered users can edit their builds.";
                    this.$modal.show("messageModal");
                }
            }
        },
        onGuestSave() {
            this.$modal.hide("messageModal");
            this.$modal.show("loadingModal");
            this.onAcceptSave();
        },
        async onAcceptSave() {
            if (this.update) {
                let loadoutReturn = await this.$store
                    .dispatch("saveLoadout", this.updateId) // send additional attributes to signify edit
                    .then((result) => {
                        // Get the new loadout id
                        let redirId = result.data.updateLoadout.id;
                        this.$modal.hide("loadingModal");
                        window.location.href = `/preview/${redirId}`;
                    })
                    .catch((e) => {
                        this.$modal.hide("loadingModal");
                        console.log(e);
                    });
            } else {
                // create fresh loadout
                let loadoutReturn = await this.$store
                    .dispatch("saveLoadout", false)
                    .then((result) => {
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
        getLoggedInUserId() {
            return this.$store.getters.getLoggedInUserId;
        },
    },
};
</script>
