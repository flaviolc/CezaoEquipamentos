<?php

namespace Ecommerce\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar marca
 * @category Ecommerce
 * @package form
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class MarcaForm extends Form {

    public function __construct() {
        parent::__construct('MarcaForm');
        $this->setAttribute('method', 'POST');
        $this->add(
                array(
                    'name' => 'id',
                    'type' => 'hidden'
                )
        );
        $this->add(
                array(
                    'name' => 'descricao',
                    'type' => 'text',
                    'options' => array(
                        'label' => 'Descrição da marca*:'
                    ),
                    'attributes' => array(
                        'placeholder' => 'Informe uma marca aqui'
                    ),
                )
        );
        $this->add(array(
            'name' => 'Salvar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Salvar',
                'class' => 'btn btn-primary'
            )
        ));
    }

}
