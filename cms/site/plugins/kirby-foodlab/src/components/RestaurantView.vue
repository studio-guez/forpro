<template>
    <k-inside>
        <k-header>
            Restaurant
            <k-button-group slot="buttons">
                <k-button icon="check" variant="filled" ^ @click="submit">
                    Enregistrer
                </k-button>
            </k-button-group>
        </k-header>

        <k-form
            v-model="menu"
            @input="input"
            @submit="submit"
            :fields="{
                hero1: {
                    type: 'headline',
                    label: 'Héro 1',
                },
                btnHero1: {
                    label: 'Action 1',
                    type: 'text',
                    width: '1/2',
                },
                btnHero2: {
                    label: 'Action 2',
                    type: 'text',
                    width: '1/2',
                },
                picHero1: {
                    label: 'Image 1',
                    type: 'files',
                    width: '1/2',
                },
                picHero2: {
                    label: 'Image 2',
                    type: 'files',
                    width: '1/2',
                },
                picHero2: {
                    label: 'Image 3',
                    type: 'files',
                    width: '1/2',
                },
                textHero1: {
                    label: 'Text Hero 1',
                    type: 'textarea',
                    width: '1/2',
                },
                line1: {
                    type: 'line',
                },
                lefood: {
                    type: 'headline',
                    label: 'Le Food',
                },
                titleFood: {
                    label: 'Title 1',
                    type: 'text',
                    width: '1',
                },
                textFood: {
                    label: 'Text Hero 1',
                    type: 'textarea',
                    width: '1/2',
                },
                fileFood1: {
                    label: 'Image 1',
                    type: 'files',
                    width: '1/2',
                },
                btnFood: {
                    label: 'Action 1',
                    type: 'text',
                    width: '1/2',
                },
                line2: {
                    type: 'line',
                },
                lelab: {
                    type: 'headline',
                    label: 'Le Lab',
                },
                titleLab: {
                    label: 'Title 1',
                    type: 'text',
                    width: '1',
                },
                fileLab1: {
                    label: 'Image 1',
                    type: 'files',
                    width: '1/2',
                },
                textLab: {
                    label: 'Text Hero 1',
                    type: 'textarea',
                    width: '1/2',
                },
                btnLab: {
                    label: 'Action 1',
                    type: 'text',
                    width: '1/2',
                },
                line3: {
                    type: 'line',
                },
                picture1: {
                    type: 'files',
                    label: 'Image 1',
                    width: '1',
                },
                line4: {
                    type: 'line',
                },
                formation: {
                    type: 'headline',
                    label: 'Equipe & Formation',
                },
                titleFormation: {
                    label: 'Title 1',
                    type: 'text',
                    width: '1',
                },
                textFormation: {
                    label: 'Text Hero 1',
                    type: 'textarea',
                    width: '1/2',
                },
                fileFormation: {
                    label: 'Image 1',
                    type: 'files',
                    width: '1/2',
                },
                btnFormation: {
                    label: 'Action 1',
                    type: 'text',
                    width: '1/2',
                },
                line5: {
                    type: 'line',
                },
                univers: {
                    type: 'headline',
                    label: 'Univers du Food',
                },
                titleUnivers: {
                    label: 'Title',
                    type: 'text',
                    width: '1',
                },
                subtitleUnivers: {
                    label: 'Subtitle',
                    type: 'text',
                    width: '1',
                },
                blogUniversTitle1: {
                    label: 'Title',
                    type: 'text',
                    width: '1/2',
                },
                blogUniversFil1: {
                    label: 'Title',
                    type: 'text',
                    width: '1/2',
                },
                blogUniversText1: {
                    label: 'Text',
                    type: 'textarea',
                    width: '1',
                },
                blogUniversTitle2: {
                    label: 'Title',
                    type: 'text',
                    width: '1/2',
                },
                blogUniversFile2: {
                    label: 'Title',
                    type: 'text',
                    width: '1/2',
                },
                blogUniversText2: {
                    label: 'Text',
                    type: 'textarea',
                    width: '1',
                },
                line6: {
                    type: 'line',
                },
                valuesHeadline: {
                    type: 'headline',
                    label: 'Engagement',
                },
                titleValues: {
                    label: 'Title',
                    type: 'text',
                    width: '1',
                },
                textValues: {
                    label: 'Text',
                    type: 'textarea',
                    width: '1',
                },
                lstValues: {
                    label: 'Liste',
                    type: 'structure',
                    fields: {
                        title: {
                            label: 'Titre',
                            type: 'text',
                        },
                        icon: {
                            label: 'Icon',
                            type: 'files',
                        },
                    },
                },
            }"
        />
    </k-inside>
</template>

<script>
export default {
    props: {
        restaurant: {
            type: Object,
        },
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
        };
    },
    methods: {
        goto(path) {
            this.$go(path);
        },
        submit() {
            this.$api.post("/restaurant/update", this.restaurant);
        },
    },
};
</script>

<style>
.k-table.k-calendar {
    table-layout: fixed;
}
</style>
