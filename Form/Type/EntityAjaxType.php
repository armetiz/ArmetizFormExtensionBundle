<?php

namespace Armetiz\FormExtensionBundle\Type;

use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;

use Doctrine\Common\Persistence\ObjectManager;

use Armetiz\FormExtensionBundle\Form\ChoiceList\ORMSimpleLoader;
use Armetiz\FormExtensionBundle\Form\EventListener\InjectEntitiesListener;
use Armetiz\FormExtensionBundle\Exception\LoaderAlreadyCreatedException;

class EntityAjaxType extends DoctrineType 
{
    /**
     * Contains the loader
     * 
     * @var Armetiz\FormExtensionBundle\Form\ChoiceList\ORMSimpleLoader 
     */
    private $loader;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->addEventSubscriber(new InjectEntitiesListener($this->loader));
    }
    
    /**
     * Return the default loader object.
     *
     * @param ObjectManager $manager
     * @param mixed         $queryBuilder
     * @param string        $class
     * @return ORMQueryBuilderLoader
     */
    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        if ($this->loader) {
            throw new LoaderAlreadyCreatedException();
        }
        
        $this->loader = new ORMSimpleLoader(
            $queryBuilder,
            $manager,
            $class
        );
        
        return $this->loader;
    }
    
    public function getName()
    {
        return 'entity_ajax';
    }
}
