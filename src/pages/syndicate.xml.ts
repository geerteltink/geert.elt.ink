import rss from '@astrojs/rss';
import type { APIRoute } from 'astro';
import { getCollection } from 'astro:content';
import sanitizeHtml from 'sanitize-html';
import MarkdownIt from 'markdown-it';
const parser = new MarkdownIt();

function sortPosts(a: { data: { published: Date } }, b: { data: { published: Date } }) {
  return Number(b.data.published) - Number(a.data.published);
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
      link: 'isCaseStudy' in item.data ? `/case-studies/${item.slug}` : `/notes/${item.slug}/`,
      pubDate: formatDate(item.data.published),
      description: item.data.description,
      content: sanitizeHtml(parser.render(item.body)),
    })),
    customData: customDataElements.join(''),
    xmlns: {
      atom: 'http://www.w3.org/2005/Atom',
      content: 'http://purl.org/rss/1.0/modules/content/',
    },
  });
};
