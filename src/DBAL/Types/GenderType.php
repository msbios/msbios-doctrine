<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Doctrine\DBAL\Types;

/**
 * Class GenderType
 *
 * @package MSBios\Doctrine\DBAL\Types
 */
class GenderType extends EnumType
{
    /** @const NAME */
    const NAME = 'gender';

    /** @const GENDER_NONE */
    const GENDER_NONE = 'NONE';

    /** @const GENDER_MALE */
    const GENDER_MALE = 'MALE';

    /** @const GENDER_FEMALE */
    const GENDER_FEMALE = 'FEMALE';

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getValues()
    {
        return [
            self::GENDER_NONE,
            self::GENDER_MALE,
            self::GENDER_FEMALE
        ];
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
