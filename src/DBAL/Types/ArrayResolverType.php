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
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Store) {
            $value = $value->toArray();
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Store
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
