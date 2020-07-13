<?php

namespace App\Utils;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId();
    }
}
