<?php
/**
 * Created by PhpStorm.
 * User: cezar
 * Date: 11/04/16
 * Time: 21:35
 */

namespace Ecommerce\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar produtos
 * @category Ecommerce
 * @package form
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

class ProdutoForm extends Form
{

    public function __construct($em)
    {
        parent::__construct('ProdutoForm');
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));
        $this->add(array(
           'name' => 'nome',
            'type' => 'text',
            'options' => array(
                'label' => 'Nome*:'
            ),
            'attributes' => array(
                'class' => 'calendario',
            )
        ));
        $this->add(
            array(
                'name' => 'descricao',
                'type' => 'textarea',
                'options' => array(
                    'label' => 'Descrição*:'
                )
            )
        );
        $this->add(array(
            'name' => 'valor',
            'type' => 'text',
            'options' => array(
                'label' => 'Valor*:'
            )
        ));
        $this->add(array(
            'name' => 'margem_de_lucro',
            'type' => 'text',
            'options' => array(
                'label' => 'Margem de lucro*:'
            )
        ));
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'marca',
            'options' => array(
                'label' => 'Marca*:',
                'object_manager' => $em,
                'target_class' => '\Ecommerce\Entity\Marca',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UMA MARCA',
                'label_generator' => function($target){
                    return $target->descricao;
                }
            ),
        ));
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'categorias',
            'options' => array(
                'label' => 'Categorias*:',
                'object_manager' => $em,
                'target_class' => '\Ecommerce\Entity\Categoria',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UMA CATEGORIA',
                'label_generator' => function($target){
                    return $target->descricao;
                }

            ),
        ));
        $this->add(array(
            'name' => 'Salvar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Salvar'
            )
        ));
        
//        $this->add(array(
//            'name' => 'cancelar',
//            'type' => 'button',
//            'attributes' => array(
//                'value' => 'Cancelar',
//                'onclick' => 'href=/admin/produtos',
//            )
//        ));
    }

}