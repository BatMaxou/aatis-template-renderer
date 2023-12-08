<?php

namespace Aatis\TemplateRenderer\Service;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Aatis\TemplateRenderer\Enum\TemplateFileExtension;

class TwigRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = TemplateFileExtension::TWIG;

    private string $twigEnvironmentPath;

    private Environment $twigEnvironment;

    public function __construct(
        private readonly string $_document_root
    ) {
        $this->twigEnvironmentPath = $this->_document_root.'/../templates';
        $this->twigEnvironment = new Environment(new FilesystemLoader($this->twigEnvironmentPath));
    }

    public function render(string $template, array $vars = []): void
    {
        if (!isset($vars['renderer'])) {
            $vars['renderer'] = $this;
        }

        echo $this->twigEnvironment->render(str_replace($this->twigEnvironmentPath, '', $template), $vars);
    }
}
