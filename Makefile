HUGO := hugo
ASSETS_DIR := assets

build-js:
	mkdir -p $(ASSETS_DIR)/js/
	mkdir -p $(ASSETS_DIR)/svg/
	cp node_modules/@fortawesome/fontawesome-free/svgs/brands/github.svg $(ASSETS_DIR)/svg/
	cp node_modules/@fortawesome/fontawesome-free/svgs/brands/instagram.svg $(ASSETS_DIR)/svg/
	cp node_modules/@fortawesome/fontawesome-free/svgs/brands/twitter.svg $(ASSETS_DIR)/svg/
	cp node_modules/@fortawesome/fontawesome-free/svgs/brands/whatsapp.svg $(ASSETS_DIR)/svg/
	cp node_modules/@fortawesome/fontawesome-free/svgs/solid/envelope.svg $(ASSETS_DIR)/svg/
	cp node_modules/@fortawesome/fontawesome-free/svgs/solid/mobile-alt.svg $(ASSETS_DIR)/svg/

install:
	npm install

build: install build-js
	$(HUGO) --gc --minify -b https://geert.elt.ink/

serve: build-js
	$(HUGO) server --buildDrafts --buildFuture

test: install
	npm run build --if-present
	npm run lint:md
	npm run lint:html:index
	npm run lint:html:components
	npm run lint:css
