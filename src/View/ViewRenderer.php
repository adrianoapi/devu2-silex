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
        $this->templateName = $name;
        extract($data);
        ob_start();
        include $this->pathTemplates . "/$this->templateName.phtml";
        $saida = ob_get_clean();
        return $saida;
    }

}
