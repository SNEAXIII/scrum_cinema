python set_ip.py
echo The ip address is successfully modified
cd api
cd api_cinema
symfony serve -d --port 8000
cd ..
cd client_cinema
symfony serve -d --port 8001
pause