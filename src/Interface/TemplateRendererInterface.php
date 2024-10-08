<?php

namespace Aatis\TemplateRenderer\Interface;

interface TemplateRendererInterface
{
    /**
     * @param array<string, mixed> $vars
     */
    public function render(string $template, array $vars = []): string;
}
