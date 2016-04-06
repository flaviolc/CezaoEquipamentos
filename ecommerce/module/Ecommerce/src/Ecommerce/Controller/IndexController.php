<?php
/**
 * Módulo Ecommerce
 *
 * @link      http://...
 * @copyright Copyright (c) 2016 Disciplina de Desenvolvimento com Frameworks
 * @license   Private
 */

namespace Ecommerce\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
