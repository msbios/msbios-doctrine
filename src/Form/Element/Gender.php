<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine\Form\Element;

use MSBios\Doctrine\DBAL\Types\GenderType;
use Zend\Form\Element\Select;

/**
 * Class Gender
 *
 * @package MSBios\Doctrine\Form\Element
 */
class Gender extends Select
{
    /**
     * @inheritdoc
     *
     * @return $this|void
     */
    public function init()
    {
        parent::init();

        $this->setValueOptions([
            GenderType::GENDER_NONE => _('None'),
            GenderType::GENDER_MALE => _('Male'),
            GenderType::GENDER_FEMALE => _('Female'),
        ]);

        return $this;
    }
}