<?php

namespace Aatis\TemplateRenderer\Interface;

interface TypedTemplateRendererInterface extends TemplateRendererInterface
{
    public function getExtension(): string;
}
