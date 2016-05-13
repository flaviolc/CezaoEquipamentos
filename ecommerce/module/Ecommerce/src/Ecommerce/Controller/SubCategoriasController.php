<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\SubCategoria;
use Ecommerce\Form\SubCategoriaForm;
use Ecommerce\Validator\SubCategoriaValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novas marcas.
 *
 * @category Ecommerce
 * @package Controller
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class SubCategoriasController extends AbstractActionController {

    public function indexAction() {
        $page = (int) $_GET['page'];

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
                ->select('SubCategoria')
                ->from('Ecommerce\Entity\SubCategoria', 'SubCategoria')
                ->orderBy('SubCategoria.descricao', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel(
                array('subCategorias' => $paginator)
        );
    }

    public function createAction() {
        $form = new SubCategoriaForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $validator = new SubCategoriaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $subCategoria = new SubCategoria();
                $subCategoria->descricao = $values['descricao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($subCategoria);
                $entityManager->flush();

                return $this->redirect()->toUrl('/ecommerce/sub-categorias');
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
            $subCategoria = $entityManager->find('\Ecommerce\Entity\SubCategoria', $id);
            $subCategoria->descricao = $values['descricao'];
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entityManager->persist($subCategoria);
            $entityManager->flush();

            return $this->redirect()->toUrl('/ecommerce/sub-categorias');
        }

        if ($id > 0) {
            $form = new SubCategoriaForm();
            $subCategoria = $entityManager->find('\Ecommerce\Entity\SubCategoria', $id);
            $form->bind($subCategoria);
            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);
        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $subCategoria = $entityManager->find('\Ecommerce\Entity\SubCategoria', $id);
        $entityManager->remove($subCategoria);
        $entityManager->flush();

        return $this->redirect()->toUrl('/ecommerce/sub-categorias');
    }

}
