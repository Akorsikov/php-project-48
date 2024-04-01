lint:
	composer exec --verbose phpcs -- --standard=PSR12 --colors bin

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 --colors bin

analise:
	vendor/bin/phpstan analyse --level 8 bin