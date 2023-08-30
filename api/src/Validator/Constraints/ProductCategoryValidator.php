<?php

namespace App\Validator\Constraints;

use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class ProductCategoryValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     * @throws Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if(!$constraint instanceof ProductCategory){
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        if(!$value instanceof \App\Entity\ProductCategory){
            throw new UnexpectedTypeException($constraint, \App\Entity\ProductCategory::class);
        }

        if(strlen($value->getName()) < 5){
            throw new Exception("Name length can not be less than 5 characters");
        }

        if(strlen($value->getDescription()) < 10){
            throw new Exception("Description length can not be less than 10 characters");
        }
    }
}