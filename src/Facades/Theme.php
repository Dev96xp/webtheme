<?php

namespace NucleusIndustries\Webtheme\Facades;

use Illuminate\Support\Facades\Facade;

// Definicion de nuestra fachada: Theme
// Aqui extendi la clase Facade de Laravel y listo, el se encarga de todo
class Theme extends Facade
{
    // Metodo statico protejido
    protected static function getFacadeAccessor(): string
    {
        return 'webtheme';
    }

}