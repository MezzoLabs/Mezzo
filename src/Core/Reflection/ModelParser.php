<?php


namespace MezzoLabs\Mezzo\Core\Reflection;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use MezzoLabs\Mezzo\Core\Cache\Singleton;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\ModelReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentRelationshipReflection;
use MezzoLabs\Mezzo\Core\Reflection\Reflections\EloquentRelationshipReflections;

class ModelParser
{
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var ModelReflection
     */
    private $modelReflection;

    /**
     * @param ModelReflection $modelReflection
     * @internal param $filename
     */
    public function __construct(ModelReflection $modelReflection)
    {
        $this->modelReflection = $modelReflection;
        $this->filename = $modelReflection->fileName();
    }

    public function tokenStream()
    {
        $filename = $this->filename();

        return Singleton::get(
            'tokens.' . $filename,
            function () use ($filename) {
                return static::tokensFromFile($filename);
            }
        );
    }

    /**
     * @param array $sequence
     * @return Collection
     */
    public function findSequences(array $order)
    {
        $stream = $this->tokenStream();

        $sequences = [];

        $pointer = 0;
        $sequence = [];
        foreach ($stream as $token) {
            $needle = $order[$pointer];
            if ($token['name'] == $needle) {
                $pointer++;
                $sequence[] = $token;
            }

            if ($pointer == count($order)) {
                $pointer = 0;
                $sequences[] = $sequence;
                $sequence = [];
            }
        }

        return $sequences;
    }

    public function publicFunctions()
    {
        return $this->findSequences(['T_PUBLIC', 'T_FUNCTION', 'T_STRING', 'T_RETURN', 'T_STRING']);
    }

    /**
     * @return EloquentRelationshipReflections
     */
    public function relationships()
    {
        $relationships = new EloquentRelationshipReflections();
        foreach ($this->publicFunctions() as $tokenSequence) {
            $name = $tokenSequence[2]['content'];
            $type = $tokenSequence[4]['content'];

            if (EloquentRelationshipReflection::isAllowed($type))
                $relationships->put($name, new EloquentRelationshipReflection($this->modelReflection, $name));
        }

        return $relationships;
    }


    public static function tokensFromFile($filename)
    {
        $sourceCode = File::get($filename);
        $allTokens = token_get_all($sourceCode);

        $tokens = new Collection();

        foreach ($allTokens as $token) {
            if (is_array($token)) {
                $name = token_name($token[0]);
                $content = $token[1];
            } else {
                $name = $token;
                $content = $token;
            }

            $token = [
                'name' => $name,
                'content' => $content
            ];

            $tokens->push($token);
        }


        return $tokens;
    }

    /**
     * @return mixed
     */
    public function filename()
    {
        return $this->filename;
    }

}