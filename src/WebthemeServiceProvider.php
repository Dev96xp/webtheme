<?php

namespace NucleusIndustries\Webtheme;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use NucleusIndustries\Webtheme\Console\Commands\MakeThemeCommand;

class WebthemeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    // Es lo promero que se ejecuta al iniciar el servicio
    // Se utiliza para registrar bindings, eventos, middlewares, etc.
    // Este método se ejecuta antes de boot
    public function register(): void
    {
        // Permite acceder a la configuración del paquete con la direccion proporcionada
        // y ademas se le otorga un nombre-alias(webtheme) para poder acceder a ella
        // desde cualquier parte de la aplicacion
        $this->mergeConfigFrom(
            __DIR__ . '/../config/webtheme.php',
            'webtheme'
        );

        $this->commands([MakeThemeCommand::class,]);
    }


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Load routes, views, migrations, etc. if needed
        $this->publishes([
            __DIR__ . '/../config/webtheme.php' => config_path('webtheme.php'),
        ], 'webtheme-config');

        // Aqui se indica de donde viene lo que se va a publicar(/Stubs)
        // y a donde se va a publicar ('stubs/webtheme) y el nopmbre que se va a utilizar
        // para publicar el archivo este caso 'webtheme-stubs', entonces para publicar
        // los archivos se debe ejecutar el comando: php artisan vendor:publish --tag=webtheme-stubs
        // Esto copiara los archivos de la carpeta Stubs a la carpeta resources/stubs/webtheme
        // dentro del proyecto laravel
        $this->publishes([
            __DIR__ . '/Stubs' => resource_path('stubs/webtheme'),
        ], 'webtheme-stubs');


        $default = config('webtheme.default');
        $active = config('webtheme.active');
        $viewPaths = base_path(config('webtheme.paths.views'));

        // --- Migrations  ---

        // $this->publishesMigrations([
        //     // __DIR__ . '/../database/migrations' => database_path('migrations'),
        //     __DIR__ . '/../database/migrations' => resource_path('database/migrations'),
        // ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        // ----- nameSpace VISTAS ------
        // Definiendo un nameSpace para las VISTAS, que nos permitira en contrar la vista WELCOME
        // dependiendo de su definicion en el archivo de configuracion
        // Para definirlo se necesitan dos cosas el nombre del namespace en este cas: 'webtheme' y
        // las rutas donde se encontraran y que son un array de rutas
        // Este Name Space nos permitira llamar a las vistas de la siguiente manera:
        View::addNamespace('webtheme', [
            $viewPaths . '/' . $active,         // 1.Esta es la ruta que fue definida enla archivo de configuracion, si NO existe tal vista AQUI,
            //   que lo busque en la la vista por default
            $viewPaths . '/' . $default,        // 2.Esta es la ruta por defecto (default) que TAMBIEN se definio en el archivo de configuracion,
            //   y por ultimo, si tampoco la encuentra AQUI, que la busque en la vista tradicional(original de laravel)
            resource_path('views'),             // 3.Esta es la ruta tradicional de laravel
        ]);



        // ----- nameSpace COMPONENTES ------
        // Componentes sin clases por eso son anonimos
        Blade::anonymousComponentNamespace(
            $viewPaths . '/' . $default . '/components',
            'webtheme'
        );

        // Registro de una clase
        // Parametro 1 - Este singleton se llamara: webtheme
        // Parametro 2 - Usara una funcion anonima que retornara una instancia de WebthemeManager
        // NOTA: El singleton es un patron de diseño que permite que una clase tenga UNA SOLA INSTANCIA
        // y proporciona un punto de acceso global a esa instancia
        $this->app->singleton('webtheme', function ($app) {
            return new WebthemeManager($app['view'], $app['url']);
        });
    }
}
