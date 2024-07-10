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
  const _sfc_main$b = {
    props: {
      mainCourses: Array,
      mainCoursesShowHide: Boolean,
      mainCoursesTitle: String,
      starters: Array,
      startersShowHide: Boolean,
      startersTitle: String,
      desserts: Array,
      dessertsShowHide: Boolean,
      dessertsTitle: String,
      bubbleWines: Array,
      bubbleWinesShowHide: Boolean,
      bubbleWinesTitle: String,
      whiteWines: Array,
      whiteWinesShowHide: Boolean,
      whiteWinesTitle: String,
      redWines: Array,
      redWinesShowHide: Boolean,
      redWinesTitle: String,
      softDrinks: Array,
      softDrinksShowHide: Boolean,
      softDrinksTitle: String,
      beers: Array,
      beersShowHide: Boolean,
      beersTitle: String,
      cocktails: Array,
      cocktailsShowHide: Boolean,
      cocktailsTitle: String,
      hotDrinks: Array,
      hotDrinksShowHide: Boolean,
      hotDrinksTitle: String,
      textTitle1: String,
      textSubtitle1: String,
      textContent1: String,
      textTitle2: String,
      textSubtitle2: String,
      textContent2: String,
      pageTitle1: String,
      pageTitle2: String,
      pageTitle3: String,
      page2Order: Array,
      page3Order: Array,
      page4Order: Array
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
        hasBeenSubmitted: false,
        isEditing: false,
        hasBeenEdited: false,
        formFields: {
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
          }
        },
        page2Order: this.page2Order,
        page3Order: this.page3Order,
        page4Order: this.page4Order,
        pageTitle2: this.pageTitle2,
        pageTitle3: this.pageTitle3,
        pageTitle4: this.pageTitle4
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
      updateTableOrder(category) {
        const listName = this.getSectionProp(category);
        this.$api.post(`/restaurant/menu/${category}/reorder`, this[listName]).then(() => {
          this.$store.dispatch(
            "notification/success",
            "Order updated successfully"
          );
        }).catch((error) => {
          console.error("Error updating order:", error);
          this.$store.dispatch(
            "notification/error",
            "Failed to update order"
          );
        });
      },
      moveSectionUp(category) {
        const { section, page } = this.getSectionAndNumber(category);
        const index = section.indexOf(category);
        if (index > 0) {
          const newOrder = [...section];
          [newOrder[index - 1], newOrder[index]] = [
            newOrder[index],
            newOrder[index - 1]
          ];
          this.updateSectionOrder(newOrder, page);
        }
      },
      moveSectionDown(category) {
        const { section, page } = this.getSectionAndNumber(category);
        const index = section.indexOf(category);
        if (index < section.length - 1) {
          const newOrder = [...section];
          [newOrder[index], newOrder[index + 1]] = [
            newOrder[index + 1],
            newOrder[index]
          ];
          this.updateSectionOrder(newOrder, page);
        }
      },
      getSectionAndNumber(category) {
        if (this.page2Order.includes(category))
          return { section: this.page2Order, page: 2 };
        if (this.page3Order.includes(category))
          return { section: this.page3Order, page: 3 };
        if (this.page4Order.includes(category))
          return { section: this.page4Order, page: 4 };
        return { section: [], page: null };
      },
      updatePageTitle2(value) {
        this.isEditing = true;
        this.$api.post("/restaurant/menu/page-title-2", { value }).then(() => {
          this.pageTitle2 = value;
          this.isEditing = false;
          this.hasBeenEdited = true;
          setTimeout(() => {
            this.hasBeenEdited = false;
          }, 2e3);
          this.$store.dispatch(
            "notification/success",
            "Page title updated successfully"
          );
        }).catch((error) => {
          console.error("Error updating page title:", error);
          this.isEditing = false;
          this.$store.dispatch(
            "notification/error",
            "Failed to update page title"
          );
        });
      },
      updatePageTitle3(value) {
        this.isEditing = true;
        this.$api.post("/restaurant/menu/page-title-3", { value }).then(() => {
          this.pageTitle3 = value;
          this.isEditing = false;
          this.hasBeenEdited = true;
          setTimeout(() => {
            this.hasBeenEdited = false;
          }, 2e3);
          this.$store.dispatch(
            "notification/success",
            "Page title updated successfully"
          );
        }).catch((error) => {
          console.error("Error updating page title:", error);
          this.isEditing = false;
          this.$store.dispatch(
            "notification/error",
            "Failed to update page title"
          );
        });
      },
      updatePageTitle4(value) {
        this.isEditing = true;
        this.$api.post("/restaurant/menu/page-title-4", { value }).then(() => {
          this.pageTitle4 = value;
          this.isEditing = false;
          this.hasBeenEdited = true;
          setTimeout(() => {
            this.hasBeenEdited = false;
          }, 2e3);
          this.$store.dispatch(
            "notification/success",
            "Page title updated successfully"
          );
        }).catch((error) => {
          console.error("Error updating page title:", error);
          this.isEditing = false;
          this.$store.dispatch(
            "notification/error",
            "Failed to update page title"
          );
        });
      },
      updateSectionTitle(category, value) {
        this.$api.post("/restaurant/menu/metadata/name", { category, value }).then(() => {
          const propName = `${this.getSectionProp(category)}Title`;
          this.$set(this, propName, value);
          this.$store.dispatch(
            "notification/success",
            "Section title updated successfully"
          );
        }).catch((error) => {
          console.error("Error updating section title:", error);
          this.$store.dispatch(
            "notification/error",
            "Failed to update section title"
          );
        });
      },
      getSectionTitle(category) {
        return this[`${this.getSectionProp(category)}Title`];
      },
      getSectionShowHide(category) {
        return this[`${this.getSectionProp(category)}ShowHide`];
      },
      getSectionComponent(category) {
        const componentMap = {
          starter: "k-starter-table",
          maincourse: "k-main-course-table",
          dessert: "k-dessert-table",
          bubblewine: "k-bubble-wine-table",
          whitewine: "k-white-wine-table",
          redwine: "k-red-wine-table",
          softdrink: "k-soft-drink-table",
          beer: "k-beer-table",
          cocktail: "k-cocktail-table",
          hotdrink: "k-hot-drink-table"
        };
        return componentMap[category];
      },
      getSectionProp(category) {
        const propMap = {
          starter: "starters",
          maincourse: "mainCourses",
          dessert: "desserts",
          bubblewine: "bubbleWines",
          whitewine: "whiteWines",
          redwine: "redWines",
          softdrink: "softDrinks",
          beer: "beers",
          cocktail: "cocktails",
          hotdrink: "hotDrinks"
        };
        return propMap[category];
      },
      getSectionData(category) {
        return this[this.getSectionProp(category)];
      },
      updateSectionOrder(newOrder, page) {
        this.$api.post(`/restaurant/menu/metadata/${page}/order`, {
          order: newOrder
        }).then(() => {
          this.$set(this, `page${page}Order`, newOrder);
          this.$store.dispatch(
            "notification/success",
            "Section order updated successfully"
          );
        }).catch((error) => {
          console.error("Error updating section order:", error);
          this.$store.dispatch(
            "notification/error",
            "Failed to update section order"
          );
        });
      }
    },
    computed: {
      pageTitleIcon2() {
        if (this.hasBeenEdited) {
          return "check";
        } else if (this.isEditing) {
          return "loader";
        } else {
          return "edit";
        }
      },
      pageTitleIcon3() {
        if (this.hasBeenEdited) {
          return "check";
        } else if (this.isEditing) {
          return "loader";
        } else {
          return "edit";
        }
      },
      pageTitleIcon4() {
        if (this.hasBeenEdited) {
          return "check";
        } else if (this.isEditing) {
          return "loader";
        } else {
          return "edit";
        }
      }
    }
  };
  var _sfc_render$b = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Menu "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { staticClass: "k-restaurant-button", attrs: { "icon": _vm.isSubmitting ? "loader" : "check", "theme": _vm.hasBeenSubmitted ? "green" : null, "variant": "filled" }, on: { "click": _vm.submit } }, [_vm._v(" Enregistrer ")]), _c("k-button", { attrs: { "icon": _vm.isGeneratingPDF ? "loader" : "wand", "disabled": _vm.isGeneratingPDF, "variant": "filled" }, on: { "click": _vm.generate } }, [_vm._v(" Générer PDF ")])], 1)], 1), _c("k-form", { attrs: { "fields": _vm.formFields }, on: { "input": _vm.input, "submit": _vm.submit }, model: { value: _vm.menu, callback: function($$v) {
      _vm.menu = $$v;
    }, expression: "menu" } }), _c("k-grid", { staticStyle: { "margin-bottom": "40px", "margin-top": "40px" } }, [_c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3" } }, [_c("hr", { staticClass: "k-line-field", attrs: { "type": "line" } })]), _c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3", "text-align": "center", "height": "100%", "align-content": "center" } }, [_c("k-input", { attrs: { "value": _vm.pageTitle2, "type": "text", "icon": _vm.pageTitleIcon2 }, on: { "input": _vm.updatePageTitle2 } })], 1), _c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3" } }, [_c("hr", { staticClass: "k-line-field", attrs: { "type": "line" } })])]), _vm._l(_vm.page2Order, function(category) {
      return [_c("k-section-header", { key: `header-${category}`, attrs: { "category": category, "title": _vm.getSectionTitle(category), "show-hide": _vm.getSectionShowHide(category) }, on: { "up": function($event) {
        return _vm.moveSectionUp(category);
      }, "down": function($event) {
        return _vm.moveSectionDown(category);
      }, "hide": function($event) {
        return _vm.$dialog(`/menu/${category}/hide`);
      }, "edit": function($event) {
        return _vm.$dialog(`/menu/${category}/title`);
      }, "create": function($event) {
        return _vm.$dialog(`/menu/${category}/create`);
      } } }), _c(_vm.getSectionComponent(category), _vm._b({ key: `table-${category}`, tag: "component", on: { "update-order": _vm.updateTableOrder, "open-dialog": _vm.$dialog } }, "component", _vm._d({}, [_vm.getSectionProp(category), _vm.getSectionData(category)])))];
    }), _c("k-grid", { staticStyle: { "margin-bottom": "40px", "margin-top": "40px" } }, [_c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3" } }, [_c("hr", { staticClass: "k-line-field", attrs: { "type": "line" } })]), _c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3", "text-align": "center", "height": "100%", "align-content": "center" } }, [_c("k-input", { attrs: { "value": _vm.pageTitle3, "type": "text", "icon": _vm.pageTitleIcon3 }, on: { "input": _vm.updatePageTitle3 } })], 1), _c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3" } }, [_c("hr", { staticClass: "k-line-field", attrs: { "type": "line" } })])]), _vm._l(_vm.page3Order, function(category) {
      return [_c("k-section-header", { key: `header-${category}`, attrs: { "category": category, "title": _vm.getSectionTitle(category), "show-hide": _vm.getSectionShowHide(category) }, on: { "up": function($event) {
        return _vm.moveSectionUp(category);
      }, "down": function($event) {
        return _vm.moveSectionDown(category);
      }, "hide": function($event) {
        return _vm.$dialog(`/menu/${category}/hide`);
      }, "edit": function($event) {
        return _vm.$dialog(`/menu/${category}/title`);
      }, "create": function($event) {
        return _vm.$dialog(`/menu/${category}/create`);
      } } }), _c(_vm.getSectionComponent(category), _vm._b({ key: `table-${category}`, tag: "component", on: { "update-order": _vm.updateTableOrder, "open-dialog": _vm.$dialog } }, "component", _vm._d({}, [_vm.getSectionProp(category), _vm.getSectionData(category)])))];
    }), _c("k-grid", { staticStyle: { "margin-bottom": "40px", "margin-top": "40px" } }, [_c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3" } }, [_c("hr", { staticClass: "k-line-field", attrs: { "type": "line" } })]), _c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3", "text-align": "center", "height": "100%", "align-content": "center" } }, [_c("k-input", { attrs: { "value": _vm.pageTitle4, "type": "text", "icon": _vm.pageTitleIcon4 }, on: { "input": _vm.updatePageTitle4 } })], 1), _c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3" } }, [_c("hr", { staticClass: "k-line-field", attrs: { "type": "line" } })])]), _vm._l(_vm.page4Order, function(category) {
      return [_c("k-section-header", { key: `header-${category}`, attrs: { "category": category, "title": _vm.getSectionTitle(category), "show-hide": _vm.getSectionShowHide(category) }, on: { "up": function($event) {
        return _vm.moveSectionUp(category);
      }, "down": function($event) {
        return _vm.moveSectionDown(category);
      }, "hide": function($event) {
        return _vm.$dialog(`/menu/${category}/hide`);
      }, "edit": function($event) {
        return _vm.$dialog(`/menu/${category}/title`);
      }, "create": function($event) {
        return _vm.$dialog(`/menu/${category}/create`);
      } } }), _c(_vm.getSectionComponent(category), _vm._b({ key: `table-${category}`, tag: "component", on: { "update-order": _vm.updateTableOrder, "open-dialog": _vm.$dialog } }, "component", _vm._d({}, [_vm.getSectionProp(category), _vm.getSectionData(category)])))];
    })], 2);
  };
  var _sfc_staticRenderFns$b = [];
  _sfc_render$b._withStripped = true;
  var __component__$b = /* @__PURE__ */ normalizeComponent(
    _sfc_main$b,
    _sfc_render$b,
    _sfc_staticRenderFns$b
  );
  __component__$b.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/MenuView.vue";
  const MenuView = __component__$b.exports;
  const _sfc_main$a = {
    name: "SectionHeader",
    props: {
      category: {
        type: String,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      showHide: {
        type: Boolean,
        required: true
      }
    },
    data() {
      return {
        isEditing: false,
        hasBeenEdited: false
      };
    },
    methods: {
      input(value) {
        this.isEditing = true;
        this.$api.post("/restaurant/menu/metadata/name", {
          value,
          category: this.category
        });
        setTimeout(() => {
          this.isEditing = false;
          this.hasBeenEdited = true;
          setTimeout(() => {
            this.hasBeenEdited = false;
          }, 5e3);
        }, 1500);
      }
    },
    computed: {
      titleIcon() {
        return this.isEditing ? "loader" : this.hasBeenEdited ? "check" : "edit";
      }
    }
  };
  var _sfc_render$a = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-grid", { staticStyle: { "margin-top": "40px" } }, [_c("div", { staticClass: "k-column", staticStyle: { "--width": "1/3", "display": "flex", "justify-content": "space-between" } }, [_c("k-button-group", { attrs: { "layout": "collapsed" } }, [_c("k-button", { attrs: { "variant": "filled", "icon": "angle-down" }, on: { "click": function($event) {
      return _vm.$emit("down");
    } } }), _c("k-button", { attrs: { "variant": "filled", "icon": "angle-up" }, on: { "click": function($event) {
      return _vm.$emit("up");
    } } })], 1), _c("k-input", { attrs: { "value": _vm.title, "type": "text", "icon": _vm.titleIcon }, on: { "input": function($event) {
      return _vm.input($event);
    } } })], 1), _c("div", { staticClass: "k-column", staticStyle: { "--width": "2/3", "justify-self": "end" } }, [_c("k-button-group", { attrs: { "layout": "collapsed" } }, [_c("k-button", { attrs: { "variant": "filled", "tooltip": _vm.showHide ? "Afficher" : "Cacher", "icon": _vm.showHide ? "hidden" : "preview" }, on: { "click": function($event) {
      return _vm.$emit("hide");
    } } }), _c("k-button", { attrs: { "variant": "filled", "icon": "plus" }, on: { "click": function($event) {
      return _vm.$emit("create");
    } } }, [_vm._v(" Ajouter ")])], 1)], 1)]);
  };
  var _sfc_staticRenderFns$a = [];
  _sfc_render$a._withStripped = true;
  var __component__$a = /* @__PURE__ */ normalizeComponent(
    _sfc_main$a,
    _sfc_render$a,
    _sfc_staticRenderFns$a
  );
  __component__$a.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/SectionHeader.vue";
  const SectionHeader = __component__$a.exports;
  const _sfc_main$9 = {
    props: {
      beers: {
        type: Array,
        required: true
      }
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$9 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.beers, "handle": true, "options": {
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
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$9 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$9._withStripped = true;
  var __component__$9 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$9,
    _sfc_render$9,
    _sfc_staticRenderFns$9
  );
  __component__$9.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/BeerTable.vue";
  const BeerTable = __component__$9.exports;
  const _sfc_main$8 = {
    props: {
      cocktails: {
        type: Array,
        required: true
      }
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$8 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.cocktails, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("cocktails");
    } } }, _vm._l(_vm.cocktails, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$8 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$8._withStripped = true;
  var __component__$8 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$8,
    _sfc_render$8,
    _sfc_staticRenderFns$8
  );
  __component__$8.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/CocktailTable.vue";
  const CocktailTable = __component__$8.exports;
  const _sfc_main$7 = {
    props: {
      desserts: Array
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$7 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.desserts, "handle": true, "options": {
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
          click: () => _vm.$dialog(`menu/dessert/${item.id}/delete`)
        }
      ] } })], 1)]);
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$7 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$7._withStripped = true;
  var __component__$7 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$7,
    _sfc_render$7,
    _sfc_staticRenderFns$7
  );
  __component__$7.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/DessertTable.vue";
  const DessertTable = __component__$7.exports;
  const _sfc_main$6 = {
    props: {
      hotDrinks: {
        type: Array,
        required: true
      }
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$6 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.hotDrinks, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("hotDrinks");
    } } }, _vm._l(_vm.hotDrinks, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$6 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$6._withStripped = true;
  var __component__$6 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$6,
    _sfc_render$6,
    _sfc_staticRenderFns$6
  );
  __component__$6.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/HotDrinkTable.vue";
  const HotDrinkTable = __component__$6.exports;
  const _sfc_main$5 = {
    props: {
      mainCourses: Array
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$5 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.mainCourses, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("mainCourses");
    } } }, _vm._l(_vm.mainCourses, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/maincourse/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/maincourse/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$5 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$5._withStripped = true;
  var __component__$5 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$5,
    _sfc_render$5,
    _sfc_staticRenderFns$5
  );
  __component__$5.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/MainCourseTable.vue";
  const MainCourseTable = __component__$5.exports;
  const _sfc_main$4 = {
    props: {
      starters: Array
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$4 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.starters, "handle": true, "options": {
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
          click: () => _vm.$dialog(`menu/starter/${item.id}/delete`)
        }
      ] } })], 1)]);
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$4 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Plat")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$4._withStripped = true;
  var __component__$4 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$4,
    _sfc_render$4,
    _sfc_staticRenderFns$4
  );
  __component__$4.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/StarterTable.vue";
  const StarterTable = __component__$4.exports;
  const _sfc_main$3 = {
    props: {
      whiteWines: Array
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$3 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.whiteWines, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("whiteWines");
    } } }, _vm._l(_vm.whiteWines, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price50cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$3 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("50cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$3._withStripped = true;
  var __component__$3 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$3,
    _sfc_render$3,
    _sfc_staticRenderFns$3
  );
  __component__$3.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/WhiteWineTable.vue";
  const WhiteWineTable = __component__$3.exports;
  const _sfc_main$2 = {
    props: {
      redWines: Array
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$2 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.redWines, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("redWines");
    } } }, _vm._l(_vm.redWines, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price50cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$2 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("50cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$2._withStripped = true;
  var __component__$2 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$2,
    _sfc_render$2,
    _sfc_staticRenderFns$2
  );
  __component__$2.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/RedWineTable.vue";
  const RedWineTable = __component__$2.exports;
  const _sfc_main$1 = {
    props: {
      bubbleWines: Array
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.bubbleWines, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("bubbleWines");
    } } }, _vm._l(_vm.bubbleWines, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.domain))]), _c("td", [_vm._v(_vm._s(item.mill))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.price10cl))]), _c("td", [_vm._v(_vm._s(item.price50cl))]), _c("td", [_vm._v(_vm._s(item.price75cl))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`menu/bubblewine/${item.id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(
            `menu/bubblewine/${item.id}/delete`
          )
        }
      ] } })], 1)]);
    }), 0)], 1);
  };
  var _sfc_staticRenderFns$1 = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Domaine")]), _c("th", [_vm._v("Millésime")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("10cl")]), _c("th", [_vm._v("50cl")]), _c("th", [_vm._v("75cl")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1
  );
  __component__$1.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/BubbleWineTable.vue";
  const BubbleWineTable = __component__$1.exports;
  const _sfc_main = {
    props: {
      softDrinks: {
        type: Array,
        required: true
      }
    },
    methods: {
      updateOrder(listName) {
        this.$emit("update-order", listName);
      },
      $dialog(path) {
        this.$emit("open-dialog", path);
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("table", { staticClass: "k-table", staticStyle: { "margin-top": "20px", "margin-bottom": "25px" } }, [_vm._m(0), _c("k-draggable", { attrs: { "list": _vm.softDrinks, "handle": true, "options": {
      fallbackClass: "k-table-row-fallback",
      ghostClass: "k-table-row-ghost"
    }, "element": "tbody" }, on: { "change": function($event) {
      return _vm.updateOrder("softDrinks");
    } } }, _vm._l(_vm.softDrinks, function(item, index) {
      return _c("tr", { key: item.id }, [_c("td", { staticClass: "k-table-index-column", attrs: { "data-sortable": "true" } }, [_c("span", { staticClass: "k-table-index" }, [_vm._v(_vm._s(index + 1))]), _c("k-sort-handle")], 1), _c("td", [_vm._v(_vm._s(item.name))]), _c("td", [_vm._v(_vm._s(item.description))]), _c("td", [_vm._v(_vm._s(item.volume))]), _c("td", [_vm._v(_vm._s(item.price))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
    }), 0)], 1);
  };
  var _sfc_staticRenderFns = [function() {
    var _vm = this, _c = _vm._self._c;
    return _c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column" }), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Volume")]), _c("th", [_vm._v("Prix")]), _c("th", { staticClass: "k-table-options-column" })])]);
  }];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns
  );
  __component__.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-foodlab/src/components/SoftDrinkTable.vue";
  const SoftDrinkTable = __component__.exports;
  panel.plugin("mediumsans/foodlab", {
    components: {
      "k-menu-view": MenuView,
      "k-section-header": SectionHeader,
      "k-beer-table": BeerTable,
      "k-dessert-table": DessertTable,
      "k-hot-drink-table": HotDrinkTable,
      "k-main-course-table": MainCourseTable,
      "k-starter-table": StarterTable,
      "k-white-wine-table": WhiteWineTable,
      "k-red-wine-table": RedWineTable,
      "k-bubble-wine-table": BubbleWineTable,
      "k-soft-drink-table": SoftDrinkTable,
      "k-cocktail-table": CocktailTable
    }
  });
})();
