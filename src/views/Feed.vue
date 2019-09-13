<template>

</template>

<script>
  import { Feed } from 'feed';

  const feed = new Feed({
    title: 'xtreamwayz',
    description: 'A web developer\'s playground, notes and thoughts.',
    id: 'https://www.xtreamwayz.com/',
    link: 'https://www.xtreamwayz.com/',
    image: 'https://www.xtreamwayz.com/image.png',
    favicon: 'https://www.xtreamwayz.com/favicon.ico',
    copyright: `Copyright (c) ${(new Date()).getFullYear()} Geert Eltink`,
    feedLinks: {
      atom: 'https://www.xtreamwayz.com/feed',
      rss: 'https://www.xtreamwayz.com/feed.rss',
      json: 'https://www.xtreamwayz.com/feed.json',
    },
    author: {
      name: 'Geert Eltink',
      email: 'xtreamwayz@gmail.com',
      link: 'https://www.xtreamwayz.com/',
    },
  });

  /*
  posts.forEach(post => {
    feed.addItem({
      title: post.title,
      id: post.url,
      link: post.url,
      description: post.description,
      content: post.content,
      author: [
        {
          name: "Jane Doe",
          email: "janedoe@example.com",
          link: "https://example.com/janedoe"
        }
      ],
      date: post.date,
      image: post.image
    });
  });
  */

  export default {
    name: 'Feed',
  };
</script>

<style scoped lang="scss">

</style>
