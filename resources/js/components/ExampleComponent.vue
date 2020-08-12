<template>
    <!-- <ApolloQuery :query="$options.query"> -->
    <!-- <template slot-scope="{result: {loading, data, error }}"> -->
    <!-- <div v-if="loading">Loading...</div> -->
    <div>
        <ul>
            <li v-for="(gun, gunId) in getGuns()" v-bind:key="gunId">Hello {{ gun.json_stats[0].dmg }}</li>
            <!-- {{ getGuns() }} -->
        </ul>
    </div>
    <!-- </template> -->
    <!-- </ApolloQuery> -->
</template>

<script>
    import gql from 'graphql-tag';

    export default {
        query: gql`
            query {
                guns {
                    data {
                        name
                        json_stats
                    }
                }
            }
        `,
        methods: {
            getGuns() {
                console.time('getGuns');
                const response = this.$apollo.query({
                    query: gql`query {
                      guns {
                            data {
                                name
                                json_stats
                            }
                        }
                   }`
                }).then(response => {
                    console.log(response.data.guns.data);
                    return response.data.guns.data;
                });

            }
        }

    };
</script>
