<?php

namespace Aatis\TemplateRenderer\Service;

use Aatis\DependencyInjection\Attribute\AsDefaultTaggedService;
use Aatis\DependencyInjection\Component\Service;
use Aatis\DependencyInjection\Enum\ServiceTagOption;
use Aatis\DependencyInjection\Interface\ServiceSubscriberInterface;
use Aatis\DependencyInjection\Trait\ServiceSubscriberTrait;
use Aatis\Tag\Interface\TagBuilderInterface;
use Aatis\TemplateRenderer\Exception\ExtensionNotSupported;
use Aatis\TemplateRenderer\Exception\FileNotFoundException;
use Aatis\TemplateRenderer\Interface\TemplateRendererInterface;
use Aatis\TemplateRenderer\Interface\TypedTemplateRendererInterface;
use Psr\Container\ContainerInterface;

/**
 * @template TypedTemplateRendererService of Service<TypedTemplateRendererInterface>
 */
#[AsDefaultTaggedService([TemplateRendererInterface::class])]
class TemplateRenderer implements TemplateRendererInterface, ServiceSubscriberInterface
{
    /**
     * @use ServiceSubscriberTrait<TypedTemplateRendererService, TypedTemplateRendererService, array{
     *  file: string,
     * }>
     */
    use ServiceSubscriberTrait {
        __construct as initServiceSubscriber;
    }

    public function __construct(
        private readonly string $_document_root,
        ContainerInterface $container,
    ) {
        $this->initServiceSubscriber($container);
    }

    public static function getSubscribedServices(TagBuilderInterface $tagBuilder): iterable
    {
        yield $tagBuilder->buildFromInterface(TypedTemplateRendererInterface::class, [ServiceTagOption::SERVICE_TARGETED]);
        yield $tagBuilder->buildFromName(HtmlRenderer::class, [ServiceTagOption::SERVICE_TARGETED, ServiceTagOption::FROM_CLASS]);
        yield $tagBuilder->buildFromName(PhpRenderer::class, [ServiceTagOption::SERVICE_TARGETED, ServiceTagOption::FROM_CLASS]);
        yield $tagBuilder->buildFromName(TwigRenderer::class, [ServiceTagOption::SERVICE_TARGETED, ServiceTagOption::FROM_CLASS]);
    }

    public function render(string $templatePath, array $vars = []): string
    {
        $vars['documentRoot'] = $this->_document_root;

        $overrideLocation = $vars['overrideLocation'] ?? null;
        if (null !== $overrideLocation && !is_string($overrideLocation)) {
            throw new \InvalidArgumentException('Override location must be a string');
        }

        $vars['templatesFolderPath'] = $overrideLocation ?? sprintf('%s/../templates', $this->_document_root);
        $fullTemplatePath = sprintf(
            '%s%s%s',
            $vars['templatesFolderPath'],
            str_starts_with($templatePath, '/') ? '' : '/',
            $templatePath,
        );

        if (!file_exists($fullTemplatePath)) {
            throw new FileNotFoundException(sprintf('Template "%s" not found', $templatePath));
        }

        $rendererServices = $this->provide(['file' => $templatePath]);
        if (empty($rendererServices)) {
            throw new ExtensionNotSupported(sprintf('Template extension "%s" not supported.', $templatePath));
        }

        /** @var TypedTemplateRendererInterface $renderer */
        $renderer = $this->serviceStack->get($rendererServices[0]->getClass());
        if (!isset($vars['renderer'])) {
            $vars['renderer'] = $renderer;
        }

        return $renderer->render($fullTemplatePath, $vars);
    }

    /**
     * @param Service<TypedTemplateRendererInterface> $service
     * @param array{file: string} $ctx
     */
    protected function pick(mixed $service, array $ctx): bool
    {
        return str_ends_with($ctx['file'], $service->getClass()::getExtension());
    }
}
