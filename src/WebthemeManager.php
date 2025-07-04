<?php

namespace NucleusIndustries\Webtheme;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\View\Factory;
use Soap\Url;

class WebthemeManager
{

    protected Factory $view;
    protected UrlGenerator $url;

    public function __construct(Factory $view, UrlGenerator $url)
    {
        $this->view = $view;
        $this->url = $url;
    }

    public function getActiveTheme(): string
    {
        return config('webtheme.active');
    }

    public function view(string $view, array $data = [])
    {
        $view = "webtheme::{$view}";
        // return view($view, $data);

        return $this->view->make($view, $data);
    }

    public function asset(string $path): string
    {
        $activeTheme = $this->getActiveTheme();
        $assetPath = config('webtheme.paths.assets');

        //'public/themes'
        $path = "{$assetPath}/{$activeTheme}/" . ltrim($path,'/');
        $path = str_replace('public/', '', $path);

        return $this->url->asset($path);
    }


}