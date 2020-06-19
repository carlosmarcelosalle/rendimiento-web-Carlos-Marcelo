all: help
#    	  | |     | |     / ____|    | |
#      	__| | __ _| | ___| |     __ _| | ___  _ __
#       / _` |/ _` | |/ _ \ |    / _` | |/ _ \| '__|
#       | (_| | (_| | |  __/ |___| (_| | | (_) | |
#        \__,_|\__,_|_|\___|\_____\__,_|_|\___/|_|

build:
	@docker-compose build
	@docker-compose up -d
start:
	@docker-compose up -d

stop:
	@docker-compose down
console:
	@docker-compose exec php bash
