<template>
  <k-inside>
    <k-header>
      Restaurant
    </k-header>


  </k-inside>
</template>

<script>
export default {
  props: {},
  methods: {
    goto(path) {
      this.$go(path);
    },
    shortenUrl(url) {
      if (url.length > 25) return url.slice(0, 22) + '...';
      else return url;
    },
    async copyToClipboard(text) {
      try {
        await navigator.clipboard.writeText(text);
        window.panel.notification.success("URL du calendrier copiée dans le presse-papier");
      } catch (err) {
        // Show error message
      }
    },
  }
};
</script>

<style>
.k-table.k-calendar {
  table-layout: fixed;
}
</style>
