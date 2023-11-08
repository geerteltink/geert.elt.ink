import rss from '@astrojs/rss';
import type { APIRoute } from 'astro';
import { getCollection } from 'astro:content';

function sortPosts(a: { data: { published: Date } }, b: { data: { published: Date } }) {
  return Number(b.data.published) - Number(a.data.published);
}

function formatDate(date: Date) {
  date.setUTCHours(0);
  return date;
}

export const GET: APIRoute = async (context) => {
  const unsortedPosts = [...(await getCollection('notes'))];
  const posts = unsortedPosts.sort((a, b) => sortPosts(a, b));

  return rss({
    title: 'geert.elt.ink',
    description: 'How can I help?',
    site: context.site!.href,
    items: posts.map((item) => ({
      title: item.data.title,
      description: item.data.description,
      link: 'isCaseStudy' in item.data ? `/case-studies/${item.slug}` : `/notes/${item.slug}/`,
      pubDate: formatDate(item.data.published),
    })),
  });
};
