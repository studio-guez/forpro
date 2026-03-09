<template>
  <k-inside>
    <k-header>
      Menu du jour
    </k-header>

    <!-- Image slider écran d'entrée -->
    <k-headline
      class="k-menu-du-jour__title"
      tag="h2"
    >Image slider écran d'entrée</k-headline>

    <hr style="width: 100%; margin: 0.75rem 0; border: .5px solid var(--color-border);"/>

    <div class="k-slider-images__grid" v-if="sliderImagesList.length">
      <div
        v-for="(image, index) in sliderImagesList"
        :key="image.filename"
        class="k-slider-images__item"
        :class="{ 'k-slider-images__item--dragover': dragOverIndex === index }"
        draggable="true"
        @dragstart="onDragStart(index, $event)"
        @dragover.prevent="onDragOver(index)"
        @dragleave="onDragLeave"
        @drop.prevent="onDrop(index)"
        @dragend="onDragEnd"
      >
        <img :src="image.url" :alt="image.filename" />
        <span class="k-slider-images__index">{{ index + 1 }}</span>
        <k-button
          class="k-slider-images__delete"
          icon="trash"
          size="xs"
          variant="filled"
          theme="negative"
          @click="deleteSliderImage(image.filename)"
        />
      </div>
    </div>

    <k-empty v-else icon="image">
      Aucune image
    </k-empty>

    <k-button
      text="Ajouter une image"
      variant="filled"
      icon="upload"
      style="margin-top: 0.75rem"
      @click="$refs.sliderFileInput.click()"
    />
    <input
      ref="sliderFileInput"
      type="file"
      accept="image/*"
      multiple
      style="display: none"
      @change="uploadSliderImages"
    />

    <k-headline
      class="k-menu-du-jour__title"
      tag="h2"
    >FoodCourt</k-headline>

    <hr style="width: 100%; margin: 0.75rem 0; border: .5px solid var(--color-border);"/>

    <k-headline
      class="k-menu-du-jour__subtitle"
      tag="h3"
    >Menu de la semaine du FoodCourt</k-headline>

    <k-button
      text="Ajouter"
      variant="filled"
      icon="add"
      style="margin-bottom: 0.75rem"
      @click="$dialog('menu-du-jour/create')"
    />

    <table class="k-table k-menu-du-jour" v-if="items && items.length">
      <thead>
        <tr>
          <th class="k-table-index-column" style="text-align: center">#</th>
          <th>Date</th>
          <th>Station 1</th>
          <th>Station 2</th>
          <th>Station 3</th>
          <th>Station 4</th>
          <th class="k-table-options-column"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="item.id">
          <td class="k-table-index-column" style="text-align: center">
            {{ index + 1 }}
          </td>
          <td>{{ item.date }}</td>
          <td>{{ item.station1_name || '–' }}</td>
          <td>{{ item.station2_name || '–' }}</td>
          <td>{{ item.station3_name || '–' }}</td>
          <td>{{ item.station4_name || '–' }}</td>
          <td class="k-table-options-column">
            <k-options-dropdown
              :options="[
                {
                  text: 'Modifier',
                  icon: 'edit',
                  click: () => $dialog(`menu-du-jour/${item.id}/edit`),
                },
                {
                  text: 'Dupliquer',
                  icon: 'copy',
                  click: () => $dialog(`menu-du-jour/${item.id}/duplicate`),
                },
                {
                  text: 'Supprimer',
                  icon: 'trash',
                  click: () => $dialog(`menu-du-jour/${item.id}/delete`),
                },
              ]"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <k-empty v-else icon="calendar" @click="$dialog('menu-du-jour/create')">
      Aucun élément pour le FoodCourt
    </k-empty>

    <!-- FoodLab -->
    <k-headline
      class="k-menu-du-jour__title"
      tag="h2"
    >FoodLab</k-headline>

    <hr style="width: 100%; margin: 0.75rem 0; border: .5px solid var(--color-border);"/>

    <k-headline
      class="k-menu-du-jour__subtitle"
      tag="h3"
    >Menu de la semaine du FoodLab</k-headline>

    <k-button
      text="Ajouter"
      variant="filled"
      icon="add"
      style="margin-bottom: 0.75rem"
      @click="$dialog('fodd-lab/create')"
    />

    <table class="k-table k-fodd-lab" v-if="foddLabItems && foddLabItems.length">
      <thead>
        <tr>
          <th class="k-table-index-column" style="text-align: center">#</th>
          <th>Date</th>
          <th>Prix</th>
          <th class="k-table-options-column"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in foddLabItems" :key="item.id">
          <td class="k-table-index-column" style="text-align: center">
            {{ index + 1 }}
          </td>
          <td>{{ item.date }}</td>
          <td>{{ item.prix || '–' }}</td>
          <td class="k-table-options-column">
            <k-options-dropdown
              :options="[
                {
                  text: 'Modifier',
                  icon: 'edit',
                  click: () => $dialog(`fodd-lab/${item.id}/edit`),
                },
                {
                  text: 'Dupliquer',
                  icon: 'copy',
                  click: () => $dialog(`fodd-lab/${item.id}/duplicate`),
                },
                {
                  text: 'Supprimer',
                  icon: 'trash',
                  click: () => $dialog(`fodd-lab/${item.id}/delete`),
                },
              ]"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <k-empty v-else icon="calendar" @click="$dialog('fodd-lab/create')">
      Aucun élément pour le FoodLab
    </k-empty>

    <!-- Texte writer field -->
    <k-headline
      class="k-menu-du-jour__subtitle"
      tag="h3"
    >
      Information dans le bas du menu imprimé du FoodLab
      <span v-if="isSaving" style="opacity: 0.5; font-weight: normal"> – sauvegarde…</span>
      <span v-else-if="hasSaved" style="opacity: 0.5; font-weight: normal"> – sauvegardé</span>
    </k-headline>


    <div class="k-foodcourt-texte">
      <div class="k-foodcourt-texte-toolbar">
        <k-button
          icon="italic"
          :variant="isItalicActive ? 'filled' : 'dimmed'"
          size="xs"
          title="Italique"
          @click="toggleItalic"
        />
      </div>
      <div
        ref="editor"
        class="k-foodcourt-texte-editor"
        contenteditable="true"
        @input="onTexteInput"
        v-html="texteValue"
      ></div>
    </div>
  </k-inside>
</template>

<script>
export default {
  props: {
    items: Array,
    foddLabItems: Array,
    foodcourtTexte: {
      type: String,
      default: "",
    },
    sliderImages: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      sliderImagesList: this.sliderImages || [],
      dragFromIndex: null,
      dragOverIndex: null,
      texteValue: this.foodcourtTexte || "",
      saveTimer: null,
      isSaving: false,
      hasSaved: false,
      isItalicActive: false,
    };
  },
  methods: {
    fileToBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result.split(",")[1]);
        reader.onerror = reject;
        reader.readAsDataURL(file);
      });
    },
    async uploadSliderImages(event) {
      const files = event.target.files;
      if (!files || files.length === 0) return;

      for (const file of files) {
        if (file.size > 10 * 1024 * 1024) {
          window.panel.notification.error(
            `${file.name} est trop volumineux (max 10 Mo)`
          );
          continue;
        }

        try {
          const base64 = await this.fileToBase64(file);
          const result = await this.$api.post("menu-du-jour/slider-images", {
            filename: file.name,
            data: base64,
            type: file.type,
          });
          this.sliderImagesList.push(result);
          window.panel.notification.success("Image ajoutée");
        } catch (e) {
          window.panel.notification.error(`Erreur: ${file.name}`);
        }
      }

      event.target.value = "";
    },
    onDragStart(index, event) {
      this.dragFromIndex = index;
      event.dataTransfer.effectAllowed = "move";
    },
    onDragOver(index) {
      this.dragOverIndex = index;
    },
    onDragLeave() {
      this.dragOverIndex = null;
    },
    onDragEnd() {
      this.dragFromIndex = null;
      this.dragOverIndex = null;
    },
    async onDrop(toIndex) {
      const fromIndex = this.dragFromIndex;
      this.dragFromIndex = null;
      this.dragOverIndex = null;

      if (fromIndex === null || fromIndex === toIndex) return;

      const list = [...this.sliderImagesList];
      const [moved] = list.splice(fromIndex, 1);
      list.splice(toIndex, 0, moved);
      this.sliderImagesList = list;

      try {
        await this.$api.post("menu-du-jour/slider-images/order", {
          order: list.map((img) => img.filename),
        });
      } catch (e) {
        window.panel.notification.error("Erreur lors du réordonnancement");
      }
    },
    async deleteSliderImage(filename) {
      try {
        await this.$api.delete(
          "menu-du-jour/slider-images/" + encodeURIComponent(filename)
        );
        this.sliderImagesList = this.sliderImagesList.filter(
          (img) => img.filename !== filename
        );
        window.panel.notification.success("Image supprimée");
      } catch (e) {
        window.panel.notification.error("Erreur lors de la suppression");
      }
    },
    onTexteInput() {
      const html = this.$refs.editor.innerHTML;
      this.hasSaved = false;

      if (this.saveTimer) clearTimeout(this.saveTimer);

      this.saveTimer = setTimeout(() => {
        this.saveTexte(html);
      }, 800);
    },
    toggleItalic() {
      document.execCommand("italic", false, null);
      this.$refs.editor.focus();
      this.checkItalicState();
      this.onTexteInput();
    },
    checkItalicState() {
      this.isItalicActive = document.queryCommandState("italic");
    },
    async saveTexte(value) {
      this.isSaving = true;
      try {
        await this.$api.post("menu-du-jour/foodcourt-texte", {
          texte: value,
        });
        this.hasSaved = true;
        setTimeout(() => {
          this.hasSaved = false;
        }, 3000);
      } catch (e) {
        window.panel.notification.error("Erreur lors de la sauvegarde");
      }
      this.isSaving = false;
    },
  },
  mounted() {
    if (this.$refs.editor) {
      this.$refs.editor.addEventListener("mouseup", this.checkItalicState);
      this.$refs.editor.addEventListener("keyup", this.checkItalicState);
    }
  },
};
</script>

<style>
.k-table.k-menu-du-jour,
.k-table.k-fodd-lab {
  table-layout: fixed;
}

.k-menu-du-jour__title {
  margin-top: 3rem;
  margin-bottom: 0.75rem;
  font-size: 1.5rem;
}

.k-menu-du-jour__subtitle {
  margin-top: 1rem;
  margin-bottom: 0.75rem;
}

.k-foodcourt-texte {
  margin-top: 0 !important;
}

.k-foodcourt-texte-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.k-foodcourt-texte-toolbar {
  margin-bottom: 0.25rem;
}

.k-foodcourt-texte-editor {
  background: var(--color-white);
  border: 1px solid var(--color-border);
  border-radius: var(--rounded);
  padding: 0.5rem 0.75rem;
  min-height: 5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  outline: none;
}

.k-foodcourt-texte-editor:focus {
  border-color: var(--color-focus);
  box-shadow: 0 0 0 2px var(--color-focus-outline);
}

.k-foodcourt-texte-editor p {
  margin: 0 0 0.5em;
}

.k-foodcourt-texte-editor p:last-child {
  margin-bottom: 0;
}

.k-slider-images__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 0.75rem;
}

.k-slider-images__item {
  position: relative;
  border-radius: var(--rounded);
  overflow: hidden;
  border: 2px solid var(--color-border);
  background: var(--color-white);
  cursor: grab;
  transition: border-color 0.15s, opacity 0.15s;
}

.k-slider-images__item:active {
  cursor: grabbing;
}

.k-slider-images__item--dragover {
  border-color: var(--color-focus);
  box-shadow: 0 0 0 2px var(--color-focus-outline);
}

.k-slider-images__item img {
  width: 100%;
  height: 120px;
  object-fit: cover;
  display: block;
  pointer-events: none;
}

.k-slider-images__index {
  position: absolute;
  top: 0.25rem;
  left: 0.25rem;
  background: rgba(0, 0, 0, 0.6);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 600;
  width: 1.25rem;
  height: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--rounded);
  pointer-events: none;
}

.k-slider-images__delete {
  position: absolute;
  top: 0.25rem;
  right: 0.25rem;
}

.k-dialog[data-size=full] {
  width: 100%;
}
</style>
