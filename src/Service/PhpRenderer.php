<?php

namespace Aatis\TemplateRenderer\Service;

class PhpRenderer extends AbstractTemplateRenderer
{
    protected const EXTENSION = '.tpl.php';

    public function render(string $template, array $vars = []): string
    {
        return $this->getTemplateContent($template, $vars);
    }
}
