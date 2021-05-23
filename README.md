# CodeFlix - Micro serviço: Catálogo de vídeos

Projeto com o objetivo de criar um catálogo de vídeos em micro serviço utilizando do framework [Laravel](https://laravel.com/), o banco de dados [MySQL](https://www.mysql.com/), sistema de cache com [Redis](https://redis.io/) e o [NGINX](https://www.nginx.com/) como proxy reverso.

# Tabela de conteúdo

-   [Primeiros passos](#primeiros-passos)

# Primeiros passos

Primeiro você precisa ter instalado o [Docker](https://docs.docker.com/engine/) e o [docker-compose](https://docs.docker.com/compose/).

Após isso você precisa clonar o repositório com:

`git clone https://github.com/jeanmolossi/codeflix-microservice-videos.git`

E em seguida:

`docker-compose up -d`

O serviço está configurado para subir utilizando o nginx na porta 8000, portanto espera-se que você consiga acesso aos endpoints em:

`http://localhost:8000`
