<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;

class TwigRenderer implements TemplateRendererInterface
{
    private const EXTENSION = 'html.twig';

    public function render(string $template, array $data = []): void
    {
        // 
    }
}
