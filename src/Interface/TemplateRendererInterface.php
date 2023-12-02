<?php

namespace Aatis\TemplateRenderer\Interface;

interface TemplateRendererInterface
{
    public function render(string $template, array $data = []): void;
}
