<?php

namespace Ecommerce\Service;

use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class MarcasService {

    protected $entity_manager;
    protected $sm;

    public function __construct(EntityManager $entity_manager, $sm) {
        $this->entity_manager = $entity_manager;
        $this->sm = $sm;
    }

    public function fetchAll() {
        $marcas = $this->entity_manager
                ->getRepository('\Ecommerce\Entity\Marca')
                ->findAll();

        return $marcas;
    }

//    public function create($data) {
//        $post = new Post();
//        $user = $this->entity_manager
//                ->find('\Admin\Entity\User', $data['user']);
//        $post->user = $user;
//        $post->title = $data['title'];
//        $post->description = $data['description'];
//        $post->article = $data['article'];
//        $post->date = new \DateTime('now');
//        $post->url_img = $data['url_img'];
//        $post->published = false;
//        $this->entity_manager->persist($post);
//        $this->entity_manager->flush();
//    }

//    public function update($id_post, $data) {
//        $post = $this->entity_manager
//                ->find('\Application\Entity\Post', $id_post);
//        $post->title = $data['title'];
//        $post->description = $data['description'];
//        $post->article = $data['article'];
//        $post->url_img = $data['url_img'];
//        $this->entity_manager->persist($post);
//        $this->entity_manager->flush();
//    }

    public function paginator($page = 0, $item_per_page = 10) {
        $select = $this->entity_manager->createQueryBuilder()
                ->select('Marca')
                ->from('\Marca\Entity\Marca', 'Marca');
        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($item_per_page);
        //$paginator->setCache($this->sm->get('Cache'));
        //$paginator->setCacheEnabled(true);

        if ($page > 0)
            $paginator->setCurrentPageNumber($page);

        return $paginator;
    }

}
