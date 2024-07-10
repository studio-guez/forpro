<template>
    <k-inside>
        <k-header>
            Menu
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
                <k-button
                    :icon="isGeneratingPDF ? 'loader' : 'wand'"
                    :disabled="isGeneratingPDF"
                    variant="filled"
                    @click="generate"
                >
                    Générer PDF
                </k-button>
            </k-button-group>
        </k-header>

        <k-form
            v-model="menu"
            @input="input"
            @submit="submit"
            :fields="formFields"
        />

        <!-- Page 2 -->
        <k-grid style="margin-bottom: 40px; margin-top: 40px">
            <div class="k-column" style="--width: 1/3">
                <hr class="k-line-field" type="line" />
            </div>
            <div
                class="k-column"
                style="
                    --width: 1/3;
                    text-align: center;
                    height: 100%;
                    align-content: center;
                "
            >
                <k-input
                    :value="pageTitle2"
                    type="text"
                    :icon="pageTitleIcon2"
                    @input="updatePageTitle2"
                />
            </div>
            <div class="k-column" style="--width: 1/3">
                <hr class="k-line-field" type="line" />
            </div>
        </k-grid>

        <template v-for="category in page2Order">
            <k-section-header
                :key="`header-${category}`"
                :category="category"
                :title="getSectionTitle(category)"
                :show-hide="getSectionShowHide(category)"
                @up="moveSectionUp(category)"
                @down="moveSectionDown(category)"
                @hide="$dialog(`/menu/${category}/hide`)"
                @edit="$dialog(`/menu/${category}/title`)"
                @create="$dialog(`/menu/${category}/create`)"
            />
            <component
                :is="getSectionComponent(category)"
                :key="`table-${category}`"
                :[getSectionProp(category)]="getSectionData(category)"
                @update-order="updateTableOrder"
                @open-dialog="$dialog"
            />
        </template>

        <!-- Page 3 -->
        <k-grid style="margin-bottom: 40px; margin-top: 40px">
            <div class="k-column" style="--width: 1/3">
                <hr class="k-line-field" type="line" />
            </div>
            <div
                class="k-column"
                style="
                    --width: 1/3;
                    text-align: center;
                    height: 100%;
                    align-content: center;
                "
            >
                <k-input
                    :value="pageTitle3"
                    type="text"
                    :icon="pageTitleIcon3"
                    @input="updatePageTitle3"
                />
            </div>
            <div class="k-column" style="--width: 1/3">
                <hr class="k-line-field" type="line" />
            </div>
        </k-grid>

        <template v-for="category in page3Order">
            <k-section-header
                :key="`header-${category}`"
                :category="category"
                :title="getSectionTitle(category)"
                :show-hide="getSectionShowHide(category)"
                @up="moveSectionUp(category)"
                @down="moveSectionDown(category)"
                @hide="$dialog(`/menu/${category}/hide`)"
                @edit="$dialog(`/menu/${category}/title`)"
                @create="$dialog(`/menu/${category}/create`)"
            />
            <component
                :is="getSectionComponent(category)"
                :key="`table-${category}`"
                :[getSectionProp(category)]="getSectionData(category)"
                @update-order="updateTableOrder"
                @open-dialog="$dialog"
            />
        </template>

        <!-- Page 4 -->
        <k-grid style="margin-bottom: 40px; margin-top: 40px">
            <div class="k-column" style="--width: 1/3">
                <hr class="k-line-field" type="line" />
            </div>
            <div
                class="k-column"
                style="
                    --width: 1/3;
                    text-align: center;
                    height: 100%;
                    align-content: center;
                "
            >
                <k-input
                    :value="pageTitle4"
                    type="text"
                    :icon="pageTitleIcon4"
                    @input="updatePageTitle4"
                />
            </div>
            <div class="k-column" style="--width: 1/3">
                <hr class="k-line-field" type="line" />
            </div>
        </k-grid>

        <template v-for="category in page4Order">
            <k-section-header
                :key="`header-${category}`"
                :category="category"
                :title="getSectionTitle(category)"
                :show-hide="getSectionShowHide(category)"
                @up="moveSectionUp(category)"
                @down="moveSectionDown(category)"
                @hide="$dialog(`/menu/${category}/hide`)"
                @edit="$dialog(`/menu/${category}/title`)"
                @create="$dialog(`/menu/${category}/create`)"
            />
            <component
                :is="getSectionComponent(category)"
                :key="`table-${category}`"
                :[getSectionProp(category)]="getSectionData(category)"
                @update-order="updateTableOrder"
                @open-dialog="$dialog"
            />
        </template>

        <!-- Divers -->
        <k-grid style="margin-bottom: 40px; margin-top: 40px">
            <div class="k-column" style="--width: 1">
                <hr class="k-line-field" type="line" />
            </div>
        </k-grid>

        <k-text style="margin-bottom: 20px">
            <h2>Divers</h2>
        </k-text>

        <k-form
            v-model="menu"
            @input="input"
            @submit="submit"
            :fields="diversFields"
        />

        <k-grid style="margin-top: 40px">
            <div class="k-column" style="--width: 1/3; justify-self: start">
                <k-input
                    :value="originsTitle"
                    type="text"
                    :icon="originTitleIcon"
                    @input="updateOriginTitle($event)"
                />
            </div>
            <div class="k-column" style="--width: 2/3; justify-self: end">
                <k-button-group layout="collapsed">
                    <k-button
                        variant="filled"
                        icon="plus"
                        @click="$dialog('/menu/origin/create')"
                    >
                        Ajouter
                    </k-button>
                </k-button-group>
            </div>
        </k-grid>
        <table class="k-table" style="margin-top: 20px; margin-bottom: 25px">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Provenance</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="origins"
                :handle="true"
                @change="updateTableOrder('origins')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in origins" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.origin }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(`menu/origin/${item.id}/edit`),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/origin/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>
    </k-inside>
</template>

<script>
export default {
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
        origins: Array,
        originsTitle: String,
        textTitle1: String,
        textSubtitle1: String,
        textContent1: String,
        textTitle2: String,
        textSubtitle2: String,
        textContent2: String,
        textURL: String,
        textTVA: String,
        textAllergy: String,
        pageTitle2: String,
        pageTitle3: String,
        pageTitle4: String,
        page2Order: Array,
        page3Order: Array,
        page4Order: Array,
    },
    data() {
        return {
            menu: {
                textTitle1: this.textTitle1,
                textSubtitle1: this.textSubtitle1,
                textContent1: this.textContent1,
                textTitle2: this.textTitle2,
                textSubtitle2: this.textSubtitle2,
                textContent2: this.textContent2,
                textURL: this.textURL,
                textTVA: this.textTVA,
                textAllergy: this.textAllergy,
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
                    width: "1/2",
                },
                textSubtitle1: {
                    label: "Sous-titre 1",
                    type: "text",
                    width: "1/2",
                },
                textContent1: {
                    label: "Contenu 1",
                    type: "textarea",
                },
                textTitle2: {
                    label: "Titre 2",
                    type: "text",
                    width: "1/2",
                },
                textSubtitle2: {
                    label: "Sous-titre 2",
                    type: "text",
                    width: "1/2",
                },
                textContent2: {
                    label: "Contenu 2",
                    type: "textarea",
                },
            },
            diversFields: {
                textURL: {
                    label: "URL",
                    type: "text",
                    help: "Apparaît dans le footer de la première page",
                    width: "1",
                },
                textTVA: {
                    label: "TVA",
                    type: "textarea",
                    help: "Apparaît dans le footer de la deuxième, troisième et quatrième page",
                    width: "1/2",
                },
                textAllergy: {
                    label: "Alergies",
                    type: "textarea",
                    help: "Apparaît dans le footer de la deuxième page",
                    width: "1/2",
                },
            },
            page2Order: this.page2Order,
            page3Order: this.page3Order,
            page4Order: this.page4Order,
            pageTitle2: this.pageTitle2,
            pageTitle3: this.pageTitle3,
            pageTitle4: this.pageTitle4,
        };
    },
    methods: {
        goto(path) {
            this.$go(path);
        },
        shortenUrl(url) {
            if (url.length > 25) return url.slice(0, 22) + "...";
            else return url;
        },
        submit() {
            this.isSubmitting = true;
            this.$api.post("/restaurant/menu/create", this.menu);

            setTimeout(() => {
                this.isSubmitting = false;
                this.hasBeenSubmitted = true;

                setTimeout(() => {
                    this.hasBeenSubmitted = false;
                }, 5000);
            }, 1500);
        },
        generate() {
            if (this.isGeneratingPDF) return;

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
                        "Le PDF a été généré avec succès",
                    );
                }, 1000);
            };

            iframe.src = this.$api.endpoint + "/restaurant/menu/generate";
            setTimeout(() => {
                this.isGeneratingPDF = false;
            }, 2500);
        },
        updateTableOrder(category) {
            const updatedList = this[category];

            this.$api
                .post(`/restaurant/menu/${category}/reorder`, updatedList)
                .then(() => {
                    // The list is already updated in the component's data, so we don't need to set it again
                    this.$store.dispatch(
                        "notification/success",
                        "Order updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating order:", error);
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update order",
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
                    newOrder[index - 1],
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
                    newOrder[index],
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
            this.$api
                .post("/restaurant/menu/page-title-2", { value })
                .then(() => {
                    this.pageTitle2 = value;
                    this.isEditing = false;
                    this.hasBeenEdited = true;
                    setTimeout(() => {
                        this.hasBeenEdited = false;
                    }, 2000);
                    this.$store.dispatch(
                        "notification/success",
                        "Page title updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating page title:", error);
                    this.isEditing = false;
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update page title",
                    );
                });
        },
        updatePageTitle3(value) {
            this.isEditing = true;
            this.$api
                .post("/restaurant/menu/page-title-3", { value })
                .then(() => {
                    this.pageTitle3 = value;
                    this.isEditing = false;
                    this.hasBeenEdited = true;
                    setTimeout(() => {
                        this.hasBeenEdited = false;
                    }, 2000);
                    this.$store.dispatch(
                        "notification/success",
                        "Page title updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating page title:", error);
                    this.isEditing = false;
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update page title",
                    );
                });
        },
        updatePageTitle4(value) {
            this.isEditing = true;
            this.$api
                .post("/restaurant/menu/page-title-4", { value })
                .then(() => {
                    this.pageTitle4 = value;
                    this.isEditing = false;
                    this.hasBeenEdited = true;
                    setTimeout(() => {
                        this.hasBeenEdited = false;
                    }, 2000);
                })
                .catch((error) => {
                    console.error("Error updating page title:", error);
                    this.isEditing = false;
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update page title",
                    );
                });
        },
        updateUrl(value) {
            this.isEditing = true;
            this.$api
                .post("/restaurant/menu/metadata/url", { value })
                .then(() => {
                    this.urlText = value;
                    this.isEditing = false;
                    this.hasBeenEdited = true;
                    setTimeout(() => {
                        this.hasBeenEdited = false;
                    }, 2000);
                })
                .catch((error) => {
                    console.error("Error updating page title:", error);
                    this.isEditing = false;
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update URL",
                    );
                });
        },
        updateTVA(value) {
            this.isEditing = true;
            this.$api
                .post("/restaurant/menu/metadata/tva", { value })
                .then(() => {
                    this.tvaText = value;
                    this.isEditing = false;
                    this.hasBeenEdited = true;
                    setTimeout(() => {
                        this.hasBeenEdited = false;
                    }, 2000);
                    this.$store.dispatch(
                        "notification/success",
                        "URL has been updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating page title:", error);
                    this.isEditing = false;
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update URL",
                    );
                });
        },
        updateSectionTitle(category, value) {
            this.$api
                .post("/restaurant/menu/metadata/name", { category, value })
                .then(() => {
                    const propName = `${this.getSectionProp(category)}Title`;
                    this.$set(this, propName, value);
                    this.$store.dispatch(
                        "notification/success",
                        "Section title updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating section title:", error);
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update section title",
                    );
                });
        },
        updateOriginTitle(value) {
            this.$api
                .post("/restaurant/menu/metadata/name", {
                    category: "origin",
                    value,
                })
                .then(() => {
                    this.$store.dispatch(
                        "notification/success",
                        "Section title updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating section title:", error);
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update section title",
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
                hotdrink: "k-hot-drink-table",
                origin: "k-origin-table",
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
                hotdrink: "hotDrinks",
                origin: "origins",
            };
            return propMap[category];
        },
        getSectionData(category) {
            return this[this.getSectionProp(category)];
        },
        updateSectionOrder(newOrder, page) {
            this.$api
                .post(`/restaurant/menu/metadata/${page}/order`, {
                    order: newOrder,
                })
                .then(() => {
                    this.$set(this, `page${page}Order`, newOrder);
                    this.$store.dispatch(
                        "notification/success",
                        "Section order updated successfully",
                    );
                })
                .catch((error) => {
                    console.error("Error updating section order:", error);
                    this.$store.dispatch(
                        "notification/error",
                        "Failed to update section order",
                    );
                });
        },
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
        },
        originTitleIcon() {
            if (this.hasBeenEdited) {
                return "check";
            } else if (this.isEditing) {
                return "loader";
            } else {
                return "edit";
            }
        },
    },
};
</script>

<style>
.k-restaurant-button[data-theme^="green"],
.k-restaurant-button[data-theme^="positive"] {
    color: black;
    background-color: hsl(80, 60%, calc(80% + -2.5%)) !important;
}
</style>
