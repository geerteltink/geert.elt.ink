const meta = require('./../_data/meta.json');
const { DateTime } = require('luxon');
const striptags = require('striptags');

exports.limit = (array, limit) => {
  return array.slice(0, limit);
};

exports.readableDate = (dateObj) => {
  if (!(dateObj instanceof Date)) {
    dateObj = new Date(dateObj);
  }

  return DateTime.fromJSDate(dateObj).setLocale(meta.lang).toLocaleString(DateTime.DATE_FULL);
};

exports.isoDate = (dateObj) => {
  if (!(dateObj instanceof Date)) {
    dateObj = new Date(dateObj);
  }

  return DateTime.fromJSDate(dateObj).toFormat('yyyy-LL-dd');
};

exports.isoDateTime = (dateObj) => {
  if (!(dateObj instanceof Date)) {
    dateObj = new Date(dateObj);
  }

  return DateTime.fromJSDate(dateObj).toISO();
};

exports.summary = (data) => {
  return striptags(data.replace(/<h1[^>]*>([\s\S]*?)<\/h1[^>]*>/, ''))
    .substring(0, 200)
    .replace(/^\s+|\s+$|\s+(?=\s)/g, '')
    .trim()
    .concat('...');
};

exports.getRandom = (collection) => {
  const slicedCollection = collection.slice(5);

  return slicedCollection.splice(Math.floor(Math.random() * slicedCollection.length), 1)[0];
};
