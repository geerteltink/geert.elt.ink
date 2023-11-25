import { defineCollection, z } from 'astro:content';

const notes = defineCollection({
  type: 'content',
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
export const collections = { notes };
