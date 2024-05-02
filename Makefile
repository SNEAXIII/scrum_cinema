api_console_path = api/api_cinema/bin/console
apiWorkdir=--dir=api/api_cinema
clientWorkdir=--dir=api/client_cinema
symfony="utils/symfony.exe"
ignoreError=|| exit /b 0
start_docker_desktop:
	$(shell python manage_docker_state.py start)
stop_wsl:
	$(shell python manage_docker_state.py stop)
create_ssl_keys:
	php $(api_console_path) lexik:jwt:generate-keypair
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
	$(symfony) server:start -d --port 8000 $(apiWorkdir)
	$(symfony) server:start -d --port 8001 $(clientWorkdir)
server_stop:
	$(symfony) server:stop $(apiWorkdir) $(ignoreError)
	$(symfony) server:stop $(clientWorkdir) $(ignoreError)
migrate:
	php $(api_console_path) doctrine:migrations:migrate --no-interaction
load:
	php $(api_console_path) doctrine:fixtures:load --no-interaction
open:
	$(symfony) open:local $(clientWorkdir)
ip:
	python set_ip.py
portainer:
	docker run -d -p 80:80 -p 9443:9443 --name portainer --restart=always -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer-ce:latest
	start https://localhost:9443/
launch:
	make start_docker_desktop
	make up
	make server
init:
	make launch
	timeout /t 10 > nul
	make migrate
	make load
end:
	make stop
	make server_stop
kill:
	make end
	make rm
reset:
	make kill
	make init