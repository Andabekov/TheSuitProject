<?php
namespace Pidzhak\Model\Seller;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class OrderClothes  implements InputFilterAwareInterface
{
    public $id;
    public $order_id;
    public $cycle_number;
    public $product_name;
    public $textile_class;
    public $textile_number;
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
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->order_id = (!empty($data['order_id'])) ? $data['order_id'] : null;
        $this->cycle_number = (!empty($data['cycle_number'])) ? $data['cycle_number'] : null;
        $this->product_name = (!empty($data['product_name'])) ? $data['product_name'] : null;
        $this->textile_class = (!empty($data['textile_class'])) ? $data['textile_class'] : null;
        $this->textile_number = (!empty($data['textile_number'])) ? $data['textile_number'] : null;
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