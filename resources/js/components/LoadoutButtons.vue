<template>
    <div class="buttonContainer">
        <modal name="loadoutNameModal" class="saveLoadoutModal">
            <h1 class="modalTitle">Name your loadout, miner!</h1>
            <h2>Name</h2>
            <input v-model="name" class="modalNameInput" placeholder="Karl's amazing loadout">
            <h2>Description</h2>
            <textarea v-model="description" class="modalDescriptionInput"
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
        <div class="button" v-on:click="onSaveClick">
            <h1 class="buttonText">SAVE</h1>
        </div>
        <div class="button" v-on:click="onShareClick">
            <h1 class="buttonText">SHARE</h1>
        </div>
    </div>
</template>

<script>
    import store from '../store';
    import apolloQueries from '../apolloQueries';
    import gql from 'graphql-tag';

    export default {
        name: 'LoadoutButtons',
        /* todo: set name and description of existing loadout if the loadout belongs to the user */
        data: () => {
            return {
                name: '',
                description: ''
            }
        },
        methods: {
            onSaveClick() {
                console.log('save loadout to backend');

                this.$modal.show('loadoutNameModal');

            },
            onShareClick() {
                console.log('generate share link without saving');
            },
            onAcceptSave() {
                /* todo: if the loadout belongs to the user, don't create new loadout but update existing */
                if (!!this.name && !!this.description) {
                    let loadoutData = store.getters.getLoadoutForUpdate();
                    loadoutData.name = this.name;
                    loadoutData.description = this.description;
                    console.log('createLoadoutData', loadoutData);
                    this.createLoadout(loadoutData).then(result => {
                        console.log('got result back', result);
                        this.name = '';
                        this.description = '';
                    });
                    this.$modal.hide('loadoutNameModal');
                }
            },
            onCancelSave() {
                this.$modal.hide('loadoutNameModal');
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
        width: 8rem;
        height: 2.2rem;
        background: linear-gradient(90deg, #fc9e00 4%, rgba(0, 0, 0, 0) 4%, rgba(0, 0, 0, 0) 8%, #fc9e00 8%, #fc9e00 92%, rgba(0, 0, 0, 0) 92%, rgba(0, 0, 0, 0) 96%, #fc9e00 96%);
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

    .saveLoadoutModal .vm--modal {
        background-color: #130e09;
        padding: 1rem;
    }

    .saveLoadoutModal .vm--modal h1.modalTitle {
        margin-top: 0;
    }

    .saveLoadoutModal .vm--modal h2 {
        margin-top: 1rem;
        margin-bottom: 0;
    }

    .modalDescriptionInput, .modalNameInput {
        width: 100%
    }
</style>
