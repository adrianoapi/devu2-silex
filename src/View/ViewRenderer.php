<?php

namespace SON\View;

class ViewRenderer
{

    private $pathTemplates;
    private $templateName;

    public function __construct($pathTemplates)
    {
        $this->pathTemplates = $pathTemplates;
    }

    public function render($name, array $data = array())
    {
        $content = $this->getOutput($name, $data);
        return $this->getOutput(__DIR__ . '/../../templates/layouts/layout.phtml', array('content' => $content));
    }

    protected function getOutput($name, $data = array())
    {
        $this->templateName = $name;
        extract($data);
        ob_start();
        if (!file_exists($this->templateName)) {
            include $this->pathTemplates . "/{$this->templateName}.phtml";
        } else {
            include $this->templateName;
        }
        $output = ob_get_clean();
        return $output;
    }

}
