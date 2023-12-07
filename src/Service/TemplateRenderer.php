<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\TemplateRenderer\Exception\ExtensionNotSupported;
use Aatis\TemplateRenderer\Exception\FileNotFoundException;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;
use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;

class TemplateRenderer implements TemplateRendererInterface
{
    /**
     * @var TypedTemplateRendererInterface[]
     */
    private array $renderers = [];

    public function __construct(
        private readonly string $_document_root
    ) {
        $this->registerRenderer(new PhpRenderer(), new TwigRenderer($_document_root));
    }

    public function render(string $templatePath, array $vars = []): void
    {
        $vars['documentRoot'] = $this->_document_root;
        $vars['templatesFolderPath'] = $vars['overrideLocation'] ?? $this->_document_root.'/../templates';
        $fullTemplatePath = $vars['templatesFolderPath'].$templatePath;

        if (!file_exists($fullTemplatePath)) {
            throw new FileNotFoundException(sprintf('Template "%s" not found', $templatePath));
        }

        $rendered = false;

        foreach ($this->renderers as $extension => $renderer) {
            if (str_ends_with($templatePath, $extension)) {
                $renderer->render($fullTemplatePath, $vars);
                $rendered = true;
            }
        }

        if (!$rendered) {
            throw new ExtensionNotSupported(sprintf('Extension template "%s" not supported.', $templatePath));
        }
    }

    /**
     * @param TypedTemplateRendererInterface $renderers
     */
    public function registerRenderer(...$renderers): void
    {
        foreach ($renderers as $renderer) {
            $this->renderers[$renderer->getExtension()] = $renderer;
        }
    }
}
