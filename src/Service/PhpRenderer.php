<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtensionEnum;

class PhpRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = TemplateFileExtensionEnum::PHP;

    public function render(string $template, array $vars = []): void
    {
        if (!isset($vars['renderer'])) {
            $vars['renderer'] = $this;
        }

        extract($vars);
        require_once $template;
    }
}
