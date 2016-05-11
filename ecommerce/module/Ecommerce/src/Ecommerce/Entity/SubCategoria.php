<?php

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity SubCategoria
 * @category Ecommerce
 * @package Entity
 * @author Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

/**
 * @ORM\Entity
 * @ORM\Table (name = "sub_categoria")
 *
 * @author  Maico.baggio <maico.baggio@unochapeco.edu.br
 * @category Ecommerce
 * @package Entity
 */
class SubCategoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id_sub_categoria;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Produto", mappedBy="sub_categoria")
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
