<?php

namespace Aatis\TemplateRenderer\Interface;

interface TypedTemplateRendererInterface extends TemplateRendererInterface
{
    public static function getExtension(): string;
}
