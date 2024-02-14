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
  var commonjsGlobal = typeof globalThis !== "undefined" ? globalThis : typeof window !== "undefined" ? window : typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : {};
  function getDefaultExportFromCjs(x) {
    return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, "default") ? x["default"] : x;
  }
  var vueClipboard = { exports: {} };
  var clipboard_min = { exports: {} };
  /*!
   * clipboard.js v2.0.11
   * https://clipboardjs.com/
   *
   * Licensed MIT © Zeno Rocha
   */
  (function(module, exports) {
    !function(t, e) {
      module.exports = e();
    }(commonjsGlobal, function() {
      return n = { 686: function(t, e, n2) {
        n2.d(e, { default: function() {
          return b;
        } });
        var e = n2(279), i = n2.n(e), e = n2(370), u = n2.n(e), e = n2(817), r2 = n2.n(e);
        function c(t2) {
          try {
            return document.execCommand(t2);
          } catch (t3) {
            return;
          }
        }
        var a = function(t2) {
          t2 = r2()(t2);
          return c("cut"), t2;
        };
        function o2(t2, e2) {
          var n3, o3, t2 = (n3 = t2, o3 = "rtl" === document.documentElement.getAttribute("dir"), (t2 = document.createElement("textarea")).style.fontSize = "12pt", t2.style.border = "0", t2.style.padding = "0", t2.style.margin = "0", t2.style.position = "absolute", t2.style[o3 ? "right" : "left"] = "-9999px", o3 = window.pageYOffset || document.documentElement.scrollTop, t2.style.top = "".concat(o3, "px"), t2.setAttribute("readonly", ""), t2.value = n3, t2);
          return e2.container.appendChild(t2), e2 = r2()(t2), c("copy"), t2.remove(), e2;
        }
        var f = function(t2) {
          var e2 = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : { container: document.body }, n3 = "";
          return "string" == typeof t2 ? n3 = o2(t2, e2) : t2 instanceof HTMLInputElement && !["text", "search", "url", "tel", "password"].includes(null == t2 ? void 0 : t2.type) ? n3 = o2(t2.value, e2) : (n3 = r2()(t2), c("copy")), n3;
        };
        function l(t2) {
          return (l = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t3) {
            return typeof t3;
          } : function(t3) {
            return t3 && "function" == typeof Symbol && t3.constructor === Symbol && t3 !== Symbol.prototype ? "symbol" : typeof t3;
          })(t2);
        }
        var s = function() {
          var t2 = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {}, e2 = t2.action, n3 = void 0 === e2 ? "copy" : e2, o3 = t2.container, e2 = t2.target, t2 = t2.text;
          if ("copy" !== n3 && "cut" !== n3)
            throw new Error('Invalid "action" value, use either "copy" or "cut"');
          if (void 0 !== e2) {
            if (!e2 || "object" !== l(e2) || 1 !== e2.nodeType)
              throw new Error('Invalid "target" value, use a valid Element');
            if ("copy" === n3 && e2.hasAttribute("disabled"))
              throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');
            if ("cut" === n3 && (e2.hasAttribute("readonly") || e2.hasAttribute("disabled")))
              throw new Error(`Invalid "target" attribute. You can't cut text from elements with "readonly" or "disabled" attributes`);
          }
          return t2 ? f(t2, { container: o3 }) : e2 ? "cut" === n3 ? a(e2) : f(e2, { container: o3 }) : void 0;
        };
        function p(t2) {
          return (p = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t3) {
            return typeof t3;
          } : function(t3) {
            return t3 && "function" == typeof Symbol && t3.constructor === Symbol && t3 !== Symbol.prototype ? "symbol" : typeof t3;
          })(t2);
        }
        function d(t2, e2) {
          for (var n3 = 0; n3 < e2.length; n3++) {
            var o3 = e2[n3];
            o3.enumerable = o3.enumerable || false, o3.configurable = true, "value" in o3 && (o3.writable = true), Object.defineProperty(t2, o3.key, o3);
          }
        }
        function y(t2, e2) {
          return (y = Object.setPrototypeOf || function(t3, e3) {
            return t3.__proto__ = e3, t3;
          })(t2, e2);
        }
        function h(n3) {
          var o3 = function() {
            if ("undefined" == typeof Reflect || !Reflect.construct)
              return false;
            if (Reflect.construct.sham)
              return false;
            if ("function" == typeof Proxy)
              return true;
            try {
              return Date.prototype.toString.call(Reflect.construct(Date, [], function() {
              })), true;
            } catch (t2) {
              return false;
            }
          }();
          return function() {
            var t2, e2 = v(n3);
            return t2 = o3 ? (t2 = v(this).constructor, Reflect.construct(e2, arguments, t2)) : e2.apply(this, arguments), e2 = this, !(t2 = t2) || "object" !== p(t2) && "function" != typeof t2 ? function(t3) {
              if (void 0 !== t3)
                return t3;
              throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }(e2) : t2;
          };
        }
        function v(t2) {
          return (v = Object.setPrototypeOf ? Object.getPrototypeOf : function(t3) {
            return t3.__proto__ || Object.getPrototypeOf(t3);
          })(t2);
        }
        function m(t2, e2) {
          t2 = "data-clipboard-".concat(t2);
          if (e2.hasAttribute(t2))
            return e2.getAttribute(t2);
        }
        var b = function() {
          !function(t3, e3) {
            if ("function" != typeof e3 && null !== e3)
              throw new TypeError("Super expression must either be null or a function");
            t3.prototype = Object.create(e3 && e3.prototype, { constructor: { value: t3, writable: true, configurable: true } }), e3 && y(t3, e3);
          }(r3, i());
          var t2, e2, n3, o3 = h(r3);
          function r3(t3, e3) {
            var n4;
            return function(t4) {
              if (!(t4 instanceof r3))
                throw new TypeError("Cannot call a class as a function");
            }(this), (n4 = o3.call(this)).resolveOptions(e3), n4.listenClick(t3), n4;
          }
          return t2 = r3, n3 = [{ key: "copy", value: function(t3) {
            var e3 = 1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : { container: document.body };
            return f(t3, e3);
          } }, { key: "cut", value: function(t3) {
            return a(t3);
          } }, { key: "isSupported", value: function() {
            var t3 = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : ["copy", "cut"], t3 = "string" == typeof t3 ? [t3] : t3, e3 = !!document.queryCommandSupported;
            return t3.forEach(function(t4) {
              e3 = e3 && !!document.queryCommandSupported(t4);
            }), e3;
          } }], (e2 = [{ key: "resolveOptions", value: function() {
            var t3 = 0 < arguments.length && void 0 !== arguments[0] ? arguments[0] : {};
            this.action = "function" == typeof t3.action ? t3.action : this.defaultAction, this.target = "function" == typeof t3.target ? t3.target : this.defaultTarget, this.text = "function" == typeof t3.text ? t3.text : this.defaultText, this.container = "object" === p(t3.container) ? t3.container : document.body;
          } }, { key: "listenClick", value: function(t3) {
            var e3 = this;
            this.listener = u()(t3, "click", function(t4) {
              return e3.onClick(t4);
            });
          } }, { key: "onClick", value: function(t3) {
            var e3 = t3.delegateTarget || t3.currentTarget, n4 = this.action(e3) || "copy", t3 = s({ action: n4, container: this.container, target: this.target(e3), text: this.text(e3) });
            this.emit(t3 ? "success" : "error", { action: n4, text: t3, trigger: e3, clearSelection: function() {
              e3 && e3.focus(), window.getSelection().removeAllRanges();
            } });
          } }, { key: "defaultAction", value: function(t3) {
            return m("action", t3);
          } }, { key: "defaultTarget", value: function(t3) {
            t3 = m("target", t3);
            if (t3)
              return document.querySelector(t3);
          } }, { key: "defaultText", value: function(t3) {
            return m("text", t3);
          } }, { key: "destroy", value: function() {
            this.listener.destroy();
          } }]) && d(t2.prototype, e2), n3 && d(t2, n3), r3;
        }();
      }, 828: function(t) {
        var e;
        "undefined" == typeof Element || Element.prototype.matches || ((e = Element.prototype).matches = e.matchesSelector || e.mozMatchesSelector || e.msMatchesSelector || e.oMatchesSelector || e.webkitMatchesSelector), t.exports = function(t2, e2) {
          for (; t2 && 9 !== t2.nodeType; ) {
            if ("function" == typeof t2.matches && t2.matches(e2))
              return t2;
            t2 = t2.parentNode;
          }
        };
      }, 438: function(t, e, n2) {
        var u = n2(828);
        function i(t2, e2, n3, o2, r2) {
          var i2 = (function(e3, n4, t3, o3) {
            return function(t4) {
              t4.delegateTarget = u(t4.target, n4), t4.delegateTarget && o3.call(e3, t4);
            };
          }).apply(this, arguments);
          return t2.addEventListener(n3, i2, r2), { destroy: function() {
            t2.removeEventListener(n3, i2, r2);
          } };
        }
        t.exports = function(t2, e2, n3, o2, r2) {
          return "function" == typeof t2.addEventListener ? i.apply(null, arguments) : "function" == typeof n3 ? i.bind(null, document).apply(null, arguments) : ("string" == typeof t2 && (t2 = document.querySelectorAll(t2)), Array.prototype.map.call(t2, function(t3) {
            return i(t3, e2, n3, o2, r2);
          }));
        };
      }, 879: function(t, n2) {
        n2.node = function(t2) {
          return void 0 !== t2 && t2 instanceof HTMLElement && 1 === t2.nodeType;
        }, n2.nodeList = function(t2) {
          var e = Object.prototype.toString.call(t2);
          return void 0 !== t2 && ("[object NodeList]" === e || "[object HTMLCollection]" === e) && "length" in t2 && (0 === t2.length || n2.node(t2[0]));
        }, n2.string = function(t2) {
          return "string" == typeof t2 || t2 instanceof String;
        }, n2.fn = function(t2) {
          return "[object Function]" === Object.prototype.toString.call(t2);
        };
      }, 370: function(t, e, n2) {
        var f = n2(879), l = n2(438);
        t.exports = function(t2, e2, n3) {
          if (!t2 && !e2 && !n3)
            throw new Error("Missing required arguments");
          if (!f.string(e2))
            throw new TypeError("Second argument must be a String");
          if (!f.fn(n3))
            throw new TypeError("Third argument must be a Function");
          if (f.node(t2))
            return c = e2, a = n3, (u = t2).addEventListener(c, a), { destroy: function() {
              u.removeEventListener(c, a);
            } };
          if (f.nodeList(t2))
            return o2 = t2, r2 = e2, i = n3, Array.prototype.forEach.call(o2, function(t3) {
              t3.addEventListener(r2, i);
            }), { destroy: function() {
              Array.prototype.forEach.call(o2, function(t3) {
                t3.removeEventListener(r2, i);
              });
            } };
          if (f.string(t2))
            return t2 = t2, e2 = e2, n3 = n3, l(document.body, t2, e2, n3);
          throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList");
          var o2, r2, i, u, c, a;
        };
      }, 817: function(t) {
        t.exports = function(t2) {
          var e, n2 = "SELECT" === t2.nodeName ? (t2.focus(), t2.value) : "INPUT" === t2.nodeName || "TEXTAREA" === t2.nodeName ? ((e = t2.hasAttribute("readonly")) || t2.setAttribute("readonly", ""), t2.select(), t2.setSelectionRange(0, t2.value.length), e || t2.removeAttribute("readonly"), t2.value) : (t2.hasAttribute("contenteditable") && t2.focus(), n2 = window.getSelection(), (e = document.createRange()).selectNodeContents(t2), n2.removeAllRanges(), n2.addRange(e), n2.toString());
          return n2;
        };
      }, 279: function(t) {
        function e() {
        }
        e.prototype = { on: function(t2, e2, n2) {
          var o2 = this.e || (this.e = {});
          return (o2[t2] || (o2[t2] = [])).push({ fn: e2, ctx: n2 }), this;
        }, once: function(t2, e2, n2) {
          var o2 = this;
          function r2() {
            o2.off(t2, r2), e2.apply(n2, arguments);
          }
          return r2._ = e2, this.on(t2, r2, n2);
        }, emit: function(t2) {
          for (var e2 = [].slice.call(arguments, 1), n2 = ((this.e || (this.e = {}))[t2] || []).slice(), o2 = 0, r2 = n2.length; o2 < r2; o2++)
            n2[o2].fn.apply(n2[o2].ctx, e2);
          return this;
        }, off: function(t2, e2) {
          var n2 = this.e || (this.e = {}), o2 = n2[t2], r2 = [];
          if (o2 && e2)
            for (var i = 0, u = o2.length; i < u; i++)
              o2[i].fn !== e2 && o2[i].fn._ !== e2 && r2.push(o2[i]);
          return r2.length ? n2[t2] = r2 : delete n2[t2], this;
        } }, t.exports = e, t.exports.TinyEmitter = e;
      } }, r = {}, o.n = function(t) {
        var e = t && t.__esModule ? function() {
          return t.default;
        } : function() {
          return t;
        };
        return o.d(e, { a: e }), e;
      }, o.d = function(t, e) {
        for (var n2 in e)
          o.o(e, n2) && !o.o(t, n2) && Object.defineProperty(t, n2, { enumerable: true, get: e[n2] });
      }, o.o = function(t, e) {
        return Object.prototype.hasOwnProperty.call(t, e);
      }, o(686).default;
      function o(t) {
        if (r[t])
          return r[t].exports;
        var e = r[t] = { exports: {} };
        return n[t](e, e.exports, o), e.exports;
      }
      var n, r;
    });
  })(clipboard_min);
  var clipboard_minExports = clipboard_min.exports;
  (function(module, exports) {
    var Clipboard = clipboard_minExports;
    var VueClipboardConfig = {
      autoSetContainer: false,
      appendToBody: true
      // This fixes IE, see #50
    };
    var VueClipboard2 = {
      install: function(Vue2) {
        var globalPrototype = Vue2.version.slice(0, 2) === "3." ? Vue2.config.globalProperties : Vue2.prototype;
        globalPrototype.$clipboardConfig = VueClipboardConfig;
        globalPrototype.$copyText = function(text, container) {
          return new Promise(function(resolve, reject) {
            var fakeElement = document.createElement("button");
            var clipboard = new Clipboard(fakeElement, {
              text: function() {
                return text;
              },
              action: function() {
                return "copy";
              },
              container: typeof container === "object" ? container : document.body
            });
            clipboard.on("success", function(e) {
              clipboard.destroy();
              resolve(e);
            });
            clipboard.on("error", function(e) {
              clipboard.destroy();
              reject(e);
            });
            if (VueClipboardConfig.appendToBody)
              document.body.appendChild(fakeElement);
            fakeElement.click();
            if (VueClipboardConfig.appendToBody)
              document.body.removeChild(fakeElement);
          });
        };
        Vue2.directive("clipboard", {
          bind: function(el, binding, vnode) {
            if (binding.arg === "success") {
              el._vClipboard_success = binding.value;
            } else if (binding.arg === "error") {
              el._vClipboard_error = binding.value;
            } else {
              var clipboard = new Clipboard(el, {
                text: function() {
                  return binding.value;
                },
                action: function() {
                  return binding.arg === "cut" ? "cut" : "copy";
                },
                container: VueClipboardConfig.autoSetContainer ? el : void 0
              });
              clipboard.on("success", function(e) {
                var callback = el._vClipboard_success;
                callback && callback(e);
              });
              clipboard.on("error", function(e) {
                var callback = el._vClipboard_error;
                callback && callback(e);
              });
              el._vClipboard = clipboard;
            }
          },
          update: function(el, binding) {
            if (binding.arg === "success") {
              el._vClipboard_success = binding.value;
            } else if (binding.arg === "error") {
              el._vClipboard_error = binding.value;
            } else {
              el._vClipboard.text = function() {
                return binding.value;
              };
              el._vClipboard.action = function() {
                return binding.arg === "cut" ? "cut" : "copy";
              };
            }
          },
          unbind: function(el, binding) {
            if (!el._vClipboard)
              return;
            if (binding.arg === "success") {
              delete el._vClipboard_success;
            } else if (binding.arg === "error") {
              delete el._vClipboard_error;
            } else {
              el._vClipboard.destroy();
              delete el._vClipboard;
            }
          }
        });
      },
      config: VueClipboardConfig
    };
    {
      module.exports = VueClipboard2;
    }
  })(vueClipboard);
  var vueClipboardExports = vueClipboard.exports;
  const VueClipboard = /* @__PURE__ */ getDefaultExportFromCjs(vueClipboardExports);
  const CalendarsView_vue_vue_type_style_index_0_lang = "";
  Vue.use(VueClipboard);
  const _sfc_main$1 = {
    props: {
      calendars: Array,
      options: Array
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
      async copyToClipboard(text) {
        try {
          await navigator.clipboard.writeText(text);
          window.panel.notification.success("URL du calendrier copiée dans le presse-papier");
        } catch (err) {
        }
      }
    }
  };
  var _sfc_render$1 = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-header", [_vm._v(" Calendrier "), _c("k-button-group", { attrs: { "slot": "buttons" }, slot: "buttons" }, [_c("k-button", { attrs: { "text": "Ajouter", "variant": "filled", "icon": "add" }, on: { "click": function($event) {
      return _vm.$dialog("calendar/create");
    } } })], 1)], 1), _c("table", { staticClass: "k-table k-calendar" }, [_c("thead", [_c("tr", [_c("th", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v("# ")]), _c("th", [_vm._v("Nom")]), _c("th", [_vm._v("Services")]), _c("th", [_vm._v("Horaires")]), _c("th", [_vm._v("Lien")]), _c("th", { staticClass: "k-table-index-column" })])]), _c("tbody", _vm._l(_vm.calendars, function(calendar, id, index) {
      return _c("tr", { key: id }, [_c("td", { staticClass: "k-table-index-column", staticStyle: { "text-align": "center" } }, [_vm._v(" " + _vm._s(index) + " ")]), _c("td", { staticStyle: { "width": "10%" } }, [_vm._v(_vm._s(calendar.name))]), _c("td", { attrs: { "data-align": "center" } }, [_vm._v(_vm._s(calendar.nbrServices))]), _c("td", { attrs: { "data-align": "center" } }, [_c("k-button", { attrs: { "icon": calendar.scheduleState.icon, "theme": calendar.scheduleState.theme, "variant": "dimmed" } }, [_vm._v(" " + _vm._s(calendar.scheduleState.status) + " ")])], 1), _c("td", [_c("k-button", { on: { "click": function($event) {
        return _vm.copyToClipboard(calendar.ical);
      } } }, [_vm._v(" " + _vm._s(_vm.shortenUrl(calendar.ical)) + " ")])], 1), _c("td", { staticClass: "k-table-options-column" }, [_c("k-options-dropdown", { attrs: { "options": [
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
