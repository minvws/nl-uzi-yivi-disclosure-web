TOKEN ?= $(shell bash -c 'read -p "github_token: " github_token; echo $$github_token')

setup:
	cp .env.example .env
	composer install
	php artisan key:generate
	# echo "//npm.pkg.github.com/:_authToken=$(TOKEN)"  >> ~/.npmrc
	npm install
	npm run build

run:
	php artisan serve
