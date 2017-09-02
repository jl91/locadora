<?php

namespace VideoPlace\Model\View;

interface ViewInterface
{
    public function __construct(string $file, array $vars = []);

    public function __toString(): string;
}