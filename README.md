
# iPorto/test-dev-php

## Passo a passo
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

ou

docker compose up - d (dependnedo da sua versão)
```
Acessar o container
```sh
docker-compose exec app bash

ou

docker compose exec app bash (dependendo da sua versão)
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
[http://localhost:8989](http://localhost:8989)


## Sobre a aplicação

Api consumida: [Binances](https://academy.especializati.com.br)

Possuí dois comandos,

### **SaveBidPriceOnDataBaseCommand**

Salva, no banco, informações sobre as criptomoedas (symbol, price e time)

É possível registrar todos os tipos de moeda

```sh
php artisan c:saveBidPriceOnDataBase
```
Ou uma moeda específica

```sh
php artisan c:saveBidPriceOnDataBase {symbol?}
```

Exemplo

Input

```sh
php artisan c:saveBidPriceOnDataBase MATICUSDT
```
Output
```sh
{"symbol":"MATICUSDT","price":"1.15200","time":1655100424046,"id":1009}
```

### **CheckAvgBigPriceCommand**

Para cada symbol de moeda pesquisado, é verificado os últimos 100 (cem) registros desta moeda no banco. Se o preço (price) do último registro for 0.5 menor que a média dos preços dos 100 verificados, é lançado um alerta.

É possível verificar todas as moedas

```sh
php artisan c:checkAvgBigPrice
```
Ou uma moeda específica

```sh
php artisan c:checkAvgBigPrice {symbol?}
```

Exemplo

Input

```sh
 php artisan c:checkAvgBigPrice OGNUSDT
```
Output
```sh
{"OGNUSDT":"Current value for OGNUSDT is ok"}
```

Ou

Input
```sh
 php artisan c:checkAvgBigPrice BNTUSDT
```
Output
```sh
{"BNTUSDT":"Current value for BNTUSDT is over 0.5% lower than average value!"}
```
