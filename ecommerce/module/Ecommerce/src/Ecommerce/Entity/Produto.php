<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model Produto
 * @category Ecommerce
 * @package Entity
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */


/**
 * @ORM\Entity
 * @ORM\Table(name="produto")
 */
class Produto {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int $id
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string $nome 
     */
    protected $nome;

    /**
     * @ORM\Column(type="text")
     *
     * @var string $descricao
     */
    protected $descricao;

    /**
     * @ORM\Column(type="float")
     *
     * @var float $valor
     */
    protected $valor;

    /**
     * @ORM\Column(type="float")
     *
     * @var float $margem_de_lucro
     */
    protected $margem_de_lucro;

    /**
     * @ORM\ManyToOne(targetEntity="Marca", inversedBy="produto")   
     * @ORM\JoinColumn(name="id_marca", referencedColumnName="id")
     *
     * @var Marca $marca
     */
    protected $marca;

    /**
     * @ORM\ManyToMany(targetEntity="Categoria")
     * @ORM\JoinTable(name="produto_categoria",
     * joinColumns={@ORM\JoinColumn(name="id_produto", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="id_categoria", referencedColumnName="id")}
     *      )
     *
     * @var ArrayCollection $categorias
     */
    protected $categorias;

    public function __construct() {
        $this->categorias = new ArrayCollection();
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
