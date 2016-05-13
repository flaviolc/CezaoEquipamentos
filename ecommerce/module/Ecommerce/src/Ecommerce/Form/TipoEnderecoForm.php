<?php

namespace Ecommerce\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar tipo de endereco
 * @category Ecommerce
 * @package form
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class TipoEnderecoForm extends Form {

    public function __construct() {
        parent::__construct('TipoEnderecoForm');
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
                        'label' => 'Descrição do endereço*:'
                    ),
                    'attributes' => array(
                        'placeholder' => 'Informe um tipo de endereço'
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
