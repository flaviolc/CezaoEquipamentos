<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model Endereco
 * @category Ecommerce
 * @package Entity
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

/**
 * @ORM\Entity
 * @ORM\Table (name = "endereco")
 *
 * @author  Maico.baggio <maico.baggio@unochapeco.edu.br
 * @category Ecommerce
 * @package Entity
 */
class Endereco {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int $id_endereco
     */
    protected $id_endereco;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $nome_do_destinatario 
     */
    protected $nome_do_destinatario;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer $cep
     */
    protected $cep;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $endereco
     */
    protected $endereco;

    /**
     * @ORM\Column(type="float")
     *
     * @var float $numero
     */
    protected $numero;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $complemento
     */
    protected $complemento;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $informacao_referencia
     */
    protected $informacao_referencia;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $bairro
     */
    protected $bairro;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $cidade
     */
    protected $cidade;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $estado
     */
    protected $estado;

    /**
     * @ORM\ManyToOne(targetEntity="TipoEndereco", inversedBy="enderecos")   
     * @ORM\JoinColumn(name="id_tipo_endereco", referencedColumnName="id_tipo_endereco")
     *
     * @var TipoEndereco $tipo_endereco
     */
    protected $tipo_endereco;

    ///**
    // * @ORM\ManyToMany(targetEntity="Pessoa")
    // * @ORM\JoinTable(name="pessoa_endereco",
    // * joinColumns={@ORM\JoinColumn(name="id_endereco", referencedColumnName="id_endereco")},
    //  * inverseJoinColumns={@ORM\JoinColumn(name="id_pessoa", referencedColumnName="id_pessoa")}
    // * )
    //  *
    //  * @var ArrayCollection $pessoas
    //  */
    //protected $pessoas;

    public function __construct() {
        //$this->tipo_endereco = new ArrayCollection();
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }

}
