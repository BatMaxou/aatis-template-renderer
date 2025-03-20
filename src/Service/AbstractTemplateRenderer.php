<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;

abstract class AbstractTemplateRenderer implements TypedTemplateRendererInterface
{
    protected const EXTENSION = '';

    /**
     * @param array<string, mixed> $vars
     */
    abstract public function render(string $template, array $vars = []): string;

    public static function getExtension(): string
    {
        if (!is_string(static::EXTENSION) || empty(static::EXTENSION)) {
            throw new \RuntimeException('Extension is not set, it must be overridden in child class');
        }

        return static::EXTENSION;
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
