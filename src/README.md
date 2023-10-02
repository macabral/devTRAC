## devTRAC ##
devTRAC é um sistema para registrar tarefas para pequenos projetos de desenvolvimento de software. Ele ajuda o Desenvolvedor a ver suas tarefas e a interagir com o Gerente de Projeto.

Inspirado no projeto TRAC (https://trac.edgewall.org/wiki/TitleIndex). 

## Perfis ##

Administrador - cria projetos, mantém tipos de tíckets e associa os usuários ao projeto.

Gerente de Projeto - mantém Releases do Projeto em que está associado

Relator - inclui tíquetes

Dev - executa os tíquetes

## Versões ##

v0.1 - primeira versão

v0.2 - 31/08/2023
- mensagem na janela de registro de usuário
- nova tabela email 
- envio de email por command/schedule de 10/10 minutos
- formatação do detalhamento de tíquetes

v0.3 - 11/09/2023
- Incluido status 'waiting' para o release. Neste caso pode-se criar tíquete mas o mesmo não será exibido em "meus tíquetes".
- Distribuição de tíquetes por desenvolvedor no dashboard.

v0.4 - 13/09/2023
- Alteração no layout do detalhamento do tíquete
- Permissão para o "tester" visualizar as estatísticas

v0.5 - 19/09/2023
- layout da página de detalhamento do tíquete
- exibir a quantidade de arquivos anexados ao tíquete
- na lista de tíquetes o ícone de anexo exibe negritado quando tiver arquivos em anexo

v0.6 - 02/10/2023
- Edição do cadastro do Usuário
- Abrir documentos em janela e realizar download quando não for PDF
- Paginação de 7 linhas

### Iniciando o projeto devTRAC ###

Laravel v10.17.1 | Splade-Breeze 2.5 | PHP v8.1.3

laravel new devTickect
 
cd devTicket
 
composer require protonemedia/laravel-splade-breeze
 
php artisan breeze:install

composer require spatie/laravel-query-builder

composer require sopamo/laravel-filepond
php artisan vendor:publish --provider="Sopamo\LaravelFilepond\LaravelFilepondServiceProvider"

composer require "maatwebsite/excel:^3"


https://github.com/lucascudo/laravel-pt-BR-localization
php artisan lang:publish
composer require lucascudo/laravel-pt-br-localization --dev 
php artisan vendor:publish --tag=laravel-pt-br-localization
// Altere Linha 85 do arquivo config/app.php para:
'locale' => 'pt_BR'

php artisan config:clear

in the AppServiceProvider class:

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ProtoneMedia\Splade\Components\Form\Input;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Input::defaultDateFormat('d/m/Y');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

## Docker ##

Para executar o devTRAC em um container DOCKER:

Esta instalação não implementou o nginx/apache e nem o servidor mysql.  Tem por propósito ter um ambiente rápido para testar o devTRAC.

Você deve ter instalado o docker e docker-compose.

(1) git clone http://github.com/macabral/devTARC.git
(2) renomear o arquivo .env.example para .env e alterar com os parâmetros para acesso ao banco de dados e servidor de email
(3) executar o php artisan migrate && php artisan db:seed para criação do banco de dados (ou restaurar o backup disponível na pasta docker)
(4) docker-compose up -d

Para acesso como Administrador utilize o login admin@admin.com/password.



