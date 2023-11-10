import mdx from '@astrojs/mdx';
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';
import vue from '@astrojs/vue';

// https://astro.build/config
export default defineConfig({
    site: 'https://geert.elt.ink',
    integrations: [mdx(), sitemap(), vue()],
});
