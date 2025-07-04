# 🎨 Laratheme

**Laratheme** es un paquete para Laravel que permite gestionar múltiples **temas visuales** (themes), facilitando la personalización de vistas y assets para tu aplicación.

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

## 🚀 Características

- ✅ Cambio dinámico de temas mediante configuración
- 🧩 Namespaces automáticos para las vistas del tema activo
- 🛠 Comando Artisan `make:theme` para generar nuevos temas con estructura predefinida
- 📁 Soporte para vistas, assets y stubs personalizables
- 📦 Totalmente integrable con Laravel 11 y 12

---

## 📦 Instalación

Requiere PHP 8.2+ y Laravel 11+.

```bash
composer require codersfree/laratheme
````

### Publicar archivos de configuración y stubs

```bash
php artisan vendor:publish --tag=theme-config
php artisan vendor:publish --tag=theme-stubs
```

Esto generará:

* `config/theme.php`: configuración de Laratheme
* `resources/themes/stubs`: plantillas base para nuevos temas

---

## ⚙️ Configuración

Archivo de configuración: `config/theme.php`

```php
return [
    'active' => env('THEME_ACTIVE', 'default'),

    'paths' => [
        'views' => env('THEME_VIEWS_PATH', resource_path('themes')),
        'assets' => env('THEME_ASSETS_PATH', public_path('themes')),
        'stubs'  => env('THEME_STUBS_PATH', resource_path('themes/stubs')),
    ],
];
```

* `views`: ubicación de las vistas por tema
* `assets`: ubicación pública de CSS, JS, imágenes, etc.
* `stubs`: plantillas para generar nuevos temas

---

## 🧪 Uso

### Crear un nuevo tema

```bash
php artisan make:theme nombre-del-tema
```

Esto creará la siguiente estructura:

```
resources/themes/nombre-del-tema/
├── welcome.blade.php
└── layouts/
    └── app.blade.php

public/themes/nombre-del-tema/
├── css/app.css
├── js/app.js
└── image/
```

---

## 🧩 Cargar vistas y assets del tema

### Cargar una vista del tema activo

```php
use Theme;

return Theme::view('welcome');
```

### Obtener una URL de asset del tema

```php
Theme::asset('css/app.css'); 
// → themes/tu-tema-activo/css/app.css
```

> ✅ Los assets deben estar en `public/themes`.

---

## 📁 Estructura del paquete

```
config/
├── theme.php

src/
├── Console/Commands/MakeThemeCommand.php
├── Facades/Theme.php
├── Services/ThemeService.php
└── ThemeServiceProvider.php

stubs/
├── welcome.blade.php.stub
└── layouts/app.blade.php.stub
```

---

##  Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más información.
