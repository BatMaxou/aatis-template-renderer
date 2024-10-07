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
        private readonly HtmlRenderer $htmlRenderer,
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

        $this->registerRenderers($this->htmlRenderer, $this->phpRenderer, $this->twigRenderer, ...$this->extraRenderers);
    }

    public function render(string $templatePath, array $vars = []): string
    {
        $vars['documentRoot'] = $this->_document_root;

        $overrideLocation = $vars['overrideLocation'] ?? null;
        if (null !== $overrideLocation && !is_string($overrideLocation)) {
            throw new \InvalidArgumentException('Override location must be a string');
        }

        $vars['templatesFolderPath'] = $overrideLocation ?? sprintf('%s/../templates', $this->_document_root);
        $fullTemplatePath = sprintf('%s%s', $vars['templatesFolderPath'], $templatePath);

        if (!file_exists($fullTemplatePath)) {
            throw new FileNotFoundException(sprintf('Template "%s" not found', $templatePath));
        }

        foreach ($this->renderers as $extension => $renderer) {
            if (str_ends_with($templatePath, $extension)) {
                if (!isset($vars['renderer'])) {
                    $vars['renderer'] = $renderer;
                }

                return $renderer->render($fullTemplatePath, $vars);
            }
        }

        throw new ExtensionNotSupported(sprintf('Template extension "%s" not supported.', $templatePath));
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
