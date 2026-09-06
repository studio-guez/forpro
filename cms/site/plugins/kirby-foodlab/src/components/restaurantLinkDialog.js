/**
 * Shared override for Kirby's `k-link-dialog` (the writer link mark) and
 * `k-toolbar-link-dialog` (the textarea toolbar "link" button).
 *
 * Both dialogs default their `href` field to `{ type: "link" }` with no
 * `options` restriction, so the type dropdown offers "Page" and "File".
 * Picking "File" renders Kirby's `k-file-browser`, which reads
 * `$panel.view.props.model.uuid` to list files. `k-restaurant-view` is a
 * custom area view with no Kirby model behind it (see views/restaurant.php
 * and RestaurantView.vue) — `model` is undefined there, so the browser
 * throws as soon as "File" is selected.
 *
 * Fix: while the restaurant view is active, swap the `href` field for
 * `k-restaurantlink-field` (see RestaurantLinkField.vue), restricted to the
 * link types that don't need a model, plus the restaurant media library in
 * place of the file browser. Every other view keeps Kirby's own default.
 *
 * `k-toolbar-link-dialog` extends `k-link-dialog` in Kirby core through a
 * direct JS import (panel/src/components/Forms/Toolbar/LinkDialog.vue), not
 * through the component registry, so overriding one does not override the
 * other: both are registered from this one helper.
 */
export default function restaurantLinkDialog(name) {
    return {
        extends: name,
        props: {
            fields: {
                default() {
                    // Vue calls prop defaults with the component instance as
                    // `this`. Kirby's plugin installer resolved the string
                    // `extends` above into the core constructor before
                    // registering this override under the same name
                    // (panel/src/panel/plugins.ts, resolveComponentExtension),
                    // so the untouched core default is reachable through it.
                    // Looking it up in the component registry instead would
                    // return this very override and recurse; reading it when
                    // the plugin bundle loads is not possible either, the
                    // Panel app does not exist yet at that point.
                    const fields =
                        this.$options.extends.options.props.fields.default.call(
                            this,
                        );

                    if (window.panel.view.component !== "k-restaurant-view") {
                        return fields;
                    }

                    return {
                        ...fields,
                        href: {
                            ...fields.href,
                            type: "restaurantlink",
                            options: ["url", "email", "tel", "anchor", "custom"],
                            // matches Restaurant::MEDIA_PATH, see
                            // fields/restaurantlink.php
                            mediaPath: "/api/restaurant/media/",
                        },
                    };
                },
            },
        },
    };
}
