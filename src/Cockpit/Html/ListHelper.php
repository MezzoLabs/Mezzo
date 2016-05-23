<?php


namespace MezzoLabs\Mezzo\Cockpit\Html;


use Illuminate\Support\Collection;

class ListHelper extends HtmlHelper
{
    /**
     * @var Collection
     */
    protected $array;
    /**
     * @var string
     */
    private $tag;


    public function __construct($array, $tag = "ul")
    {
        $this->array = new Collection($array);

        $this->tag = $tag;
    }

    public function render()
    {
        $this->addContent("<{$this->tag}>");

        foreach ($this->array as $key => $value) {
            $this->addContent('<li>' . $value . '</li>');
        }

        $this->addContent("</{$this->tag}>");

        return $this->finishContent();
    }


}