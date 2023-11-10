declare module 'astro:content' {
	interface Render {
		'.mdx': Promise<{
			Content: import('astro').MarkdownInstance<{}>['Content'];
			headings: import('astro').MarkdownHeading[];
			remarkPluginFrontmatter: Record<string, any>;
		}>;
	}
}

declare module 'astro:content' {
	interface Render {
		'.md': Promise<{
			Content: import('astro').MarkdownInstance<{}>['Content'];
			headings: import('astro').MarkdownHeading[];
			remarkPluginFrontmatter: Record<string, any>;
		}>;
	}
}

declare module 'astro:content' {
	export { z } from 'astro/zod';

	type Flatten<T> = T extends { [K: string]: infer U } ? U : never;

	export type CollectionKey = keyof AnyEntryMap;
	export type CollectionEntry<C extends CollectionKey> = Flatten<AnyEntryMap[C]>;

	export type ContentCollectionKey = keyof ContentEntryMap;
	export type DataCollectionKey = keyof DataEntryMap;

	// This needs to be in sync with ImageMetadata
	export type ImageFunction = () => import('astro/zod').ZodObject<{
		src: import('astro/zod').ZodString;
		width: import('astro/zod').ZodNumber;
		height: import('astro/zod').ZodNumber;
		format: import('astro/zod').ZodUnion<
			[
				import('astro/zod').ZodLiteral<'png'>,
				import('astro/zod').ZodLiteral<'jpg'>,
				import('astro/zod').ZodLiteral<'jpeg'>,
				import('astro/zod').ZodLiteral<'tiff'>,
				import('astro/zod').ZodLiteral<'webp'>,
				import('astro/zod').ZodLiteral<'gif'>,
				import('astro/zod').ZodLiteral<'svg'>,
				import('astro/zod').ZodLiteral<'avif'>,
			]
		>;
	}>;

	type BaseSchemaWithoutEffects =
		| import('astro/zod').AnyZodObject
		| import('astro/zod').ZodUnion<[BaseSchemaWithoutEffects, ...BaseSchemaWithoutEffects[]]>
		| import('astro/zod').ZodDiscriminatedUnion<string, import('astro/zod').AnyZodObject[]>
		| import('astro/zod').ZodIntersection<BaseSchemaWithoutEffects, BaseSchemaWithoutEffects>;

	type BaseSchema =
		| BaseSchemaWithoutEffects
		| import('astro/zod').ZodEffects<BaseSchemaWithoutEffects>;

	export type SchemaContext = { image: ImageFunction };

	type DataCollectionConfig<S extends BaseSchema> = {
		type: 'data';
		schema?: S | ((context: SchemaContext) => S);
	};

	type ContentCollectionConfig<S extends BaseSchema> = {
		type?: 'content';
		schema?: S | ((context: SchemaContext) => S);
	};

	type CollectionConfig<S> = ContentCollectionConfig<S> | DataCollectionConfig<S>;

	export function defineCollection<S extends BaseSchema>(
		input: CollectionConfig<S>
	): CollectionConfig<S>;

	type AllValuesOf<T> = T extends any ? T[keyof T] : never;
	type ValidContentEntrySlug<C extends keyof ContentEntryMap> = AllValuesOf<
		ContentEntryMap[C]
	>['slug'];

	export function getEntryBySlug<
		C extends keyof ContentEntryMap,
		E extends ValidContentEntrySlug<C> | (string & {}),
	>(
		collection: C,
		// Note that this has to accept a regular string too, for SSR
		entrySlug: E
	): E extends ValidContentEntrySlug<C>
		? Promise<CollectionEntry<C>>
		: Promise<CollectionEntry<C> | undefined>;

	export function getDataEntryById<C extends keyof DataEntryMap, E extends keyof DataEntryMap[C]>(
		collection: C,
		entryId: E
	): Promise<CollectionEntry<C>>;

	export function getCollection<C extends keyof AnyEntryMap, E extends CollectionEntry<C>>(
		collection: C,
		filter?: (entry: CollectionEntry<C>) => entry is E
	): Promise<E[]>;
	export function getCollection<C extends keyof AnyEntryMap>(
		collection: C,
		filter?: (entry: CollectionEntry<C>) => unknown
	): Promise<CollectionEntry<C>[]>;

	export function getEntry<
		C extends keyof ContentEntryMap,
		E extends ValidContentEntrySlug<C> | (string & {}),
	>(entry: {
		collection: C;
		slug: E;
	}): E extends ValidContentEntrySlug<C>
		? Promise<CollectionEntry<C>>
		: Promise<CollectionEntry<C> | undefined>;
	export function getEntry<
		C extends keyof DataEntryMap,
		E extends keyof DataEntryMap[C] | (string & {}),
	>(entry: {
		collection: C;
		id: E;
	}): E extends keyof DataEntryMap[C]
		? Promise<DataEntryMap[C][E]>
		: Promise<CollectionEntry<C> | undefined>;
	export function getEntry<
		C extends keyof ContentEntryMap,
		E extends ValidContentEntrySlug<C> | (string & {}),
	>(
		collection: C,
		slug: E
	): E extends ValidContentEntrySlug<C>
		? Promise<CollectionEntry<C>>
		: Promise<CollectionEntry<C> | undefined>;
	export function getEntry<
		C extends keyof DataEntryMap,
		E extends keyof DataEntryMap[C] | (string & {}),
	>(
		collection: C,
		id: E
	): E extends keyof DataEntryMap[C]
		? Promise<DataEntryMap[C][E]>
		: Promise<CollectionEntry<C> | undefined>;

	/** Resolve an array of entry references from the same collection */
	export function getEntries<C extends keyof ContentEntryMap>(
		entries: {
			collection: C;
			slug: ValidContentEntrySlug<C>;
		}[]
	): Promise<CollectionEntry<C>[]>;
	export function getEntries<C extends keyof DataEntryMap>(
		entries: {
			collection: C;
			id: keyof DataEntryMap[C];
		}[]
	): Promise<CollectionEntry<C>[]>;

	export function reference<C extends keyof AnyEntryMap>(
		collection: C
	): import('astro/zod').ZodEffects<
		import('astro/zod').ZodString,
		C extends keyof ContentEntryMap
			? {
					collection: C;
					slug: ValidContentEntrySlug<C>;
			  }
			: {
					collection: C;
					id: keyof DataEntryMap[C];
			  }
	>;
	// Allow generic `string` to avoid excessive type errors in the config
	// if `dev` is not running to update as you edit.
	// Invalid collection names will be caught at build time.
	export function reference<C extends string>(
		collection: C
	): import('astro/zod').ZodEffects<import('astro/zod').ZodString, never>;

	type ReturnTypeOrOriginal<T> = T extends (...args: any[]) => infer R ? R : T;
	type InferEntrySchema<C extends keyof AnyEntryMap> = import('astro/zod').infer<
		ReturnTypeOrOriginal<Required<ContentConfig['collections'][C]>['schema']>
	>;

	type ContentEntryMap = {
		"notes": {
"2013-11-05-hello-world.mdx": {
	id: "2013-11-05-hello-world.mdx";
  slug: "hello-world";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2013-12-06-symfony-2-flash-messages.mdx": {
	id: "2013-12-06-symfony-2-flash-messages.mdx";
  slug: "symfony-2-flash-messages";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2014-06-20-jekyll-atom-feed.mdx": {
	id: "2014-06-20-jekyll-atom-feed.mdx";
  slug: "jekyll-atom-feed";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2014-10-02-symfony-2-dynamic-router.mdx": {
	id: "2014-10-02-symfony-2-dynamic-router.mdx";
  slug: "symfony-2-dynamic-router";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2014-11-03-howto-update-teamspeak-3.mdx": {
	id: "2014-11-03-howto-update-teamspeak-3.mdx";
  slug: "howto-update-teamspeak-3-on-debian";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2014-11-04-phpunit-selenium-2.mdx": {
	id: "2014-11-04-phpunit-selenium-2.mdx";
  slug: "phpunit-and-selenium-server-2";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2014-11-10-using-sismo-as-your-local-continuous-integration-server.mdx": {
	id: "2014-11-10-using-sismo-as-your-local-continuous-integration-server.mdx";
  slug: "using-sismo-as-your-personal-ci-server-on-windows";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-01-24-check-git-status-recursively-on-windows.mdx": {
	id: "2015-01-24-check-git-status-recursively-on-windows.mdx";
  slug: "check-git-status-recursively-on-windows";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-03-13-grunt-gulp-and-npm.mdx": {
	id: "2015-03-13-grunt-gulp-and-npm.mdx";
  slug: "grunt-gulp-and-npm";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-05-20-git-workflow.mdx": {
	id: "2015-05-20-git-workflow.mdx";
  slug: "git-workflow";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-05-21-git-troubleshooting.mdx": {
	id: "2015-05-21-git-troubleshooting.mdx";
  slug: "git-troubleshooting";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-06-04-symfony-shibboleth-login-the-easy-way.mdx": {
	id: "2015-06-04-symfony-shibboleth-login-the-easy-way.mdx";
  slug: "symfony-2-6-shibboleth-login-the-easy-way";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-09-07-pimple-3-container-interop.mdx": {
	id: "2015-09-07-pimple-3-container-interop.mdx";
  slug: "container-interop-wrapper-for-pimple-3-0";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-12-09-contributing-to-github-projects.mdx": {
	id: "2015-12-09-contributing-to-github-projects.mdx";
  slug: "contributing-to-a-github-project";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-12-12-setup-doctrine-for-zend-expressive.mdx": {
	id: "2015-12-12-setup-doctrine-for-zend-expressive.mdx";
  slug: "how-to-setup-doctrine-for-zend-expressive";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-12-14-phpstorm-container-interop-code-completion.mdx": {
	id: "2015-12-14-phpstorm-container-interop-code-completion.mdx";
  slug: "phpstorm-psr-11-container-interface-code-completion";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-12-29-zend-expressive-action-factory-one-for-all.mdx": {
	id: "2015-12-29-zend-expressive-action-factory-one-for-all.mdx";
  slug: "zend-expressive-one-action-factory-for-all";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2015-12-30-psr7-abstract-action-factory-one-for-all.mdx": {
	id: "2015-12-30-psr7-abstract-action-factory-one-for-all.mdx";
  slug: "one-abstract-action-factory-for-all";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2016-02-07-zend-expressive-console-cli-commands.mdx": {
	id: "2016-02-07-zend-expressive-console-cli-commands.mdx";
  slug: "zend-expressive-console-cli-commands";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2017-12-03-phpstorm-phpunit-docker-compose-windows.mdx": {
	id: "2017-12-03-phpstorm-phpunit-docker-compose-windows.mdx";
  slug: "running-phpunit-in-phpstorm-with-docker-compose-on-windows";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2018-08-26-cqrs-message-validation.mdx": {
	id: "2018-08-26-cqrs-message-validation.mdx";
  slug: "cqrs-message-validation";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2019-09-24-from-jekyll-to-expressive-to-vue-to-hugo.mdx": {
	id: "2019-09-24-from-jekyll-to-expressive-to-vue-to-hugo.mdx";
  slug: "from-jekyll-to-zend-expressive-to-vue-js-to-hugo";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2019-09-28-wsl2.mdx": {
	id: "2019-09-28-wsl2.mdx";
  slug: "wsl-2-and-visual-studio-code";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2019-11-09-project-documentation-with-hugo.mdx": {
	id: "2019-11-09-project-documentation-with-hugo.mdx";
  slug: "project-documentation-with-hugo-modules";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2020-10-29-about-workshops.mdx": {
	id: "2020-10-29-about-workshops.mdx";
  slug: "about-the-value-of-workshops";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2021-09-15-vuejs-composition-api.mdx": {
	id: "2021-09-15-vuejs-composition-api.mdx";
  slug: "vue-js-3-2-composition-api-setup-with-typescript";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2022-07-16-git-squash-branch.mdx": {
	id: "2022-07-16-git-squash-branch.mdx";
  slug: "git-squash-all-commits-in-a-branch";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2022-12-04-advent-of-code.mdx": {
	id: "2022-12-04-advent-of-code.mdx";
  slug: "advent-of-code";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
"2023-11-11-astro-islands-with-vue.mdx": {
	id: "2023-11-11-astro-islands-with-vue.mdx";
  slug: "astro-islands-with-vue";
  body: string;
  collection: "notes";
  data: InferEntrySchema<"notes">
} & { render(): Render[".mdx"] };
};

	};

	type DataEntryMap = {
		
	};

	type AnyEntryMap = ContentEntryMap & DataEntryMap;

	type ContentConfig = typeof import("../src/content/config");
}
