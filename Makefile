# Makefile as a Deployment Tool
#
# Docs and examples:
#	http://www.gnu.org/software/make/manual/make.html
#	https://cbednarski.com/articles/makefiles-for-everyone/
#	https://github.com/njh/easyrdf/blob/master/Makefile

.PHONY: init update build test fix deploy

init:
	mkdir -p data/cache
	mkdir -p data/log
	npm install
	composer install

update:
	npm update
	composer update

build:
	rm -rf data/build
	rm -rf public/assets/*
	mkdir -p data/build
	mkdir -p public/assets/css
	mkdir -p public/assets/fonts
	mkdir -p public/assets/img
	mkdir -p public/assets/js
	node_modules/.bin/node-sass \
		resources/public/scss/core.scss data/build/core.css
	node_modules/.bin/postcss \
		--use autoprefixer --autoprefixer.browsers "last 2 versions" \
		--output data/build/core.prefixed.css data/build/core.css
	node_modules/.bin/uglifycss \
		data/build/core.prefixed.css > public/assets/css/core.min.css
	node_modules/.bin/imagemin \
	    resources/public/img/* --out-dir=public/assets/img/
	cp resources/public/icons/*.svg public/assets/img/

test:
	composer validate --no-check-publish
	vendor/bin/phpcs
	vendor/bin/phpunit --report-useless-tests

fix:
	vendor/bin/phpcbf

deploy:
	touch public/.maintenance
	git fetch --all
	git reset --hard origin/$(target)
	composer install --no-dev --no-scripts --no-interaction --optimize-autoloader
	rm -rf data/cache/*
	rm -f data/config-cache.php
	rm -f data/cache/config-cache.php
	rm -f data/cache/fastroute.php.cache
	rm -f public/.maintenance
