<template>
  <header>
    <nav class="navbar navbar-light justify-content-between">
      <div class="container">
        <router-link to="/" class="navbar-brand" itemprop="url">
          <svg class="icon icon-logo" style="font-size:20px;">
            <use xlink:href="/img/icons.svg#logo"></use>
          </svg>
          <strong itemprop="name" class="ml-2">XTREAMWAYZ</strong>
        </router-link>
        <ul class="nav">
          <li class="nav-item" itemprop="url">
            <router-link to="/blog" class="nav-link" itemprop="name">Blog</router-link>
            <!--a class="nav-link" href="{{ path('blog') }}" itemprop="name">Blog</a-->
          </li>
          <li class="nav-item" itemprop="url">
            <router-link to="/code" class="nav-link" itemprop="name">Code</router-link>
            <!--a class="nav-link" href="{{ path('code') }}" itemprop="name">Code</a-->
          </li>
          <li class="nav-item" itemprop="url">
            <router-link to="/contact" class="nav-link" itemprop="name">Contact</router-link>
            <!--a class="nav-link" href="{{ path('contact') }}" itemprop="name">Contact</a-->
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script>
  export default {
    name: 'AppHeader',
  };
</script>

<style scoped lang="scss">

</style>
