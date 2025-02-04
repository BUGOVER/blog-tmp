<?php

declare(strict_types=1);

namespace App\Serializer;

use DateTimeInterface;

class DateAtCallback
{
    public function __invoke(null|string|DateTimeInterface $innerObject): null|string|DateTimeInterface
    {
        if (null === $innerObject) {
            return null;
        }

        if (!($innerObject instanceof DateTimeInterface)) {
            return $innerObject;
        }

        return $innerObject->format('Y-m-d H:i:s');
    }
}
