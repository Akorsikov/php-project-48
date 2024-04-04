lint:
	composer exec --verbose phpcs -- --standard=PSR12 --colors src bin

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 --colors src bin

analise:
	vendor/bin/phpstan analyse --level 8 src bin

test:
	composer exec --verbose phpunit tests