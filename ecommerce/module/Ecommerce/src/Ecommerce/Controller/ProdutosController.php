<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Produto;
use Ecommerce\Form\ProdutoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ecommerce\Validator\ProdutoValidator;

/**
 * Controlador para cadastrar novos produtos
 *
 * @category Ecommerce
 * @package Controller
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

class ProdutosController extends AbstractActionController {

    public function indexAction() {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $produtos = $entityManager->getRepository('\Ecommerce\Entity\Produto')->findAll();

        return new ViewModel(
                array('produtos' => $produtos)
        );
    }

    public function createAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new ProdutoForm($em);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $validator = new ProdutoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $produto = new Produto();


                $produto->marca = $em->find('\Ecommerce\Entity\Marca', $values['marca']);
                $produto->nome = $values['nome'];
                $produto->descricao = $values['descricao'];
                $produto->valor = $values['valor'];
                $produto->margem_de_lucro = $values['margem_de_lucro'];

                foreach ($values['categorias'] as $categoria)
                    $produto->categorias->add($em->find('\Ecommerce\Entity\Categoria', $categoria));

                $em->persist($produto);

                try {
                    $em->flush();
                    $this->flashMessenger()->addSuccessMessage('Produto inserido com sucesso');
                    //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

                    return $this->redirect()->toUrl('/ecommerce/produtos/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Erro ao inserir produto');
                }
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function updateAction() {
        $id = $this->params()->fromRoute('id', 0);
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $values = $request->getPost();
            $produto = $em->find('\Ecommerce\Entity\Produto', $id);

            $produto->marca = $em->find('\Ecommerce\Entity\Marca', $values['marca']);
            $produto->nome = $values['nome'];
            $produto->descricao = $values['descricao'];
            $produto->valor = $values['valor'];
            $produto->margem_de_lucro = $values['margem_de_lucro'];

            $produto->categorias->clear();

            foreach ($values['categorias'] as $categoria)
                $produto->categorias->add($em->find('\Ecommerce\Entity\Categoria', $categoria));

            //$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $em->persist($produto);


            try {
                $em->flush();
                $this->flashMessenger()->addSuccessMessage('Produto editado com sucesso');
                //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

                return $this->redirect()->toUrl('/ecommerce/produtos/index');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Erro ao editar produto');
            }
            //$em->flush();

            //return $this->redirect()->toUrl('/admin/produtos');
        }

        if ($id > 0) {
            $form = new ProdutoForm($em);
            $produto = $em->find('\Ecommerce\Entity\Produto', $id);
            $form->bind($produto);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $produto = $entityManager->find('\Ecommerce\Entity\Produto', $id);
        $entityManager->remove($produto);

        try {
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Produto excluido com sucesso');
            //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

            return $this->redirect()->toUrl('/ecommerce/produtos/index');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Erro ao excluir produto');
        }
    }

}
