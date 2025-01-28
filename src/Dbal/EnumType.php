<?php

declare(strict_types=1);

namespace App\Dbal;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Enum;

use function is_string;
use function sprintf;

abstract class EnumType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /* @var Enum $enum */
        $enum = $this->getEnum();
        $cases = array_map(
            static fn(Enum $enumItem) => "'$enumItem->value'",
            $enum::cases()
        );

        return sprintf('ENUM(%s)', implode(', ', $cases));
    }

    /**
     * @return class-string
     */
    abstract protected function getEnum(): string;

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    public function convertToDatabaseValue(mixed $enum, AbstractPlatform $platform): mixed
    {
        return is_string($enum) ? $enum : $enum->value;
    }

    /**
     * @return class-string
     */
    abstract protected function getName(): string;
}
