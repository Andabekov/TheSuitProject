<?php
namespace Pidzhak\Model\Seller;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class OrderClothes  implements InputFilterAwareInterface
{
    public $id;
    public $order_id;
    public $product_id;
    public $cycle_id;
    public $preferred_date;
    public $textile_id;
    //public $textile_class;
    public $pricelistnum;
    public $actual_amount;
    public $paytype;
    public $typeof_measure;
    public $label_brand;
    public $style_number;
    public $first_monogram_location;
    public $first_monogram_font;
    public $first_monogram_font_color;
    public $first_monogram_caption;
    public $second_monogram_location;
    public $second_monogram_font;
    public $second_monogram_font_color;
    public $second_monogram_caption;
    public $seller_comment;





    public function exchangeArray($data)
    {
        $this->id = (!empty($data['c_id'])) ? $data['c_id'] : null;
        $this->order_id = (!empty($data['order_id'])) ? $data['order_id'] : null;
        $this->cycle_id = (!empty($data['cycle_id'])) ? $data['cycle_id'] : null;
        $this->preferred_date = (!empty($data['preferred_date'])) ? $data['preferred_date'] : null;
        $this->product_id = (!empty($data['product_id'])) ? $data['product_id'] : null;
        //$this->textile_class = (!empty($data['textile_class'])) ? $data['textile_class'] : null;
        $this->pricelistnum = (!empty($data['pricelistnum'])) ? $data['pricelistnum'] : null;
        $this->actual_amount = (!empty($data['actual_amount'])) ? $data['actual_amount'] : null;
        $this->paytype = (!empty($data['paytype'])) ? $data['paytype'] : null;
        $this->textile_id = (!empty($data['textile_id'])) ? $data['textile_id'] : null;
        $this->typeof_measure = (!empty($data['typeof_measure'])) ? $data['typeof_measure'] : null;
        $this->label_brand = (!empty($data['label_brand'])) ? $data['label_brand'] : null;
        $this->style_number = (!empty($data['style_number'])) ? $data['style_number'] : null;
        $this->first_monogram_location = (!empty($data['first_monogram_location'])) ? $data['first_monogram_location'] : null;
        $this->first_monogram_font = (!empty($data['first_monogram_font'])) ? $data['first_monogram_font'] : null;
        $this->first_monogram_font_color = (!empty($data['first_monogram_font_color'])) ? $data['first_monogram_font_color'] : null;
        $this->first_monogram_caption = (!empty($data['first_monogram_caption'])) ? $data['first_monogram_caption'] : null;
        $this->second_monogram_location = (!empty($data['second_monogram_location'])) ? $data['second_monogram_location'] : null;
        $this->second_monogram_font = (!empty($data['second_monogram_font'])) ? $data['second_monogram_font'] : null;
        $this->second_monogram_font_color = (!empty($data['second_monogram_font_color'])) ? $data['second_monogram_font_color'] : null;
        $this->second_monogram_caption = (!empty($data['second_monogram_caption'])) ? $data['second_monogram_caption'] : null;
        $this->seller_comment = (!empty($data['seller_comment'])) ? $data['seller_comment'] : null;
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