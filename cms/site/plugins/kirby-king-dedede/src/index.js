import FacebookPreview from "./components/facebook-preview.vue";
import GooglePreview from "./components/google-preview.vue";
import TwitterPreview from "./components/twitter-preview.vue";

window.panel.plugin("mediumsans/king-dedede", {
  sections: {
    'facebook-preview': FacebookPreview,
    'google-preview': GooglePreview,
    'twitter-preview': TwitterPreview
  }
});
