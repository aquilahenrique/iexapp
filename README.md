# Algumas decisões

Eu construi o projeto com uma base de arquitetura hexagonal, não contruí outras camadas por ser um teste técnico.
Por exemplo, o retorno do repositório está como array, mas num projeto grande faria o mapeamento
da camada de Dominio melhor.

Como camada de Infraestrutura, eu deixei a própria pasta do framework `app`.

També só criei o teste para o usecase principal `SearchQuoteUseCase`.

# Requisitos

1. Docker

# Instalação

## Clonar o repositório
`git clone git@github.com:aquilahenrique/iexapp.git`

## Instalar as dependências
`docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/var/www/html \
-w /var/www/html \
laravelsail/php81-composer:latest \
composer install --ignore-platform-reqs`

## Cofigurar .env
Duplicar `.env.example` para `.env`

## Iniciar a aplicação
Esse comando vai baixar as imagens e iniciar os containers
`./vendor/bin/sail up`

## Acessar
`http://localhost`

