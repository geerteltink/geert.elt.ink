# Makefile as a Deployment Tool
#
# Docs and examples:
#	http://www.gnu.org/software/make/manual/make.html
#	https://cbednarski.com/articles/makefiles-for-everyone/
#	https://github.com/njh/easyrdf/blob/master/Makefile

.PHONY: init init-dir update clean build test fix deploy

init: init-dir
	@if [ -f package.json ]; then \
		npm install; \
	fi;
	@if [ -f composer.json ]; then \
		composer install; \
	fi;

init-dir:
	mkdir -p data/{cache,import,log}
	mkdir -p public/uploads

update:
	@if [ -f package.json ]; then \
		npm update; \
	fi;
	@if [ -f composer.json ]; then \
		composer update; \
	fi;

clean:
	rm -rf data/build
	rm -rf public/assets/*

build: clean
	mkdir -p data/build
	mkdir -p public/assets/css
	mkdir -p public/assets/fonts
	mkdir -p public/assets/img
	mkdir -p public/assets/js
	node_modules/.bin/uglifyjs \
		node_modules/svgxuse/svgxuse.js \
		--compress --mangle --screw-ie8 --output public/assets/js/core.min.js
	node_modules/.bin/node-sass \
		resources/public/scss/core.scss data/build/core.css
	node_modules/.bin/postcss \
		--use autoprefixer --autoprefixer.browsers "last 2 versions" \
		--output data/build/core.prefixed.css data/build/core.css
	node_modules/.bin/uglifycss \
		data/build/core.prefixed.css > public/assets/css/core.min.css
	cp resources/public/img/* public/assets/img/

test:
	vendor/bin/phpcs
	vendor/bin/phpunit --report-useless-tests

fix:
	vendor/bin/phpcbf

deploy:
	git pull
	rm -rf data/cache/*
	composer install --no-dev --optimize-autoloader
