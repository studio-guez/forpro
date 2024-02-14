(function() {
  "use strict";
  const SchedulesView_vue_vue_type_style_index_0_lang = "";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    if (functionalTemplate) {
      options.functional = true;
    }
    if (scopeId) {
      options._scopeId = "data-v-" + scopeId;
    }
    var hook;
    if (moduleIdentifier) {
      hook = function(context) {
        context = context || // cached call
        this.$vnode && this.$vnode.ssrContext || // stateful
        this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext;
        if (!context && typeof __VUE_SSR_CONTEXT__ !== "undefined") {
          context = __VUE_SSR_CONTEXT__;
        }
        if (injectStyles) {
          injectStyles.call(this, context);
        }
        if (context && context._registeredComponents) {
          context._registeredComponents.add(moduleIdentifier);
        }
      };
      options._ssrRegister = hook;
    } else if (injectStyles) {
      hook = shadowMode ? function() {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        );
      } : injectStyles;
    }
    if (hook) {
      if (options.functional) {
        options._injectStyles = hook;
        var originalRender = options.render;
        options.render = function renderWithStyleInjection(h, context) {
          hook.call(context);
          return originalRender(h, context);
        };
      } else {
        var existing = options.beforeCreate;
        options.beforeCreate = existing ? [].concat(existing, hook) : [hook];
      }
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main$3 = {
    props: {
      calendar: Array,
      schedules: Array,
      services: Array
    },
    methods: {
      goto(path) {
        this.$go(path);
      },
      getDayFromId(id) {
        let days = [
          "Dimanche",
          "Lundi",
          "Mardi",
          "Mercredi",
          "Jeudi",
          "Vendredi",
          "Samedi"
        ];
        return days[id];
      },
      formatIsClosed(state) {
        return state ? "Oui" : "Non";
      }
    }
  };
  var _sfc_render$3 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Horaires "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog(`schedule/${_vm.calendar.id}/create`);
    } } })], 1)], 1), _c("table", { staticClass: "k-table" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" # ")]), _c("th", [_vm._v("Jours")]), _c("th", [_vm._v("Ouverture")]), _c("th", [_vm._v("Fermeture")]), _c("th", [_vm._v("Fermé ?")]), _c("th", { staticClass: "k-table-index-column" })])]), _c("tbody", _vm._l(_vm.schedules, function(schedule, id, index) {
      return _c("tr", { key: id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index) + " ")]), _c("td", { staticStyle: { "width": "10%" } }, [_vm._v(_vm._s(_vm.getDayFromId(schedule.day_id)))]), _c("td", [_vm._v(_vm._s(schedule.opening_hour))]), _c("td", [_vm._v(_vm._s(schedule.closing_hour))]), _c("td", [_vm._v(_vm._s(_vm.formatIsClosed(schedule.is_closed)))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`schedule/${id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`schedule/${id}/delete`)
        }
      ] } })], 1)]);
    }), 0)])], 1);
  };
  var _sfc_staticRenderFns$3 = [];
  _sfc_render$3._withStripped = true;
  var __component__$3 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$3,
    _sfc_render$3,
    _sfc_staticRenderFns$3,
    false,
    null,
    null,
    null,
    null
  );
  __component__$3.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-calendars/src/components/SchedulesView.vue";
  const SchedulesView = __component__$3.exports;
  const ServicesView_vue_vue_type_style_index_0_lang = "";
  const _sfc_main$2 = {
    props: {
      calendar: Array,
      schedules: Array,
      services: Array
    },
    methods: {
      goto(path) {
        this.$go(path);
      }
    }
  };
  var _sfc_render$2 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Services "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog(`service/${_vm.calendar.id}/create`);
    } } })], 1)], 1), _c("table", { staticClass: "k-table" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" # ")]), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Durée")]), _c("th", { staticClass: "k-table-index-column" })])]), _c("tbody", _vm._l(_vm.services, function(service, id, index) {
      return _c("tr", { key: id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index) + " ")]), _c("td", { staticStyle: { "width": "10%" } }, [_vm._v(_vm._s(service.name))]), _c("td", [_vm._v(_vm._s(service.duration))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`service/${id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`service/${id}/delete`)
        }
      ] } })], 1)]);
    }), 0)])], 1);
  };
  var _sfc_staticRenderFns$2 = [];
  _sfc_render$2._withStripped = true;
  var __component__$2 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$2,
    _sfc_render$2,
    _sfc_staticRenderFns$2,
    false,
    null,
    null,
    null,
    null
  );
  __component__$2.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-calendars/src/components/ServicesView.vue";
  const ServicesView = __component__$2.exports;
  const CalendarsView_vue_vue_type_style_index_0_lang = "";
  const _sfc_main$1 = {
    props: {
      calendars: Array,
      options: Array
    },
    methods: {
      goto(path) {
        this.$go(path);
      }
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Calendrier "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("calendar/create");
    } } })], 1)], 1), _c("table", { staticClass: "k-table k-calendar" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v("# ")]), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Description")]), _c("th", [_vm._v("Services")]), _c("th", [_vm._v("Horaires")]), _c("th", { staticClass: "k-table-index-column" })])]), _c("tbody", _vm._l(_vm.calendars, function(calendar, id, index) {
      return _c("tr", { key: id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index) + " ")]), _c("td", { staticStyle: { "width": "10%" } }, [_vm._v(_vm._s(calendar.name))]), _c("td", [_vm._v(_vm._s(calendar.description))]), _c("td", { attrs: { "data-align": "center" } }, [_vm._v(_vm._s(calendar.nbrServices))]), _c("td", { attrs: { "data-align": "center" } }, [_c("k-button", { attrs: { "icon": calendar.scheduleState.icon, "theme": calendar.scheduleState.theme, "variant": "dimmed" } }, [_vm._v(" " + _vm._s(calendar.scheduleState.status) + " ")])], 1), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`calendar/${id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`calendar/${id}/delete`)
        },
        {
          text: "Services",
          icon: "dashboard",
          click: () => _vm.goto(`/kirby-calendars/calendar/${id}/services`)
        },
        {
          text: "Horaires",
          icon: "clock",
          click: () => _vm.goto(`/kirby-calendars/calendar/${id}/schedules`)
        },
        {
          text: "Évènements",
          icon: "page",
          click: () => _vm.goto(`/kirby-calendars/calendar/${id}/events`)
        }
      ] } })], 1)]);
    }), 0)])], 1);
  };
  var _sfc_staticRenderFns$1 = [];
  _sfc_render$1._withStripped = true;
  var __component__$1 = /* @__PURE__ */ normalizeComponent(
    _sfc_main$1,
    _sfc_render$1,
    _sfc_staticRenderFns$1,
    false,
    null,
    null,
    null,
    null
  );
  __component__$1.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-calendars/src/components/CalendarsView.vue";
  const CalendarsView = __component__$1.exports;
  const EventsView_vue_vue_type_style_index_0_lang = "";
  const _sfc_main = {
    props: {
      events: Array,
      options: Array
    },
    methods: {
      goto(path) {
        this.$go(path);
      },
      formatDate(date) {
        if (date) {
          const [year, month, day] = date.split("-");
          return `${day}.${month}.${year}`;
        }
        return "";
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Évènements "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("event/create");
    } } })], 1)], 1), _c("table", { staticClass: "k-table" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" # ")]), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Date")]), _c("th", [_vm._v("Début")]), _c("th", [_vm._v("Fin")]), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Prénom")]), _c("th", [_vm._v("Email")]), _c("th", [_vm._v("Téléphone")]), _c("th", { staticClass: "k-table-index-column" })])]), _c("tbody", _vm._l(_vm.events, function(event, id, index) {
      return _c("tr", { key: id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index) + " ")]), _c("td", { staticStyle: { "width": "10%" } }, [_vm._v(_vm._s(event.name))]), _c("td", { attrs: { "data-align": "center" } }, [_vm._v(_vm._s(_vm.formatDate(event.date)))]), _c("td", { attrs: { "data-align": "center" } }, [_vm._v(_vm._s(event.start_time))]), _c("td", { attrs: { "data-align": "center" } }, [_vm._v(_vm._s(event.end_time))]), _c("td", [_vm._v(_vm._s(event.lastname))]), _c("td", [_vm._v(_vm._s(event.firstname))]), _c("td", [_vm._v(_vm._s(event.email))]), _c("td", [_vm._v(_vm._s(event.phone))]), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
        {
          text: "Modifier",
          icon: "edit",
          click: () => _vm.$dialog(`calendar/${id}/edit`)
        },
        {
          text: "Supprimer",
          icon: "trash",
          click: () => _vm.$dialog(`calendar/${id}/delete`)
        }
      ] } })], 1)]);
    }), 0)])], 1);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns,
    false,
    null,
    null,
    null,
    null
  );
  __component__.options.__file = "/Users/scardoso/Documents/dev/forpro/cms/site/plugins/kirby-calendars/src/components/EventsView.vue";
  const EventsView = __component__.exports;
  panel.plugin("mediumsans/kirby-calendars", {
    components: {
      "k-calendars-view": CalendarsView,
      "k-schedules-view": SchedulesView,
      "k-services-view": ServicesView,
      "k-events-view": EventsView
    }
  });
})();
