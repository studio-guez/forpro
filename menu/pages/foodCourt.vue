<template>
    <section
        class="v-food-court app-page"
        ref="elementToScale"
    >
        <div class="v-food-court__menu app-page__menu"
             ref="elementForSize"
        >
            <div class="v-food-court__menu__header">
                <AppHeader/>
            </div>
            <div class="v-food-court__menu__text"
                 v-if="foodCourtData"
            >
                <AppTextContent
                    day="Lundi"
                    :cuisine_du_monde="[foodCourtData.jour1_station4_menu]"
                    :fourchette_verte="[foodCourtData.jour1_station3_menu]"
                    :burger=          "[foodCourtData.jour1_station1_menu]"
                    :street_food=     "[foodCourtData.jour1_station2_menu]"
                    color="black"
                />
                <AppTextContent
                    day="Mardi"
                    :cuisine_du_monde="[foodCourtData.jour2_station4_menu]"
                    :fourchette_verte="[foodCourtData.jour2_station3_menu]"
                    :burger=          "[foodCourtData.jour2_station1_menu]"
                    :street_food=     "[foodCourtData.jour2_station2_menu]"
                    color="black"
                />
                <AppTextContent
                    day="Mercredi"
                    :cuisine_du_monde="[foodCourtData.jour3_station4_menu]"
                    :fourchette_verte="[foodCourtData.jour3_station3_menu]"
                    :burger=          "[foodCourtData.jour3_station1_menu]"
                    :street_food=     "[foodCourtData.jour3_station2_menu]"
                    color="black"
                />
                <AppTextContent
                    day="Jeudi"
                    :cuisine_du_monde="[foodCourtData.jour4_station4_menu]"
                    :fourchette_verte="[foodCourtData.jour4_station3_menu]"
                    :burger=          "[foodCourtData.jour4_station1_menu]"
                    :street_food=     "[foodCourtData.jour4_station2_menu]"
                    color="black"
                />
                <AppTextContent
                    day="Vendredi"
                    :cuisine_du_monde="[foodCourtData.jour5_station4_menu]"
                    :fourchette_verte="[foodCourtData.jour5_station3_menu]"
                    :burger=          "[foodCourtData.jour5_station1_menu]"
                    :street_food=     "[foodCourtData.jour5_station2_menu]"
                    color="black"
                />
            </div>
            <div class="v-food-court__menu__bg">
                <AppSvgFoodCourt/>
            </div>
        </div>
    </section>
</template>





<script setup lang="ts">
import {nextTick, onBeforeUnmount, onMounted, ref, type Ref, type UnwrapRef} from 'vue'
import AppHeader from "../components/AppHeader.vue";
import AppTextContent from "../components/AppTextContent.vue";
import AppSvgFoodCourt from "../components/AppSvgFoodCourt.vue";
import {scaleTransform} from "~/utils/scaleTransform";
import {
  foodCourt_GetCurrentWeekMenu,
  getFoodCourtData,
  type IMenuData__foodCourt__weekMenu
} from "~/composables/foodCourtData";

const foodCourtData = ref<null | IMenuData__foodCourt__weekMenu>(null)

const elementToScale: Ref<UnwrapRef<null | HTMLElement>> = ref(null)
const elementForSize: Ref<UnwrapRef<null | HTMLElement>> = ref(null)

onMounted(() => {

    getFoodCourtData().then(data => {
      foodCourtData.value = foodCourt_GetCurrentWeekMenu(data)
    })

    nextTick(() => {
        setPageScale()
        window.addEventListener('resize', setPageScale)
    })
})

function setPageScale() {
    if(elementToScale.value && elementForSize.value) scaleTransform({
        elementToScale: elementToScale.value,
        elementForSize: elementForSize.value
    })
}

onBeforeUnmount(() => {
    window.removeEventListener('resize', setPageScale)
})


</script>





<style lang="scss" scoped >
@media screen {
  :global(body) {
    background: var(--app-color--yellow);
  }
}

.v-food-court__menu__header {
    position: absolute;
    top: 5.75cm;
    width: 100%;
}

.v-food-court__menu__text {
    position: absolute;
    top: 11.075cm;
    left: 7.65cm;
    height: 153.4mm;
    width: 12cm;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    //transform: translate(-2cm, 0);
}

.v-food-court__menu__bg {
    user-select: none;
    pointer-events: none;
    width: 100%;
    height: 100%;
}
</style>
