setup:
	cp .env.example .env
	composer install
	php artisan key:generate
	npm install
	npm run build

run:
	php artisan serve
