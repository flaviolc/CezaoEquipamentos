<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Marca;
use Ecommerce\Form\MarcaForm;
use Ecommerce\Validator\MarcaValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Controlador para cadastrar novas marcas.
 *
 * @category Ecommerce
 * @package Controller
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

class MarcasController extends AbstractActionController {

    public function indexAction() {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $marcas = $entityManager->getRepository('\Ecommerce\Entity\Marca')->findAll();

        return new ViewModel(
                array('marcas' => $marcas)
        );
    }

    public function createAction() {
        $form = new MarcaForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $validator = new MarcaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $marca = new Marca();
                $marca->descricao = $values['descricao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($marca);
                $entityManager->flush();

                return $this->redirect()->toUrl('/ecommerce/marcas');
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
            $marca = $entityManager->find('\Ecommerce\Entity\Marca', $id);
            $marca->descricao = $values['descricao'];
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entityManager->persist($marca);
            $entityManager->flush();

            return $this->redirect()->toUrl('/ecommerce/marcas');
        }

        if ($id > 0) {
            $form = new MarcaForm();
            $marca = $entityManager->find('\Ecommerce\Entity\Marca', $id);
            $form->bind($marca);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $marca = $entityManager->find('\Ecommerce\Entity\Marca', $id);
        $entityManager->remove($marca);
        $entityManager->flush();

        return $this->redirect()->toUrl('/ecommerce/marcas');
    }

}
