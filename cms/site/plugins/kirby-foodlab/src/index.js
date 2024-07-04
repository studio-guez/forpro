import RestaurantView from "./components/RestaurantView.vue";
import MenuView from "./components/MenuView.vue";

panel.plugin("mediumsans/foodlab", {
  components: {
    'k-restaurant-view': RestaurantView,
    'k-menu-view': MenuView,
  }
});
