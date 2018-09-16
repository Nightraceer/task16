<?php

namespace Main\Controller;


use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

/**
 * Class BaseController
 * @package Main\Controller
 */
class BaseController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Создание единственных экземпляров необходимых классов
     *
     * @param $serviceManager
     * @return $this
     */
    public function setManagers($serviceManager)
    {
        $this->setServiceManager($serviceManager);
        $serviceManager = $this->getServiceManager();

        $this->setEntityManager($serviceManager->get("Doctrine\ORM\EntityManager"));

        return $this;
    }
}