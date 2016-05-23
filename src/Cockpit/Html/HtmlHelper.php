<?php


namespace MezzoLabs\Mezzo\Cockpit\Html;

class HtmlHelper
{
    /**
     * Buffer for css classes
     *
     * @var array
     */
    protected $cssClasses = [];

    /**
     * Buffer for HTML content.
     *
     * @var string
     */
    protected $content = '';

    public function table($array = null)
    {
        $this->resetBuffers();
        $tableHelper = new TableHelper($array);
        return $tableHelper->render();
    }

    protected function resetBuffers()
    {
        $this->startNewCssClass();
        $this->content = '';
    }

    protected function startNewCssClass()
    {
        $this->cssClasses = [];
    }

    public function ul($array = null)
    {
        $this->resetBuffers();
        $tableHelper = new ListHelper($array, 'ul');
        return $tableHelper->render();
    }

    public function ol($array = null)
    {
        $this->resetBuffers();
        $tableHelper = new ListHelper($array, 'ol');
        return $tableHelper->render();
    }

    public function css($key, $parameters)
    {
        $this->startNewCssClass();

        if ($key == 'sidebar')
            return $this->sidebar()->cssClass($parameters);
    }

    public function sidebar()
    {
        $this->resetBuffers();
        return new SidebarHelper();
    }

    /**
     * Checks if a section is set in the child template.
     *
     * @param $sectionName
     * @return bool
     */
    public function sectionExists($sectionName)
    {
        return array_key_exists('content-aside', \View::getSections());
    }

    protected function cssClassString()
    {
        $string = implode(' ', $this->cssClasses);
        $this->startNewCssClass();
        return $string;
    }

    protected function decideCssClass($decision, $class1, $class2)
    {
        if ($decision)
            $this->addCssClass($class1);
        else
            $this->addCssClass($class2);
    }

    /**
     * @param $class
     */
    protected function addCssClass($class)
    {
        $this->cssClasses[] = $class;
    }

    protected function addContent($content)
    {
        $this->content .= $content;
    }

    protected function finishContent()
    {
        $content = $this->content;

        $this->content = "";
        return $content;
    }

    public function viewKey($shortName)
    {
        $shortName = str_replace(['.', '-', '_'], '', $shortName);
        //TODO-SCHS: Replace this with something nicer
        switch ($shortName) {
            case 'editpagelayout':
                return 'cockpit::pages.layouts.edit';
            case 'formcontentedit':
                return 'cockpit::partials.form-content-edit';
            case 'formcontentcreate':
                return 'cockpit::partials.form-content-create';
            case 'formcontentcreateoredit':
                return 'cockpit::partials.formcontent.create_or_edit';
            case 'submitedit':
                return 'cockpit::partials.pages.edit_submit';
            default:
                return 'UNKOWN VIEW KEY ' . $shortName;
        }
    }
}