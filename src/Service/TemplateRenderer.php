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

    public function __construct(
        private readonly string $_document_root
    ) {
        $this->registerRenderer(new PhpRenderer(), new TwigRenderer());
    }

    public function render(string $templatePath, array $vars = []): void
    {
        $vars['documentRoot'] = $this->_document_root;
        $vars['templateFolderPath'] = $vars['overrideLocation'] ?? $this->_document_root.'/../templates';
        $fullTemplatePath = $vars['templateFolderPath'].$templatePath;

        if (!file_exists($fullTemplatePath)) {
            throw new FileNotFoundException(sprintf('Template "%s" not found', $templatePath));
        }

        foreach ($this->renderers as $extension => $renderer) {
            if (str_ends_with($templatePath, $extension)) {
                $renderer->render($fullTemplatePath, $vars);
            }
        }
    }

    /**
     * @param TemplateRendererInterface $renderers
     */
    public function registerRenderer(...$renderers): void
    {
        foreach ($renderers as $renderer) {
            $this->renderers[$renderer::EXTENSION] = $renderer;
        }
    }
}
