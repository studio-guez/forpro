<template>
  <k-inside>

    <k-header>
      Menu
      <k-button-group slot="buttons">
        <k-button
            icon="check"
            variant="filled"
^           @click="submit"
        >
          Enregistrer
        </k-button>
        <k-button
            icon="wand"
            variant="filled"
            @click="generate"
        >
          Générer PDF
        </k-button>
      </k-button-group>
    </k-header>

    <k-form v-model="menu" @input="input" @submit="submit" :fields="{
        textTitle1: {
          label: 'Titre 1',
          type: 'text',
          width: '1/2'
        },
        textSubtitle1: {
          label: 'Sous-titre 1',
          type: 'text',
          width: '1/2'
        },
        textContent1: {
          label: 'Contenu 1',
          type: 'textarea'
        },
        textTitle2: {
          label: 'Titre 2',
          type: 'text',
          width: '1/2'
        },
        textSubtitle2: {
          label: 'Sous-titre 2',
          type: 'text',
          width: '1/2'
        },
        textContent2: {
          label: 'Contenu 2',
          type: 'textarea'
        },
        line1: {
         type: 'line'
        }
    }"/>

    <k-bar>
      <div>
        <k-text>
          <h4>Entrées</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/starter/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Plat</th>
        <th>Description</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in starters" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/starter/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/starter/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Plats principaux</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/maincourse/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Plat</th>
        <th>Description</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in mainCourses" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/maincourse/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/maincourse/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Desserts</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/dessert/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Plat</th>
        <th>Description</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in desserts" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/dessert/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/dessert/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Vins Pétillants</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/bubblewine/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Domaine</th>
        <th>Millésime</th>
        <th>Description</th>
        <th>10cl</th>
        <th>75cl</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in bubbleWines" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.domain }}</td>
        <td>{{ item.mill }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.price10cl }}</td>
        <td>{{ item.price75cl }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/bubblewine/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/bubblewine/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Vins Blancs</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/whitewine/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Domaine</th>
        <th>Millésime</th>
        <th>Description</th>
        <th>10cl</th>
        <th>75cl</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in whiteWines" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.domain }}</td>
        <td>{{ item.mill }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.price10cl }}</td>
        <td>{{ item.price75cl }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/whitewine/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/whitewine/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Vins Rouges</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/redwine/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Domaine</th>
        <th>Millésime</th>
        <th>Description</th>
        <th>10cl</th>
        <th>75cl</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in redWines" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.domain }}</td>
        <td>{{ item.mill }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.price10cl }}</td>
        <td>{{ item.price75cl }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/redwine/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/redwine/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Boissons froides</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/softdrink/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Volume</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in softDrinks" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.volume }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/softdrink/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/softdrink/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Bières</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/beer/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Volume</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in beers" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.volume }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/beer/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/beer/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Apéritives et digestives</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/cocktail/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Volume</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in cocktails" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.volume }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/cocktail/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/cocktail/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>

    <k-bar>
      <div>
        <k-text>
          <h4>Boissons Chaudes</h4>
        </k-text>
      </div>
      <div></div>
      <div>
        <k-button variant="filled" icon="plus"
                  @click="$dialog('/menu/hotdrink/create')">
          Ajouter
        </k-button>
      </div>
    </k-bar>

    <table style="margin-top: 20px;margin-bottom: 25px;" class="k-table">
      <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Volume</th>
        <th>Prix</th>
        <th class="k-table-index-column"></th>
      </tr>
      <tr v-for="(item, id) in hotDrinks" :key="id">
        <td>{{ item.name }}</td>
        <td>{{ item.description }}</td>
        <td>{{ item.volume }}</td>
        <td>{{ item.price }}</td>
        <td class="k-table-options-column">
          <k-options-dropdown :options="[
              {
                text: 'Modifier',
                icon: 'edit',
                click: () => $dialog(`menu/hotdrink/${item.id}/edit`)
              },
              {
                text: 'Supprimer',
                icon: 'trash',
                click: () => $dialog(`menu/hotdrink/${item.id}/delete`)
              },
            ]"/>
        </td>
      </tr>
    </table>
  </k-inside>
</template>

<script>
export default {
  props: {
    mainCourses:  Array,
    starters:     Array,
    desserts:     Array,
    bubbleWines:  Array,
    whiteWines:   Array,
    redWines:     Array,
    softDrinks:   Array,
    beers:        Array,
    cocktails:    Array,
    hotDrinks:    Array,
    textTitle1:   String,
    textSubtitle1: String,
    textContent1: String,
    textTitle2:   String,
    textSubtitle2: String,
    textContent2: String,
  },
  data() {
    return {
      menu: {
        textTitle1:    this.textTitle1,
        textSubtitle1: this.textSubtitle1,
        textContent1:  this.textContent1,
        textTitle2:    this.textTitle2,
        textSubtitle2: this.textSubtitle2,
        textContent2:  this.textContent2,
      },
    };
  },
  methods: {
    goto(path) {
      this.$go(path);
    },
    shortenUrl(url) {
      if (url.length > 25) return url.slice(0, 22) + '...';
      else return url;
    },
    submit() {
      this.$api.post('/restaurant/menu/create', this.menu);
    },
    generate() {
      const headers = {
        'X-CSRF': this.$csrf
      };

      this.$api.get('/restaurant/menu/generate', {}, { responseType: 'blob', headers: headers })
          .then(response => {

            const blob = new Blob([response.data], { type: 'application/pdf' });

            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'menu.pdf';
            link.click();

            window.URL.revokeObjectURL(link.href);
          })
          .catch(error => {
            console.error('Error generating PDF:', error);
            this.$store.dispatch('notification/error', 'Failed to generate PDF');
          });
    },
    async copyToClipboard(text) {
      try {
        await navigator.clipboard.writeText(text);
        window.panel.notification.success("URL du calendrier copiée dans le presse-papier");
      } catch (err) {
        // Show error message
      }
    },
  }
};
</script>

<style>
.k-table.k-calendar {
  table-layout: fixed;
}
</style>
