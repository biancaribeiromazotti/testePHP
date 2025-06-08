DOCUMENTAÇÃO PARA TESTE DA APLICAÇÃO
====================================================================

--------------------------------------------------------------------
1. PRÉ-REQUISITOS
--------------------------------------------------------------------

Certifique-se de que os seguintes softwares estejam instalados:

- Docker: https://docs.docker.com/get-docker/
- Docker Compose: https://docs.docker.com/compose/install/

--------------------------------------------------------------------
2. CONFIGURANDO O ARQUIVO .ENV
--------------------------------------------------------------------

Copie o arquivo de exemplo para criar o arquivo de configuração:

    cp .env.example .env

--------------------------------------------------------------------
3. SUBINDO OS CONTAINERS
--------------------------------------------------------------------

Execute o seguinte comando:

    docker-compose up -d --build

--------------------------------------------------------------------
4. INSTALANDO AS DEPENDÊNCIAS
--------------------------------------------------------------------

Instale as dependências PHP com o Composer (dentro do container):

    docker-compose exec app sh -c "cd /var/www/sistema-pedidos && composer install"

--------------------------------------------------------------------
5. GERANDO A CHAVE DA APLICAÇÃO
--------------------------------------------------------------------

Gere a chave da aplicação Laravel:

    docker-compose exec app sh -c "cd /var/www/sistema-pedidos && php artisan key:generate"

--------------------------------------------------------------------
6. EXECUTANDO AS MIGRATIONS
--------------------------------------------------------------------

Crie as tabelas no banco de dados com:

    docker-compose exec app sh -c "cd /var/www/sistema-pedidos && php artisan migrate"

--------------------------------------------------------------------
7. ACESSANDO A APLICAÇÃO
--------------------------------------------------------------------

Abra o navegador e acesse:

    http://localhost:8080

