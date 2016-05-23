<?php


namespace MezzoLabs\Mezzo\Core\Templating;


class BladeDirectives
{
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    public function __construct()
    {
        $this->compiler = $this->getCompiler();
    }

    /**
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    public static function getCompiler()
    {
        return app('view')->getEngineResolver()->resolve('blade')->getCompiler();
    }

    public function addDirectives()
    {
        $this->addIfAngular();
    }

    protected function addIfAngular()
    {
        $this->compiler->directive('ifangular', function ($expression) {

            return '<?php
                if($module_page->isRenderedByFrontend())
                    echo ' . $expression . ';
            ?>';

        });
    }


}