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
      mainCourses: Array,
      starters: Array,
      desserts: Array,
      bubbleWines: Array,
      whiteWines: Array,
      redWines: Array,
      softDrinks: Array,
      beers: Array,
      cocktails: Array,
      hotDrinks: Array,
      textTitle1: String,
      textSubtitle1: String,
      textContent1: String,
      textTitle2: String,
      textSubtitle2: String,
      textContent2: String
    },
    data() {
      return {
        menu: {
          textTitle1: this.textTitle1,
          textSubtitle1: this.textSubtitle1,
          textContent1: this.textContent1,
          textTitle2: this.textTitle2,
          textSubtitle2: this.textSubtitle2,
          textContent2: this.textContent2
        },
        isGeneratingPDF: false,
        isSubmitting: false,
        hasBeenSubmitted: false
      };
    },
    methods: {
      goto(path) {
        this.$go(path);
      },
      shortenUrl(url) {
        if (url.length > 25)
          return url.slice(0, 22) + "...";
        else
          return url;
      },
      submit() {
        this.isSubmitting = true;
        this.$api.post("/restaurant/menu/create", this.menu);
        setTimeout(() => {
          this.isSubmitting = false;
          this.hasBeenSubmitted = true;
          setTimeout(() => {
            this.hasBeenSubmitted = false;
          }, 5e3);
        }, 1500);
      },
      generate() {
        if (this.isGeneratingPDF)
          return;
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
              "Le PDF a été généré avec succès"
            );
          }, 1e3);
        };
        iframe.src = this.$api.endpoint + "/restaurant/menu/generate";
        setTimeout(() => {
          this.isGeneratingPDF = false;
        }, 2500);
      },
      updateOrder(listName) {
        this.$api.post(
          `/restaurant/menu/${listName}/reorder`,
          this[listName]
        );
        this.isSubmitting = true;
        setTimeout(() => {
          this.isSubmitting = false;
          this.hasBeenSubmitted = true;
          setTimeout(() => {
            this.hasBeenSubmitted = false;
          }, 5e3);
        }, 1500);
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Menu "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "icon": _vm.isSubmitting ? "loader" : "check", "theme": _vm.hasBeenSubmitted ? "green" : null, "variant": "filled" }, on: { "click": _vm.submit } }, [_vm._v(" Enregistrer ")]), _c("k-button", { attrs: { "icon": _vm.isGeneratingPDF ? "loader" : "wand", "disabled": _vm.isGeneratingPDF, "variant": "filled" }, on: { "click": _vm.generate } }, [_vm._v(" Générer PDF ")])], 1)], 1), _c("k-form", { attrs: { "fields": {
      textTitle1: {
        label: "Titre 1",
        type: "text",
        width: "1/2"
      },
      textSubtitle1: {
        label: "Sous-titre 1",
        type: "text",
        width: "1/2"
      },
      textContent1: {
        label: "Contenu 1",
        type: "textarea"
      },
      textTitle2: {
        label: "Titre 2",
        type: "text",
        width: "1/2"
      },
      textSubtitle2: {
        label: "Sous-titre 2",
        type: "text",
        width: "1/2"
      },
      textContent2: {
        label: "Contenu 2",
        type: "textarea"
      },
      line1: {
        type: "line"
      }
    } }, on: { "input": _vm.input, "submit": _vm.submit }, model: { value: _vm.menu, callback: function($$v) {
      _vm.menu = $$v;
    }, expression: "menu" } }), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Entrées")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/starter/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.starters, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("starters");
    } } }, _vm._l(_vm.starters, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/starter/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/starter/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Plats principaux")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/maincourse/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.mainCourses, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("mainCourses");
    } } }, _vm._l(_vm.mainCourses, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(
            `menu/maincourse/${item.id}/edit`
          )
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/maincourse/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Desserts")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/dessert/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.desserts, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("desserts");
    } } }, _vm._l(_vm.desserts, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/dessert/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/dessert/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Vins Pétillants")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/bubblewine/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.bubbleWines, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("bubbleWines");
    } } }, _vm._l(_vm.bubbleWines, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(
            `menu/bubblewine/${item.id}/edit`
          )
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/bubblewine/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Vins Blancs")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/whitewine/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.whiteWines, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("whiteWines");
    } } }, _vm._l(_vm.whiteWines, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(
            `menu/whitewine/${item.id}/edit`
          )
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/whitewine/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Vins Rouges")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/redwine/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.redWines, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("redWines");
    } } }, _vm._l(_vm.redWines, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/redwine/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/redwine/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Boissons froides")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/softdrink/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.softDrinks, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("softDrinks");
    } } }, _vm._l(_vm.softDrinks, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(
            `menu/softdrink/${item.id}/edit`
          )
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/softdrink/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Bières")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/beer/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.beers, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("beers");
    } } }, _vm._l(_vm.beers, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/beer/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/beer/${item.id}/delete`)
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Apéritives et digestives")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/cocktail/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.cocktails, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("cocktails");
    } } }, _vm._l(_vm.cocktails, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(
            `menu/cocktail/${item.id}/edit`
          )
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/cocktail/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Boissons Chaudes")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/hotdrink/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]), _c("k-draggable", { attrs: { "list": _vm.hotDrinks, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("hotDrinks");
    } } }, _vm._l(_vm.hotDrinks, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(
            `menu/hotdrink/${item.id}/edit`
          )
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/hotdrink/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1)], 1);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns
  );
  __component__.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/MenuView.vue";
  const MenuView = __component__.exports;
  panel.plugin("mediumsans/foodlab", {
    components: {
      "k-menu-view": MenuView
    }
  });
})();
