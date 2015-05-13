<?php
/**
 * Created by PhpStorm.
 * User: Abu Andabekov
 * Date: 12/05/2015
 * Time: 19:53
 */

namespace Pidzhak\Form\Redactor;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Form\Form;

class ClothMeasureForm extends Form
{
    protected $adapter;

    public function __construct(AdapterInterface $dbAdapter = null, $name = null)
    {
        $this->adapter = $dbAdapter;

        parent::__construct('orderclothes');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'clother_id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'customer_id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'growth',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Рост',
            ),
        ));
        $this->add(array(
            'name' => 'weight',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Вес',
            ),
        ));
        $this->add(array(
            'name' => 'chest_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Грудь (Chest Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'stomach_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Живот (Stomach Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'jacket_seat_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Зад Пиджака (Seat Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'biceps_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Бицепс (Biceps Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Левый рукав (Left sleeve Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Правый рукав (Right sleeve Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'back_length_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина изделия со спины (Back length Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'front_length_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина изделия спереди (Front length Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'shoulder_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Ширина спины (Shoulder Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'waist_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Брючная талия (Waist Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'seat_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Зад Брюк (Seat Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'thigh_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Бедро (Thigh Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'outseam_l_and_r_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина брюк (Outseam L and R Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'knee_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Колено (Knee Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'pant_bottom_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Низ брюк (Pant bottom Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_finished',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Шов сиденья (U-rise Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'right_cuff',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Правый манжет (Right cuff)',
            ),
        ));
        $this->add(array(
            'name' => 'left_cuff',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Левый манжет (Left cuff)',
            ),
        ));
        $this->add(array(
            'name' => 'shirt_neck',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Шея сорочки (Shirt neck)',
            ),
        ));
    }
}