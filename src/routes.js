import Home from './routes/Home.svelte'
import Edit from './routes/Edit.svelte'
// import List from './routes/List.svelte'
// import Map from './routes/Map.svelte'

export const routes = {
  '/': Home,
  '/edit/:id': Edit,
  //   '/map': Map,
  //   '*': NotFound,
}
