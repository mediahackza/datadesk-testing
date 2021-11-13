<script>
  import { onMount } from 'svelte'
  import auth from './authService'
  import { isAuthenticated, user } from './store-auth'
  import Router from 'svelte-spa-router'
  import { routes } from './routes'
  import LoginScreen from './components/LoginScreen.svelte'
  import Header from './components/Header.svelte'
  // import Header from './components/Header.svelte'

  let auth0Client

  onMount(async () => {
    auth0Client = await auth.createClient()
    isAuthenticated.set(await auth0Client.isAuthenticated())
    user.set(await auth0Client.getUser())
  })

  function login() {
    auth.loginWithPopup(auth0Client)
  }

  function logout() {
    auth.logout(auth0Client)
  }
</script>

<!-- <Header /> -->
{#if $isAuthenticated}
  <Header on:click={logout} />
  <Router {routes} />
{:else}
  <LoginScreen on:click={login} />
{/if}
