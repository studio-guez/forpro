import MenuView from "./components/MenuView.vue";
import MenuSpecialView from "./components/MenuSpecialView.vue";
import RestaurantView from "./components/RestaurantView.vue";
import RestaurantFilesField from "./components/RestaurantFilesField.vue";
import RestaurantFilesFieldPreview from "./components/RestaurantFilesFieldPreview.vue";
import RestaurantLinkField from "./components/RestaurantLinkField.vue";
import RestaurantLinkFieldPreview from "./components/RestaurantLinkFieldPreview.vue";
import RestaurantMediaInput from "./components/RestaurantMediaInput.vue";
import restaurantLinkDialog from "./components/restaurantLinkDialog.js";
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

panel.plugin("eclypsys/foodlab", {
  fields: {
    restaurantfiles: RestaurantFilesField,
    restaurantlink: RestaurantLinkField,
  },
  components: {
    "k-restaurantfiles-field-preview": RestaurantFilesFieldPreview,
    "k-restaurantlink-field-preview": RestaurantLinkFieldPreview,
    "k-restaurant-media-input": RestaurantMediaInput,
    "k-link-dialog": restaurantLinkDialog("k-link-dialog"),
    "k-toolbar-link-dialog": restaurantLinkDialog("k-toolbar-link-dialog"),
    "k-menu-view": MenuView,
    "k-menu-special-view": MenuSpecialView,
    "k-restaurant-view": RestaurantView,
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
