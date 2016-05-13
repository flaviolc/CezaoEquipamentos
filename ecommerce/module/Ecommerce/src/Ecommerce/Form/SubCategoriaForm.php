<?php

namespace Ecommerce\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar sub categoria
 * @category Ecommerce
 * @package form
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class SubCategoriaForm extends Form {

    public function __construct() {
        parent::__construct('SubCategoriaForm');
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
                        'label' => 'Descrição da sub categoria*:'
                    ),
                    'attributes' => array(
                        'placeholder' => 'Informe uma sub categoria'
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
