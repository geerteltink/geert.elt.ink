<template>
  <div class="d-flex justify-content-center align-items-stretch">
    <a href="https://github.com/xtreamwayz" title="Follow me on GitHub">
      <svg class="icon icon-github">
        <use xlink:href="/img/icons.svg#icon-github"></use>
      </svg>
      / xtreamwayz
    </a>
  </div>
</template>

<script>
  export default {
    name: 'Code',
    metaInfo: {
      title: 'Code',
    },
  };
</script>

<style scoped lang="scss">
  @import "../styles/config";
  @import "../styles/components/project-list";

  a {
    font-size: 8vw;
    margin-top: 4vh;
  }
</style>
