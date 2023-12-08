<?php

namespace Aatis\TemplateRenderer\Enum;

enum TemplateFileExtension: string
{
    case PHP = '.tpl.php';
    case TWIG = '.html.twig';
    case DEFAULT = 'The extension must be overridden in the child class.';
}
