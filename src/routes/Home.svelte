<script>
  import { onMount } from 'svelte'

  import Icon from '../components/Icon.svelte'
  import InputSection from '../components/InputSection.svelte'

  // let api = 'http://localhost:8888/api/datadesk/'
  let api = 'https://api.datadesk.co.za/'
  let tables = []
  export let loading = false
  export let reload = false
  let showDeleted = false
  let displayTables = []
  let tag
  let showInput = false

  $: if (reload) {
    getTables()
  }

  $: if (showDeleted) {
    displayTables = tables
  }
  $: if (!showDeleted) {
    if (tag) {
      displayTables = tables.filter(
        (table) => table.deleted === 'false' && table.tags.includes(tag)
      )
    } else {
      displayTables = tables.filter((table) => table.deleted === 'false')
    }
  }
  ;('')

  async function getTables() {
    await fetch(`${api}/fetch-tables.php`)
      .then((data) => data.json())
      .then((data) => {
        tables = data
        displayTables = data.filter((t) => t.deleted === 'false')
      })
      .then(() => {
        reload = false
      })
  }

  onMount(async () => {
    getTables()
  })

  function deleteSource(id) {
    let API_ENDPOINT = `${api}delete-source.php?id=${id}`
    fetch(API_ENDPOINT)
      .then((response) => response.json())
      .then((response) => {
        reload = true
      })
  }

  function restoreSource(id) {
    let API_ENDPOINT = `${api}restore-source.php?id=${id}`
    fetch(API_ENDPOINT)
      .then((response) => response.json())
      .then((response) => {
        reload = true
      })
  }

  function splitTags(t) {
    t.replace(' ', '')
    let tags = t.split(',')
    return tags
  }
  function setTag(t) {
    tag = t
    reload = true
  }
  function showInputBox() {
    showInput = !showInput
  }
</script>

<main>
  {#if loading}
    <div class="loading"><img src="assets/loading.svg" alt="Loading" /></div>
  {/if}
  <div class="masthead">
    <h1><img src="/assets/datadesk.svg" alt="Datadesk Logo" />DataDesk</h1>
  </div>

  <div class="control-input" on:click={showInputBox}>
    <Icon name="file-plus" stroke="dodgerblue" strokeWidth="2" />
    <div class="file-add-label">Add new record</div>
  </div>
  {#if showInput}
    <InputSection {reload} {loading} />
  {/if}
  <!-- <div class="options">
    <div class="options-item options-left">
      {#if tag}
        <span class="strong">Tag:</span>
        {tag}
        <div style="display: inline; padding-top: 5px;">
          <div
            class="tag-clear link"
            on:click={() => {
              tag = null
            }}
          >
            [ Clear tag ]
          </div>
        </div>
      {/if}
    </div>
    <div class="options-item options-right">
      Show deleted: <input type="checkbox" bind:checked={showDeleted} />
    </div>
  </div> -->
  <div class="table-container">
    <div class="options">
      <div class="options-item options-left">
        {#if tag}
          <span class="strong">Tag:</span>
          {tag}
          <div style="display: inline; padding-top: 5px;">
            <div
              class="tag-clear link"
              on:click={() => {
                tag = null
              }}
            >
              [ Clear tag ]
            </div>
          </div>
        {/if}
      </div>
      <div class="options-item options-right">
        Show deleted: <input type="checkbox" bind:checked={showDeleted} />
      </div>
    </div>
    <table>
      {#each displayTables as table, i}
        <tr class:deleted-row={table.deleted === 'true'}>
          <td>
            {#if table.type === 'local'}
              <Icon name="database" stroke="dodgerblue" strokeWidth="2" />
            {:else}
              <Icon name="external-link" stroke="green" strokeWidth="2" />
            {/if}
          </td>
          <td>
            {#if table.type === 'remote'}
              <a
                href="{api}csvjson.php?table={table.db_name}"
                target="_blank"
                class="link-button link-json">JSON</a
              >&nbsp;
              <a
                href="{api}csvremote.php?table={table.db_name}"
                target="_blank"
                class="link-button link-csv">CSV</a
              >{:else}
              <a
                href="{api}json.php?table={table.db_name}"
                target="_blank"
                class="link-button link-json">JSON</a
              >&nbsp;
              <a
                href="{api}csv.php?table={table.db_name}"
                target="_blank"
                class="link-button link-csv">CSV</a
              >
            {/if}</td
          >
          <td class="name">
            <span class="record-name"
              ><a href="/#/edit/{table.id}"
                ><span class="icon"
                  ><Icon
                    name="edit"
                    strokeWidth="2"
                    stroke="cornflowerblue"
                  /></span
                >{table.table_name}</a
              ></span
            >
            <div class="table-links">
              |
              {table.db_name}
              {#if table.source}
                |
                <a href={table.source} target="_blank">Source File</a>
              {/if}
              {#if table.original_file}
                |
                <a href={table.original_file} target="_blank">Original</a>
              {/if}
              | {table.updated}
              {#if table.count}
                | {table.count} rows
              {/if}
              {#if table.deleted === 'true'}
                | <span class="deleted">deleted</span>
              {/if}<br />
              {#if table.tags}
                <div class="tags">
                  {#each splitTags(table.tags) as tag}
                    <div
                      class="tag"
                      on:click={() => {
                        setTag(tag)
                      }}
                    >
                      {tag}
                    </div>
                  {/each}
                </div>
              {/if}
            </div></td
          >
          <td>
            {#if table.deleted !== 'true'}
              <div
                class="delete-source"
                on:click={() => {
                  deleteSource(table.id)
                }}
              >
                <Icon name="trash" stroke="red" strokeWidth="2" />
              </div>
            {/if}
            {#if table.deleted === 'true'}
              <div
                class="delete-source"
                on:click={() => {
                  restoreSource(table.id)
                }}
              >
                <Icon name="rotate-ccw" stroke="green" strokeWidth="2" />
              </div>
            {/if}
            <!-- {:else}
                <div
                  class="delete-source"
                  on:click={() => {
                    deleteSource(table.id)
                  }}
                >
                  <Icon name="arrow-ccw" stroke="green" />
                </div> -->
          </td>
        </tr>
      {/each}
    </table>
  </div>
</main>

<style>
  .loading {
    z-index: 3000;
    position: fixed;
    top: 40vh;
    left: 0px;
    width: 100%;
  }
  .loading img {
    width: 80px;
  }

  h1 {
    font-family: 'Titillium Web', 'Roboto', Arial, Helvetica, sans-serif;
    text-transform: uppercase;
    font-size: 3em;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  td {
    padding: 10px 10px;
    padding-top: 15px;
    text-align: left;
    border-top: solid 1px rgb(211, 211, 211);
    text-align: center;
  }
  td.name {
    text-align: left;
    line-height: 1.1rem;
  }
  .masthead img {
    width: 50px;
    margin-right: 10px;
    transform: translate(0px, 10px);
  }
  .masthead {
    color: #000;
    font-weight: 900;
  }
  .link-button {
    padding: 2px 10px;
    border: none;
    font-size: 0.7rem;
    text-decoration: none;
    border-radius: 10px;
    color: var(--white);
    background: var(--primaryColor);
    margin: 0px 0px;
    font-weight: 700;
    opacity: 0.8;
  }
  .link-button:hover {
    opacity: 1;
  }
  .link-json {
    background: lightseagreen;
  }
  .link-csv {
    background: goldenrod;
  }
  .table-links {
    font-weight: 300;
    font-size: 0.8rem;
    margin-top: 3px;
    color: var(--gray700);
    font-family: 'Roboto', Arial, Helvetica, sans-serif;
  }
  .table-links a {
    color: var(--gray500);
  }
  .table-container {
    background: #f8f8f8;
    border: solid 1px var(--gray100);
    padding: 20px;
  }
  .delete-source {
    opacity: 0.5;
    cursor: pointer;
  }
  .delete-source:hover {
    opacity: 1;
  }
  .deleted {
    color: var(--red);
  }
  .deleted-row {
    background: rgba(255, 0, 0, 0.05);
  }
  .options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    margin-bottom: 10px;
    text-align: right;
    font-size: 0.7rem;
    text-transform: uppercase;
  }
  .options-item {
    padding: 0px;
  }
  .options-left {
    padding-top: 5px;
    text-align: left;
    padding-left: 10px;
  }
  .tags .tag {
    color: var(--primaryColor);
    cursor: pointer;
    margin-top: 10px;
  }
  .tags * {
    display: inline-block;
  }
  .tag {
    margin-right: 5px;
    /* text-decoration: underline; */
    background: lightsteelblue;
    color: #000 !important;
    font-weight: 500;
    padding: 1px 12px;
    border-radius: 50px;
  }
  .tag-clear {
    display: inline-block;
    color: var(--primaryColor);
    margin-left: 20px;
    cursor: pointer;
  }
  .record-name {
    font-weight: 700;
  }
  .record-name a {
    text-decoration: none;
    color: #000;
  }
  .record-name .icon {
    margin-right: 5px;
  }
  .control-input {
    margin-bottom: 10px;

    max-width: 300px;
    margin-left: auto;
    margin-right: auto;

    /* padding: 10px; */
    font-size: 1.5rem;
    cursor: pointer;
  }
  .file-add-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: var(--gray600);
    /* opacity: 0; */
  }
  /* .control-input:hover .file-add-label {
    opacity: 1;
  } */
  .table-border {
    border: solid 1px gray;
  }
</style>
