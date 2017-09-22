<?php
/**
 * Created by PhpStorm.
 * User: judzhin
 * Date: 9/22/17
 * Time: 7:12 PM
 */

namespace MSBios\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;
use MSBios\Json\Store;

/**
 * Class ArrayResolverType
 * @package MSBios\Doctrine\DBAL\Types
 */
class ArrayResolverType extends JsonArrayType
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
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // /** @var array $data */
        // $data = parent::convertToPHPValue($value, $platform);
        //
        // /** @var array $arr */
        // $arr = [];
        //
        // foreach ($data as $key => $value) {
        //     $object = new ArrayObject($value);
        //     $arr[] = $object;
        // }
        //
        // return $arr;

        return new Store(
            parent::convertToPHPValue($value, $platform)
        );
    }
}