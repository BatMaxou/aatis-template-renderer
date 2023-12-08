<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\DependencyInjection\Entity\Service;
use Aatis\DependencyInjection\Service\ServiceInstanciator;
use Aatis\TemplateRenderer\Exception\ExtensionNotSupported;
use Aatis\TemplateRenderer\Exception\FileNotFoundException;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;
use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;

class TemplateRenderer implements TemplateRendererInterface
{
    /**
     * @var array<string, TypedTemplateRendererInterface>
     */
    private array $renderers = [];

    /**
     * @var TypedTemplateRendererInterface[]
     */
    private array $extraRenderers = [];

    /**
     * @param array<class-string> $extraRenderers
     */
    public function __construct(
        private readonly string $_document_root,
        private readonly PhpRenderer $phpRenderer,
        private readonly TwigRenderer $twigRenderer,
        private readonly ServiceInstanciator $serviceInstanciator,
        array $extraRenderers = []
    ) {
        foreach ($extraRenderers as $extraRenderer) {
            if (class_exists($extraRenderer)) {
                $renderer = $this->serviceInstanciator->instanciate(new Service($extraRenderer));

                if (!$renderer instanceof TypedTemplateRendererInterface) {
                    throw new \InvalidArgumentException(sprintf('Renderer "%s" must implement "%s"', $extraRenderer, TypedTemplateRendererInterface::class));
                }

                $this->extraRenderers[] = $renderer;
            }
        }

        $this->registerRenderers($this->phpRenderer, $this->twigRenderer, ...$this->extraRenderers);
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
    public function registerRenderers(...$renderers): void
    {
        foreach ($renderers as $renderer) {
            $this->renderers[$renderer->getExtension()] = $renderer;
        }
    }
}
