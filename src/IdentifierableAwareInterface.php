<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Doctrine;

/**
 * Interface IdentifierableAwareInterface
 * @package MSBios\Doctrine
 */
interface IdentifierableAwareInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id);
}
