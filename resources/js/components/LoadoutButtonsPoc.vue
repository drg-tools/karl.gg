<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady">
        <modal name="loadingModal" class="loadoutModal" :adaptive="true" :height="250">
                <div class="contentContainer">
                    <h1>Saving Loadout...FOR KARL!</h1>
                    <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
                </div>
                
        </modal>
        <div class="buttonContainer">
            
            <modal name="messageModal" class="loadoutModal" :adaptive="true" :height="250">
                <div class="contentContainer">
                    <h1 class="modalTitle">{{messageTitle}}</h1>
                    <h2>{{messageText}}</h2>
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
                    <h2>Loadout Name</h2>
                    <div class="error" v-if="!$v.name.required">Field is required</div>
                    <div class="error" v-if="!$v.name.maxLength">Max {{$v.name.$params.maxLength.max}} characters.</div>

                    <input v-model="$v.name.$model" class="modalNameInput" placeholder="Karl's amazing loadout"
                        :class="{ 'form-group--error': $v.name.$error }" @input="setName($event.target.value)">
                    <h2>Description</h2>
                    <MarkdownEditor :guide.sync="description" :loadoutDescription="loadoutDescription"></MarkdownEditor>
                </div>
            <!-- todo: show loadout name to the left of there buttons if build belongs to user, show 'new loadout' if not -->
            <div class="button" v-on:click="onSaveClick">
                <span class="buttonText">SAVE</span>
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
                submitStatus: null,
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
                    this.creatorId = hydrateData.creator.id
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
                        console.log('You are this id: ' + loggedInUserId + ' and you are editing a loadout by: ' + this.creatorId);
                        // We know you are the creator, so you are allowed to update this existing build.
                        this.update = true;
                        // console.log('Update: ' + this.update);
                    }
                    console.log('accept save should fire');
                    this.$modal.show('loadingModal');
                    this.onAcceptSave();
                }).catch(err => {
                    console.log('onsaveclick guest fired');
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
                // console.log(loadoutData);

                console.log('This name:' + this.name);
                console.log('This description:' + this.description);

                // Our props hold the latest values, so let's grab those
                loadoutData.name = this.name;
                loadoutData.description = this.description;

                // Vuelidate form validation code; checks status of our form validation
                this.$v.$touch();
                if (this.$v.$invalid) {
                    // Vuelidate errors detected, do not submit.
                    this.submitStatus = 'ERROR';
                } else {
                    if (this.update) {
                        // this user created the loadout, so let him update it instead of creating a new one
                        this.updateLoadout(loadoutData).then(result => {
                            let redirId = result.data.updateLoadout.id;
                            this.$modal.hide('loadingModal');
                            window.location.href = `/preview/${redirId}`;
                            /* todo: show success messages and redirect to loadout preview */
                        });
                    } else {
                        // create fresh loadout
                        this.createLoadout(loadoutData).then(result => {
                            // Get the new loadout id
                            let redirId = result.data.createLoadout.id;
                            this.$modal.hide('loadingModal');
                            window.location.href = `/preview/${redirId}`;
                            /* todo: show success messages and redirect to loadout preview */
                        });
                    }
                }
            },
            onCloseMessageModal() {
                this.$modal.hide('messageModal');
            },
            async createLoadout(params) {
                let variables = {
                    name: params.name,
                    description: params.description,
                    character_id: params.character_id,
                    mods: params.mods,
                    overclocks: params.overclocks,
                    equipment_mods: params.equipment_mods,
                    throwable_id: params.throwable_id
                };
                // Call to the graphql mutation
                /* todo: 'user_id' cannot be null when saving as a guest */
                const result = await this.$apollo.mutate({
                    // Query
                    mutation: gql`mutation (
                    $name: String!,
                    $description: String,
                    $character_id: Int!,
                    $mods: [Int!]!,
                    $overclocks: [Int!]!,
                    $equipment_mods: [Int!]!,
                    $throwable_id: Int!,
                    ) {
                        createLoadout(
                            name: $name
                            description: $description
                            character_id: $character_id
                            mods: $mods
                            overclocks: $overclocks
                            equipment_mods: $equipment_mods
                            throwable_id: $throwable_id
                          ) {
                          id
                          name
                          description
                        }
                      }`,
                    // Parameters
                    variables: variables
                });
                return result;
            },
            async updateLoadout(params) {
                let variables = {
                    id: params.loadout_id,
                    name: params.name,
                    description: params.description,
                    character_id: params.character_id,
                    mods: params.mods,
                    overclocks: params.overclocks,
                    equipment_mods: params.equipment_mods,
                    throwable_id: params.throwable_id
                };
                // Call to the graphql mutation
                const result = await this.$apollo.mutate({
                    // Query
                    mutation: gql`mutation (
                    $id: Int!,
                    $name: String!,
                    $description: String,
                    $character_id: Int!,
                    $mods: [Int!]!,
                    $overclocks: [Int!]!,
                    $equipment_mods: [Int!]!,
                    $throwable_id: Int!,
                    ) {
                        updateLoadout(
                            id: $id
                            name: $name
                            description: $description
                            character_id: $character_id
                            mods: $mods
                            overclocks: $overclocks
                            equipment_mods: $equipment_mods
                            throwable_id: $throwable_id
                          ) {
                          id
                          name
                          description
                        }
                      }`,
                    // Parameters
                    variables: variables
                });
                return result;
            }
        },
        created: function () {
            console.log("I have created thee");
            // TODO: Find a more stable way to get LoadoutId...
            // This just grabs the id from /build/{id} 
            // Could easily be fudged?
            let path = window.location.pathname.split('/');
            let loadoutId = path[path.length - 1];
            if(loadoutId !== 'build') {
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

    .loadoutModal .vm--modal {
        background-color: #130e09;
        padding: 1rem;
    }

    .loadoutModal .vm--modal h1.modalTitle {
        margin-top: 0;
    }

    .loadoutModal .vm--modal h2 {
        margin-top: 1rem;
        margin-bottom: 0;
    }

    .modalDescriptionInput, .modalNameInput {
        width: 100%
    }

    .error {
        font-size: 1.3rem;
        font-weight: normal;
        color: red;
        font-family: BebasNeue, sans-serif;
    }
</style>
