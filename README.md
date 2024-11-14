# Wappi

1) clonar el respositorio con github desktop o por linea de comandos

    Link del repositorio: https://github.com/bianquir/proyectoW
    luego entrar a la carpeta del proyecto 

2) Duplicar el env.example y renombrar para dejar solo .env (borrar el .example)

3) instalar composer y npm con los siguientes comandos en terminal:
    * composer install
    * npm install

4) Ejecutar el comando:
     * php artisan key:generate
  
5) Instalar Filament
    * composer require filament/filament

6) Instalar Breeze
    * composer require laravel/breeze --dev
    * php artisan breeze:install

7) Instalar Livewire
    * composer require livewire/livewire
  
8) Instalar Pusher
    * composer require pusher/pusher-php-server

9) Ejecutar las migraciones y los seeders (antes coloca en el archivo .env el nombre de la base de datos que creaste para utilizar
    *Ejemplo:
    *DB_DATABASE=nombre-base-de-datos
    *DB_USERNAME=root
    *DB_PASSWORD=
    ):

    Luego ejecutar:
    * php artisan migrate
    * php artisan db:seed

10) Ingresar a la web https://ngrok.com/ e ingresar con el siguiente mail y contraseña:
    *Mail: triodinamicoapps@gmail.com
    *Contraseña: Triodinamico123-

    *Una vez ingresado dirigirse al panel "Getting Started", a la opción "Your Authtoken":
    *Copia el Token dado y colocalo reemplazando la variable $YOUR_AUTHTOKEN. Luego, coloca el siguiente comando en la terminal:
    *ngrok config add-authtoken $YOUR_AUTHTOKEN

11) Levantar el servidor, las dependencias de Js y ngrok

    * php artisan serve
    * npm run dev
    * ngrok http 8000

12) Conectar el acceso en developers.facebook (Contacta con nosotros para gestionar el acceso)
