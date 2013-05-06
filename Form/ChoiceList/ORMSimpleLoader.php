<?php
namespace Armetiz\FormExtensionBundle\Form\ChoiceList;

use Doctrine\Common\Collections\ArrayCollection;

class ORMSimpleLoader extends ORMQuertyBuilderLoader {
    /**
     * Contains the entities directly associated to the field.
     * 
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $values;
    
    public function setValues($values) {
        $this->values = $values;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntities()
    {
        if (null === $this->values) {
            $this->values = new ArrayCollection();
        }
        
        return $this->values;
    }
}
