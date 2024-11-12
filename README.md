# Wappi

1) clonar el respositorio con github desktop o por linea de comandos

Link del repositorio: https://github.com/bianquir/proyectoW
luego entrar a la carpeta del proyecto 

2) Duplicar el env.example y renombrar para dejar solo .env (borrar el .example)

3)instalar composer y npm con los siguientes comandos en terminal:
    * composer install
    * npm install

4) Ejecutar el comando:
     * php artisan key:generate
  
5) Instalar Filament
    * composer require filament/filament
    * php artisan migrate

6) Instalar Breeze
    * composer require laravel/breeze --dev
    * php artisan breeze:install
    * php artisan migrate

7) Instalar Livewire
    * composer require livewire/livewire

8) Ejecutar las migraciones
    * php artisan migrate
  
9) Levantar el servidor, las dependencias de Js y ngrok

    * php artisan serve
    * npm run dev
    * ngrok http 8000

10) Conectar el acceso en developers.facebook (Contacta con nosotros para gestionar el acceso)
