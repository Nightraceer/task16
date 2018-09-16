<?php

namespace Main\Controller;


use Doctrine\ORM\EntityManager;
use Main\Controller\BaseController;
use Main\Entity\BillsRuEvents;
use Main\Util\Parser;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;

class MainController extends BaseController
{
    public function indexAction()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getEntityManager();

        $parser = new Parser($entityManager);
        $parser->run();
        var_dump($parser->getLog());
        die();
        return new ViewModel();
    }
}