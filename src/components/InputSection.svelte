<script>
  let api = 'https://api.datadesk.co.za/'
  let files
  let filename
  let fileLinkName
  let fileLinkSource
  let originalFile
  let localCsv = true
  let remoteCsv = false

  let linkCsv = false

  export let reload
  export let loading

  function localCsvTab() {
    localCsv = true
    linkCsv = false
    remoteCsv = false
  }
  function remoteCsvTab() {
    localCsv = false
    linkCsv = false
    remoteCsv = true
  }
  function linkCsvTab() {
    localCsv = false
    linkCsv = true
    remoteCsv = false
  }

  async function uploadFile() {
    loading = true
    let formData = new FormData()
    formData.append('file', files[0])

    formData.append('filename', filename)
    let API_ENDPOINT = `${api}upload-table.php`

    await fetch(API_ENDPOINT, {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((response) => {})
  }

  function addFileUpload() {
    uploadFile().then(() => {
      reload = true
      filename = ''
      files = null
      loading = false
    })
  }

  async function addFileLink() {
    let formData = new FormData()
    formData.append('tablename', fileLinkName)
    formData.append('source', fileLinkSource)
    formData.append('original', originalFile)
    let API_ENDPOINT = `${api}link-table.php`

    fetch(API_ENDPOINT, {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((response) => {
        fileLinkName = ''
        fileLinkSource = ''
        reload = true
      })
  }

  async function uploadCsvLink() {
    let formData = new FormData()
    formData.append('tablename', fileLinkName)
    formData.append('source', fileLinkSource)
    formData.append('original', originalFile)
    let API_ENDPOINT = `${api}upload-remote-csv.php`

    fetch(API_ENDPOINT, {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json())
      .then((response) => {
        console.log(response)

        fileLinkName = ''
        fileLinkSource = ''
        reload = true
      })
  }
</script>

<div class="input-section">
  <div class="input-tabs">
    <div class:tab-selected={localCsv} class="input-tab" on:click={localCsvTab}>
      Upload (local CSV)
    </div>
    <div
      class:tab-selected={remoteCsv}
      class="input-tab"
      on:click={remoteCsvTab}
    >
      Upload (remote CSV)
    </div>
    <div class:tab-selected={linkCsv} class="input-tab" on:click={linkCsvTab}>
      Link (remote CSV)
    </div>
  </div>
  {#if localCsv}
    <div class="input-box input-db">
      <div class="table-form">
        <div class="input-description">
          Add a new table to the MySQL database from a CSV file.
        </div>

        <input type="text" bind:value={filename} placeholder="Table Name" />
        <div class="file-selection">
          <input type="file" bind:files />
        </div>
        <button id="fileAdd" class="button-add" on:click={addFileUpload}
          >Add table</button
        >
      </div>
    </div>
  {/if}

  {#if remoteCsv}
    <div class="input-box input-db">
      <div class="table-form">
        <div class="input-description">
          Import a remote CSV file eg. Google Sheet
        </div>
        <input
          type="text"
          bind:value={fileLinkName}
          placeholder="Table Name"
        /><br />
        <input
          type="text"
          bind:value={originalFile}
          placeholder="Original File Link"
        /><br />
        <input
          class="link-file-input"
          type="text"
          bind:value={fileLinkSource}
          placeholder="CSV URL"
        />

        <br />
        <button id="fileLink" class="button-add" on:click={uploadCsvLink}
          >Add table</button
        >
      </div>
    </div>
  {/if}

  {#if linkCsv}
    <div class="input-box input-db">
      <div class="table-form">
        <div class="input-description">Link to a table &nbsp;</div>
        <input
          type="text"
          bind:value={fileLinkName}
          placeholder="Table Name"
        /><br />
        <input
          class="link-file-input"
          type="text"
          bind:value={fileLinkSource}
          placeholder="File Link"
        /><br />
        <input
          type="text"
          bind:value={originalFile}
          placeholder="Original File Link"
        /><br />
        <br />
        <button id="fileLink" class="button-add" on:click={addFileLink}
          >Link table</button
        >
      </div>
    </div>
  {/if}
</div>

<style>
  .input-section {
    width: 100%;
    text-align: left;
    margin-bottom: 20px;
  }
  .input-tabs * {
    display: inline-block;
  }
  .input-tab {
    border-left: solid 1px var(--gray100);
    border-right: solid 1px var(--gray100);
    border-top: solid 1px var(--gray100);
    margin: 0px;
    padding: 8px 10px;
    border-radius: 7px 7px 0px 0px;
    background: #fff;
    transform: translate(0px, 0px);
    /* border-bottom: solid 1px var(--gray100); */
    background: #eee;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: 700;
  }
  .input-tab:hover {
    background: rgb(250, 250, 250);
  }
  .tab-selected {
    border-bottom: solid 1px #fff;
    transform: translate(0px, 1px);
    background: #fff;
  }
  .input-box {
    background: #fff;
    padding: 15px;
    border: solid 1px var(--gray100);
    min-height: 200px;
    line-height: 2rem;
    text-align: center;
  }
  .input-description {
    text-transform: uppercase;
    font-size: 0.8rem;
    color: var(--gray300);
    margin-bottom: 15px;
  }
  .file-selection {
    width: 400px;
    text-align: center;
    border: none !important;

    margin: 10px auto;
  }
  /* .input-dialog {
    background: rgb(245, 245, 245);
    padding: 10px;
    text-align: center;
  } */
  .link-file-input {
    margin-top: 10px;
  }
</style>
