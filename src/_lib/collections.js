const _ = require('lodash');

function filterPost(post) {
  if (process.env.NODE_ENV !== 'production') {
    return true;
  }

  if (post.data.draft === true) {
    return false;
  }

  let now = new Date().getTime();
  if (now < post.date.getTime()) {
    return false;
  }

  return true;
}

exports.posts = (collections) => {
  return collections
    .getFilteredByTag('post')
    .reverse()
    .filter((post) => filterPost(post));
};

exports.notes = (collections) => {
  return collections
    .getFilteredByTag('note')
    .reverse()
    .filter((post) => filterPost(post));
};
