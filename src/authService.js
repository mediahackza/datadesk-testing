import createAuth0Client from '@auth0/auth0-spa-js'
import { user, isAuthenticated, popupOpen } from './store-auth.js'
import { config } from './auth_config.js'

async function createClient() {
  let auth0Client = await createAuth0Client({
    domain: config.domain,
    client_id: config.clientId,
  })

  return auth0Client
}

async function loginWithPopup(client, options) {
  popupOpen.set(true)
  try {
    await client.loginWithPopup(options)

    user.set(await client.getUser())
    isAuthenticated.set(true)
  } catch (e) {
    console.error(e)
    // console.log('denied access')
    client.logout()
  } finally {
    popupOpen.set(false)
  }
}

function logout(client) {
  return client.logout()
}

const auth = {
  createClient,
  loginWithPopup,
  logout,
}

export default auth
