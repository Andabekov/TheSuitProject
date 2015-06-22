<?php
namespace Pidzhak\Model\Seller;

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

    public $butt_position;
    public $u_rise_auto;

    public $order_cloth_id;

    public $measureTypeSelect;

    private $postfix;


    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'.$this->postfix])) ? $data['id'.$this->postfix] : null;
        $this->clother_id = (!empty($data['clother_id'.$this->postfix])) ? $data['clother_id'.$this->postfix] : null;
        $this->customer_id = (!empty($data['customer_id'.$this->postfix])) ? $data['customer_id'.$this->postfix] : null;
        $this->growth = (!empty($data['growth'.$this->postfix])) ? $data['growth'.$this->postfix] : null;
        $this->weight = (!empty($data['weight'.$this->postfix])) ? $data['weight'.$this->postfix] : null;
        $this->arm_position = (!empty($data['arm_position'.$this->postfix])) ? $data['arm_position'.$this->postfix] : null;
        $this->neck = (!empty($data['neck'.$this->postfix])) ? $data['neck'.$this->postfix] : null;
        $this->chest = (!empty($data['chest'.$this->postfix])) ? $data['chest'.$this->postfix] : null;
        $this->stomach = (!empty($data['stomach'.$this->postfix])) ? $data['stomach'.$this->postfix] : null;
        $this->seat = (!empty($data['seat'.$this->postfix])) ? $data['seat'.$this->postfix] : null;
        $this->thigh = (!empty($data['thigh'.$this->postfix])) ? $data['thigh'.$this->postfix] : null;
        $this->knee_finished = (!empty($data['knee_finished'.$this->postfix])) ? $data['knee_finished'.$this->postfix] : null;
        $this->pant_bottom_finished = (!empty($data['pant_bottom_finished'.$this->postfix])) ? $data['pant_bottom_finished'.$this->postfix] : null;
        $this->u_rise = (!empty($data['u_rise'.$this->postfix])) ? $data['u_rise'.$this->postfix] : null;
        $this->otseam_l_and_r = (!empty($data['otseam_l_and_r'.$this->postfix])) ? $data['otseam_l_and_r'.$this->postfix] : null;
        $this->nape_to_waist = (!empty($data['nape_to_waist'.$this->postfix])) ? $data['nape_to_waist'.$this->postfix] : null;
        $this->front_waist_length = (!empty($data['front_waist_length'.$this->postfix])) ? $data['front_waist_length'.$this->postfix] : null;
        $this->back_waist_height = (!empty($data['back_waist_height'.$this->postfix])) ? $data['back_waist_height'.$this->postfix] : null;
        $this->front_waist_height = (!empty($data['front_waist_height'.$this->postfix])) ? $data['front_waist_height'.$this->postfix] : null;
        $this->biceps = (!empty($data['biceps'.$this->postfix])) ? $data['biceps'.$this->postfix] : null;
        $this->back_shoulder = (!empty($data['back_shoulder'.$this->postfix])) ? $data['back_shoulder'.$this->postfix] : null;
        $this->right_sleeve = (!empty($data['right_sleeve'.$this->postfix])) ? $data['right_sleeve'.$this->postfix] : null;
        $this->left_sleeve = (!empty($data['left_sleeve'.$this->postfix])) ? $data['left_sleeve'.$this->postfix] : null;
        $this->back_length = (!empty($data['back_length'.$this->postfix])) ? $data['back_length'.$this->postfix] : null;
        $this->overcoat_back_length = (!empty($data['overcoat_back_length'.$this->postfix])) ? $data['overcoat_back_length'.$this->postfix] : null;
        $this->waist = (!empty($data['waist'.$this->postfix])) ? $data['waist'.$this->postfix] : null;
        $this->right_wrist = (!empty($data['right_wrist'.$this->postfix])) ? $data['right_wrist'.$this->postfix] : null;
        $this->left_wrist = (!empty($data['left_wrist'.$this->postfix])) ? $data['left_wrist'.$this->postfix] : null;
        $this->style = (!empty($data['style'.$this->postfix])) ? $data['style'.$this->postfix] : null;

        $this->butt_position = (!empty($data['butt_position'.$this->postfix])) ? $data['butt_position'.$this->postfix] : null;
        $this->u_rise_auto = (!empty($data['u_rise_auto'.$this->postfix])) ? $data['u_rise_auto'.$this->postfix] : null;

        $this->order_cloth_id = (!empty($data['order_cloth_id'.$this->postfix])) ? $data['order_cloth_id'.$this->postfix] : null;

        $this->measureTypeSelect = (!empty($data['measureTypeSelect'])) ? $data['measureTypeSelect'] : null;
    }

    public function getArrayCopy()
    {
        $return_array = array();
        $fields_array = get_object_vars($this);
        foreach ($fields_array as $field=> $value) {
            $return_array[$field.$this->postfix] = $value;
        }
        return $return_array;
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

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @param mixed $postfix
     */
    public function setPostfix($postfix)
    {
        $this->postfix = $postfix;
    }


}