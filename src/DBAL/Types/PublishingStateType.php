<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Doctrine\DBAL\Types;

/**
 * Class PublishingStateType
 * @package MSBios\Doctrine\DBAL\Types
 */
class PublishingStateType extends EnumType
{

    /** @const NAME */
    const NAME = 'publishing_state';

    const PUBLISHING_STATE_DRAFT = 'DRAFT';
    const PUBLISHING_STATE_PREVIEW = 'PREVIEW';
    const PUBLISHING_STATE_PUBLISHED = 'PUBLISHED';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            self::PUBLISHING_STATE_DRAFT,
            self::PUBLISHING_STATE_PREVIEW,
            self::PUBLISHING_STATE_PUBLISHED
        ];
    }

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
}
