<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtensionEnum;
use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;

abstract class AbstractTemplateRenderer implements TypedTemplateRendererInterface
{
    protected const EXTENSION = TemplateFileExtensionEnum::DEFAULT;

    abstract public function render(string $template, array $vars = []): void;

    public function getExtension(): string
    {
        return static::EXTENSION->value;
    }
}
