import type { APIContext } from 'astro';

export function GET({ generator, site }: APIContext) {
  return new Response(
    JSON.stringify(
      {
        build: new Date().toISOString(),
        generator: generator,
        site: site,
        env: import.meta.env.MODE,
      },
      undefined,
      2
    )
  );
}
