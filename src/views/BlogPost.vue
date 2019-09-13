<template>
  <article itemscope itemtype="http://schema.org/BlogPosting" class="container">
    <header>
      <h1 itemprop="name headline">
        {{ attributes.title }}
      </h1>
      <div class="article-meta">
        <svg v-if="attributes.published" class="icon icon-clock">
          <use xlink:href="/img/icons.svg#icon-clock"></use>
        </svg>
        <time v-if="attributes.published" :datetime="attributes.published.toISOString()" itemprop="datePublished" title="Published">
          {{ attributes.published.toDateString() }}
        </time>
        &nbsp;
        <svg v-if="attributes.modified" class="icon icon-sync">
          <use xlink:href="/img/icons.svg#icon-sync"></use>
        </svg>
        <time v-if="attributes.modified" :datetime="attributes.modified.toISOString()" itemprop="datePublished" title="Modified">
          {{ attributes.modified.toLocaleString() }}
        </time>
        &nbsp;
        <svg v-if="attributes.tags" class="icon icon-tag">
          <use xlink:href="/img/icons.svg#icon-tag"></use>
        </svg>
        <span itemprop="keywords" v-for="tag in attributes.tags" :key="tag"> &num;{{ tag }} </span>
      </div>
      <hr />
    </header>
    <div class="content" itemprop="text" v-html="body"></div>
    <footer>
      <ul class="list-inline share-social">
        <li class="list-inline-item">Share this article:</li>
        <li class="list-inline-item">
          <a :href="twitterUri" title="Share on Twitter" target="_blank">
            <svg class="icon icon-twitter-square">
              <use xlink:href="/img/icons.svg#icon-twitter-square"></use>
            </svg>
            Twitter
          </a>
        </li>
        <li class="list-inline-item">
          <a :href="facebookUri" title="Share on Facebook" target="_blank">
            <svg class="icon icon-facebook-square">
              <use xlink:href="/img/icons.svg#icon-facebook-square"></use>
            </svg>
            Facebook
          </a>
        </li>
      </ul>
      <hr />
      <div id="disqus_thread"></div>
    </footer>
  </article>
</template>

<script>
  import fm from 'front-matter';
  import hljs from 'highlight.js/lib/highlight';
  import bash from 'highlight.js/lib/languages/bash';
  import javascript from 'highlight.js/lib/languages/javascript';
  import php from 'highlight.js/lib/languages/php';
  import powershell from 'highlight.js/lib/languages/powershell';
  import xml from 'highlight.js/lib/languages/xml';
  import yaml from 'highlight.js/lib/languages/yaml';
  import marked from 'marked';

  hljs.registerLanguage('bash', bash);
  hljs.registerLanguage('javascript', javascript);
  hljs.registerLanguage('php', php);
  hljs.registerLanguage('powershell', powershell);
  hljs.registerLanguage('xml', xml);
  hljs.registerLanguage('yaml', yaml);

  export default {
    name: 'BlogPost',

    metaInfo () {
      return {
        title: (this.post) ? this.post.title : 'I am',
      }
    },

    data: function () {
      return {
        id: null,
        content: {
          attributes: {
            id: null,
            title: null,
            published: null,
            modified: null,
            tags: [],
          },
          body: '*loading, please wait...*',
        },
      };
    },

    computed: {
      post() {
        return this.$store.getters.post(this.id);
      },
      attributes() {
        return this.content.attributes;
      },
      body() {
        return marked(this.content.body);
      },
      twitterUri() {
        const uri = `http://twitter.com/share?url=https://xtreamwayz.com/blog/${this.id}&amp;text=${this.attributes.title}`;
        return encodeURI(uri);
      },
      facebookUri() {
        const uri = `http://www.facebook.com/sharer.php?u=https://xtreamwayz.com/blog/${this.id}&amp;t=${this.attributes.title}`;
        return encodeURI(uri);
      },
    },

    created: function () {
      this.id = this.$route.params.id;
      const post = this.post;
      if (!post) {
        return this.$router.push('/404');
      }

      fetch(`/api/posts/${this.id}.md`)
        .then(response => response.text())
        .then(data => {
          this.content = fm(data);
        });
    },

    updated: function () {
      this.$nextTick(function () {
        let blocks = document.querySelectorAll('pre code:not(.hljs)');
        blocks.forEach((block) => {
          hljs.highlightBlock(block);
        });
      });
    },
  };
</script>

<style lang="scss">
  @import "../styles/config";
  @import "../styles/components/article";
  @import "../../node_modules/highlight.js/styles/darcula.css";

  pre {
    background: #2b2b2b;
    border-radius: 2px;
    border: 1px solid #292929;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.24);
    transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
  }

  pre:hover {
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  }

  pre code {
    display: block;
    padding: 1rem;
    overflow-x: auto;
    color: #bababa;
  }
</style>
