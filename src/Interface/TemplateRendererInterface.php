<?php

namespace Aatis\TemplateRenderer\Interface;

interface TemplateRendererInterface
{
    public const EXTENSION = '';

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): void;
}
