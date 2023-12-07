<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtension;
use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;

abstract class AbstractTemplateRenderer implements TypedTemplateRendererInterface
{
    protected const EXTENSION = TemplateFileExtension::DEFAULT;

    abstract public function render(string $template, array $vars = []): void;

    public function getExtension(): string
    {
        return static::EXTENSION->value;
    }
}
