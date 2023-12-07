<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtension;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;

class TwigRenderer implements TemplateRendererInterface
{
    public const EXTENSION = TemplateFileExtension::TWIG;

    public function render(string $template, array $vars = []): void
    {
        if (!isset($vars['renderer'])) {
            $vars['renderer'] = $this;
        }

        extract($vars);
        require_once $template;
    }
}
