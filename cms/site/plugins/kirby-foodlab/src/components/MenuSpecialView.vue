<template>
  <k-panel-inside>
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
        :fields="mainFormFields"
    />

    <k-grid style="margin-top: 20px; margin-bottom: 10px;">
      <div class="k-column" style="--width: 1">
        <k-button
          :icon="menu.showPartner ? 'hidden' : 'preview'"
          :text="menu.showPartner ? 'Masquer Logo Partenaire' : 'Afficher Logo Partenaire'"
          @click="togglePartner"
          variant="filled"
        />
      </div>
    </k-grid>

    <k-form
        v-if="menu.showPartner"
        v-model="menu"
        @input="input"
        @submit="submit"
        :fields="partnerFormFields"
    />

    <k-label style="margin-top: 40px;">
      <span>Aperçu</span>
    </k-label>
    <div style="margin-top: 40px; margin-bottom: 40px; display: flex; width: 100%;">
      <iframe title="Preview" ref="htmlPreview" :srcdoc="html" style="margin: 0 auto; width: 1124px; height: 797px; border: 1px solid #ccc;"></iframe>
    </div>

    <k-header style="margin-top: 20px;">
      Contenu
      <k-button-group slot="buttons">
        <k-button-group layout="collapsed">
          <k-button
              variant="filled"
              icon="plus"
              @click="$refs.addPageDropdown.toggle()"
          >
            Ajouter une page
          </k-button>
          <k-dropdown-content ref="addPageDropdown" align-x="end">
            <k-dropdown-item icon="grid-left" @click="addPage('wines-dishes')">Vins - Menu</k-dropdown-item>
            <k-dropdown-item icon="grid-right" @click="addPage('dishes-wines')">Menu - Vins</k-dropdown-item>
            <k-dropdown-item icon="grid-full" @click="addPage('dishes-dishes')">Menu - Menu</k-dropdown-item>
          </k-dropdown-content>
        </k-button-group>
      </k-button-group>
    </k-header>

    <div v-for="page in menu.pages" :key="page.id">
      <k-grid style="margin-top: 40px">
        <div class="k-column" style="--width: 1/2; justify-self: start">
          <k-text>
            <h2>{{ page.title }}</h2>
          </k-text>
          <span class="page-layout-badge">{{ layoutLabel(page.layout) }}</span>
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

      <!-- Vins section with toggle (hidden for dishes-dishes layout) -->
      <k-grid v-if="(page.layout || 'wines-dishes') !== 'dishes-dishes'" style="margin-top: 40px">
        <div class="k-column" style="--width: 1/2; justify-self: start">
          <k-input
              :value="page.winesTitle"
              type="text"
              icon="edit"
              @input="updateWinesTitles($event, page.id)"
              :disabled="page.showWines === false"
              placeholder="Vins"
          />
        </div>
        <div class="k-column" style="--width: 1/2; justify-self: end">
          <k-button-group layout="collapsed">
            <k-button
                variant="filled"
                icon="plus"
                @click="$dialog('/menu/special/wine/add/' + page.id)"
                :disabled="page.showWines === false"
            >
              Ajouter un vin
            </k-button>
            <k-button
                :icon="page.showWines === false ? 'preview' : 'hidden'"
                :text="page.showWines === false ? 'Afficher' : 'Masquer'"
                @click="toggleWinesSection(page.id)"
                variant="filled"
            />
          </k-button-group>
        </div>
      </k-grid>
      <table v-if="(page.layout || 'wines-dishes') !== 'dishes-dishes'" class="k-table" style="margin-top: 20px; margin-bottom: 25px" :class="{ 'disabled-section': page.showWines === false }">
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
            disabled: page.showWines === false
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
                    disabled: page.showWines === false
                },
                {
                    text: 'Supprimer',
                    icon: 'trash',
                    click: () =>
                        $dialog(`menu/special/wine/${item.id}/delete/${page.id}`),
                    disabled: page.showWines === false
                },
            ]"
              />
            </td>
          </tr>
        </k-draggable>
      </table>

      <!-- Menu section with toggle -->
      <k-grid style="margin-top: 40px">
        <div class="k-column" style="--width: 1/2; justify-self: start">
          <k-input
              :value="page.dishesTitle"
              type="text"
              icon="edit"
              @input="updateDishesTitle($event, page.id)"
              :disabled="page.showDishes === false"
              placeholder="Menu"
          />
        </div>
        <div class="k-column" style="--width: 1/2; justify-self: end">
          <k-button-group layout="collapsed">
            <k-button
                variant="filled"
                icon="plus"
                @click="$dialog('/menu/special/dish/add/' + page.id)"
                :disabled="page.showDishes === false"
            >
              Ajouter un plat ou choix
            </k-button>
            <k-button
                :icon="page.showDishes === false ? 'preview' : 'hidden'"
                :text="page.showDishes === false ? 'Afficher' : 'Masquer'"
                @click="toggleDishesSection(page.id)"
                variant="filled"
            />
          </k-button-group>
        </div>
      </k-grid>
      <table class="k-table" style="margin-top: 20px; margin-bottom: 25px" :class="{ 'disabled-section': page.showDishes === false }">
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
            disabled: page.showDishes === false
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
                    disabled: page.showDishes === false
                },
                {
                    text: 'Supprimer',
                    icon: 'trash',
                    click: () =>
                        $dialog(`menu/special/dish/${item.id}/delete/${page.id}`),
                    disabled: page.showDishes === false
                },
            ]"
              />
            </td>
          </tr>
        </k-draggable>
      </table>

      <!-- Second dishes section (only for dishes-dishes layout) -->
      <template v-if="(page.layout || 'wines-dishes') === 'dishes-dishes'">
        <k-grid style="margin-top: 40px">
          <div class="k-column" style="--width: 1/2; justify-self: start">
            <k-input
                :value="page.dishesTitle2 || 'Plats 2'"
                type="text"
                icon="edit"
                @input="updateDishesTitle2($event, page.id)"
                :disabled="page.showDishes2 === false"
                placeholder="Menu 2"
            />
          </div>
          <div class="k-column" style="--width: 1/2; justify-self: end">
            <k-button-group layout="collapsed">
              <k-button
                  variant="filled"
                  icon="plus"
                  @click="$dialog('/menu/special/dish2/add/' + page.id)"
                  :disabled="page.showDishes2 === false"
              >
                Ajouter un plat ou choix
              </k-button>
              <k-button
                  :icon="page.showDishes2 === false ? 'preview' : 'hidden'"
                  :text="page.showDishes2 === false ? 'Afficher' : 'Masquer'"
                  @click="toggleDishesSection2(page.id)"
                  variant="filled"
              />
            </k-button-group>
          </div>
        </k-grid>
        <table class="k-table" style="margin-top: 20px; margin-bottom: 25px" :class="{ 'disabled-section': page.showDishes2 === false }">
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
              :list="page.dishes2 || []"
              :handle="true"
              @change="updateOrder('dishes2', page.id)"
              :options="{
              fallbackClass: 'k-table-row-fallback',
              ghostClass: 'k-table-row-ghost',
              disabled: page.showDishes2 === false
          }"
              element="tbody"
          >
            <tr v-for="(item, index) in (page.dishes2 || [])" :key="item.id">
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
                          $dialog(`menu/special/dish2/${item.id}/edit/${page.id}`),
                      disabled: page.showDishes2 === false
                  },
                  {
                      text: 'Supprimer',
                      icon: 'trash',
                      click: () =>
                          $dialog(`menu/special/dish2/${item.id}/delete/${page.id}`),
                      disabled: page.showDishes2 === false
                  },
              ]"
                />
              </td>
            </tr>
          </k-draggable>
        </table>
      </template>
    </div>
  </k-panel-inside>
</template>

<script>
export default {
  props: {
    menu: {
      type: Object,
      default: () => ({
        pages: [
          {
            id: 1,
            title: "Page 1",
            menuTitle: "",
            menuDescription: "",
            winesTitle: "Vins",
            dishesTitle: "Plats",
            showWines: true,
            showDishes: true,
            wines: [],
            dishes: []
          }
        ],
        qrUrl: "",
        textAboveQr: "",
        showPartner: false,
        partnerLogo: "",
        titlePartner: "",
        subtitlePartner: "",
        partnerLogoWidth: "",
        partnerLogoHeight: "",
        partnerLogoTop: "",
        partnerLogoBottom: "",
        partnerLogoLeft: "",
        partnerLogoRight: "",
      }),
    },
  },
  data() {
    return {
      isGeneratingPDF: false,
      isSubmitting: false,
      hasBeenSubmitted: false,
      updateTimeout: null,
      debouncedGetHtml: null,
      mainFormFields: {
        qrUrl: {
          label: "URL du QR Code",
          type: "text",
          width: "1/2",
          help: "L'URL vers laquelle le QR code redirigera. S'affiche seulement lors de la génération avec les images.",
          placeholder: "https://..."
        },
        textAboveQr: {
          label: "Texte au-dessus du QR",
          type: "text",
          width: "1/2",
          help: "Court texte affiché au-dessus du QR code sur la première page"
        },
      },
      partnerFormFields: {
        partnerLogo: {
          label: "Logo partenaire",
          type: "textarea",
          buttons: false,
          width: "1",
          size: "medium",
          help: "Insérer le contenu du SVG ici",
        },
        titlePartner: {
          label: "Titre",
          type: "text",
          width: "1/2",
          size: "small",
        },
        subtitlePartner: {
          label: "Sous-Titre",
          type: "text",
          width: "1/2",
          size: "small",
        },
        textPartner: {
          label: "Text",
          type: "text",
          width: "1",
        },
        partnerLogoWidth: {
          label: "Grandeur",
          type: "range",
          width: "1",
          tooltip: {
            after: "px"
          },
          min: 100,
          max: 500,
        },
        partnerLogoTop: {
          label: "Position Haut",
          type: "range",
          width: "1/2",
          tooltip: {
            after: "px"
          },
          min: 0,
          max: 500,
          help: "0 positionne le logo en haut de la page",
        },
        partnerLogoRight: {
          label: "Position Droite",
          type: "range",
          width: "1/2",
          tooltip: {
            after: "px"
          },
          min: 0,
          max: 500,
          help: "0 positionne le logo à droite de la page",
        },
      },
      html: '',
    };
  },
  created() {
    // Create debounced versions of methods
    this.debouncedGetHtml = this.debounce(this.getHtml, 500);
    this.debouncedUpdateMenu = this.debounce(this.updateMenuOnServer, 500);
  },
  mounted() {
    this.getHtml();
  },
  beforeDestroy() {
    // Clean up any pending timeouts
    if (this.updateTimeout) {
      clearTimeout(this.updateTimeout);
    }
  },
  watch: {
    // Only watch specific properties that should trigger updates
    'menu.qrUrl': 'debouncedUpdateMenu',
    'menu.textAboveQr': 'debouncedUpdateMenu',
    'menu.partnerLogo': 'debouncedUpdateMenu',
    'menu.titlePartner': 'debouncedUpdateMenu',
    'menu.subtitlePartner': 'debouncedUpdateMenu',
  },
  methods: {
    layoutLabel(layout) {
      const labels = {
        'wines-dishes': 'Vins - Menu',
        'dishes-wines': 'Menu - Vins',
        'dishes-dishes': 'Menu - Menu'
      };
      return labels[layout] || labels['wines-dishes'];
    },

    // Utility function for debouncing
    debounce(fn, wait) {
      let timeout;
      return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), wait);
      };
    },

    togglePartner() {
      this.$set(this.menu, 'showPartner', !this.menu.showPartner);
      this.debouncedUpdateMenu();
    },

    // Form input handler
    input() {
      this.debouncedUpdateMenu();
    },

    // Updated method for updating wine titles
    updateWinesTitles(value, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        this.$set(page, 'winesTitle', value);
        this.debouncedUpdateMenu();
      }
    },

    // Updated method for updating dishes title
    updateDishesTitle(value, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        this.$set(page, 'dishesTitle', value);
        this.debouncedUpdateMenu();
      }
    },

    // Layout selector
    updatePageLayout(value, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        this.$set(page, 'layout', value);
        // Initialize dishes2 array if switching to dishes-dishes layout
        if (value === 'dishes-dishes' && !page.dishes2) {
          this.$set(page, 'dishes2', []);
          this.$set(page, 'dishesTitle2', 'Plats 2');
          this.$set(page, 'showDishes2', true);
        }
        this.debouncedUpdateMenu();
      }
    },

    // Dishes 2 methods
    updateDishesTitle2(value, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        this.$set(page, 'dishesTitle2', value);
        this.debouncedUpdateMenu();
      }
    },

    toggleDishesSection2(pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        const newValue = page.showDishes2 === false;
        this.$set(page, 'showDishes2', newValue);
        this.debouncedUpdateMenu();
        if (newValue) {
          this.$panel.notification.success("Section Plats 2 activée");
        } else {
          this.$panel.notification.info("Section Plats 2 désactivée");
        }
      }
    },

    submit() {
      if (this.isSubmitting) return;

      this.isSubmitting = true;
      this.$api.post("/restaurant/menu/special/create", this.menu)
          .then(() => {
            this.isSubmitting = false;
            this.hasBeenSubmitted = true;
            this.$panel.notification.success("Menu enregistré avec succès");
            setTimeout(() => {
              this.hasBeenSubmitted = false;
            }, 5000);
            this.getHtml();
          })
          .catch(error => {
            this.isSubmitting = false;
            this.$panel.notification.error("Erreur lors de l'enregistrement du menu");
            console.error("Error submitting menu:", error);
          });
    },

    getHtml() {
      this.$api.get("/restaurant/menu/special/html")
          .then(response => {
            // Sanitize the HTML content by wrapping it in a restrictive container
            this.html = `
            <html>
              <head>
                <style>
                  body {
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                  }
                  .content {
                    width: 100%;
                    height: 100%;
                  }
                </style>
              </head>
              <body>
                <div class="content">
                  ${response}
                </div>
              </body>
            </html>
          `;
          })
          .catch(error => {
            this.$panel.notification.error("Erreur lors de la récupération du HTML");
            console.error("Error getting HTML:", error);
          });
    },

    generatePDF(withAssets = false) {
      if (this.isGeneratingPDF) return;

      this.isGeneratingPDF = true;

      // Ensure we have the latest menu saved before generating PDF
      this.submit();

      const url = this.$api.endpoint +
          "/restaurant/menu/special/generate/" +
          (withAssets ? "with-assets" : "without-assets");

      // Use a more reliable approach to open the PDF
      const pdfWindow = window.open(url, '_blank');

      // Set a timeout to reset the state, but also handle cases where
      // the window couldn't be opened (e.g., popup blocked)
      if (pdfWindow) {
        setTimeout(() => {
          this.isGeneratingPDF = false;
          this.$panel.notification.success("Le PDF a été généré avec succès"
          );
        }, 1500);
      } else {
        this.isGeneratingPDF = false;
        this.$panel.notification.error("Le PDF n'a pas pu être généré. Vérifiez que les pop-ups sont autorisés."
        );
      }
    },

    updateOrder(category, pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        this.debouncedUpdateMenu();
      }
    },

    addPage(layout = 'wines-dishes') {
      if (!this.menu.pages) {
        this.menu.pages = [];
      }

      // Use maximum existing ID + 1 for better reliability
      const maxId = this.menu.pages.reduce((max, page) => Math.max(max, page.id), 0);
      const newId = maxId + 1;

      this.menu.pages.push({
        id: newId,
        title: `Page ${this.menu.pages.length + 1}`,
        layout: layout,
        menuTitle: "",
        menuDescription: "",
        winesTitle: "Vins",
        dishesTitle: "Plats",
        showWines: true,
        showDishes: true,
        wines: [],
        dishes: [],
        dishesTitle2: "Plats 2",
        showDishes2: true,
        dishes2: []
      });

      this.debouncedUpdateMenu();
    },

// Toggle visibility of Wines section
    toggleWinesSection(pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        // Toggle the current value
        const newValue = page.showWines === false;
        this.$set(page, 'showWines', newValue);
        this.debouncedUpdateMenu();

        // Show appropriate notification
        if (newValue) {
          this.$panel.notification.success("Section Vins activée");
        } else {
          this.$panel.notification.info("Section Vins désactivée");
        }
      }
    },

// Toggle visibility of Dishes section
    toggleDishesSection(pageId) {
      const page = this.menu.pages.find(p => p.id === pageId);
      if (page) {
        // Toggle the current value
        const newValue = page.showDishes === false;
        this.$set(page, 'showDishes', newValue);
        this.debouncedUpdateMenu();

        // Show appropriate notification
        if (newValue) {
          this.$panel.notification.success("Section Plats activée");
        } else {
          this.$panel.notification.info("Section Plats désactivée");
        }
      }
    },

    deletePage(pageId) {
      // Don't allow deleting the last page
      if (this.menu.pages.length <= 1) {
        this.$panel.notification.error("Impossible de supprimer la dernière page");
        return;
      }

      const index = this.menu.pages.findIndex(page => page.id === pageId);
      if (index !== -1) {
        this.menu.pages.splice(index, 1);
        this.updatePageTitles();
        this.debouncedUpdateMenu();
      }
    },

    movePageUp(pageId) {
      const index = this.menu.pages.findIndex(page => page.id === pageId);
      if (index > 0) {
        const temp = this.menu.pages[index];
        this.$set(this.menu.pages, index, this.menu.pages[index - 1]);
        this.$set(this.menu.pages, index - 1, temp);
        this.updatePageTitles();
        this.debouncedUpdateMenu();
      }
    },

    movePageDown(pageId) {
      const index = this.menu.pages.findIndex(page => page.id === pageId);
      if (index < this.menu.pages.length - 1) {
        const temp = this.menu.pages[index];
        this.$set(this.menu.pages, index, this.menu.pages[index + 1]);
        this.$set(this.menu.pages, index + 1, temp);
        this.updatePageTitles();
        this.debouncedUpdateMenu();
      }
    },

    updatePageTitles() {
      this.menu.pages.forEach((page, index) => {
        this.$set(page, 'title', `Page ${index + 1}`);
      });
    },

    updateMenuOnServer() {
      this.$api.post("restaurant/menu/special/create", this.menu)
          .then(() => {
            // Only get HTML after successful update
            this.debouncedGetHtml();
          })
          .catch(error => {
            this.$panel.notification.error("Erreur lors de la mise à jour du menu");
            console.error("Error updating menu:", error);
          });
    },
  }
};
</script>

<style>
.k-restaurant-button[data-theme^="green"],
.k-restaurant-button[data-theme^="positive"] {
  color: black;
  background-color: hsl(80, 60%, calc(80% + -2.5%)) !important;
}

/* Additional styles for improved UI */
.k-table {
  width: 100%;
}

/* Add transitions for smoother UI */
.k-button, .k-dropdown-item {
  transition: background-color 0.2s ease;
}

.disabled-section {
  opacity: 0.6;
  pointer-events: none;
  position: relative;
}

.disabled-section::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(255, 255, 255, 0.2);
  z-index: 1;
}

/* Layout badge under page title */
.page-layout-badge {
  display: inline-block;
  margin-top: 4px;
  padding: 2px 10px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #fff;
  background-color: #ff5300;
  border-radius: 3px;
}

/* Button transition for smoother toggle */
.k-button {
  transition: all 0.2s ease-in-out;
}
</style>