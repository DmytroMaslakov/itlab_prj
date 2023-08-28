<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class ProductValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if(!$constraint instanceof Product){
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        if(!$value instanceof \App\Entity\Product){
            throw new UnexpectedTypeException($constraint, \App\Entity\Product::class);
        }

        $this->context->addViolation("Error");
    }
}