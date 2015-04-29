<?php
namespace Pidzhak\Form\Seller;

use Zend\Form\Form;

class MeasureForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('measure');


        $this->addFirstColumnBodyMeasure();
        $this->addSecondColumnBodyMeasure();
        $this->addThirdColumnBodyMeasure();
        $this->addForthColumnBodyMeasure();
        $this->addFifthColumnBodyMeasure();


        $this->addFirstColumnClotherMeasure();
        $this->addSecondColumnClotherMeasure();
        $this->addThirdColumnClotherMeasure();

    }


    private function addFirstColumnBodyMeasure(){
        /*first column*/
        $this->add(array(
            'name' => 'id_1',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'clother_id_1',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'customer_id_1',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'growth_1',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Рост',
            ),
        ));
        $this->add(array(
            'name' => 'weight_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Вес',
            ),
        ));
        $this->add(array(
            'name' => 'arm_position_1',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
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
            'name' => 'butt_position_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Положение ягодицы',
            ),
        ));
        $this->add(array(
            'name' => 'neck_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Шея (Neck)',
            ),
        ));
        $this->add(array(
            'name' => 'chest_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Грудь (Chest)',
            ),
        ));
        $this->add(array(
            'name' => 'stomach_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Живот (Stomach)',
            ),
        ));
        $this->add(array(
            'name' => 'seat_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Зад (Seat)',
            ),
        ));
        $this->add(array(
            'name' => 'thigh_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Бедро (Thigh)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'knee_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Колено (Knee Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'pant_bottom_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Низ брюк (Pant bottom Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Шов сиденья (U-rise)',
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_auto_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Шов сиденья (auto)',
            ),
        ));
        $this->add(array(
            'name' => 'otseam_l_and_r_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Длина брюк (Otseam L and R)',
            ),
        ));
        $this->add(array(
            'name' => 'nape_to_waist_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина до талии спины (ДТС) (Nape to waist)',
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_length_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина до талии полочки (ДТП) (Front waist length)',
            ),
        ));
        $this->add(array(
            'name' => 'back_waist_height_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Расстояние от талии спины до пояса брюк (Back waist height)',
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_height_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Расстояние от талии полочки до пояса брюк (Front waist height)',
            ),
        ));
        $this->add(array(
            'name' => 'biceps_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Бицепс (Biceps)',
            ),
        ));
        $this->add(array(
            'name' => 'back_shoulder_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Ширина спины (Back shoulder)',
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Правый рукав (Right sleeve)',
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Левый рукав (Left sleeve)',
            ),
        ));
        $this->add(array(
            'name' => 'back_length_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина изделия со спины (Back length)',
            ),
        ));
        $this->add(array(
            'name' => 'overcoat_back_length_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Длина пальто cо спины (Overcoat back length)',
            ),
        ));
        $this->add(array(
            'name' => 'waist_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Брючная талия (Waist)',
            ),
        ));
        $this->add(array(
            'name' => 'right_wrist_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Запястье (правое) (Right wrist)',
            ),
        ));
        $this->add(array(
            'name' => 'left_wrist_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Запястье (левое) (Left wrist)',
            ),
        ));
        $this->add(array(
            'name' => 'style_1',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
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

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary'
            ),
        ));
    }
    private function addSecondColumnBodyMeasure(){
        /*seconde column*/
        $this->add(array(
            'name' => 'id_2',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'clother_id_2',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'customer_id_2',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'growth_2',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'weight_2',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'arm_position_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'butt_position_2',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Normal',
                    '2' => 'Prominent',
                    '3' => 'Drop',
                    '4' => 'Flat',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'neck_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'chest_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'stomach_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'seat_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'thigh_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'knee_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'pant_bottom_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_auto_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'otseam_l_and_r_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'nape_to_waist_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_length_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'back_waist_height_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_height_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'biceps_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'back_shoulder_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'back_length_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'overcoat_back_length_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'waist_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'right_wrist_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'left_wrist_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'style_2',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Приталенный',
                    '3' => 'Свободный',
                ),
            ),
        ));
    }
    private function addThirdColumnBodyMeasure(){

        /*third column*/

        $this->add(array(
            'name' => 'id_3',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'clother_id_3',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'customer_id_3',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'growth_3',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'weight_3',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'arm_position_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'butt_position_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'neck_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'chest_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'stomach_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'seat_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'thigh_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'knee_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'pant_bottom_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_auto_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'otseam_l_and_r_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'nape_to_waist_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_length_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_waist_height_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_height_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'biceps_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_shoulder_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_length_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'overcoat_back_length_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'waist_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'right_wrist_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'left_wrist_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'style_3',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Приталенный',
                    '2' => 'Полуприталенный',
                    '3' => 'Свободный',
                ),
            ),
        ));
    }
    private function addForthColumnBodyMeasure(){

        /*forth column */

        $this->add(array(
            'name' => 'id_4',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'clother_id_4',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'customer_id_4',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'growth_4',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'weight_4',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'arm_position_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'butt_position_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'neck_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'chest_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'stomach_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'seat_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'thigh_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'knee_finished_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'pant_bottom_finished_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_auto_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'otseam_l_and_r_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'nape_to_waist_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_length_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_waist_height_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_height_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'biceps_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'back_shoulder_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'back_length_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'overcoat_back_length_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'waist_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'right_wrist_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'left_wrist_4',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'style_4',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Приталенный',
                    '2' => 'Полуприталенный',
                    '3' => 'Свободный',
                ),
            ),
        ));
    }
    private function addFifthColumnBodyMeasure(){

        /*fifth column*/

        $this->add(array(
            'name' => 'id_5',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'clother_id_5',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'customer_id_5',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'growth_5',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'weight_5',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'arm_position_5',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Normal',
                    '2' => 'Backward',
                    '3' => 'Forward',
                ),
            ),
        ));
        $this->add(array(
            'name' => 'butt_position_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'neck_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'chest_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'stomach_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'seat_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'thigh_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'knee_finished_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'pant_bottom_finished_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'u_rise_auto_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'otseam_l_and_r_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'nape_to_waist_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_length_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_waist_height_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'front_waist_height_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'biceps_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_shoulder_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'right_sleeve_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'left_sleeve_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'back_length_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'overcoat_back_length_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'waist_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'right_wrist_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'left_wrist_5',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'style_5',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'Не выбрано',
                    '1' => 'Приталенный',
                    '2' => 'Полуприталенный',
                    '3' => 'Свободный',
                ),
            ),
        ));
    }


    private function addFirstColumnClotherMeasure(){
        $this->add(array(
            'name' => 'c_id_1',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_clother_id_1',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_customer_id_1',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_growth_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Рост',
            ),
        ));
        $this->add(array(
            'name' => 'c_weight_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Вес',
            ),
        ));
        $this->add(array(
            'name' => 'c_chest_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Грудь (Chest Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_stomach_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Живот (Stomach Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_jacket_seat_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Зад Пиджака (Seat Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_biceps_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Бицепс (Biceps Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_left_sleeve_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Левый рукав (Left sleeve Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_right_sleeve_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Правый рукав (Right sleeve Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_back_length_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина изделия со спины (Back length Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_front_length_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина изделия спереди (Front length Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_shoulder_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Ширина спины (Shoulder Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_waist_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Брючная талия (Waist Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_seat_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Зад Брюк (Seat Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_thigh_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Бедро (Thigh Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_outseam_l_and_r_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Длина брюк (Outseam L and R Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_knee_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Колено (Knee Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_pant_bottom_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Низ брюк (Pant bottom Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_u_rise_finished_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Шов сиденья (U-rise Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_right_cuff_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Правый манжет (Right cuff)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_left_cuff_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Левый манжет (Left cuff)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_shirt_neck_1',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Шея сорочки (Shirt neck)',
                'disabled' => 'true'
            ),
        ));
    }
    private function addSecondColumnClotherMeasure(){
        $this->add(array(
            'name' => 'c_id_2',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_clother_id_2',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_customer_id_2',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_growth_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_weight_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_chest_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_stomach_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_jacket_seat_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_biceps_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_left_sleeve_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_right_sleeve_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_back_length_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_front_length_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_shoulder_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_waist_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_seat_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_thigh_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_outseam_l_and_r_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_knee_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_pant_bottom_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_u_rise_finished_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'c_right_cuff_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Правый манжет (Right cuff)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_left_cuff_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Левый манжет (Left cuff)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_shirt_neck_2',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Шея сорочки (Shirt neck)',
                'disabled' => 'true'
            ),
        ));
    }
    private function addThirdColumnClotherMeasure(){
        $this->add(array(
            'name' => 'c_id_3',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_clother_id_3',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_customer_id_3',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'c_growth_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Рост',
            ),
        ));
        $this->add(array(
            'name' => 'c_weight_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Вес',
            ),
        ));
        $this->add(array(
            'name' => 'c_chest_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Грудь (Chest Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_stomach_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Живот (Stomach Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_jacket_seat_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Зад Пиджака (Seat Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_biceps_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Бицепс (Biceps Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_left_sleeve_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Левый рукав (Left sleeve Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_right_sleeve_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Правый рукав (Right sleeve Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_back_length_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина изделия со спины (Back length Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_front_length_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Длина изделия спереди (Front length Finished)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_shoulder_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Ширина спины (Shoulder Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_waist_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Брючная талия (Waist Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_seat_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Зад Брюк (Seat Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_thigh_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Бедро (Thigh Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_outseam_l_and_r_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Длина брюк (Outseam L and R Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_knee_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Колено (Knee Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_pant_bottom_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Низ брюк (Pant bottom Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_u_rise_finished_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => 'true'
            ),
            'options' => array(
                'label' => 'Шов сиденья (U-rise Finished)',
            ),
        ));
        $this->add(array(
            'name' => 'c_right_cuff_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Правый манжет (Right cuff)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_left_cuff_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Левый манжет (Left cuff)',
                'disabled' => 'true'
            ),
        ));
        $this->add(array(
            'name' => 'c_shirt_neck_3',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Шея сорочки (Shirt neck)',
                'disabled' => 'true'
            ),
        ));
    }

}