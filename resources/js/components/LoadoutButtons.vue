<template>
    <div class="buttonContainer">
        <!-- todo: show loading spinner in modals -->
        <modal name="loadoutNameModal" class="loadoutModal">
            <h1 class="modalTitle">Name your loadout, miner!</h1>
            <h2>Name</h2>
            <div class="error" v-if="!$v.name.required">Field is required</div>
            <div class="error" v-if="!$v.name.maxLength">Max {{$v.name.$params.maxLength.max}} characters.</div>

            <input v-model="$v.name.$model" class="modalNameInput" placeholder="Karl's amazing loadout" :class="{ 'form-group--error': $v.name.$error }" @input="setName($event.target.value)">
            <h2>Description</h2>
            <div class="error" v-if="!$v.description.required">Field is required</div>
            <div class="error" v-if="!$v.description.maxLength">Max {{$v.description.$params.maxLength.max}} characters.</div>
            <textarea v-model="$v.description.$model" class="modalDescriptionInput"
                      placeholder="Deep Rock really need to invest in some better equipment."></textarea>
            <div class="buttonContainer">
                <div class="button" v-on:click="onAcceptSave">
                    <h1 class="buttonText">SAVE</h1>
                </div>
                <div class="button" v-on:click="onCancelSave">
                    <h1 class="buttonText">CANCEL</h1>
                </div>
            </div>
        </modal>
        <modal name="messageModal" class="loadoutModal">
            <h1 class="modalTitle">{{messageTitle}}</h1>
            <h2>{{messageText}}</h2>
            <!-- todo: buttons for save anonymously / log in / cancel / ...? -->
            <div class="buttonContainer">
                <div class="button guest-btn" v-on:click="onGuestSave">
                    <h1 class="buttonText">SAVE AS GUEST</h1>
                </div>
                <div class="button" v-on:click="onCloseMessageModal">
                    <h1 class="buttonText">CLOSE</h1>
                </div>
            </div>
        </modal>
        <!-- todo: show loadout name to the left of there buttons if build belongs to user, show 'new loadout' if not -->
        <div class="button" v-on:click="onSaveClick">
            <h1 class="buttonText">SAVE</h1>
        </div>
        <!-- todo: hide this on edit for now. We do not have a mechanism to share while editing. -->
        <!-- <div class="button" v-on:click="onShareClick">
            <h1 class="buttonText">SHARE</h1>
        </div> -->
    </div>
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';
    import { required, maxLength } from 'vuelidate/lib/validators';

    export default {
        name: 'LoadoutButtons',
        data: () => {
            return {
                name: '',
                description: '',
                messageTitle: '',
                messageText: '',
                update: false,
                guest: false,
                submitStatus: null,
            };
        },
        validations: {
            name: {
                required,
                maxLength: maxLength(255)
            },
            description: {
                required,
                maxLength: maxLength(500)
            }
        },
        methods: {
            setName(value) {
                this.name = value;
                this.$v.name.$touch();
            },
            setDescription(value) {
                this.description = description;
                this.$v.description.$touch();
            },
            onSaveClick() {
                console.log('save loadout to backend');
                // Set this.name & description
                this.getLoggedInUser().then(response => {
                    let loadoutAuthorId = store.state.loadoutDetails.authorId;
                    let loggedInUserId = response;
                    if (loadoutAuthorId === loggedInUserId) {
                        this.name = store.state.loadoutDetails.name;
                        this.description = store.state.loadoutDetails.description;
                        this.update = true;
                    }
                    this.$modal.show('loadoutNameModal');
                }).catch(err => {
                    console.log('no logged in user', err);
                    this.messageTitle = 'Not logged in :(';
                    this.messageText = 'You can save your loadout anonymously or log in first. PLEASE NOTE: You will not be able to edit your build later. Only registered users can edit their builds.';
                    this.guest = true;
                    this.$modal.show('messageModal');
                    /* todo: keep loadout in local storage so his stuff is not lost when he goes to create an account.. */
                });

            },
            onGuestSave() {
                this.$modal.hide('messageModal');
                this.$modal.show('loadoutNameModal');
            },
            onShareClick() {
                console.log('generate share link without saving');
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
                if (!!this.name && !!this.description) {
                    let loadoutData = store.getters.getLoadoutForUpdate();
                    loadoutData.name = this.name;
                    loadoutData.description = this.description;
                    this.$v.$touch()
                    if (this.$v.$invalid) {
                        this.submitStatus = 'ERROR'
                        console.log('error state applied.');
                    } else {
                        if (this.update) {
                                // this user created the loadout, so let him update it instead of creating a new one
                                console.log('update loadout', loadoutData);
                                this.updateLoadout(loadoutData).then(result => {
                                    console.log('got result back', result);
                                    this.name = '';
                                    this.description = '';
                                    this.$modal.hide('loadoutNameModal');
                                    let redirId = result.data.updateLoadout.id;
                                    window.location.href = `/preview/${redirId}`;
                                    /* todo: show success messages and redirect to loadout preview */
                                });
                            } else {
                                // create fresh loadout
                                console.log('create loadout', loadoutData);
                                this.createLoadout(loadoutData).then(result => {
                                    console.log('got result back', result);
                                    this.name = '';
                                    this.description = '';
                                    this.$modal.hide('loadoutNameModal');
                                    // Get the new loadout id
                                    let redirId = result.data.createLoadout.id;
                                    window.location.href = `/preview/${redirId}`;
                                    /* todo: show success messages and redirect to loadout preview */
                                });
                            }
                        }
                }
            },
            onCancelSave() {
                this.$modal.hide('loadoutNameModal');
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
                console.log('send variables', variables);
                // Call to the graphql mutation
                const result = await this.$apollo.mutate({
                    // Query
                    mutation: gql`mutation (
                    $name: String!,
                    $description: String!,
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
                console.log('send variables', variables);
                // Call to the graphql mutation
                const result = await this.$apollo.mutate({
                    // Query
                    mutation: gql`mutation (
                    $id: Int!,
                    $name: String!,
                    $description: String!,
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
