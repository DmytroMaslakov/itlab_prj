<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[Attribute]
class ProductCategory extends Constraint
{
    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return get_class($this) . "Validator";
    }

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}