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
  const _sfc_main$1 = {
    props: {
      restaurant: {
        type: Object
      }
    },
    data() {
      return {
        restaurant: {
          picHero1: this.restaurant.picHero1,
          picHero2: this.restaurant.picHero2,
          picHero3: this.restaurant.picHero3,
          textHero1: this.restaurant.textHero1,
          titleFood: this.restaurant.titleFood,
          textFood: this.restaurant.textFood,
          fileFood1: this.restaurant.fileFood1,
          btnFood: this.restaurant.btnFood,
          titleLab: this.restaurant.titleLab,
          fileLab1: this.restaurant.fileLab1,
          textLab: this.restaurant.textLab,
          btnLab: this.restaurant.btnLab,
          picture1: this.restaurant.picture1,
          titleFormation: this.restaurant.titleFormation,
          textFormation: this.restaurant.textFormation,
          fileFormation: this.restaurant.fileFormation,
          btnFormation: this.restaurant.btnFormation,
          titleUnivers: this.restaurant.titleUnivers,
          subtitleUnivers: this.restaurant.subtitleUnivers,
          blogUniversTitle1: this.restaurant.blogUniversTitle1,
          blogUniversFil1: this.restaurant.blogUniversFil1,
          blogUniversText1: this.restaurant.blogUniversText1,
          blogUniversTitle2: this.restaurant.blogUniversTitle2,
          blogUniversFile2: this.restaurant.blogUniversFile2,
          blogUniversText2: this.restaurant.blogUniversText2,
          titleValues: this.restaurant.titleValues,
          textValues: this.restaurant.textValues,
          lstValues: this.restaurant.lstValues,
          textFooter1: this.restaurant.textFooter1,
          textFooter2: this.restaurant.textFooter2,
          textFooter3: this.restaurant.textFooter3,
          btnFooter1: this.restaurant.btnFooter1,
          btnFooter2: this.restaurant.btnFooter2
        }
      };
    },
    methods: {
      goto(path) {
        this.$go(path);
      },
      submit() {
        this.$api.post("/restaurant/update", this.restaurant);
      }
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Restaurant "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "icon": "check", "variant": "filled", "^": "" }, on: { "click": _vm.submit } }, [_vm._v(" Enregistrer ")])], 1)], 1), _c("k-form", { attrs: { "fields": {
      hero1: {
        type: "headline",
        label: "Héro 1"
      },
      btnHero1: {
        label: "Action 1",
        type: "text",
        width: "1/2"
      },
      btnHero2: {
        label: "Action 2",
        type: "text",
        width: "1/2"
      },
      picHero1: {
        label: "Image 1",
        type: "files",
        width: "1/2"
      },
      picHero2: {
        label: "Image 2",
        type: "files",
        width: "1/2"
      },
      picHero2: {
        label: "Image 3",
        type: "files",
        width: "1/2"
      },
      textHero1: {
        label: "Text Hero 1",
        type: "textarea",
        width: "1/2"
      },
      line1: {
        type: "line"
      },
      lefood: {
        type: "headline",
        label: "Le Food"
      },
      titleFood: {
        label: "Title 1",
        type: "text",
        width: "1"
      },
      textFood: {
        label: "Text Hero 1",
        type: "textarea",
        width: "1/2"
      },
      fileFood1: {
        label: "Image 1",
        type: "files",
        width: "1/2"
      },
      btnFood: {
        label: "Action 1",
        type: "text",
        width: "1/2"
      },
      line2: {
        type: "line"
      },
      lelab: {
        type: "headline",
        label: "Le Lab"
      },
      titleLab: {
        label: "Title 1",
        type: "text",
        width: "1"
      },
      fileLab1: {
        label: "Image 1",
        type: "files",
        width: "1/2"
      },
      textLab: {
        label: "Text Hero 1",
        type: "textarea",
        width: "1/2"
      },
      btnLab: {
        label: "Action 1",
        type: "text",
        width: "1/2"
      },
      line3: {
        type: "line"
      },
      picture1: {
        type: "files",
        label: "Image 1",
        width: "1"
      },
      line4: {
        type: "line"
      },
      formation: {
        type: "headline",
        label: "Equipe & Formation"
      },
      titleFormation: {
        label: "Title 1",
        type: "text",
        width: "1"
      },
      textFormation: {
        label: "Text Hero 1",
        type: "textarea",
        width: "1/2"
      },
      fileFormation: {
        label: "Image 1",
        type: "files",
        width: "1/2"
      },
      btnFormation: {
        label: "Action 1",
        type: "text",
        width: "1/2"
      },
      line5: {
        type: "line"
      },
      univers: {
        type: "headline",
        label: "Univers du Food"
      },
      titleUnivers: {
        label: "Title",
        type: "text",
        width: "1"
      },
      subtitleUnivers: {
        label: "Subtitle",
        type: "text",
        width: "1"
      },
      blogUniversTitle1: {
        label: "Title",
        type: "text",
        width: "1/2"
      },
      blogUniversFil1: {
        label: "Title",
        type: "text",
        width: "1/2"
      },
      blogUniversText1: {
        label: "Text",
        type: "textarea",
        width: "1"
      },
      blogUniversTitle2: {
        label: "Title",
        type: "text",
        width: "1/2"
      },
      blogUniversFile2: {
        label: "Title",
        type: "text",
        width: "1/2"
      },
      blogUniversText2: {
        label: "Text",
        type: "textarea",
        width: "1"
      },
      line6: {
        type: "line"
      },
      valuesHeadline: {
        type: "headline",
        label: "Engagement"
      },
      titleValues: {
        label: "Title",
        type: "text",
        width: "1"
      },
      textValues: {
        label: "Text",
        type: "textarea",
        width: "1"
      },
      lstValues: {
        label: "Liste",
        type: "structure",
        fields: {
          title: {
            label: "Titre",
            type: "text"
          },
          icon: {
            label: "Icon",
            type: "files"
          }
        }
      },
      line7: {
        type: "line"
      },
      footerHeadline: {
        type: "headline",
        label: "Footer"
      },
      textFooter1: {
        label: "Text 1",
        type: "textarea",
        width: "1/2"
      },
      textFooter2: {
        label: "Text 2",
        type: "textarea",
        width: "1/2"
      },
      textFooter3: {
        label: "Text 3",
        type: "textarea",
        width: "1/2"
      },
      btnFooter1: {
        label: "Action 1",
        type: "text",
        width: "1/2"
      },
      btnFooter2: {
        label: "Action 2",
        type: "text",
        width: "1/2"
      }
    } }, on: { "input": _vm.input, "submit": _vm.submit }, model: { value: _vm.restaurant, callback: function($$v) {
      _vm.restaurant = $$v;
    }, expression: "restaurant" } })], 1);
  };
  var _sfc_staticRenderFns$1 = [];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1
  );
  __component__$1.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/RestaurantView.vue";
  const RestaurantView = __component__$1.exports;
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
        }
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
        this.$api.post("/restaurant/menu/create", this.menu);
      },
      generate() {
        const headers = {
          "X-CSRF": this.$csrf
        };
        this.$api.get("/restaurant/menu/generate", {}, { responseType: "blob", headers }).then((response) => {
          const blob = new Blob([response.data], { type: "application/pdf" });
          const link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = "menu.pdf";
          link.click();
          window.URL.revokeObjectURL(link.href);
        }).catch((error) => {
          console.error("Error generating PDF:", error);
          this.$store.dispatch("notification/error", "Failed to generate PDF");
        });
      },
      async copyToClipboard(text) {
        try {
          await navigator.clipboard.writeText(text);
          window.panel.notification.success("URL du calendrier copiée dans le presse-papier");
        } catch (err) {
        }
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Menu "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "icon": "check", "variant": "filled", "^": "" }, on: { "click": _vm.submit } }, [_vm._v(" Enregistrer ")]), _c("k-button", { attrs: { "icon": "wand", "variant": "filled" }, on: { "click": _vm.generate } }, [_vm._v(" Générer PDF ")])], 1)], 1), _c("k-form", { attrs: { "fields": {
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
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.starters, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/starter/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/starter/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Plats principaux")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/maincourse/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.mainCourses, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/maincourse/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/maincourse/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Desserts")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/dessert/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.desserts, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/dessert/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/dessert/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Vins Pétillants")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/bubblewine/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.bubbleWines, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/bubblewine/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/bubblewine/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Vins Blancs")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/whitewine/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.whiteWines, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/whitewine/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/whitewine/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Vins Rouges")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/redwine/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.redWines, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/redwine/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/redwine/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Boissons froides")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/softdrink/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.softDrinks, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/softdrink/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/softdrink/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Bières")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/beer/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.beers, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Apéritives et digestives")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/cocktail/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.cocktails, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/cocktail/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/cocktail/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2), _c("k-bar", [_c("div", [_c("k-text", [_c("h4", [_vm._v("Boissons Chaudes")])])], 1), _c("div"), _c("div", [_c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$dialog("/menu/hotdrink/create");
    } } }, [_vm._v(" Ajouter ")])], 1)]), _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_c("tr", [_c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-index-column" })]), _vm._l(_vm.hotDrinks, function(item, id) {
      return _c("tr", { key: id }, [_c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/hotdrink/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`menu/hotdrink/${item.id}/delete`)
        }
      ] } })], 1)]);
    })], 2)], 1);
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
      "k-restaurant-view": RestaurantView,
      "k-menu-view": MenuView
    }
  });
})();
