---
title: 2019-09-24-from-jekyll-to-expressive-to-vue-to-hugo
summary: From Jekyll to zend-expressive to Vue.js to Hugo
date: 2019-09-24
tags:
    - jekyll
    - zend-expressive
    - hugo
    - vuejs
---

Back in [2013](/blog/2013-11-05-hello-world), I started this blog based on Jekyll. A static generated site, hosted on GitHub pages. It did its job good and was ok to work with.

Later on, in 2015, I got involved with [zend-expressive](https://github.com/zendframework/zend-expressive). I needed something to play around, get to know it better and test changes. This blog [turned into a playground](https://github.com/xtreamwayz/www.elt.ink/tree/expressive) for exactly that purpose. I got my private VPS to host it, created something to load markdown files and re-used blog posts.

Last year I had some issues with a project I was doing for a client. It was a custom build webshop running on zend-expressive. The problem was that it had over 30 different settings to filter the items in the store. Even though there was a lot of caching going on, that many combos were hard to cache. I had tried different solutions but restricted by client wishes there was no satisfying solution. Their next wish was around the corner and they wanted an application as well. That's when I started looking for a JavaScript-based solution. Within a few days, I choose to get started with Vue.js and so this site converted to a [Vue.js based blog](https://github.com/xtreamwayz/www.elt.ink/tree/vuejs). It was a fun experiment and Vue.js is a joy to work with. I even got it working as a Progressive Web App. I know, a lot of overkill, but it served as a proof of concept for my client.

In case you wonder, their store is working fast and all the filtering is done client-side in their Vue.js based PWA. The requests to the server are minimized and the server specs are even downgraded. In contrast to my blog, that store is a good example of a site where heavy use of JavaScript is justified.

Proof of concept done, project done and finally having some free time again, it was time to make my site fast again. Not that a Vue.js can't be fast, but nothing beats a static generated site. This time I choose [Hugo](https://gohugo.io/), mainly for its speed.

I always skipped static site generators because I couldn't use it to queue blog posts. That's were GitHub Actions come in. It has support for cron jobs. So basically you can trigger new builds each morning. When you set a blog post to a future date it won't be visible until that day the cron job was triggered.

I have to admit that Hugo has a very steep learning curve. But once you dig into it and have the patience you can build a [fast site](https://github.com/xtreamwayz/www.elt.ink/tree/master) with a 100% lighthouse score. It's my preferred setup now for basic sites: Hugo, GitHub Actions, using the Netlify content delivery network to bring it as fast as possible to your device.
