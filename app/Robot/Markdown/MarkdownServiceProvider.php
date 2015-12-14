<?php namespace Robot\Markdown;

use Illuminate\Support\ServiceProvider;
use Event;
use App;

class MarkdownServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton('Robot\Markdown\Markdown', function ($app) {
            return new \Robot\Markdown\Markdown;
        });
    }

    public function provides()
    {
        return ['Robot\Markdown\Markdown'];
    }
}
