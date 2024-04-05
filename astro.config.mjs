import mdx from '@astrojs/mdx';
import sitemap from '@astrojs/sitemap';
import vue from '@astrojs/vue';
import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://geert.elt.ink',
  integrations: [mdx(), sitemap(), vue()],
  output: 'static',
});
