fix-analyze:
	php vendor/bin/phpcbf --standard=PSR12 src tests

analyze:
	php vendor/bin/phpcs --standard=PSR12 src tests

tests-simple:
	php vendor/bin/phpunit tests --color=always

tests-watcher:
	php vendor/bin/phpunit-watcher watch tests --color=always