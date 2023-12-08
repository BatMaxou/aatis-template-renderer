<?php

namespace Aatis\TemplateRenderer\Interface;

interface TemplateRendererInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): void;
}
