<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Doctrine\Form\Element;

use Laminas\Form\Element\Select;
use MSBios\Doctrine\DBAL\Types\GenderType;


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
     * @return $this
     */
    public function init(): self
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
