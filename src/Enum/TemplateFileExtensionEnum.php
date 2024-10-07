<?php

namespace Aatis\TemplateRenderer\Enum;

enum TemplateFileExtensionEnum: string
{
    case HTML = '.html';
    case PHP = '.tpl.php';
    case TWIG = '.html.twig';
    case DEFAULT = 'default';
}
