<?php

namespace Workbench\App\Documentation;

final readonly class WorkbenchCollection implements \Stringable
{
    public function __toString(): string
    {
        return 'Workbench';
    }
}
