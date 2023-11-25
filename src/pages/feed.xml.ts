import rss from '@astrojs/rss';
import type { APIRoute } from 'astro';
import { getCollection } from 'astro:content';

function sortPosts(a: { data: { started: Date } }, b: { data: { started: Date } }) {
  return Number(b.data.started) - Number(a.data.started);
}

function formatDate(date: Date) {
  date.setUTCHours(0);
  return date;
}

const customDataElements = [
  // enable Atom feed feature
  // prettier-ignore
  `<atom:link href="${import.meta.env.SITE}blog/feed.xml" rel="self" type="application/rss+xml" />`,
  // enable english language metadata
  `<language>en-us</language>`,
];

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
      pubDate: formatDate(item.data.started),
    })),
    customData: customDataElements.join(''),
    xmlns: {
      atom: 'http://www.w3.org/2005/Atom',
      content: 'http://purl.org/rss/1.0/modules/content/',
    },
  });
};
