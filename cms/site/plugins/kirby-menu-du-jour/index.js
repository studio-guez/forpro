(function() {
  "use strict";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main = {
    props: {
      items: Array,
      foddLabItems: Array,
      foodcourtTexte: {
        type: String,
        default: ""
      },
      sliderImages: {
        type: Array,
        default: () => []
      }
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
        isItalicActive: false
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
              type: file.type
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
            order: list.map((img) => img.filename)
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
      onTexteInput(event) {
        const html = event;
        this.hasSaved = false;
        if (this.saveTimer) clearTimeout(this.saveTimer);
        this.saveTimer = setTimeout(() => {
          this.saveTexte(html);
        }, 800);
      },
      async saveTexte(value) {
        this.isSaving = true;
        try {
          await this.$api.post("menu-du-jour/foodcourt-texte", {
            texte: value
          });
          this.hasSaved = true;
          setTimeout(() => {
            this.hasSaved = false;
          }, 3e3);
        } catch (e) {
          window.panel.notification.error("Erreur lors de la sauvegarde");
        }
        this.isSaving = false;
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Menu de la semaine ")]), _c("k-headline", { staticClass: "k-menu-du-jour__title", attrs: { "tag": "h2" } }, [_vm._v("Image slider écran d'entrée")]), _c("hr", { staticStyle: { "width": "100%", "margin": "0.75rem 0", "border": ".5px solid var(--color-border)" } }), _vm.sliderImagesList.length ? _c("div", { staticClass: "k-slider-images__grid" }, _vm._l(_vm.sliderImagesList, function(image, index) {
      return _c("div", { key: image.filename, staticClass: "k-slider-images__item", class: { "k-slider-images__item--dragover": _vm.dragOverIndex === index }, attrs: { "draggable": "true" }, on: { "dragstart": function($event) {
        return _vm.onDragStart(index, $event);
      }, "dragover": function($event) {
        $event.preventDefault();
        return _vm.onDragOver(index);
      }, "dragleave": _vm.onDragLeave, "drop": function($event) {
        $event.preventDefault();
        return _vm.onDrop(index);
      }, "dragend": _vm.onDragEnd } }, [_c("img", { attrs: { "src": image.url, "alt": image.filename } }), _c("span", { staticClass: "k-slider-images__index" }, [_vm._v(_vm._s(index + 1))]), _c("k-button", { staticClass: "k-slider-images__delete", attrs: { "icon": "trash", "size": "xs", "variant": "filled", "theme": "negative" }, on: { "click": function($event) {
        return _vm.deleteSliderImage(image.filename);
      } } })], 1);
    }), 0) : _c("k-empty", { attrs: { "icon": "image" } }, [_vm._v(" Aucune image ")]), _c("k-button", { staticStyle: { "margin-top": "0.75rem" }, attrs: { "text": "Ajouter une image", "variant": "filled", "icon": "upload" }, on: { "click": function($event) {
      return _vm.$refs.sliderFileInput.click();
    } } }), _c("input", { ref: "sliderFileInput", staticStyle: { "display": "none" }, attrs: { "type": "file", "accept": "image/*", "multiple": "" }, on: { "change": _vm.uploadSliderImages } }), _c("k-headline", { staticClass: "k-menu-du-jour__title", attrs: { "tag": "h2" } }, [_vm._v("FoodCourt")]), _c("hr", { staticStyle: { "width": "100%", "margin": "0.75rem 0", "border": ".5px solid var(--color-border)" } }), _c("k-headline", { staticClass: "k-menu-du-jour__subtitle", attrs: { "tag": "h3" } }, [_vm._v("Menu de la semaine du FoodCourt")]), _c("k-button", { staticStyle: { "margin-bottom": "0.75rem" }, attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("menu-du-jour/create");
    } } }), _vm.items && _vm.items.length ? _c("table", { staticClass: "k-table k-menu-du-jour" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v("#")]), _c("th", [_vm._v("Date")]), _c("th", [_vm._v("Station 1")]), _c("th", [_vm._v("Station 2")]), _c("th", [_vm._v("Station 3")]), _c("th", [_vm._v("Station 4")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("tbody", _vm._l(_vm.items, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index + 1) + " ")]), _c("td", [_vm._v(_vm._s(item.date))]), _c("td", [_vm._v(_vm._s(item.station1_name || "–"))]), _c("td", [_vm._v(_vm._s(item.station2_name || "–"))]), _c("td", [_vm._v(_vm._s(item.station3_name || "–"))]), _c("td", [_vm._v(_vm._s(item.station4_name || "–"))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu-du-jour/${item.id}/edit`)
        },
        {
          text: "Dupliquer",
          icon: "copy",
          click: () => _vm.$dialog(`menu-du-jour/${item.id}/duplicate`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu-du-jour/${item.id}/delete`)
        }
      ] } })], 1)]);
    }), 0)]) : _c("k-empty", { attrs: { "icon": "calendar" }, on: { "click": function($event) {
      return _vm.$dialog("menu-du-jour/create");
    } } }, [_vm._v(" Aucun élément pour le FoodCourt ")]), _c("k-headline", { staticClass: "k-menu-du-jour__title", attrs: { "tag": "h2" } }, [_vm._v("FoodLab")]), _c("hr", { staticStyle: { "width": "100%", "margin": "0.75rem 0", "border": ".5px solid var(--color-border)" } }), _c("k-headline", { staticClass: "k-menu-du-jour__subtitle", attrs: { "tag": "h3" } }, [_vm._v("Menu de la semaine du FoodLab")]), _c("k-button", { staticStyle: { "margin-bottom": "0.75rem" }, attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("fodd-lab/create");
    } } }), _vm.foddLabItems && _vm.foddLabItems.length ? _c("table", { staticClass: "k-table k-fodd-lab" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v("#")]), _c("th", [_vm._v("Date")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("tbody", _vm._l(_vm.foddLabItems, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index + 1) + " ")]), _c("td", [_vm._v(_vm._s(item.date))]), _c("td", [_vm._v(_vm._s(item.prix || "–"))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`fodd-lab/${item.id}/edit`)
        },
        {
          text: "Dupliquer",
          icon: "copy",
          click: () => _vm.$dialog(`fodd-lab/${item.id}/duplicate`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`fodd-lab/${item.id}/delete`)
        }
      ] } })], 1)]);
    }), 0)]) : _c("k-empty", { attrs: { "icon": "calendar" }, on: { "click": function($event) {
      return _vm.$dialog("fodd-lab/create");
    } } }, [_vm._v(" Aucun élément pour le FoodLab ")]), _c("k-headline", { staticClass: "k-menu-du-jour__subtitle", attrs: { "tag": "h3" } }, [_vm._v(" Information dans le bas du menu imprimé du FoodLab "), _vm.isSaving ? _c("span", { staticStyle: { "opacity": "0.5", "font-weight": "normal" } }, [_vm._v(" – sauvegarde…")]) : _vm.hasSaved ? _c("span", { staticStyle: { "opacity": "0.5", "font-weight": "normal" } }, [_vm._v(" – sauvegardé")]) : _vm._e()]), _c("k-writer-input", { staticClass: "k-foodcourt-texte-editor", attrs: { "value": _vm.texteValue, "nodes": false, "marks": ["italic"], "inline": true }, on: { "input": function($event) {
      return _vm.onTexteInput($event);
    } } })], 1);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns
  );
  __component__.options.__file = "/Users/azertypow/IdeaProjects/forpro/cms/site/plugins/kirby-menu-du-jour/src/components/MenuDuJourView.vue";
  const MenuDuJourView = __component__.exports;
  panel.plugin("mediumsans/kirby-menu-du-jour", {
    components: {
      "k-menu-du-jour-view": MenuDuJourView
    }
  });
})();
