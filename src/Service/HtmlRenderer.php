<?php

namespace Aatis\TemplateRenderer\Service;

class HtmlRenderer extends AbstractTemplateRenderer
{
    protected const EXTENSION = '.html';

    public function render(string $template, array $vars = []): string
    {
        return $this->getTemplateContent($template);
    }
}
