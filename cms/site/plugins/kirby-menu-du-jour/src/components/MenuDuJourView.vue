<template>
  <k-inside>
    <k-header>
      Menu du jour

      <k-button-group slot="buttons">
        <k-button
          text="Ajouter"
          variant="filled"
          icon="add"
          @click="$dialog('menu-du-jour/create')"
        />
      </k-button-group>
    </k-header>

    <k-headline class="k-menu-du-jour__title">FoodCourt</k-headline>

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
          <td>{{ item.station1_menu || '–' }}</td>
          <td>{{ item.station2_menu || '–' }}</td>
          <td>{{ item.station3_menu || '–' }}</td>
          <td>{{ item.station4_menu || '–' }}</td>
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
    <k-headline class="k-menu-du-jour__title">FoodLab</k-headline>

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
          <th>Menu</th>
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
          <td>{{ item.menu || '–' }}</td>
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
    <k-headline class="k-menu-du-jour__title">
      FoodLab | Texte d'information
    </k-headline>


    <div class="k-foodcourt-texte" style="margin-top: 1.5rem">
      <label class="k-foodcourt-texte-label">
        <span v-if="isSaving" style="opacity: 0.5; font-weight: normal"> – sauvegarde…</span>
        <span v-else-if="hasSaved" style="opacity: 0.5; font-weight: normal"> – sauvegardé</span>
        <span v-else style="opacity: 0.5; font-weight: normal">&nbsp;</span>
      </label>
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
  },
  data() {
    return {
      texteValue: this.foodcourtTexte || "",
      saveTimer: null,
      isSaving: false,
      hasSaved: false,
      isItalicActive: false,
    };
  },
  methods: {
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
  margin-bottom: 0.75rem
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
</style>
