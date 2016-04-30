<?php

namespace Ecommerce\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar categoriass
 * @category Ecommerce
 * @package form
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

class CategoriaForm extends Form
{
    public function __construct()
    {
        parent::__construct('CategoriaForm');
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
                    'label' => 'Descrição da categoria*:'
                ),
                'attributes' => array(
                  'placeholder' => 'Informe a categoria aqui'
                ),
            )
        );
        $this->add(
            array(
                'name' => 'Salvar',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Salvar'
                )
            )
        );
    }
}
