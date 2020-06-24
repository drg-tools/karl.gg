import { HttpLink } from 'apollo-link-http'
import { onError } from 'apollo-link-error'
import { InMemoryCache } from 'apollo-cache-inmemory'
import ApolloClient from 'apollo-boost'
import { ApolloLink } from 'apollo-link'

const token = document.head.querySelector('meta[name="csrf-token"]').content

//Sets the headers using Apollo Http Link
const httpLink = new HttpLink({
    headers: {
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    }
})

// Sets a global error handler using the Apollo Error Link
const errorLink = onError(({graphQLErrors, networkError}) => {
    if (graphQLErrors)

        if (graphQLErrors[0]) {

            if (graphQLErrors[0].extensions.category === 'authentication') {
                // redirect?
            }
        }

    if (networkError) console.log(`[Network error]: ${networkError}`)
})

// Combines the Apollo Http and Error links
const link = ApolloLink.from([
    errorLink,
    httpLink,
])

const cache = new InMemoryCache()

// Create the apollo client
export const Apollo = new ApolloClient({
    link,
    cache,
    connectToDevTools: true,
})
