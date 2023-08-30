<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class UserValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if(!$constraint instanceof User){
            throw new UnexpectedTypeException($constraint, User::class);
        }

        if(!$value instanceof \App\Entity\User){
            throw new UnexpectedTypeException($constraint, \App\Entity\User::class);
        }

        if(count($value->getOrders())>2){
            $this->context->addViolation("Cannot be more than 2 orders for one User");
        }

    }
}