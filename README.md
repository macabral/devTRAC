## devTRAC Instalação

Para executar o devTRAC em container Docker siga os passos:

>git clone https://github.com/macabral/devTRAC

Vá para a pasta devTRAC/src e renomeie o arquivo .env.example para .env e edite as informações:

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=4
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_NAME="${APP_NAME}"



>docker-compose up -d   

na primeira vez realizará todas as instalações.