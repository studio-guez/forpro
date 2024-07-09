<template>
    <k-inside>
      
        <k-header>
            Menu
            <k-button-group slot="buttons">
                <k-button
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
            :fields="{
                textTitle1: {
                    label: 'Titre 1',
                    type: 'text',
                    width: '1/2',
                },
                textSubtitle1: {
                    label: 'Sous-titre 1',
                    type: 'text',
                    width: '1/2',
                },
                textContent1: {
                    label: 'Contenu 1',
                    type: 'textarea',
                },
                textTitle2: {
                    label: 'Titre 2',
                    type: 'text',
                    width: '1/2',
                },
                textSubtitle2: {
                    label: 'Sous-titre 2',
                    type: 'text',
                    width: '1/2',
                },
                textContent2: {
                    label: 'Contenu 2',
                    type: 'textarea',
                },
                line1: {
                    type: 'line',
                },
            }"
        />

        <k-bar>
            <div>
                <k-text>
                    <h4>Entrées</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/starter/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Plat</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="starters"
                :handle="true"
                @change="updateOrder('starters')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in starters" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(`menu/starter/${item.id}/edit`),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/starter/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Plats principaux</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/maincourse/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Plat</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="mainCourses"
                :handle="true"
                @change="updateOrder('mainCourses')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in mainCourses" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(
                                            `menu/maincourse/${item.id}/edit`,
                                        ),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/maincourse/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Desserts</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/dessert/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Plat</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="desserts"
                :handle="true"
                @change="updateOrder('desserts')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in desserts" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(`menu/dessert/${item.id}/edit`),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/dessert/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Vins Pétillants</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/bubblewine/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Domaine</th>
                    <th>Millésime</th>
                    <th>Description</th>
                    <th>10cl</th>
                    <th>75cl</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="bubbleWines"
                :handle="true"
                @change="updateOrder('bubbleWines')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in bubbleWines" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.domain }}</td>
                    <td>{{ item.mill }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price10cl }}</td>
                    <td>{{ item.price75cl }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(
                                            `menu/bubblewine/${item.id}/edit`,
                                        ),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/bubblewine/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Vins Blancs</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/whitewine/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Domaine</th>
                    <th>Millésime</th>
                    <th>Description</th>
                    <th>10cl</th>
                    <th>75cl</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="whiteWines"
                :handle="true"
                @change="updateOrder('whiteWines')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in whiteWines" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.domain }}</td>
                    <td>{{ item.mill }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price10cl }}</td>
                    <td>{{ item.price75cl }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(
                                            `menu/whitewine/${item.id}/edit`,
                                        ),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/whitewine/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Vins Rouges</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/redwine/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Domaine</th>
                    <th>Millésime</th>
                    <th>Description</th>
                    <th>10cl</th>
                    <th>75cl</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="redWines"
                :handle="true"
                @change="updateOrder('redWines')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in redWines" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.domain }}</td>
                    <td>{{ item.mill }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price10cl }}</td>
                    <td>{{ item.price75cl }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(`menu/redwine/${item.id}/edit`),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/redwine/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Boissons froides</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/softdrink/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Volume</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="softDrinks"
                :handle="true"
                @change="updateOrder('softDrinks')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in softDrinks" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.volume }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(
                                            `menu/softdrink/${item.id}/edit`,
                                        ),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/softdrink/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Bières</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/beer/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Volume</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="beers"
                :handle="true"
                @change="updateOrder('beers')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in beers" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.volume }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(`menu/beer/${item.id}/edit`),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(`menu/beer/${item.id}/delete`),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Apéritives et digestives</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/cocktail/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Volume</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="cocktails"
                :handle="true"
                @change="updateOrder('cocktails')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in cocktails" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.volume }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(
                                            `menu/cocktail/${item.id}/edit`,
                                        ),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/cocktail/${item.id}/delete`,
                                        ),
                                },
                            ]"
                        />
                    </td>
                </tr>
            </k-draggable>
        </table>

        <k-bar>
            <div>
                <k-text>
                    <h4>Boissons Chaudes</h4>
                </k-text>
            </div>
            <div></div>
            <div>
                <k-button
                    variant="filled"
                    icon="plus"
                    @click="$dialog('/menu/hotdrink/create')"
                >
                    Ajouter
                </k-button>
            </div>
        </k-bar>

        <table style="margin-top: 20px; margin-bottom: 25px" class="k-table">
            <thead>
                <tr>
                    <th class="k-table-index-column"></th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Volume</th>
                    <th>Prix</th>
                    <th class="k-table-options-column"></th>
                </tr>
            </thead>
            <k-draggable
                :list="hotDrinks"
                :handle="true"
                @change="updateOrder('hotDrinks')"
                :options="{
                    fallbackClass: 'k-table-row-fallback',
                    ghostClass: 'k-table-row-ghost',
                }"
                element="tbody"
            >
                <tr v-for="(item, index) in hotDrinks" :key="item.id">
                    <td class="k-table-index-column" data-sortable="true">
                        <span class="k-table-index">{{ index + 1 }}</span>
                        <k-sort-handle />
                    </td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.volume }}</td>
                    <td>{{ item.price }}</td>
                    <td class="k-table-options-column">
                        <k-options-dropdown
                            :options="[
                                {
                                    text: 'Modifier',
                                    icon: 'edit',
                                    click: () =>
                                        $dialog(
                                            `menu/hotdrink/${item.id}/edit`,
                                        ),
                                },
                                {
                                    text: 'Supprimer',
                                    icon: 'trash',
                                    click: () =>
                                        $dialog(
                                            `menu/hotdrink/${item.id}/delete`,
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
        textContent2: String,
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
            },
            isGeneratingPDF: false,
            isSubmitting: false,
            hasBeenSubmitted: false,
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
        updateOrder(listName) {
            this.$api.post(
                `/restaurant/menu/${listName}/reorder`,
                this[listName],
            );
          this.isSubmitting = true;
          setTimeout(() => {
            this.isSubmitting = false;
            this.hasBeenSubmitted = true;

            setTimeout(() => {
              this.hasBeenSubmitted = false;
            }, 5000);
          }, 1500);
        },
    },
};
</script>
