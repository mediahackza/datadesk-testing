<script>
  import { push, pop, replace } from 'svelte-spa-router'
  import { onMount } from 'svelte'
  export let params = {}
  let api = 'https://api.datadesk.co.za/'
  import Icon from '../components/Icon.svelte'
  let record = []

  async function getRecord() {
    let response = await fetch(`${api}get-record.php?id=${params.id}`)
    record = await response.json()
    record = record[0]
  }

  onMount(() => {
    getRecord()
  })

  function updateRecord() {
    let formData = new FormData()
    formData.append('id', params.id)
    formData.append('tags', record.tags)
    formData.append('tablename', record.table_name)
    formData.append('original_file', record.original_file)
    let API_ENDPOINT = `${api}update-record.php?id=${params.id}`
    // console.log(params)

    fetch(API_ENDPOINT, {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((response) => {
        console.log(response)

        push('/')
      })
  }
  function cancelEdit() {
    pop()
  }
</script>

<main>
  <div class="links"><a href="/"><Icon name="corner-up-left" /></a></div>
  <div class="edit"><h3>Edit Record</h3></div>

  <div class="edit-form">
    <table>
      <tr>
        <td class="label">Table name</td><td class="detail"
          ><input type="text" bind:value={record.table_name} /></td
        >
      </tr>
      <tr>
        <td class="label">DB name</td><td class="detail">{record.db_name}</td>
      </tr>
      <tr>
        <td class="label">Source (CSV)</td><td class="detail"
          >{record.source}</td
        >
      </tr>
      <tr>
        <td class="label">Original File</td><td class="detail"
          ><input type="text" bind:value={record.original_file} /></td
        >
      </tr>

      <tr>
        <td class="label">Tags</td>
        <td class="detail"><input type="text" bind:value={record.tags} /></td>
      </tr>
      <tr>
        <td colspan="2"
          ><button class="update-button" on:click={updateRecord}>Update</button>
          <button class="cancel" on:click={cancelEdit}>Cancel</button></td
        >
      </tr>
    </table>
  </div>
</main>

<style>
  .links {
    width: 100%;
    text-align: left;
    margin-bottom: 10px;
  }
  table {
    border-collapse: collapse;
    background: #fff;
    width: 100%;
  }
  td {
    padding: 10px;
    border: solid 1px #eee;
    font-size: 0.8rem;
  }
  td.label {
    width: 30%;
    text-align: right;
    background: #eee;
    border: solid 1px #fff;
    font-weight: 700;
  }
  td.detail {
    text-align: left;
  }
  input {
    width: 400px;
  }
</style>
