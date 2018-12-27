<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use MSBios\Doctrine\InvalidArgumentException;

/**
 * Class EnumType
 * @package MSBios\Doctrine\DBAL\Types
 */
abstract class EnumType extends Type
{
    /**
     * @return array
     */
    abstract public function getValues();

    /**
     * @inheritdoc
     *
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return sprintf(
            "ENUM(%s) COMMENT '(MSR\\D2Type:%s)'",
            implode(', ', array_map(function ($val) {
                return "'{$val}'";
            }, $this->getValues())),
            $this->getName()
        );
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (! in_array($value, $this->getValues())) {
            throw new InvalidArgumentException("Invalid '{$this->getName()}' value.");
        }
        return $value;
    }
}
