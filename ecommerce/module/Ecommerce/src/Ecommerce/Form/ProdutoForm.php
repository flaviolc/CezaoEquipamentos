<?php

/**
 * Created by PhpStorm.
 * User: cezar
 * Date: 11/04/16
 * Time: 21:35
 */

namespace Ecommerce\Form;

use Zend\Form\Form;
use Zend\InputFilter;

/**
 * Form para cadastrar produtos
 * @category Ecommerce
 * @package form
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class ProdutoForm extends Form {

    public function __construct($em) {

        parent::__construct('ProdutoForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', '');


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
                //'placeholder' => 'Ex: fulano10',
                //'class' => 'form-control'
                'class'  => 'form-horizontal'
            ),
        ));
        $this->add(
                array(
                    'name' => 'descricao',
                    'type' => 'textarea',
                    'options' => array(
                        'label' => 'Descrição*:'
                    ),
                    'attributes' => array(
                        //'placeholder' => 'Ex: fulano10',
                        'class' => 'form-control'
                    ),
                )
        );
        $this->add(array(
            'name' => 'valor',
            'type' => 'text',
            'options' => array(
                'label' => 'Valor*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'margem_de_lucro',
            'type' => 'text',
            'options' => array(
                'label' => 'Margem de lucro*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
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
                'label_generator' => function($target) {
                    return $target->descricao;
                }
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'modelo',
            'options' => array(
                'label' => 'Modelos*:',
                'object_manager' => $em,
                'target_class' => '\Ecommerce\Entity\Modelo',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UM MODELO',
                'label_generator' => function($target) {
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
                'label_generator' => function($target) {
                    return $target->descricao;
                }
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'subcategoria',
            'options' => array(
                'label' => 'Sub Categoria*:',
                'object_manager' => $em,
                'target_class' => '\Ecommerce\Entity\SubCategoria',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UMA SUB CATEGORIA',
                'label_generator' => function($target) {
                    return $target->descricao;
                }
            ),
        ));

        $this->add(
                array(
                    'name' => 'photo',
                    'type' => 'file',
                    'options' => array(
                        'label' => 'Foto*',
                    ),
                    'attributes' => array(
                    //'class' => 'form-control',
                    //'multiple' => true,
                    )
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

//        $this->add(array(
//            'name' => 'cancelar',
//            'type' => 'button',
//            'attributes' => array(
//                'value' => 'Cancelar',
//                'onclick' => 'href=/admin/produtos',
//            )
//        ));

        $this->addInputFilter();
    }

    public function addInputFilter() {
        $inputFilter = new InputFilter\InputFilter();
        $fileInput = new InputFilter\FileInput('photo');
        $fileInput->setRequired(true);
        $fileInput->getValidatorChain()
                ->attachByName('filesize', array('max' => 204800))
                ->attachByName('filemimetype', array('mimeType' => 'image/jpeg'))
                ->attachByName('fileimagesize', array('maxWidth' => 1000, 'maxHeight' => 1000));
        $fileInput->getFilterChain()->attachByName(
                'filerenameupload', array(
            'target' => getcwd() . '/public/img/photos_produtos/photo.jpg',
            'randomize' => true,
                )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
    
    public function getOptions()
{
    return array('class' => 'form-horizontal');
}

}
