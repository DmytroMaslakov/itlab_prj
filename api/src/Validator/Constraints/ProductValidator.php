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
class ProductValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     * @throws Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if(!$constraint instanceof Product){
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        if(!$value instanceof \App\Entity\Product){
            throw new UnexpectedTypeException($constraint, \App\Entity\Product::class);
        }

        if(strlen($value->getName()) < 5){
            throw new Exception("Name length can not be less than 5 characters");
        }

        if(intval($value->getPrice()) > 99){
            throw new Exception("Price can not be greater than 99!");
        }

        if(intval($value->getPrice()) <10){
            throw new Exception("Price can not be less than 10");
        }
    }
}