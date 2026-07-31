<template>
    <div class="v-app" >
        <NuxtLayout>
            <NuxtPage/>
        </NuxtLayout>

<!--        <div style="position: fixed; z-index: -1; top: 0; left: 50%; transform: translate(-50%, 0); width: 21cm; height: 29.7cm; background: #d5d5d5"></div>-->

    </div>
</template>





<script setup lang="ts">
import { useHead } from '#imports'

useHead({
    script: [{
        type: 'application/ld+json',
        innerHTML: JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'FoodEstablishment',
            name: 'ForPro',
            url: 'https://menus.for-pro.ch',
            hasMenu: {
                '@type': 'Menu',
                name: 'Menus de la semaine',
                url: 'https://menus.for-pro.ch',
            },
        }),
    }],
})
</script>





<style lang="scss" scoped >
</style>
