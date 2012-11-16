<?php

namespace Armetiz\FormExtensionBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Armetiz\FormExtensionBundle\Form\ChoiceList\ORMSimpleLoader;

class InjectEntitiesListener implements EventSubscriberInterface {
    private $choiceList;

    /**
     * Constructor.
     *
     * @param ChoiceListInterface $choiceList
     */
    public function __construct(ORMSimpleLoader $choiceList)
    {
        $this->choiceList = $choiceList;
    }

    public function preSetData(FormEvent $event)
    {
        $datas = $event->getData();
        
        if(null === $datas) {
            return;
        }
        
        if (!($datas instanceof Collection)) {
            $datas = new ArrayCollection(array($datas));
        }
        
        $this->choiceList->setValues($datas);
    }
    
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
}
