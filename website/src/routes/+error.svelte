<script lang="ts">
  import {page} from "$app/stores";

</script>

<div class="s-error app-page-simple_content" >
  <h1>{$page.status}</h1>
  <p>{$page.error.message}</p>
  <a class="app-button app-button--rounded"
     href="/"
  >Retourner à la page d'accueil</a>
</div>


