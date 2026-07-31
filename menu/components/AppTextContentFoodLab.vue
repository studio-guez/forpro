<template>
    <section class="v-app-text-content-foodlab"
    >
        <div class="v-app-text-content-foodlab__content">
          <slot/>
        </div>
    </section>
</template>





<script setup lang="ts">
import { defineProps } from 'vue'

const props = defineProps<{
    color: string
}>()
</script>





<style lang="scss" scoped >
.v-app-text-content-foodlab {
  color: v-bind(color);
}

.v-app-text-content-foodlab__title {
    font-size: 17pt;
    line-height: 15pt;
    font-weight: 600;
}

.v-app-text-content-foodlab__content {
    font-size: 10.5pt;
    line-height: 15pt;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    height: 60pt;
}
</style>
