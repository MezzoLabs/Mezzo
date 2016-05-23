<?php


namespace MezzoLabs\Mezzo\Modules\Generator\Generators;


use MezzoLabs\Mezzo\Core\Schema\Attributes\AtomicAttribute;
use MezzoLabs\Mezzo\Core\Schema\ModelSchema;

class PhpCodeGenerator
{

    public $noRulesFor = ['id', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * @var array
     */
    private $lines;

    public function __construct()
    {

    }

    public function rulesArray(ModelSchema $model)
    {
        $atomicAttributes = $model->attributes()->atomicAttributes();

        $rulesArray = [];
        $atomicAttributes->each(function (AtomicAttribute $attribute) use (&$rulesArray) {
            $name = $attribute->name();
            if (in_array($name, $this->noRulesFor)) return true;

            $rulesArray[$name] = $attribute->rules()->toString();
        });

        return $this->arrayString($rulesArray);
    }

        /**
     * Form an array string out of a Collection or an array.
     *
     * @param array $array
     * @return string
     */
    public function arrayString($array = [])
    {
        $this->lines = [];

        $i = 0;
        foreach ($array as $key => $element) {

            $parameter = $this->toParameter($element);

            if ($key === $i) {
                $this->line($parameter);
            } else {
                $this->line("'" . $key . "' => " . $parameter);
            }

            $i++;
        }

        $elementsString = implode(', ' . static::nl(2), $this->lines);

        return '[' . static::nl(2) . $elementsString . static::nl(1) . '];';
    }

    private function toParameter($var)
    {
        return static::parameterize($var);
    }

    /**
     * Procudes a php code parameter from a variable.
     *
     * @param $var
     * @return string
     */
    public static function parameterize($var)
    {
        if (is_numeric($var)) return $var;

        return "\"" . $var . "\"";
    }

    private function line($line)
    {
        $this->lines[] = $line;
    }

    /**
     * Creates a new line with a correct indent (no tabs but spaces)
     *
     * @param int $tabs
     * @return string
     */
    public static function nl($tabs = 1)
    {
        $indent = "";
        for ($i = 0; $i != $tabs; $i++) {
            $indent .= '    ';
        }

        return "\r\n" . $indent;
    }

    public function hiddenArray(ModelSchema $model)
    {
        return $this->arrayString($model->option('hidden'));
    }

    public function fillableArray(ModelSchema $model)
    {
        return $this->arrayString($model->option('fillable'));
    }

    public function castsArray(ModelSchema $model)
    {
        return $this->arrayString($model->option('casts'));
    }

    public function timestampsBoolean(ModelSchema $model)
    {
        return $this->booleanString($model->option('timestamps'));
    }

    public function booleanString($boolean)
    {
        return ($boolean) ? 'true;' : 'false;';
    }

    public function openingTag()
    {
        return '<?php';
    }

    /**
     * @return AnnotationGenerator
     */
    public function annotationGenerator()
    {
        return new AnnotationGenerator();
    }

    private function set($string)
    {
        $this->string = $string;
    }
}