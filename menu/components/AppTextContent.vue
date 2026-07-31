<template>
    <section class="v-app-text-content"
    >
        <div class="v-app-text-content__title" v-if="day">{{day}}</div>
        <div class="v-app-text-content__content">{{cuisine_du_monde.filter(Boolean).join(", ")}}</div>
        <div class="v-app-text-content__content">{{fourchette_verte.filter(Boolean).join(", ")}}</div>
        <div class="v-app-text-content__content">{{burger.filter(Boolean).join(", ")}}</div>
        <div class="v-app-text-content__content">{{street_food.filter(Boolean).join(", ")}}</div>
    </section>
</template>





<script setup lang="ts">
import { defineProps } from 'vue'
import type {CellValue} from "read-excel-file";

const props = defineProps<{
    day?: string
    cuisine_du_monde: CellValue[]
    fourchette_verte: CellValue[]
    burger: CellValue[]
    street_food: CellValue[]
    color: string
}>()
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
