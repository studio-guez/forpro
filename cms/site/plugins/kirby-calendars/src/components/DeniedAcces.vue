<template>
  <k-inside>
    <k-header>
      <h1>Access Denied</h1>
    </k-header>

    <k-text>
      <p>Sorry, you do not have permission to access this area.</p>
    </k-text>

  </k-inside>
</template>

<script>
export default {
    name: 'DeniedAccessView',
    props: {
        msg: String,
    },
    methods: {}
};
</script>

<style scoped>
.k-denied-access {
    text-align: center;
    padding: 2rem;
}
</style>
