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
     * @var int $id_produto
     */
    protected $id_produto;

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
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $url_photo;

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
     * @ORM\JoinColumn(name="id_marca", referencedColumnName="id_marca")
     *
     * @var Marca $marca
     */
    protected $marca;

    /**
     * @ORM\ManyToOne(targetEntity="Modelo", inversedBy="produto")   
     * @ORM\JoinColumn(name="id_modelo", referencedColumnName="id_modelo")
     *
     * @var Modelo $modelos
     */
    protected $modelo;

    /**
     * @ORM\ManyToOne(targetEntity="SubCategoria", inversedBy="produto")   
     * @ORM\JoinColumn(name="id_sub_categoria", referencedColumnName="id_sub_categoria")
     *
     * @var SubCategoria $subcategoria
     */
    protected $subcategoria;

    /**
     * @ORM\ManyToMany(targetEntity="Categoria")
     * @ORM\JoinTable(name="produto_categoria",
     * joinColumns={@ORM\JoinColumn(name="id_produto", referencedColumnName="id_produto")},
     * inverseJoinColumns={@ORM\JoinColumn(name="id_categoria", referencedColumnName="id_categoria")}
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
