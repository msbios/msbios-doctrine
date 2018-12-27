<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use MSBios\Json\Store;

/**
 * Class ArrayResolverType
 * @package MSBios\Doctrine\DBAL\Types
 */
class ArrayResolverType extends JsonType
{
    /** @const NAME */
    const NAME = 'array_resolver';

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return false|mixed|string|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Store) {
            $value = $value->toArray();
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return mixed|Store|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        /** @var array $data */
        $data = parent::convertToPHPValue($value, $platform);

        if (! is_array($data)) {
            $data = [];
        }

        return new Store($data);
    }
}
