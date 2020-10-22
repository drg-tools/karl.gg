<template>
    <div v-if="!dataReady" class="loadingIndicator">
        <img src="../assets/img/karl-spinner-free.gif" alt="loading...">
    </div>
    <div v-else-if="dataReady">
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
                        :class="{ 'form-group--error': $v.name.$error }" @input="setName($event.target.value)" ref="loadout_name">
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
                    // console.log('YaYEET'+ JSON.stringify(response.data.loadout));
                    let hydrateData = response.data.loadout;
                    this.name = hydrateData.name;
                    this.description = hydrateData.description;
                    this.loadoutDescription = hydrateData.description;
                    this.creatorId = hydrateData.creator.id
                    this.dataReady = true;
                });

            },         
            onSaveClick() {
                // save loadout to backend
                // Set this.name & description
                this.getLoggedInUser().then(response => {
                    let loggedInUserId = response;
                    if (this.creatorId === loggedInUserId) {
                        console.log('You are this id: ' + loggedInUserId + ' and you are editing a loadout by: ' + this.creatorId);
                        this.name = store.state.loadoutDetails.name;
                        this.description = store.state.loadoutDetails.description;
                        this.update = true;
                    }
                    console.log('accept save should fire');
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
                if (!!this.name) {
                    let loadoutData = store.getters.getLoadoutForUpdate();
                    console.log('loadoutData garbage');
                    console.log(loadoutData);
                    console.log(this.$refs.loadout_name.value);
                    loadoutData.name = this.name;
                    loadoutData.description = this.description;
                    this.$v.$touch();
                    if (this.$v.$invalid) {
                        this.submitStatus = 'ERROR';
                    } else {
                        if (this.update) {
                            // this user created the loadout, so let him update it instead of creating a new one
                            this.updateLoadout(loadoutData).then(result => {
                                let redirId = result.data.updateLoadout.id;
                                window.location.href = `/preview/${redirId}`;
                                /* todo: show success messages and redirect to loadout preview */
                            });
                        } else {
                            // create fresh loadout
                            this.createLoadout(loadoutData).then(result => {
                                // Get the new loadout id
                                let redirId = result.data.createLoadout.id;
                                window.location.href = `/preview/${redirId}`;
                                /* todo: show success messages and redirect to loadout preview */
                            });
                        }
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
