<?php

namespace Aatis\TemplateRenderer\Service;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Aatis\TemplateRenderer\Enum\TemplateFileExtensionEnum;

class TwigRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = TemplateFileExtensionEnum::TWIG;

    private string $twigEnvironmentPath;

    private Environment $twigEnvironment;

    public function __construct(
        private readonly string $_document_root
    ) {
        $this->twigEnvironmentPath = sprintf('%s/../templates', $this->_document_root);
        $this->twigEnvironment = new Environment(new FilesystemLoader($this->twigEnvironmentPath));
    }

    public function render(string $template, array $vars = []): string
    {
        return $this->twigEnvironment->render(str_replace($this->twigEnvironmentPath, '', $template), $vars);
    }
}
