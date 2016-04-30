<?php

namespace Core\Acl;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Db\Sql\Sql;

class Builder implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
//return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * Constroi a ACL
     * @return Acl
     */
    public function build() {

        $acl = new Acl();
        $adapter = $this->getServiceManager()->get('DbAdapter');
        $adapter->query("SET search_path TO schema_autenticacao;", 'execute');
        $sql = new Sql($adapter);

        $roles = $this->roles($sql);


        $resources = $this->resources($sql);

        $privileges = $this->privilege($sql, $roles['auxPrivilege']);


        foreach ($roles['auxRoles']as $role => $parent) {
            $acl->addRole(new Role($role), $parent);
        }
        foreach ($resources as $r) {
            $acl->addResource(new Resource($r));
        }
        
        foreach ($privileges as $role => $privilege) {
            if (isset($privilege['allow'])) {
                foreach ($privilege['allow'] as $p) {
                    $acl->allow($role, $p);
                }
            }
           
            if (isset($privilege['deny'])) {
             
                foreach ($privilege['deny'] as $p) {
                    
                    $acl->deny($role, $p);
                } 
            }
        }

        return $acl;
    }

    private function roles($sql) {
        $select = $sql->select()->columns(array('id', 'desc_perfil'))->from('perfil');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $roles['auxRoles']['unauthenticated'] = null;
        $roles['auxRoles']['authenticated'] = 'unauthenticated';
        $roles['auxPrivilege'] = null;
        foreach ($result as $role) {
            $roles['auxPrivilege'][] = $role;
            $roleUCFirst = ucfirst(strtolower($role['desc_perfil']));
            $roles['auxRoles'][$roleUCFirst] = 'authenticated';
        }


        return $roles;
    }

    private function resources($sql) {
        $select = $sql->select()->columns(array('controller_programa'))->from('programa')
                ->join('filial', 'filial.id = programa.id_filial', array('desc_modulo_filial'))
                ->join('acao', 'programa.id = acao.id_programa', array('acao_url'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resources = array_values(iterator_to_array($result));
        $resourcesMount = $this->mountResources($resources);
        $resourcesMount[] = "Auth\Controller\Index.index";
        $resourcesMount[] = "Auth\Controller\Index.logout";
        $resourcesMount[] = "Auth\Controller\Index.login";
        $resourcesMount[] = "Auth\Controller\Index.recuperar-senha";
        $resourcesMount[] = "Auth\Controller\Index.novasenha";
        $resourcesMount[] = "Auth\Controller\SelecionarPerfil.index";
        $resourcesMount[] = "Auth\Controller\SelecionarPerfil.carrega-sessao";
        $resourcesMount[] = "Auth\Controller\Home.index";
        $resourcesMount[] = "Auth\Controller\Home.noauthorize";
        $resourcesMount[] = "Auth\Controller\SelecionarPerfil.selecionar-perfil";
        $resourcesMount[] = "DoctrineORMModule\Yuml\YumlController.index";

        return $resourcesMount;
    }

    private function privilege($sql, $roles) {
        if ($roles) {
            foreach ($roles as $role) {

                $select = $sql->select()->columns(array('controller_programa'))->from('programa')
                        ->join('programa_perfil', 'programa_perfil.id_programa = programa.id')
                        ->join('filial', 'filial.id = programa.id_filial', array('desc_modulo_filial'))
                        ->join('programa_perfil_acao', 'programa_perfil_acao.id_programa_perfil = programa_perfil.id')
                        ->join('acao', 'programa_perfil_acao.id_acao = acao.id', array('acao_url'))
                        ->where('programa_perfil.id_perfil = ' . $role['id']);
                $statement = $sql->prepareStatementForSqlObject($select);
                $result = $statement->execute();
                $resources = array_values(iterator_to_array($result));

                $resourcesMount = $this->mountResources($resources);

                $roleUCFirst = ucfirst(strtolower($role['desc_perfil']));

                $privilege[$roleUCFirst] = array(
                    'allow' => $resourcesMount
                );
            }
        }


        $privilege['unauthenticated'] = array(
            'allow' => array(
                'Auth\Controller\Index.index',
                'Auth\Controller\Index.logout',
                'Auth\Controller\Index.login',
            	"Auth\Controller\Home.noauthorize",
                'Auth\Controller\Index.recuperar-senha',
                'Auth\Controller\Index.novasenha',
                'DoctrineORMModule\Yuml\YumlController.index'
            )
        );

        $privilege['authenticated'] = array(
            'allow' => array(
                'Auth\Controller\SelecionarPerfil.index',
                'Auth\Controller\SelecionarPerfil.carrega-sessao',
                'Auth\Controller\Home.index',
                "Auth\Controller\Home.noauthorize",
                'Auth\Controller\SelecionarPerfil.selecionar-perfil',
            )
        );

        return $privilege;
    }

    private function mountResources($resources) {
        $resourcesMount = array();
        
        foreach ($resources as $resource) {
            $modulo = ucfirst(strtolower($resource['desc_modulo_filial']));
            $controller = ucfirst(strtolower($resource['controller_programa']));
            if (strstr($controller, '-')) {
                $controllerExplode = explode('-', $controller);
                $controller = '';
                foreach ($controllerExplode as $c) {
                    $controller .= ucfirst(strtolower($c));
                }
            }

            $action = strtolower($resource['acao_url']);
            $resourcesMount[] = "$modulo\\Controller\\$controller.$action";
        }

        return $resourcesMount;
    }

}
