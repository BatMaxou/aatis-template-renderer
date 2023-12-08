# Aatis TR

## About

Customizable and easy to use template renderer based on file extension name.

## Installation

```bash
composer require aatis/template-renderer
```

## Usage

### Requirements

First, add the template renderer into your container.

```yaml
# In config/services.yaml file :

include_services:
    Namespace\Of\The\Template\Renderer:
```

### Basic usage

Call **render()** method with template path and data array.

```php
$templateRenderer = new TemplateRenderer();
$templateRenderer->render('path/to/template', [
    'var_name' => 'value'
]);
```

### Custom Template Renderer

By default, this template renderer supports **.tpl.php** and **.html.twig** files.

You can add your own template renderer by creating:
- an enum with the extension you want to target with your custom renderers.
- a class that extends **AbstractTemplateRenderer**.

```php
enum ExtraTemplateFileExtension: string
{
    case EXTRA = '.extra';
}
```

This Custom Template Renderer must contains: 
- **EXTENSION** constant calling the case of the enum corresponding to the target extension.
- **render()** method that will be called by the base template renderer.

```php
class ExtraRenderer extends AbstractTemplateRenderer
{
    public const EXTENSION = ExtraTemplateFileExtension::EXTRA;

    public function render(string $template, array $vars = []): void
    {
        // Render the special extension type template
    }
}
```

Then, inform the template renderer that you want to use your custom renderer.

```yaml
# In config/services.yaml file :

services:
    Namespace\Of\The\Template\Renderer:
        arguments:
            extraRenderers:
                - Namespace\Of\Your\Custom\Template\Renderer
```
