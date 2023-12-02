<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Exception\FileNotFoundException;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;

class TemplateRenderer implements TemplateRendererInterface
{
    public function __construct(
        // ...
    )
    {
    }

    public function render(string $templatePath, array $data = []): void
    {
        if (
            !file_exists($templatePath)
            || !str_ends_with($templatePath, '.html.twig')
            || str_ends_with($templatePath, '.php')
        ) {
            throw new FileNotFoundException(sprintf('Template "%s" not found', $templatePath));
        }
    }
}
