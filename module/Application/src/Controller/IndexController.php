<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use ZendTwig\View\TwigModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $tm = new TwigModel();
        $tm->setTemplate('application/index/index');
        $tm->setTerminal(true);
        $tm->setVariable('name', 'My Friend');
        return $tm;
    }
}
