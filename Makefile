
.PHONY: help

## Colors
COLOR_RESET			= \033[0m
COLOR_ERROR			= \033[31m
COLOR_INFO			= \033[32m
COLOR_COMMENT		= \033[33m
COLOR_TITLE_BLOCK	= \033[0;44m\033[37m


## Help
help:
	@printf "${COLOR_TITLE_BLOCK} Makefile${COLOR_RESET}\n"
	@printf "\n"
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make [target]\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[a-zA-Z\-\_0-9\@]+:/ { \
		helpLine = match(lastLine, /^## (.*)/); \
		helpCommand = substr($$1, 0, index($$1, ":")); \
		helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
		printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)


####################################
# Dependencies
####################################

fix-analyze:
	php vendor/bin/phpcbf --standard=PSR12 src tests

analyze:
	php vendor/bin/phpcs --standard=PSR12 src tests

tests-simple:
	php vendor/bin/phpunit tests --color=always  --stop-on-failure

tests-watcher:
	php vendor/bin/phpunit-watcher watch tests --color=always