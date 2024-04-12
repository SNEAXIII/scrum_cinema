api_console_path = api/api_cinema/bin/console
apiWorkdir="--dir=api/api_cinema"
clientWorkdir="--dir=api/client_cinema"
execFlags="-w $(path)"
dockName="ubu_custom"
execApi="docker exec "$(execFlags)" "$(dockName)""
execClient="docker exec "$(execFlags)" "$(dockName)""
symfony="utils/symfony.exe"
ignoreError=|| exit /b 0
start_docker_desktop:
	$(shell python manage_docker_state.py start)
stop_wsl:
	$(shell python manage_docker_state.py stop)
up:
	docker compose up -d
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
rm:
	docker rm -f $(shell docker ps -aq) $(ignoreError)
prune:
	make rm
	docker system prune -af
server:
	$(symfony) serve -d --port 8000 $(apiWorkdir)
	$(symfony) serve -d --port 8001 $(clientWorkdir)
server_stop:
	$(symfony) server:stop $(apiWorkdir) $(ignoreError)
	$(symfony) server:stop $(clientWorkdir) $(ignoreError)
migrate:
	php $(api_console_path) doctrine:migrations:migrate --no-interaction
load:
	php $(api_console_path) doctrine:fixtures:load --no-interaction
ip:
	python set_ip.py
launch:
	make start_docker_desktop
	make up
	make server
init:
	make launch
	make migrate
	make load
end:
	make stop
	make server_stop
kill:
	make end
	make prune
reset:
	make kill
	make init