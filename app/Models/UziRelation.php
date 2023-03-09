<?php

declare(strict_types=1);

namespace App\Models;

class UziRelation
{
    public function __construct(
        public string $entityName,
        public string $ura,
        public array $roles
    ) {
    }
}
