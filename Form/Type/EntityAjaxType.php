<?php

namespace Armetiz\FormExtensionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

use Armetiz\FormExtensionBundle\Form\ChoiceList\ORMSimpleLoader;
use Armetiz\FormExtensionBundle\Form\EventListener\InjectEntitiesListener;

class EntityAjaxType extends DoctrineType 
{
    /**
     * Contains the loader
     * 
     * @var Armetiz\FormExtensionBundle\Form\ChoiceList\ORMSimpleLoader[]
     */
    private $mapLoaders;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->addEventSubscriber(new InjectEntitiesListener($this->mapLoaders[$options["class"]]));
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
        $loader = null;
        
        if (!$this->hasLoader($class)) {
            $loader = new ORMSimpleLoader(
                $queryBuilder,
                $manager,
                $class
            );
            
            $this->addLoader($class, $loader);
        }
        else {
            $loader = $this->getLoaderFromHash($class);
        }
        
        return $loader;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $queryBuilder = function(EntityRepository $er) {
                return $er->createQueryBuilder('u');
        };
        
        $resolver->setDefaults(array(
            'query_builder' => $queryBuilder,
        ));
    }
    
    public function getName()
    {
        return 'entity_ajax';
    }
    
    private function addLoader($class, ORMSimpleLoader $loader) {
        if (null === $this->mapLoaders) {
            $this->mapLoaders = array();
        }
        
        return $this->mapLoaders[$class] = $loader;
    }
    
    private function getLoaderFromHash($class) {
        if (!$this->hasLoader($class)) {
            return null;
        }
        
        return $this->mapLoaders[$class];
    }
    
    private function hasLoader($class) {
        if (null === $this->mapLoaders) {
            $this->mapLoaders = array();
        }
        
        return isset($this->mapLoaders[$class]);
    }
}
