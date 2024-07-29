<template>
  <k-inside>
    <k-header>
      Menu Spécial
      <k-button-group slot="buttons">
        <k-button
            class="k-restaurant-button"
            :icon="isSubmitting ? 'loader' : 'check'"
            :theme="hasBeenSubmitted ? 'green' : null"
            variant="filled"
            @click="submit"
        >
          Enregistrer
        </k-button>
        <k-button-group layout="collapsed">
          <k-button
              :icon="isGeneratingPDF ? 'loader' : 'preview'"
              :disabled="isGeneratingPDF"
              variant="filled"
              @click="$refs.dropdown.toggle()"
          >
            Aperçu
          </k-button>
          <k-dropdown-content ref="dropdown" align-x="end">
            <k-dropdown-item
                icon="file-image"
                @click="generatePDF(true)"
            >Avec image et fond</k-dropdown-item>
            <k-dropdown-item
                icon="file-document"
                @click="generatePDF(false)"
            >Sans image et fond</k-dropdown-item>
          </k-dropdown-content>
        </k-button-group>
      </k-button-group>
    </k-header>

    <k-form
        v-model="menu"
        @input="input"
        @submit="submit"
        :fields="formFields"
    />

    <k-header style="margin-top: 20px;">
      Contenu
      <k-button-group slot="buttons">
        <k-button-group layout="collapsed">
          <k-button
              variant="filled"
              icon="plus"
              @click="addPage"
          >
            Ajouter une page
          </k-button>
        </k-button-group>
      </k-button-group>
    </k-header>

    <div v-for="page in menu.pages" :key="page.id">
      <k-grid style="margin-top: 40px">
        <div class="k-column" style="--width: 1/2; justify-self: start">
          <k-text>
            <h2>{{ page.title }}</h2>
          </k-text>
        </div>
        <div class="k-column" style="--width: 1/2; justify-self: end">
          <k-button-group>
            <k-button
                variant="filled"
                icon="trash"
                theme="negative"
                @click="deletePage(page.id)"
            >
            </k-button>
            <k-button-group layout="collapsed">
              <k-button
                  variant="filled"
                  icon="angle-down"
                  @click="movePageDown(page.id)"
              ></k-button>
              <k-button
                  variant="filled"
                  icon="angle-up"
                  @click="movePageUp(page.id)"
              ></k-button>
            </k-button-group>
          </k-button-group>
        </div>
      </k-grid>

      <!-- Vins section -->
      <k-grid style="margin-top: 40px">
        <div class="k-column" style="--width: 1/3; justify-self: start">
          <k-text>
            <h3>Vins</h3>
          </k-text>
        </div>
        <div class="k-column" style="--width: 2/3; justify-self: end">
          <k-button-group layout="collapsed">
            <k-button
                variant="filled"
                icon="plus"
                @click="$dialog('/menu/special/wine/add/' + page.id)"
            >
              Ajouter
            </k-button>
          </k-button-group>
        </div>
      </k-grid>
      <table class="k-table" style="margin-top: 20px; margin-bottom: 25px">
        <thead>
        <tr>
          <th class="k-table-index-column"></th>
          <th>Nom</th>
          <th>Domaine</th>
          <th>Millésime</th>
          <th>Description</th>
          <th class="k-table-options-column"></th>
        </tr>
        </thead>
        <k-draggable
            :list="page.wines"
            :handle="true"
            @change="updateOrder('wines', page.id)"
            :options="{
                  fallbackClass: 'k-table-row-fallback',
                  ghostClass: 'k-table-row-ghost',
              }"
            element="tbody"
        >
          <tr v-for="(item, index) in page.wines" :key="item.id">
            <td class="k-table-index-column" data-sortable="true">
              <span class="k-table-index">{{ index + 1 }}</span>
              <k-sort-handle />
            </td>
            <td>{{ item.name }}</td>
            <td>{{ item.domain }}</td>
            <td>{{ item.mill }}</td>
            <td>{{ item.description }}</td>
            <td class="k-table-options-column">
              <k-options-dropdown
                  :options="[
                      {
                          text: 'Modifier',
                          icon: 'edit',
                          click: () =>
                              $dialog(`menu/special/wine/${item.id}/edit/${page.id}`),
                      },
                      {
                          text: 'Supprimer',
                          icon: 'trash',
                          click: () =>
                              $dialog(`menu/special/wine/${item.id}/delete/${page.id}`),
                      },
                  ]"
              />
            </td>
          </tr>
        </k-draggable>
      </table>


      <!-- Menu section -->
      <k-grid style="margin-top: 40px">
        <div class="k-column" style="--width: 1/3; justify-self: start">
          <k-text>
            <h3>Plats</h3>
          </k-text>
        </div>
        <div class="k-column" style="--width: 2/3; justify-self: end">
          <k-button-group layout="collapsed">
            <k-button
                variant="filled"
                icon="plus"
                @click="$dialog('/menu/special/dish/add/' + page.id)"
            >
              Ajouter un plat ou choix
            </k-button>
          </k-button-group>
        </div>
      </k-grid>
      <k-grid>
        <div class="k-column" style="--width: 1/2; margin-top: 10px;">
          <k-input
              v-model="page.menuTitle"
              type="text"
              placeholder="Titre de la section"
              :icon="menuTitleIcon"
              @input="updateMenuTitle($event, page.id)"
          />
        </div>
        <div class="k-column" style="--width: 1/2; margin-top: 10px; margin-left: 10px;">
          <k-input
              v-model="page.menuDescription"
              type="text"
              placeholder="Description de la section"
              :icon="menuTitleIcon"
              @input="updateMenuDescription($event, page.id)"
          />
        </div>
      </k-grid>
      <table class="k-table" style="margin-top: 20px; margin-bottom: 25px">
        <thead>
          <tr>
            <th class="k-table-index-column"></th>
            <th>Plat</th>
            <th>Description</th>
            <th style="text-align: center;">Choix</th>
            <th>Plat</th>
            <th>Description</th>
            <th class="k-table-options-column"></th>
          </tr>
        </thead>
        <k-draggable
            :list="page.menu"
            :handle="true"
            @change="updateOrder('menu', page.id)"
            :options="{
                  fallbackClass: 'k-table-row-fallback',
                  ghostClass: 'k-table-row-ghost',
              }"
            element="tbody"
        >
          <tr v-for="(item, index) in page.dishes" :key="item.id">
            <td class="k-table-index-column" data-sortable="true">
              <span class="k-table-index">{{ index + 1 }}</span>
              <k-sort-handle />
            </td>
            <td>{{ item.name1 }}</td>
            <td>{{ item.description1 }}</td>
            <td style="text-align: center;">{{ item.option == true ? 'oui' : 'non' }}</td>
            <td>{{ item.name2 }}</td>
            <td>{{ item.description2 }}</td>
            <td class="k-table-options-column">
              <k-options-dropdown
                  :options="[
                      {
                          text: 'Modifier',
                          icon: 'edit',
                          click: () =>
                              $dialog(`menu/special/dish/${item.id}/edit/${page.id}`),
                      },
                      {
                          text: 'Supprimer',
                          icon: 'trash',
                          click: () =>
                              $dialog(`menu/special/dish/${item.id}/delete/${page.id}`),
                      },
                  ]"
              />
            </td>
          </tr>
        </k-draggable>
      </table>
    </div>
  </k-inside>
</template>

<script>
export default {
  props: {
    menu: {
      type: Array,
      default: () => ({
        pages: [
          {
            id: 1,
            title: "Page 1",
            menuTitle: "",
            menuDescription: "",
            wines: [],
            dishes: []
          }
        ],
        textInfo: "",
        partnerLogo: ""
      }),
    },
  },
  data() {
    return {
      isGeneratingPDF: false,
      isSubmitting: false,
      hasBeenSubmitted: false,
      isEditing: false,
      hasBeenEdited: false,
      formFields: {
        textInfo: {
          label: "Informations Contact",
          type: "textarea",
          width: "1/2",
        },
        partnerLogo: {
          label: "Logo partenaire",
          type: "files",
          max: 1,
          width: "1/2",
        },
      },
    };
  },
  methods: {
    submit() {
      this.isSubmitting = true;
      this.$api.post("/restaurant/menu/special/create", this.menu)
          .then(() => {
            this.isSubmitting = false;
            this.hasBeenSubmitted = true;
            this.$store.dispatch("notification/success", "Menu enregistré avec succès");
            setTimeout(() => {
              this.hasBeenSubmitted = false;
            }, 5000);
          })
          .catch(error => {
            this.isSubmitting = false;
            this.$store.dispatch("notification/error", "Erreur lors de l'enregistrement du menu");
            console.error("Error submitting menu:", error);
          });
    },
    generatePDF(withAssets = false) {
      if (this.isGeneratingPDF) return;

      this.isGeneratingPDF = true;

      const iframe = document.createElement("iframe");
      iframe.style.display = "none";
      document.body.appendChild(iframe);

      iframe.onload = () => {
        setTimeout(() => {
          document.body.removeChild(iframe);
          this.isGeneratingPDF = false;
          this.$store.dispatch(
              "notification/success",
              "Le PDF a été généré avec succès",
          );
        }, 1000);
      };

      let url =
          this.$api.endpoint +
          "/restaurant/menu/special/generate/" +
          (withAssets ? "with-assets" : "without-assets");

      iframe.src = url;
      setTimeout(() => {
        this.isGeneratingPDF = false;
      }, 2500);
    },
    updateOrder(category, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        this.updateMenuOnServer();
      }
    },
    addPage() {
      const newId = this.menu.pages.length + 1;
      this.menu.pages.push({
        id: newId,
        title: `Page ${newId}`,
        menuTitle: "",
        menuDescription: "",
        wines: [],
        dishes: []
      });
      this.updateMenuOnServer();
    },
    deletePage(pageId) {
      const index = this.menu.pages.findIndex(page => page.id === pageId);
      if (index !== -1) {
        this.menu.pages.splice(index, 1);
        this.updatePageTitles();
        this.updateMenuOnServer();
      }
    },
    movePageUp(pageId) {
      const index = this.menu.pages.findIndex(page => page.id === pageId);
      if (index > 0) {
        const temp = this.menu.pages[index];
        this.menu.pages.splice(index, 1);
        this.menu.pages.splice(index - 1, 0, temp);
        this.updatePageTitles();
        this.updateMenuOnServer();
      }
    },
    movePageDown(pageId) {
      const index = this.menu.pages.findIndex(page => page.id === pageId);
      if (index < this.menu.pages.length - 1) {
        const temp = this.menu.pages[index];
        this.menu.pages.splice(index, 1);
        this.menu.pages.splice(index + 1, 0, temp);
        this.updatePageTitles();
        this.updateMenuOnServer();
      }
    },
    updatePageTitles() {
      this.menu.pages.forEach((page, index) => {
        this.$set(page, 'title', `Page ${index + 1}`);
      });
    },
    updateMenuTitle(value, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        page.menuTitle = value;
        this.updateMenuOnServer();
      }
    },
    updateMenuDescription(value, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        page.menuDescription = value;
        this.updateMenuOnServer();
      }
    },
    updateMenuOnServer() {
      if (this.updateTimeout) {
        clearTimeout(this.updateTimeout);
      }
      this.updateTimeout = setTimeout(() => {
        this.$api.post("restaurant/menu/special/create", this.menu)
            .then(() => {
              this.$store.dispatch("notification/success", "Menu mis à jour avec succès");
            })
            .catch(error => {
              this.$store.dispatch("notification/error", "Erreur lors de la mise à jour du menu");
              console.error("Error updating menu:", error);
            });
      }, 500);
    },
  },
  computed: {
    menuTitleIcon() {
      if (this.hasBeenEdited) {
        return "check";
      } else if (this.isEditing) {
        return "loader";
      } else {
        return "edit";
      }
    },
  },
};
</script>

<style>
.k-restaurant-button[data-theme^="green"],
.k-restaurant-button[data-theme^="positive"] {
  color: black;
  background-color: hsl(80, 60%, calc(80% + -2.5%)) !important;
}
</style>
