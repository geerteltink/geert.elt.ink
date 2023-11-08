import { defineCollection, z } from 'astro:content';

const notes = defineCollection({
  type: 'content',
  schema: z.object({
    title: z.string(),
    description: z.string(),
    published: z.coerce.date(),
    modified: z.coerce.date().optional(),
    tags: z.array(z.string()).optional(),
    coverImage: z.string().optional(),
    coverImageAlt: z.string().optional(),
  }),
});

// Export a single `collections` object to register your collection(s)
// This key should match your collection directory name in "src/content"
export const collections = { notes };
