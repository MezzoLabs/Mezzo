<?php


namespace MezzoLabs\Mezzo\Core\Annotations\Reader;


use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Annotations\Reader as ReaderInterface;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;

class AnnotationReader
{
    /**
     * @var Collection
     */
    protected $modelAnnotationsCache;

    /**
     * @var bool
     */
    protected $debug = true;

    /**
     * @var string
     */
    protected $cacheStorage = 'doctrineAnnotations';

    /**
     * @var ReaderInterface
     */
    protected $doctrineReader;


    public function __construct()
    {
        $this->modelAnnotationsCache = new Collection();

        $this->doctrineReader = $this->makeDoctrineAnnotationReader();
    }

    /**
     * @return ReaderInterface
     */
    protected function makeDoctrineAnnotationReader()
    {
        //@TODO-SCHS: Move to CachedReader


        $this->registerSilentAutoloading();

        return new FileCacheReader(
            new DoctrineAnnotationReader(),
            storage_path('app/') . $this->cacheStorage,
            $this->debug
        );
    }

    private function registerSilentAutoloading()
    {
        AnnotationRegistry::registerLoader('class_exists');

    }

    public function model(ModelReflection $modelReflection)
    {
        if ($this->modelAnnotationsCache->has($modelReflection->className()))
            return $this->modelAnnotationsCache->get($modelReflection->className());

        return new ModelAnnotations($modelReflection);
    }

    /**
     * @param ModelAnnotations $modelAnnotations
     */
    public function cache(ModelAnnotations $modelAnnotations)
    {
        $this->modelAnnotationsCache->put($modelAnnotations->modelClassName(), $modelAnnotations);
    }

    /**
     * @param \ReflectionProperty $property
     * @return Annotations
     */
    public function getPropertyAnnotations(\ReflectionProperty $property)
    {
        $annotationArray = $this->doctrineReader()->getPropertyAnnotations($property);

        return new Annotations($annotationArray);
    }

    /**
     * @return ReaderInterface
     */
    public function doctrineReader()
    {
        return $this->doctrineReader;
    }

    private function registerAutoloadNamespace()
    {
        AnnotationRegistry::registerAutoloadNamespace(
            'MezzoLabs\Mezzo\Core\Annotations',
            mezzo()->path()->toSource());
    }
}