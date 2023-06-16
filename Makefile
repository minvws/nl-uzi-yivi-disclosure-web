TOKEN ?= $(shell bash -c 'read -p "github_token: " github_token; echo $$github_token')

setup:
	cp .env.example .env
	composer install
	php artisan key:generate
	echo "@minvws:registry=https://npm.pkg.github.com" >> .npmrc
	echo "//npm.pkg.github.com/:_authToken=$(TOKEN)"  >> ~/.npmrc
	npm install
	npm run dev

run:	
	php artisan serve

	
