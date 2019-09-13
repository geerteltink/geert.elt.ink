<template>
  <div class="app-hero flex-container" itemscope itemtype="http://schema.org/WPHeader">
    <div class="app-hero-content text-center container">
      <!--h1 class="app-hero-title" itemprop="headline">xtreamwayz</h1>
      <p class="app-hero-text" itemprop="description">Building web applications since 1999.</p-->
    </div>
  </div>
</template>

<script>
  export default {
    name: 'AppHero',
  };
</script>

<style scoped lang="scss">
  @import "../styles/config";

  .app-hero {
    min-height: 196px;

    color: $header-color;
    background: transparent url("../assets/banner-background.jpg") no-repeat center center;
    background-size: cover;
  }

  .app-hero-content {
    margin-left: 2em;
    margin-right: 2em;
  }

  .app-hero-title {
    font-size: 3vw;

    text-transform: uppercase;
  }

  .app-hero-text {
    font-size: 1.1rem;
    font-style: italic;
  }
</style>
