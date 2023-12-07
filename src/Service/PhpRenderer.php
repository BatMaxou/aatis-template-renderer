<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtension;

class PhpRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = TemplateFileExtension::PHP;

    public function render(string $template, array $vars = []): void
    {
        if (!isset($vars['renderer'])) {
            $vars['renderer'] = $this;
        }

        extract($vars);
        require_once $template;
    }
}
