<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Schema;


use MezzoLabs\Mezzo\Core\Files\File;
use MezzoLabs\Mezzo\Modules\Generator\Generators\AnnotationGenerator;
use MezzoLabs\Mezzo\Modules\Generator\Generators\PhpCodeGenerator;

abstract class FileSchema
{
    /**
     * The content of the generated file.
     *
     * @return string
     */
    abstract public function content();

    /**
     * The name of the template inside view folder.
     *
     * @return string
     */
    abstract protected function templateName();

    /**
     * The file name of the according file.
     *
     * @return string
     */
    abstract protected function shortFileName();

    /**
     * Adds the template name to the template directory.
     *
     * @return string
     */
    protected function fullTemplateName()
    {
        return 'modules.generator::templates.' . $this->templateName();
    }

    /**
     * Returns the filled out template.
     *
     * @param $data
     * @return string
     */
    protected function fillTemplate($data)
    {
        $viewFactory = mezzo()->makeViewFactory();

        $phpGenerator = mezzo()->make(PhpCodeGenerator::class);
        $annotationGenerator = mezzo()->make(AnnotationGenerator::class);

        $templateData = [
            'PHP_OPENING_TAG' => '<?php',
            'php' => $phpGenerator,
            'annotation' => $annotationGenerator
        ];

        return $viewFactory->make($this->fullTemplateName(), $data, $templateData)->render();
    }


    /**
     * Returns a File instance. The content is the filled out template.
     *
     * @param $folder
     * @return \MezzoLabs\Mezzo\Core\Files\File
     */
    public function file($folder)
    {

        return new File($folder . '/' . $this->shortFileName(), $this->content());
    }

} 