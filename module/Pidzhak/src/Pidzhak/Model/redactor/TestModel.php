<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 05/05/2015
 * Time: 13:55
 */

namespace Pidzhak\Model\redactor;

class TestModel
{

    /**
     * @var SystemCode
     */
    protected $systemCodes;

    /**
     * @param SystemCode $systemCode
     * @return TestModel
     */
    public function setSystemCodes (SystemCode $systemCode)
    {
        $this->systemCodes = $systemCode;
        return $this;
    }

    /**
     * @return SystemCode
     */
    public function getSystemCodes (){
        return $this->systemCodes;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}