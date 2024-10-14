// @ts-check

import jsLint from '@eslint/js';
import json from '@eslint/json';
import markdown from '@eslint/markdown';
import astroParser from 'astro-eslint-parser';
import astro from 'eslint-plugin-astro';
import { dirname } from 'path';
import tsLint from 'typescript-eslint';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

export default [
  jsLint.configs.recommended,
  ...tsLint.configs.recommended,
  //...tsLint.configs.recommendedTypeChecked,

  {
    ignores: ['.git/**', '.github/**', '.astro/**', 'dist/**', 'node_modules/**', '**/*.d.ts'],
  },
  {
    files: ['**/*.json'],
    ignores: ['package-lock.json'],
    language: 'json/json',
    // @ts-expect-error: JSON config may not be typed correctly
    ...json.configs.recommended,
    rules: {
      'no-irregular-whitespace': 'off',
    },
  },
  {
    files: ['**/*.md', '**/*.mdx'],
    plugins: {
      markdown,
    },
    language: 'markdown/commonmark',
    rules: {
      'markdown/no-html': 'off',
      'no-irregular-whitespace': 'off',
    },
  },
  {
    ...jsLint.configs.recommended,
    files: ['**/*.{js,mjs,cjs,ts,mts,jsx,tsx}'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      parserOptions: {
        project: './tsconfig.json',
        parser: tsLint.parser,
        tsconfigRootDir: __dirname,
      },
    },
    rules: {
      '@typescript-eslint/no-unsafe-call': 'off',
      '@typescript-eslint/no-unsafe-assignment': 'off',
      '@typescript-eslint/no-unsafe-return': 'off',
      '@typescript-eslint/no-unsafe-member-access': 'off',
      '@typescript-eslint/no-unsafe-argument': 'off',
      '@typescript-eslint/no-explicit-any': 'warn',
      '@typescript-eslint/no-floating-promises': 'warn',
      '@typescript-eslint/restrict-template-expressions': 'warn',
      '@typescript-eslint/await-thenable': 'warn',
      '@typescript-eslint/consistent-type-imports': [
        'warn',
        { fixStyle: 'inline-type-imports', disallowTypeAnnotations: false },
      ],
      '@typescript-eslint/no-unused-vars': [
        'warn',
        { argsIgnorePattern: '^_', varsIgnorePattern: '^_' },
      ],
      '@typescript-eslint/restrict-plus-operands': 'warn',
      '@typescript-eslint/no-import-type-side-effects': 'warn',
      'no-empty': 'off',
      'no-undef': 'off',
    },
  },
  {
    ...astro.configs.recommended,
    files: ['*.astro'],
    plugins: {
      astro,
    },
    languageOptions: {
      parser: astroParser,
      parserOptions: {
        extraFileExtensions: ['.astro'],
        tsconfigRootDir: process.cwd(),
        project: ['./tsconfig.json'],
        parser: tsLint.parser,
        sourceType: 'module',
      },
    },
    rules: {
      'astro/no-deprecated-getentrybyslug': 'warn',
      'astro/no-conflict-set-directives': 'error',
      'astro/no-deprecated-astro-canonicalurl': 'error',
      'astro/no-deprecated-astro-fetchcontent': 'error',
      'astro/no-deprecated-astro-resolve': 'error',
      'astro/no-set-text-directive': 'error',
      'astro/no-unused-define-vars-in-style': 'error',
      'astro/prefer-class-list-directive': 'error',
      'astro/prefer-object-class-list': 'error',
      'astro/prefer-split-class-list': 'error',
      'astro/valid-compile': 'error',
    },
  },
];
