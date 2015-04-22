<?php
namespace Pidzhak\Model\Seller;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Order  implements InputFilterAwareInterface
{
    public $id;
    public $customer_id;
    public $dateofsale;
    public $cityofsale;
    public $pointofsale;
    public $seller_id;
    public $status;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->customer_id = (!empty($data['customer_id'])) ? $data['customer_id'] : null;
        $this->dateofsale = (!empty($data['dateofsale'])) ? $data['dateofsale'] : null;
        $this->cityofsale = (!empty($data['cityofsale'])) ? $data['cityofsale'] : null;
        $this->pointofsale = (!empty($data['pointofsale'])) ? $data['pointofsale'] : null;
        $this->seller_id = (!empty($data['seller_id'])) ? $data['seller_id'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public $inputFilter;

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}