# API BASE

Desarrollo del Sistema Interno de Gestión Veterinaria

## Requerimientos

	- PHP >= 5.6.4
	- OpenSSL PHP Extension
	- PDO PHP Extension
	- Mbstring PHP Extension
	- Tokenizer PHP Extension
	- XML PHP Extension
	- Dom PHP Extension
	- Gmp PHP Extension
	- Utilizar comando php -i | grep -i extension para verificar que se encuentran instaladas las extensiones

## ¿Como instalar para desarrollo?

	git clone 
	cd apibase
	composer install
	composer dump-autoload
	php artisan migrate:install
	php artisan migrate:refresh --seed

## Configuración

	Editar el archivo .env
