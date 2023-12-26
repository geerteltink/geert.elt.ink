module.exports = {
  trailingComma: 'es5',
  tabWidth: 2,
  semi: true,
  singleQuote: true,
  printWidth: 100,
  quoteProps: 'as-needed',
  bracketSpacing: true,
  arrowParens: 'always',
  endOfLine: 'lf',
  useTabs: false,
  plugins: ['prettier-plugin-astro'],
  overrides: [
    {
      files: '*.astro',
      options: {
        parser: 'astro',
      },
    },
    {
      files: ['*.md', '*.mdx'],
      options: {
        singleQuote: false,
        proseWrap: 'always',
      },
    },
  ],
};
