<?php
namespace Pidzhak\Model\Seller;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ClotherMeasure  implements InputFilterAwareInterface
{
    public $inputFilter;

    public $id;
    public $clother_id;
    public $customer_id;
    public $growth;
    public $weight;
    public $chest_finished;
    public $stomach_finished;
    public $jacket_seat_finished;
    public $biceps_finished;
    public $left_sleeve_finished;
    public $right_sleeve_finished;
    public $back_length_finished;
    public $front_length_finished;
    public $shoulder_finished;
    public $waist_finished;
    public $seat_finished;
    public $thigh_finished;
    public $outseam_l_and_r_finished;
    public $knee_finished;
    public $pant_bottom_finished;
    public $u_rise_finished;

    public $right_cuff;
    public $left_cuff;
    public $shirt_neck;

    private $postfix;
    private $prefix;

    public $order_cloth_id;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data[$this->prefix.'id'.$this->postfix])) ? $data[$this->prefix.'id'.$this->postfix] : null;
        $this->clother_id = (!empty($data[$this->prefix.'clother_id'.$this->postfix])) ? $data[$this->prefix.'clother_id'.$this->postfix] : null;
        $this->customer_id = (!empty($data[$this->prefix.'customer_id'.$this->postfix])) ? $data[$this->prefix.'customer_id'.$this->postfix] : null;
        $this->growth = (!empty($data[$this->prefix.'growth'.$this->postfix])) ? $data[$this->prefix.'growth'.$this->postfix] : null;
        $this->weight = (!empty($data[$this->prefix.'weight'.$this->postfix])) ? $data[$this->prefix.'weight'.$this->postfix] : null;
        $this->chest_finished = (!empty($data[$this->prefix.'chest_finished'.$this->postfix])) ? $data[$this->prefix.'chest_finished'.$this->postfix] : null;
        $this->stomach_finished = (!empty($data[$this->prefix.'stomach_finished'.$this->postfix])) ? $data[$this->prefix.'stomach_finished'.$this->postfix] : null;
        $this->jacket_seat_finished = (!empty($data[$this->prefix.'jacket_seat_finished'.$this->postfix])) ? $data[$this->prefix.'jacket_seat_finished'.$this->postfix] : null;
        $this->biceps_finished = (!empty($data[$this->prefix.'biceps_finished'.$this->postfix])) ? $data[$this->prefix.'biceps_finished'.$this->postfix] : null;
        $this->left_sleeve_finished = (!empty($data[$this->prefix.'left_sleeve_finished'.$this->postfix])) ? $data[$this->prefix.'left_sleeve_finished'.$this->postfix] : null;
        $this->right_sleeve_finished = (!empty($data[$this->prefix.'right_sleeve_finished'.$this->postfix])) ? $data[$this->prefix.'right_sleeve_finished'.$this->postfix] : null;
        $this->back_length_finished = (!empty($data[$this->prefix.'back_length_finished'.$this->postfix])) ? $data[$this->prefix.'back_length_finished'.$this->postfix] : null;
        $this->front_length_finished = (!empty($data[$this->prefix.'front_length_finished'.$this->postfix])) ? $data[$this->prefix.'front_length_finished'.$this->postfix] : null;
        $this->shoulder_finished = (!empty($data[$this->prefix.'shoulder_finished'.$this->postfix])) ? $data[$this->prefix.'shoulder_finished'.$this->postfix] : null;
        $this->waist_finished = (!empty($data[$this->prefix.'waist_finished'.$this->postfix])) ? $data[$this->prefix.'waist_finished'.$this->postfix] : null;
        $this->seat_finished = (!empty($data[$this->prefix.'seat_finished'.$this->postfix])) ? $data[$this->prefix.'seat_finished'.$this->postfix] : null;
        $this->thigh_finished = (!empty($data[$this->prefix.'thigh_finished'.$this->postfix])) ? $data[$this->prefix.'thigh_finished'.$this->postfix] : null;
        $this->outseam_l_and_r_finished = (!empty($data[$this->prefix.'outseam_l_and_r_finished'.$this->postfix])) ? $data[$this->prefix.'outseam_l_and_r_finished'.$this->postfix] : null;
        $this->knee_finished = (!empty($data[$this->prefix.'knee_finished'.$this->postfix])) ? $data[$this->prefix.'knee_finished'.$this->postfix] : null;
        $this->pant_bottom_finished = (!empty($data[$this->prefix.'pant_bottom_finished'.$this->postfix])) ? $data[$this->prefix.'pant_bottom_finished'.$this->postfix] : null;
        $this->u_rise_finished = (!empty($data[$this->prefix.'u_rise_finished'.$this->postfix])) ? $data[$this->prefix.'u_rise_finished'.$this->postfix] : null;

        $this->order_cloth_id = (!empty($data[$this->prefix.'order_cloth_id'.$this->postfix])) ? $data[$this->prefix.'order_cloth_id'.$this->postfix] : null;

        $this->right_cuff = (!empty($data[$this->prefix.'right_cuff'.$this->postfix])) ? $data[$this->prefix.'right_cuff'.$this->postfix] : null;
        $this->left_cuff = (!empty($data[$this->prefix.'left_cuff'.$this->postfix])) ? $data[$this->prefix.'left_cuff'.$this->postfix] : null;
        $this->shirt_neck = (!empty($data[$this->prefix.'shirt_neck'.$this->postfix])) ? $data[$this->prefix.'shirt_neck'.$this->postfix] : null;

    }

    public function getArrayCopy()
    {
        $return_array = array();
        $fields_array = get_object_vars($this);
        foreach ($fields_array as $field=> $value) {
            $return_array[$this->prefix.$field.$this->postfix] = $value;
        }
        return $return_array;
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

    /**
     * @param mixed $postfix
     */
    public function setPostfix($postfix)
    {
        $this->postfix = $postfix;
    }

    /**
     * @param mixed $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }







}