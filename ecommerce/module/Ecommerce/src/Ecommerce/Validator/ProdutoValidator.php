<?php

namespace Ecommerce\Validator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class ProdutoValidator extends InputFilter {

    public function __construct() {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
                    'name' => 'id',
                    'required' => false,
                    'filters' => array(
                        array('name' => 'Int'),
                    ),
        )));
        $this->add($factory->createInput(array(
                    'name' => 'nome',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => 'StringToUpper',
                            'options' => array('encoding' => 'UTF-8')
                        ),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 3,
                                'max' => 255,
                            ),
                        ),
                        array(
                            'name' => 'NotEmpty',
                        )),
        )));

        $this->add($factory->createInput(array(
                    'name' => 'descricao',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => 'StringToUpper',
                            'options' => array('encoding' => 'UTF-8')
                        ),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 3,
                                'max' => 255,
                            ),
                        ),
                        array(
                            'name' => 'NotEmpty',
                        )),
        )));
        $this->add($factory->createInput(array(
                    'name' => 'valor',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Int'),
                    ),
            'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                        )),
        )));
        
                $this->add($factory->createInput(array(
                    'name' => 'margem_de_lucro',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Int'),
                    ),
            'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                        )),
        )));
        
//                $this->add($factory->createInput(array(
//                    'name' => 'marca',
//                    'required' => true,
//                    'filters' => array(
//                        array('name' => 'StripTags'),
//                        array('name' => 'StringTrim'),
//                        array('name' => 'StringToUpper',
//                            'options' => array('encoding' => 'UTF-8')
//                        ),
//                    ),
//                    'validators' => array(
//                        array(
//                            'name' => 'StringLength',
//                            'options' => array(
//                                'encoding' => 'UTF-8',
//                                'min' => 3,
//                                'max' => 255,
//                            ),
//                        ),
//                        array(
//                            'name' => 'NotEmpty',
//                        )),
//        )));
    }

}
