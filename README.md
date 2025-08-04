# Aatis Template Renderer

## Installation

```bash
composer require aatis/template-renderer
```

## Dependencies

- `twig/twig`
- `aatis/dependency-injection` (https://github.com/BatMaxou/aatis-dependency-injection)
- `aatis/stacking-service` (https://github.com/BatMaxou/aatis-stacking-service)

## Usage

### Requirements

Add the `TemplateRenderer` service into the `Container`.

```yaml
# In config/services.yaml file :

include_services:
  - 'Aatis\TemplateRenderer\Service\TemplateRenderer'
```

```php
// Directly in PHP code when building the container :

(new ContainerBuilder($ctx))
    ->register(TemplateRenderer::class)
    ->build();
```

### Basic usage

Call `render()` method with template path and an optionnal array of data.

```php
$templateRenderer->render('path/to/template', [
    'var_name' => 'value'
]);
```

### Custom Template Renderer

By default, this template renderer supports `.html`, `.tpl.php` and `.html.twig` files.

You can add your own template renderer by creating a class that extends `AbstractTemplateRenderer`.

This Custom Template Renderer must contains:

- `EXTENSION` constant calling the case of the enum corresponding to the target extension.
- `render()` method that will be called by the base template renderer.

```php
class ExtraRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = '.tpl.extra';

    public function render(string $template, array $vars = []): string
    {
        // Transform the special extension type template into a string
    }
}
```

If needed, you can use the `getTemplateContent()` method of the `AbstractTemplateRenderer` to extract the vars and get the content of the template which will be rendered.

```php
class ExtraRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = '.tpl.extra';

    public function render(string $template, array $vars = []): string
    {
        // do some stuff
        $content = $this->getTemplateContent($template, $vars);
        // do some stuff and return the content
    }
}
```

