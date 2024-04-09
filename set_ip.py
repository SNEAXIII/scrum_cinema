from re import findall, sub
from subprocess import run

STR_CMD = "ipconfig | findstr /R \"IPv4\""
IP_REGEX = r'\d+\.\d+\.\d+\.\d+'
CONSTANT_TEMPLATE = "public const API_LINK = \"http://%s:8000/api\";"

cmd_result = run(STR_CMD, shell=True, capture_output=True, text=True).stdout

list_of_ip = findall(IP_REGEX, cmd_result)

if len(list_of_ip) > 1:
    print("Every ip on your computer:")
    for index, ip in enumerate(list_of_ip):
        print(f"{index} : {ip}")
    user_choice = int(input("Choose an ip adress : "))
    selected_ip = list_of_ip[user_choice]
else:
    selected_ip = list_of_ip[0]

with open("api/client_cinema/src/Services/Constants.php", encoding="utf-8", mode="r+") as file:
    old_constant = CONSTANT_TEMPLATE % IP_REGEX
    new_constant = CONSTANT_TEMPLATE % selected_ip
    file_content = file.read()
    file_content_with_correct_ip = sub(old_constant, new_constant, file_content)
    file.seek(0)
    file.write(file_content_with_correct_ip)
    file.truncate()

print("The ip address is successfully modified")