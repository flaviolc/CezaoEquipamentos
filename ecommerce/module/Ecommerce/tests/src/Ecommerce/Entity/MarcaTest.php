<?php
namespace Ecommerce\Entity;

use Core\Test\ModelTestCase;
use \Ecommerce\Entity\Marca;

/**
 * @group Entity
 */
class MarcaTest extends ModelTestCase
{

    public function testInsert()
    {
        $marca = new Marca();
        $marca->setId('1');
		$marca->setMarca('Lenovo');
		$marca->setDescricaoMarca('Notebook lenovo.');
		$this->getEntityManager()->persist($marca);
		$this->getEntityManager()->flush();
		$dados_marca = $this->getEntityManager()
		->getRepository('\Ecommerce\Entity\Marca')->findAll();
		$this->assertEquals(1, count($dados_marca));
		$this->assertEquals('Lenovo', $dados_marca[0]->getMarca());
		$this->assertEquals('Notebook lenovo.', $dados_marca[0]->getDescricaoMarca());
		$this->assertEquals(1, $dados_marca[0]->getId());
    }

	/**
	 * @expectedException \Doctrine\DBAL\Exception\NotNullConstraintViolationException
	 */
    public function testInsertInvalid()
    {
        $marca = new Marca();
		$marca->setMarca(null);
		$marca->setDescricaoMarca(null);
		$this->getEntityManager()->persist($marca);
		$this->getEntityManager()->flush();
    }

//testes Novos
  
    public function testUpdate()
    {
        $marca = $this->addMarca();
        
        $id = $marca->getId();
        $this->assertEquals(1, $id);
        $user = $this->getEntityManager()->find('Ecommerce\Entity\Marca', $id);
        $this->assertEquals('Lenovo', $marca->getMarca());
        $this->assertEquals('Lenovo G400S', $marca->getDescricaoMarca());
        $marca->setMarca('HP');
        $marca->setDescricaoMarca('Notebook HP');
        $this->getEntityManager()->persist($marca);
        $this->getEntityManager()->flush();
        
        $marca = $this->getEntityManager()->find('Ecommerce\Entity\Marca', $id);
        $this->assertEquals('HP', $marca->getMarca());
        $this->assertEquals('Notebook HP', $marca->getDescricaoMarca());
    }

    public function testDelete()
    {
        $marca = $this->addMarca();
        
        $id = $marca->getId();
        $marca = $this->getEntityManager()->find('Ecommerce\Entity\Marca', $id);
        $this->getEntityManager()->remove($marca);
        $this->getEntityManager()->flush();
        $marca = $this->getEntityManager()->find('Ecommerce\Entity\Marca', $id);
        $this->assertNull($marca);
    }
    
    private function addMarca()
    {
        $marca = new Marca();
        $marca->setMarca('Lenovo');
        $marca->setDescricaoMarca('Lenovo G400S');
        $this->getEntityManager()->persist($marca);
        $this->getEntityManager()->flush();
        return $marca;
    }


}

