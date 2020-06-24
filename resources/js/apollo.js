import { InMemoryCache } from 'apollo-cache-inmemory'
import ApolloClient from 'apollo-boost'

const token = document.head.querySelector('meta[name="csrf-token"]').content

const cache = new InMemoryCache()

// Create the apollo client
export const Apollo = new ApolloClient({
    headers: {
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    cache,
    connectToDevTools: true
})
