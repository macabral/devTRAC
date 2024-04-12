## devTRAC Instalação

Pré-requisito que você tenha o docker e docker-compose instalado.

Para executar o devTRAC em container Docker siga os passos:

[1] Clonando o repositório do devTRAC

>git clone https://github.com/macabral/devTRAC

[2] Renomeando o arquivo .env.example

Vá para a pasta devTRAC/src e renomeie o arquivo '.env.example' para '.env' e edite as informações:

DB_CONNECTION=mysql
DB_HOST=ip do servidor local
DB_PORT=3306
DB_DATABASE=devtrac
DB_USERNAME=devtrac
DB_PASSWORD=devtrac

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=4
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_NAME="${APP_NAME}"

[3] Criando o container Docker da aplicação

>docker-compose build
>docker-compose up -d
>docker exec -it php sh
/var/www/app # composer install
/var/www/app # chmode -R 777/var/www/app/storage
/var/www/app # chmod -R777 /var/www/app/public
/var/www/app # php artisan migrate
/var/www/app # php artisan db:seed

[4] Executando o devTRAC

 http://localhost:8000 para acessar a aplicação devTRAC com o usuário 'admin@admin.com' e senha 'password'.

 http://localhost:8080 para acessar o phpmyadmin para administração do banco de dados.