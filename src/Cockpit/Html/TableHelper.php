<?php


namespace MezzoLabs\Mezzo\Cockpit\Html;


use Illuminate\Support\Collection;

class TableHelper extends HtmlHelper
{
    /**
     * @var Collection
     */
    protected $array;


    public function __construct($array)
    {
        $this->array = new Collection($array);

    }

    public function render()
    {
        $this->addContent('<table class="table table-striped table-bordered">');

        $this->renderHeader();
        $this->renderBody();

        $this->addContent('</table>');
        return $this->finishContent();
    }

    protected function renderHeader()
    {
        $first = $this->array->first();

        $this->addContent('<tr>');

        foreach ($first as $key => $value) {
            $this->addContent('<th>');
            $this->addContent(ucfirst($key));
            $this->addContent('</th>');
        }


        $this->addContent('</tr>');

    }

    protected function renderBody()
    {
        foreach ($this->array as $line) {
            $this->addContent('<tr>');
            foreach ($line as $column) {
                $this->addContent('<td>');
                $this->addContent($column);
                $this->addContent('</td>');
            }
            $this->addContent('</tr>');
        }
    }

}