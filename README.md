# Aatis Template Renderer

## About

Customizable and easy to use template renderer based on file extension name.

## Advertisement

This package is a part of `Aatis` and can't be used without the following packages :

- `aatis/dependency-injection` (https://github.com/BatMaxou/aatis-dependency-injection)

## Installation

```bash
composer require aatis/template-renderer
```

## Usage

### Requirements

Add the `TemplateRenderer` service into the `Container`.

```yaml
# In config/services.yaml file :

include_services:
  - 'Aatis\TemplateRenderer\Service\TemplateRenderer'
```

### Basic usage

Call `render()` method with template path and data array.

```php
$templateRenderer = new TemplateRenderer();
$templateRenderer->render('path/to/template', [
    'var_name' => 'value'
]);
```

### Custom Template Renderer

By default, this template renderer supports `.html`, `.tpl.php` and `.html.twig` files.

You can add your own template renderer by creating:

- an enum with the extension you want to target with your custom renderers.
- a class that extends `AbstractTemplateRenderer`.

```php
enum ExtraTemplateFileExtension: string
{
    case EXTRA = '.extra';
}
```

This Custom Template Renderer must contains:

- `EXTENSION` constant calling the case of the enum corresponding to the target extension.
- `render()` method that will be called by the base template renderer.

```php
class ExtraRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = ExtraTemplateFileExtension::EXTRA;

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
    public const EXTENSION = ExtraTemplateFileExtension::EXTRA;

    public function render(string $template, array $vars = []): string
    {
        // do some stuff
        $content = $this->getTemplateContent($template, $vars);
        // do some stuff and return the content
    }
}
```

Finally, do not forget to add it into the `TemplateRenderer` configuration:

```yaml
# In config/services.yaml file :

services:
  Aatis\TemplateRenderer\Service\TemplateRenderer:
    arguments:
      extraRenderers:
        - 'Namespace\Of\Your\Custom\Template\Renderer'
```
