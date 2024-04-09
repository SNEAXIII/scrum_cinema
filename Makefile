api_console_path = api/api_cinema/bin/console
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
up:
	docker compose up -d
rm:
	docker rm -f $(shell docker ps -aq)
server:
	call server.bat
create_db:
	php $(api_console_path) doctrine:database:create
migrate:
	php $(api_console_path) doctrine:migrations:migrate
load:
	php $(api_console_path) doctrine:fixtures:load
ip:
	python set_ip.py