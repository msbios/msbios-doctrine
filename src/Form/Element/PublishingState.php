<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine\Form\Element;

use MSBios\Doctrine\DBAL\Types\PublishingStateType;
use Zend\Form\Element\Select;

/**
 * Class PublishingState
 * @package MSBios\Doctrine\Form\Element
 */
class PublishingState extends Select
{
    /**
     * @inheritdoc
     *
     * @return $this
     */
    public function init(): self
    {
        parent::init();

        $this->setValueOptions([
            PublishingStateType::PUBLISHING_STATE_DRAFT => _('Draft'),
            PublishingStateType::PUBLISHING_STATE_PREVIEW => _('Preview'),
            PublishingStateType::PUBLISHING_STATE_PUBLISHED => _('Published'),
        ]);

        return $this;
    }
}
