<template>
    <section class="v-app-text-content"
    >
        <div class="v-app-text-content__title" v-if="day">{{day}}</div>
        <div class="v-app-text-content__content" v-html="toSingleLine(cuisine_du_monde)"/>
        <div class="v-app-text-content__content" v-html="toSingleLine(fourchette_verte)"/>
        <div class="v-app-text-content__content" v-html="toSingleLine(burger)"/>
        <div class="v-app-text-content__content" v-html="toSingleLine(street_food)"/>
    </section>
</template>





<script setup lang="ts">
import type {CellValue} from "read-excel-file";
import {flattenLineBreaks} from "~/utils/flattenLineBreaks";

const props = defineProps<{
    day?: string
    cuisine_du_monde: CellValue[]
    fourchette_verte: CellValue[]
    burger: CellValue[]
    street_food: CellValue[]
    color: string
}>()

function toSingleLine(values: CellValue[]): string {
    return values
        .map(flattenLineBreaks)
        .filter(Boolean)
        .join(", ")
}
</script>





<style lang="scss" scoped >
.v-app-text-content {
  color: v-bind(color);
}

.v-app-text-content__title {
    font-size: 17pt;
    line-height: 15pt;
    font-weight: 600;
}

.v-app-text-content__content {
    font-size: 10.5pt;
    line-height: 15pt;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
}
</style>
