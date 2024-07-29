import MenuView from "./components/MenuView.vue";
import MenuSpecialView from "./components/MenuSpecialView.vue";
import SectionHeader from "./components/SectionHeader.vue";
import BeerTable from "./components/BeerTable.vue";
import CocktailTable from "./components/CocktailTable.vue";
import DessertTable from "./components/DessertTable.vue";
import HotDrinkTable from "./components/HotDrinkTable.vue";
import MainCourseTable from "./components/MainCourseTable.vue";
import StarterTable from "./components/StarterTable.vue";
import WhiteWineTable from "./components/WhiteWineTable.vue";
import RedWineTable from "./components/RedWineTable.vue";
import BubbleWineTable from "./components/BubbleWineTable.vue";
import SoftDrinkTable from "./components/SoftDrinkTable.vue";

panel.plugin("mediumsans/foodlab", {
  components: {
    "k-menu-view": MenuView,
    "k-menu-special-view": MenuSpecialView,
    "k-section-header": SectionHeader,
    "k-beer-table": BeerTable,
    "k-dessert-table": DessertTable,
    "k-hot-drink-table": HotDrinkTable,
    "k-main-course-table": MainCourseTable,
    "k-starter-table": StarterTable,
    "k-white-wine-table": WhiteWineTable,
    "k-red-wine-table": RedWineTable,
    "k-bubble-wine-table": BubbleWineTable,
    "k-soft-drink-table": SoftDrinkTable,
    "k-cocktail-table": CocktailTable,
  },
});
