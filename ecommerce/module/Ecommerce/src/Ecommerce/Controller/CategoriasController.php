<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Categoria;
use Ecommerce\Form\CategoriaForm;
use Ecommerce\Validator\CategoriaValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Controlador para cadastrar novas marcas.
 *
 * @category Ecommerce
 * @package Controller
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class CategoriasController extends AbstractActionController {

    public function indexAction() {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $categorias = $entityManager->getRepository('\Ecommerce\Entity\Categoria')->findAll();

        return new ViewModel(
                array('categorias' => $categorias)
        );
    }

    public function createAction() {
        $form = new CategoriaForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $validator = new CategoriaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $categoria = new Categoria();
                $categoria->descricao = $values['descricao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($categoria);
                $entityManager->flush();

                return $this->redirect()->toUrl('/ecommerce/categorias');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function updateAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $values = $request->getPost();
            $categoria = $entityManager->find('\Ecommerce\Entity\Categoria', $id);
            $categoria->descricao = $values['descricao'];
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entityManager->persist($categoria);
            $entityManager->flush();

            return $this->redirect()->toUrl('/ecommerce/categorias');
        }

        if ($id > 0) {
            $form = new CategoriaForm();
            $categoria = $entityManager->find('\Ecommerce\Entity\Categoria', $id);
            $form->bind($categoria);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $categoria = $entityManager->find('\Ecommerce\Entity\Categoria', $id);
        $entityManager->remove($categoria);
        $entityManager->flush();

        return $this->redirect()->toUrl('/ecommerce/categorias');
    }

}
