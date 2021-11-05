<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="/assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady">
        <modal name="loadingModal" class="loadoutModal" :adaptive="true" :height="250">
            <div class="contentContainer">
                <h1>Saving Loadout...FOR KARL!</h1>
                <img src="/assets/img/karl-spinner-free.gif" alt="loading...">
            </div>

        </modal>
        <div class="buttonContainer">
            <div class="button" v-on:click="onSaveClick">
                <span class="buttonText">SAVE</span>
            </div>
        </div>
        <div class="buttonContainer">

            <modal name="messageModal" class="loadoutModal" :adaptive="true" :height="250">
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

            <div class="container">
                <p v-if="isError">
                <ul class="text-red-600 text-xl">
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
                </p>
                <h2 class="loadoutNameHeading">Loadout Name</h2>


                <input v-model="$v.name.$model" class="text-gray-900 shadow-sm outline-none block w-full sm:text-sm border-gray-300 rounded-md p-2 mb-2" placeholder="Karl's amazing loadout"
                       :class="{ 'form-group--error': $v.name.$error }" @input="setName($event.target.value)">
                <div class="error" v-if="!$v.name.maxLength">Max {{ $v.name.$params.maxLength.max }} characters.</div>
                <div class="error" v-if="!$v.name.required">Field is required</div>
                <h2>Description</h2>
                <MarkdownEditor :guide.sync="description" :loadoutDescription="loadoutDescription"
                                class="guideField"></MarkdownEditor>

                <!-- todo: show loadout name to the left of there buttons if build belongs to user, show 'new loadout' if not -->

            </div>
            <!-- todo: hide this on edit for now. We do not have a mechanism to share while editing. -->
            <!-- <div class="button" v-on:click="onShareClick">
                <h1 class="buttonText">SHARE</h1>
            </div> -->
        </div>
    </div>
</template>

<script>
import store from '../store';
import gql from 'graphql-tag';
import {get} from 'lodash';
import {required, maxLength} from 'vuelidate/lib/validators';
import MarkdownEditor from './MarkdownEditor.vue';

export default {
    name: 'LoadoutButtonsPoc',
    components: {MarkdownEditor},
    data: () => {
        return {
            name: '',
            description: '',
            loadoutDescription: '',
            messageTitle: '',
            messageText: '',
            creatorId: '',
            update: false,
            guest: false,
            isError: false,
            errors: [],
            dataReady: false
        };
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255)
        }
    },

    methods: {
        setName(value) {
            this.name = value;
            this.$v.name.$touch();
        },
        onLoadHydrate(loadoutEditingId) {
            //let's just query the DB to get our loadout details when we edit a loadout
            const response = this.$apollo.query({
                query: gql`query {
                      loadout(id: ${loadoutEditingId}) {
                                id
                                name
                                description
                                creator {
                                    id
                                }
                            }
                   }`
            }).then(response => {
                let hydrateData = response.data.loadout;
                this.name = hydrateData.name;
                this.description = hydrateData.description;
                // loadoutDescription is our initial value for editing. There may be a better way to do this.
                this.loadoutDescription = hydrateData.description;
                // let's just grab the creator ID instead of relying on a store query
                // assume guest by default, ID 0
                this.creatorId = 0;
                if (hydrateData.creator) {
                    this.creatorId = hydrateData.creator.id;
                }

                this.dataReady = true;
            });

        },
        onSaveClick() {
            // User clicked SAVE
            // Do a quick check if you're logged in or a guest
            // Set update to true if you're logged in & author
            // Fire the onAcceptSave at the proper time.
            this.getLoggedInUser().then(response => {
                let loggedInUserId = response;
                if (this.creatorId === loggedInUserId) {
                    // We know you are the creator, so you are allowed to update this existing build.
                    this.update = true;
                }
                this.$modal.show('loadingModal');
                this.onAcceptSave();
            }).catch(err => {
                console.warn('no logged in user', err);
                this.messageTitle = 'Not logged in :(';
                this.messageText = 'You can save your loadout anonymously or log in first. PLEASE NOTE: You will not be able to edit your build later. Only registered users can edit their builds.';
                this.guest = true;
                this.$modal.show('messageModal');
                /* todo: keep loadout in local storage so his stuff is not lost when he goes to create an account.. */
            });

        },
        onGuestSave() {
            this.$modal.hide('messageModal');
            this.$modal.show('loadingModal');
            this.onAcceptSave();
        },
        async getLoggedInUser() {
            const response = await this.$apollo.query({
                query: gql`
                    {
                        me {
                            id
                            name
                        }
                    }
                    `
            });
            if (!response.data.me) {
                throw new Error('Not logged in');
            } else {
                return response.data.me.id;
            }
        },
        onAcceptSave() {
            // TODO: Rework this function? Why are we doing this?
            let loadoutData = store.getters.getLoadoutForUpdate();

            // Our props hold the latest values, so let's grab those
            loadoutData.name = this.name;
            loadoutData.description = this.description;

            // Vuelidate form validation code; checks status of our form validation
            this.$v.$touch();
            if (this.$v.$invalid) {
                // Vuelidate errors detected, do not submit.
                this.isError = true;
            } else {
                if (this.update) {
                    // current user created the loadout, so let him update it instead of creating a new one
                    this.updateLoadout(loadoutData)
                        .then(result => {
                            let redirId = result.data.updateLoadout.id;
                            this.$modal.hide('loadingModal');
                            window.location.href = `/preview/${redirId}`;
                        })
                        .catch(e => {
                            this.isError = true;
                            this.$modal.hide('loadingModal');
                            for (var key in e.graphQLErrors[0]?.extensions?.validation) {
                                this.errors.push(e.graphQLErrors[0].extensions.validation[key][0])
                            }
                        });
                } else {
                    // create fresh loadout
                    this.createLoadout(loadoutData)
                        .then(result => {
                            // Get the new loadout id
                            let redirId = result.data.createLoadout.id;
                            this.$modal.hide('loadingModal');
                            window.location.href = `/preview/${redirId}`;
                        })
                        .catch(e => {
                            this.isError = true;
                            this.$modal.hide('loadingModal');
                            for (var key in e.graphQLErrors[0]?.extensions?.validation) {
                                this.errors.push(e.graphQLErrors[0].extensions.validation[key][0])
                            }
                        });
                    ;
                }
            }
        },
        onCloseMessageModal() {
            this.$modal.hide('messageModal');
        },
        resetErrors() {
            this.isError = false;
            this.errors = [];
        },
        
    },
    created: function () {
        // TODO: Find a more stable way to get LoadoutId...
        // This just grabs the id from /build/{id}
        // Could easily be fudged?
        let path = window.location.pathname.split('/');
        let loadoutId = path[path.length - 1];
        if (loadoutId !== 'build') {
            // we are editing a build
            this.onLoadHydrate(loadoutId);
        } else {
            // go ahead and load the page, we are doing a new buid
            this.dataReady = true;
        }
    }
};
</script>

<style>
/* todo: put these in global css */
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
    min-width: 8rem;
    height: 2.2rem;
    background: linear-gradient(90deg, #fc9e00 4%, rgba(0, 0, 0, 0) 4%, rgba(0, 0, 0, 0) 8%, #fc9e00 8%, #fc9e00 92%, rgba(0, 0, 0, 0) 92%, rgba(0, 0, 0, 0) 96%, #fc9e00 96%);
}

.button.guest-btn {
    padding-left: 20px;
    padding-right: 20px;
}

/* todo: base all hover effects on this one, like buttons ingame */
.button:hover {
    background: linear-gradient(90deg, #ffd200 4%, rgba(0, 0, 0, 0) 4%, rgba(0, 0, 0, 0) 8%, #ffd200 8%, #ffd200 92%, rgba(0, 0, 0, 0) 92%, rgba(0, 0, 0, 0) 96%, #ffd200 96%);
}

.buttonText {
    color: #000000;
    font-size: 1.6rem;
    letter-spacing: 0.05rem;
    font-weight: bold;
    font-family: BebasNeue, sans-serif;
}


.loadoutModal .vm--modal h1.modalTitle {
    margin-top: 0;
}

.loadoutModal .vm--modal h2 {
    margin-top: 1rem;
    margin-bottom: 0;
}

.modalDescriptionInput {
    width: 100%
}

.guideField {
    margin-bottom: 2.75%;
}

.guideField .tui-editor-defaultUI-toolbar {
    background: #111927;
}

.error {
    font-size: 1.1em;
    font-weight: normal;
    color: red;
    font-family: BebasNeue, sans-serif;
}

h2 .loadoutNameHeading {
    margin-top: 1%;
}

</style>
