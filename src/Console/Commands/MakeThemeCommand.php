<?php

namespace NucleusIndustries\Webtheme\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeThemeCommand extends Command
{

    protected Filesystem $files;

    // Esta clase es un comando de consola que permite crear un nuevo tema
    // Inyectar una clase
    public function __construct(Filesystem $files)


    {
        // Llamar al constructor del padre
        parent::__construct();

        $this->files = $files;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Comando artisam que desencadena una acción en la consola
    // El nombre del comando es make:theme y recibe un argumento llamado name

    protected $signature = 'make:theme {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Asi es como debes crear un tema
        //resources/views/webtheme/{name}
        $name = $this->argument('name');
        $viewsPath = base_path(config('webtheme.paths.views') . '/' . $name);
        $assetsPath = base_path(config('webtheme.paths.assets') . '/' . $name);

        if ($this->files->isDirectory($viewsPath)) {
            $this->error("Theme '{$name}' already exists!");
            return 1;
        }

        if ($this->files->isDirectory($assetsPath)) {
            $this->error("Theme '{$name}' already exists!");
            return 1;
        }

        // Crea el directorio del tema y sus subdirectorios
        $this->createThemeDirectory($name, $viewsPath, $assetsPath);
        $this->createThemeFiles($name, $viewsPath, $assetsPath);

        // Notifica que las carpetas se han creado correctamente
        $this->info("Theme '{$name}' created successfully!");
        $this->info("View path: {$viewsPath}");
        $this->info("Assets path: {$assetsPath}");

        return 0;
    }

    public function createThemeDirectory($name, $viewsPath, $assetsPath)
    {

        $viewsPath = base_path(config('webtheme.paths.views') . '/' . $name);
        $assetsPath = base_path(config('webtheme.paths.assets') . '/' . $name);


        // Crea el directorio del tema y sus subdirectorios
        $this->files->makeDirectory($viewsPath, 0755, true);
        $this->files->makeDirectory("{$viewsPath}/layouts", 0755, true);
        $this->files->makeDirectory("{$viewsPath}/components", 0755, true);

        $this->files->makeDirectory($assetsPath, 0755, true);
        $this->files->makeDirectory("{$assetsPath}/css", 0755, true);
        $this->files->makeDirectory("{$assetsPath}/js", 0755, true);
        $this->files->makeDirectory("{$assetsPath}/images", 0755, true);
    }

    public function createThemeFiles($name, $viewsPath, $assetsPath)
    {
        // Crea los archivos de welcome
        $welcomeStub = $this->getStubContent('views/welcome.blade.php.stub', [
            'themeName' => $name,
        ]);
        $this->files->put("{$viewsPath}/welcome.blade.php", $welcomeStub);

        // Crea los archivos de layout
        $layoutStub = $this->getStubContent('views/layouts/app.blade.php.stub', [
            'themeName' => $name,
        ]);
        $this->files->put("{$viewsPath}/layouts/app.blade.php", $layoutStub);

        // Crea los archivos de los assets - css
        $cssStub = $this->getStubContent('assets/css/app.css.stub', ['themeName' => $name,]);
        $this->files->put("{$assetsPath}/css/app.css", $cssStub);

        // Crea los archivos de los assets - js
        $jsStub = $this->getStubContent('assets/js/app.js.stub', ['themeName' => $name,]);
        $this->files->put("{$assetsPath}/js/app.js", $jsStub);
    }

    public function getStubContent(string $path, array $data)
    {
        // $path = __DIR__ . '/../../stubs/' . $path;
        $path = $this->getStubPath($path);

        $stub = $this->files->get($path);

        foreach ($data as $key => $value) {
            $stub = str_replace('{{' . $key . '}}', $value, $stub);
        }
        return $stub;
    }

    public function getStubPath(string $path): string
    {
        // Primero busca en resources/stubs/webtheme
        $customPath = base_path(config('webtheme.paths.stubs') . '/' . $path);
        
        if ($this->files->exists($customPath)) {
            return $customPath;
        }

        // Si no lo encuentra, busca en el directorio del paquete
        return __DIR__ . '/../../stubs/' . $path;
    }
}
