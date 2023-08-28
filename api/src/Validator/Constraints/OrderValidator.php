<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
/**
 * @Annotation
 */
class OrderValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint) : void
    {
        if(!$constraint instanceof Order){
            throw new UnexpectedTypeException($constraint, Order::class);
        }

        if(!$value instanceof \App\Entity\Order){
            throw new UnexpectedTypeException($constraint, \App\Entity\Order::class);
        }

        if(count($value->getProducts())>3){
            $this->context->addViolation("Cannot be more than 3 products in one order");
        }
    }
}