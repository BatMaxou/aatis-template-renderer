<?php

namespace Aatis\TemplateRenderer\Enum;

enum TemplateFileExtensionEnum: string
{
    case PHP = '.tpl.php';
    case TWIG = '.html.twig';
    case DEFAULT = 'The extension must be overridden in the child class.';
}
