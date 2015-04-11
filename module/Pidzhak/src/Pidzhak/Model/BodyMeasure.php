<?php
namespace Pidzhak\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class BodyMeasure  implements InputFilterAwareInterface
{
    public $id;
    public $clother_id;
    public $customer_id;
    public $growth;
    public $weight;
    public $arm_position;
    public $neck;
    public $chest;
    public $stomach;
    public $seat;
    public $thigh;
    public $knee_finished;
    public $pant_bottom_finished;
    public $u_rise;
    public $otseam_l_and_r;
    public $nape_to_waist;
    public $front_waist_length;
    public $back_waist_height;
    public $front_waist_height;
    public $biceps;
    public $back_shoulder;
    public $right_sleeve;
    public $left_sleeve;
    public $back_length;
    public $overcoat_back_length;
    public $waist;
    public $right_wrist;
    public $left_wrist;
    public $style;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->clother_id = (!empty($data['clother_id'])) ? $data['clother_id'] : null;
        $this->customer_id = (!empty($data['customer_id'])) ? $data['customer_id'] : null;
        $this->growth = (!empty($data['growth'])) ? $data['growth'] : null;
        $this->weight = (!empty($data['weight'])) ? $data['weight'] : null;
        $this->arm_position = (!empty($data['arm_position'])) ? $data['arm_position'] : null;
        $this->neck = (!empty($data['neck'])) ? $data['neck'] : null;
        $this->chest = (!empty($data['chest'])) ? $data['chest'] : null;
        $this->stomach = (!empty($data['stomach'])) ? $data['stomach'] : null;
        $this->seat = (!empty($data['seat'])) ? $data['seat'] : null;
        $this->thigh = (!empty($data['thigh'])) ? $data['thigh'] : null;
        $this->knee_finished = (!empty($data['knee_finished'])) ? $data['knee_finished'] : null;
        $this->pant_bottom_finished = (!empty($data['pant_bottom_finished'])) ? $data['pant_bottom_finished'] : null;
        $this->u_rise = (!empty($data['u_rise'])) ? $data['u_rise'] : null;
        $this->otseam_l_and_r = (!empty($data['otseam_l_and_r'])) ? $data['otseam_l_and_r'] : null;
        $this->nape_to_waist = (!empty($data['nape_to_waist'])) ? $data['nape_to_waist'] : null;
        $this->front_waist_length = (!empty($data['front_waist_length'])) ? $data['front_waist_length'] : null;
        $this->back_waist_height = (!empty($data['back_waist_height'])) ? $data['back_waist_height'] : null;
        $this->front_waist_height = (!empty($data['front_waist_height'])) ? $data['front_waist_height'] : null;
        $this->biceps = (!empty($data['biceps'])) ? $data['biceps'] : null;
        $this->back_shoulder = (!empty($data['back_shoulder'])) ? $data['back_shoulder'] : null;
        $this->right_sleeve = (!empty($data['right_sleeve'])) ? $data['right_sleeve'] : null;
        $this->left_sleeve = (!empty($data['left_sleeve'])) ? $data['left_sleeve'] : null;
        $this->back_length = (!empty($data['back_length'])) ? $data['back_length'] : null;
        $this->overcoat_back_length = (!empty($data['overcoat_back_length'])) ? $data['overcoat_back_length'] : null;
        $this->waist = (!empty($data['waist'])) ? $data['waist'] : null;
        $this->right_wrist = (!empty($data['right_wrist'])) ? $data['right_wrist'] : null;
        $this->left_wrist = (!empty($data['left_wrist'])) ? $data['left_wrist'] : null;
        $this->style = (!empty($data['style'])) ? $data['style'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}