<?php
/**
 * Módulo Ecommerce
 *
 * @link      http://...
 * @copyright Copyright (c) 2016 Disciplina de Desenvolvimento com Frameworks
 * @license   Private
 */

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table (name = "marca")
 *
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br
 * @category Ecommerce
 * @package Entity
 */
class Marca
{
   	/**
 	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	* @ORM\Column(type="integer", name="id_marca")
	*
	* @var int
	*/
	protected $id;

	  	/**
	* @ORM\Column(type="string")
	*
	* @var string
	*/
	protected $marca;
	
  	/**
	* @ORM\Column(type="string")
	*
	* @var string
	*/
	protected $descricao_marca;

	//GETS

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	* @return string
	*/
	public function getMarca()
	{
		return $this->marca;
	}
	/**
	* @return string
	*/
	public function getDescricaoMarca()
	{
		return $this->descricao_marca;
	}

	//SETs
	
	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $marca
	 */
	public function setMarca($marca)
	{
		$this->marca = $marca;
	}

	/**
	 * @param string $descricao_marca
	 */
	public function setDescricaoMarca($descricao_marca)
	{
		$this->descricao_marca = $descricao_marca;
	}
}
