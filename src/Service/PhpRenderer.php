<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;

class PhpRenderer implements TemplateRendererInterface
{
    private const EXTENSION = 'tpl.php';

    public function render(string $template, array $data = []): void
    {
        // 
    }
}
