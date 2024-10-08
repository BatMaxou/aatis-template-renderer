<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Enum\TemplateFileExtensionEnum;
use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;

abstract class AbstractTemplateRenderer implements TypedTemplateRendererInterface
{
    public const EXTENSION = TemplateFileExtensionEnum::DEFAULT;

    /**
     * @param array<string, mixed> $vars
     */
    abstract public function render(string $template, array $vars = []): string;

    public function getExtension(): string
    {
        if (TemplateFileExtensionEnum::DEFAULT === static::EXTENSION) {
            throw new \RuntimeException('Extension is set to default, it must be overridden in child class');
        }

        return static::EXTENSION->value;
    }

    /**
     * @param array<string, mixed> $vars
     */
    protected function getTemplateContent(string $template, array $vars = []): string
    {
        extract($vars);

        ob_start();
        require_once $template;
        $content = ob_get_contents();
        ob_end_clean();

        if (false === $content) {
            throw new \RuntimeException(sprintf('Failed to get content of template %s', $template));
        }

        return $content;
    }
}
