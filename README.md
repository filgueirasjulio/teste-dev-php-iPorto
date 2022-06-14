
# iPorto/test-dev-php
[Assine a Academy, e Seja VIP!](https://academy.especializati.com.br)

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/filgueirasjulio/teste-dev-php-iPorto.git
```

```sh
cd teste-dev-php-iPorto/
```
Crie o Arquivo .env
```sh
cp .env.example .env
```
Suba os containers do projeto
```sh
docker-compose up -d
```
Acessar o container
```sh
docker-compose exec app bash
```
Instalar as dependências do projeto
```sh
composer install
```
Gerar a key do projeto Laravel
```sh
php artisan key:generate
```


Acesse o projeto
[http://localhost:8180](http://localhost:8989)
