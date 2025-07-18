# Configuração do Ambiente de Desenvolvimento

Neste projeto utilizamos o Laravel Sail, que é uma ferramenta oficial do Laravel para gerenciar ambientes de desenvolvimento usando Docker.

## Pré-requisitos

Antes de começar, certifique-se de que você tem os seguintes softwares instalados em sua máquina:

- **Docker**: O Laravel Sail depende do Docker para criar e gerenciar contêineres.
  - [Instale o Docker](https://docs.docker.com/get-docker/)
- **Docker Compose**: Geralmente já vem instalado com o Docker, mas certifique-se de que está disponível.
  - [Instale o Docker Compose](https://docs.docker.com/compose/install/)
- **Git**: Para clonar o repositório do projeto.
  - [Instale o Git](https://git-scm.com/downloads)

## Passo 1: Clonar o Repositório

Primeiro, clone o repositório do projeto para o seu ambiente local:

HTTPS
```bash
git clone https://github.com/JoaoNahid/books-manager.git
```
SSH
```bash
git clone git@github.com:JoaoNahid/books-manager.git
```

## Passo 2: Acessar o projeto e configurar o .env

```bash
cd books-manager && cp .env-example .env
```

## Passo 3: Rodar o composer install
```bash
composer install
```

## Passo 4: Rodar o sail
Rode o comando abaixo para instalar o sail:
```bash
php artisan sail:install
```

Você também pode criar  um alias para  facilitar o uso do sail:
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

Agora inicie o container
```bash
sail up -d
```


## Passo 5: Rodar dependencias do frontend
Rode as migrations e popule o banco:
```bash
npm install
```

## Passo 6: Banco de dados

Rode as migrations e popule o banco:
```bash
sail artisan:migrate --seed
```