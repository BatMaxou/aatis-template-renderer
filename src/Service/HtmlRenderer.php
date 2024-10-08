<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtensionEnum;

class HtmlRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = TemplateFileExtensionEnum::HTML;

    public function render(string $template, array $vars = []): string
    {
        return $this->getTemplateContent($template);
    }
}
