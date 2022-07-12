const eleventyNavigationPlugin = require('@11ty/eleventy-navigation');
const pluginRss = require('@11ty/eleventy-plugin-rss');
const Image = require('@11ty/eleventy-img');
const htmlmin = require('html-minifier');
const collections = require('./src/_lib/collections.js');
const filters = require('./src/_lib/filters.js');

async function imageShortcode(src, alt, sizes) {
  let metadata = await Image(src, {
    widths: [400, 800, 1280, 1920],
    formats: ['webp', 'jpeg'],
    outputDir: '_site/assets/images',
    urlPath: '/assets/images',
  });

  let imageAttributes = {
    alt,
    class: 'img-fluid',
    sizes: sizes || '100vw',
    loading: 'lazy',
    decoding: 'async',
  };

  return Image.generateHTML(metadata, imageAttributes, {
    whitespaceMode: 'inline',
  });
}

module.exports = function (eleventyConfig) {
  Object.keys(collections).forEach((name) => {
    eleventyConfig.addCollection(name, collections[name]);
  });

  Object.keys(filters).forEach((name) => {
    eleventyConfig.addFilter(name, filters[name]);
  });

  eleventyConfig.addPlugin(eleventyNavigationPlugin);
  eleventyConfig.addPlugin(pluginRss);

  eleventyConfig.addShortcode('year', () => `${new Date().getFullYear()}`);
  eleventyConfig.addAsyncShortcode('image', imageShortcode);

  eleventyConfig.addWatchTarget('./src/_lib');
  eleventyConfig.addWatchTarget('./src/assets');

  eleventyConfig.addPassthroughCopy('./src/favicon.png');
  eleventyConfig.addPassthroughCopy('./src/assets/images/logo-linksbek.gif');

  eleventyConfig.addTransform('htmlmin', function (content, outputPath) {
    if (process.env.NODE_ENV === 'production' && outputPath && outputPath.endsWith('.html')) {
      let minified = htmlmin.minify(content, {
        useShortDoctype: true,
        removeComments: true,
        collapseWhitespace: true,
      });
      return minified;
    }

    return content;
  });

  return {
    dir: {
      input: 'src',
      output: '_site',
      data: '_data',
      includes: '_includes',
    },
    templateFormats: ['html', 'njk', 'md', '11ty.js'],
    passthroughFileCopy: true,
    //markdownTemplateEngine: 'liquid',
    //htmlTemplateEngine: 'liquid',
    //dataTemplateEngine: false
  };
};
