<template>
  <div>
    <h1>Blog Posts</h1>
    <ul class="timeline" itemscope itemtype="http://schema.org/Blog">
      <li v-for="post in posts" :key="post.id" class="timeline-item" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="timeline-item-image"></div>
        <div class="timeline-item-content">
          <h2 itemprop="name headline">
            <router-link :to="'/blog/' + post.id" itemprop="url">{{ post.title }}</router-link>
          </h2>
          <p itemprop="description">{{ post.summary }}</p>
          <time class="timeline-item-date" :datetime="post.created.toISOString()" itemprop="datePublished">
            {{ post.created.toLocaleDateString() }}
          </time>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    name: 'Blog',

    metaInfo: {
      title: 'Blog',
    },

    computed: {
      posts() {
        return this.$store.getters.posts;
      },
    },
  };
</script>

<style scoped lang="scss">
  @import "../styles/config";
  @import "../styles/components/timeline";
</style>
