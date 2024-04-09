api_console_path = api/api_cinema/bin/console
export apiPath="api/api_cinema"
export clientPath="api/client_cinema"
export execFlags="-w $(path)"
export dockName="ubu_custom"
export execApi="docker exec "$(execFlags)" "$(dockName)""
export execClient="docker exec "$(execFlags)" "$(dockName)""
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
up:
	docker compose up -d
rm:
	docker rm -f $(shell docker ps -aq)
prune:
	make rm
	docker system prune -af
server:
	symfony serve -d --port 8000 --dir=api/api_cinema
	symfony serve -d --port 8001 --dir=api/client_cinema
server_stop:
	symfony server:stop --dir=api/api_cinema || exit /b 0
	symfony server:stop --dir=api/client_cinema || exit /b 0
migrate:
	php $(api_console_path) doctrine:migrations:migrate --no-interaction
load:
	php $(api_console_path) doctrine:fixtures:load --no-interaction
ip:
	python set_ip.py
init:
	make up
	make server_stop
	make server
	make migrate
	make load
launch:
	make up
	make server_stop
	make server
end:
	make stop
	make server_stop
kill:
	make end
	make prune
reset:
	make kill
	make init