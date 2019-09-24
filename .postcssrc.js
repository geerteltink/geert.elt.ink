module.exports = {
  plugins: {
    autoprefixer: {},
    '@fullhuman/postcss-purgecss': {
      content: [
        './content/**/*.md',
        './layouts/**/*.html'
      ],
      whitelist: ['pre', 'code', 'blockquote']
    }
  }
};
