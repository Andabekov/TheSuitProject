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

class BodyMeasureForm extends Form
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
            'name' => 'arm_position',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Положение рук (Arm position)',
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Normal',
                    '2' => 'Backward',
                    '3' => 'Forward',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'butt_position',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Положение ягодицы',
            ),
        ));
        $this->add(array(
            'name' => 'neck',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Шея (Neck)',
            ),
        ));
        $this->add(array(
            'name' => 'chest',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Грудь (Chest)',
            ),
        ));
        $this->add(array(
            'name' => 'stomach',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Живот (Stomach)',
            ),
        ));
        $this->add(array(
            'name' => 'seat',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Зад (Seat)',
            ),
        ));
        $this->add(array(
            'name' => 'thigh',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Бедро (Thigh)',
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
            'name' => 'u_rise',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Шов сиденья (U-rise)',
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_auto',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Шов сиденья (auto)',
            ),
        ));
        $this->add(array(
            'name' => 'otseam_l_and_r',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина брюк (Otseam L and R)',
            ),
        ));
        $this->add(array(
            'name' => 'nape_to_waist',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина до талии спины (ДТС) (Nape to waist)',
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_length',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина до талии полочки (ДТП) (Front waist length)',
            ),
        ));
        $this->add(array(
            'name' => 'back_waist_height',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Расстояние от талии спины до пояса брюк (Back waist height)',
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_height',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Расстояние от талии полочки до пояса брюк (Front waist height)',
            ),
        ));
        $this->add(array(
            'name' => 'biceps',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Бицепс (Biceps)',
            ),
        ));
        $this->add(array(
            'name' => 'back_shoulder',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Ширина спины (Back shoulder)',
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Правый рукав (Right sleeve)',
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Левый рукав (Left sleeve)',
            ),
        ));
        $this->add(array(
            'name' => 'back_length',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина изделия со спины (Back length)',
            ),
        ));
        $this->add(array(
            'name' => 'overcoat_back_length',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Длина пальто cо спины (Overcoat back length)',
            ),
        ));
        $this->add(array(
            'name' => 'waist',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Брючная талия (Waist)',
            ),
        ));
        $this->add(array(
            'name' => 'right_wrist',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Запястье (правое) (Right wrist)',
            ),
        ));
        $this->add(array(
            'name' => 'left_wrist',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Запястье (левое) (Left wrist)',
            ),
        ));
        $this->add(array(
            'name' => 'style',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Приталенность (Style)',
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Приталенный',
                    '2' => 'Полуприталенный',
                    '3' => 'Свободный',
                ),
            ),
        ));
    }
}