import { glob } from 'astro/loaders';
import { z } from 'astro/zod';
import { defineCollection } from 'astro:content';

const notesCollection = defineCollection({
  /* Retrieve all Markdown files in your notes directory and prevent
   * the raw body of content files from being stored in the data store. */
  loader: glob({
    pattern: '**/*.mdx',
    base: './src/content/notes',
  }),
  schema: ({ image }) =>
    z.object({
      title: z.string(),
      description: z.string(),
      started: z.coerce.date(),
      updated: z.coerce.date().optional(),
      cover: image().optional(),
      topics: z.array(z.string()).optional(),
    }),
});

// Export a single `collections` object to register your collection(s)
// This key should match your collection directory name in "src/content"
export const collections = { notes: notesCollection };
