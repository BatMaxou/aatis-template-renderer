<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Exception\FileNotFoundException;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;

class TemplateRenderer implements TemplateRendererInterface
{
    /**
     * @var TemplateRendererInterface[] 
     */
    private array $renderers = [];

    /**
     * @param TemplateRendererInterface[] $additionnalRenderers
     */
    public function __construct(private array $additionnalRenderers = [])
    {
        $this->registerRenderer(new PhpRenderer(), new TwigRenderer(), ...$additionnalRenderers);
    }

    public function render(string $templatePath, array $data = []): void
    {
        if (
            !file_exists($templatePath)
            || !str_ends_with($templatePath, '.html.twig')
            || str_ends_with($templatePath, '.php')
        ) {
            throw new FileNotFoundException(sprintf('Template "%s" not found', $templatePath));
        }
    }

    private function registerRenderer(...$renderers): void
    {
    }
}
