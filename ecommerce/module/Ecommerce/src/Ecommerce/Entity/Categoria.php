<?php

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model Categoria
 * @category Ecommerce
 * @package Entity
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

/**
 * @ORM\Entity
 * @ORM\Table (name = "categoria")
 *
 * @author  Cezar Junior de Souza <cezar08@unochapeco.edu.br
 * @category Admin
 * @package Entity
 */
class Categoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $descricao;

    /**
     * @ORM\ManyToMany(targetEntity="Produto", mappedBy="categorias")
     *
     * @var ArrayCollection $produtos
     */
    protected $produtos;

    public function __construct()
    {
        $this->produtos = new ArrayCollection();
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function __set($name, $value)
    {
       $this->$name = $value;
    }

    public function __get($name)
    {
       return $this->$name;
    }
}
