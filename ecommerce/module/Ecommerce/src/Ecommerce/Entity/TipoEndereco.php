<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table (name = "tipo_endereco")
 *
 * @author  Maico.baggio <maico.baggio@unochapeco.edu.br
 * @category Ecommerce
 * @package Entity
 */
class TipoEndereco {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $id_tipo_endereco;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $descricao;

    /**
     * @ORM\OneToMany(targetEntity="Endereco", mappedBy="tipo_endereco")
     *
     * @var ArrayCollection $enderecos
     */
    protected $enderecos;

    public function __construct() {
        $this->enderecos = new ArrayCollection();
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
