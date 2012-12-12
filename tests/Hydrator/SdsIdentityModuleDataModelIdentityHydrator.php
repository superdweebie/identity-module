<?php

namespace DoctrineMongoODMModule\Hydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class SdsIdentityModuleDataModelIdentityHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="string") */
        if (isset($data['firstname'])) {
            $value = $data['firstname'];
            $return = (string) $value;
            $this->class->reflFields['firstname']->setValue($document, $return);
            $hydratedData['firstname'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['lastname'])) {
            $value = $data['lastname'];
            $return = (string) $value;
            $this->class->reflFields['lastname']->setValue($document, $return);
            $hydratedData['lastname'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['email'])) {
            $value = $data['email'];
            $return = (string) $value;
            $this->class->reflFields['email']->setValue($document, $return);
            $hydratedData['email'] = $return;
        }

        /** @EmbedOne */
        if (isset($data['profile'])) {
            $embeddedDocument = $data['profile'];
            $className = $this->dm->getClassNameFromDiscriminatorValue($this->class->fieldMappings['profile'], $embeddedDocument);
            $embeddedMetadata = $this->dm->getClassMetadata($className);
            $return = $embeddedMetadata->newInstance();

            $embeddedData = $this->dm->getHydratorFactory()->hydrate($return, $embeddedDocument, $hints);
            $this->unitOfWork->registerManaged($return, null, $embeddedData);
            $this->unitOfWork->setParentAssociation($return, $this->class->fieldMappings['profile'], $document, 'profile');

            $this->class->reflFields['profile']->setValue($document, $return);
            $hydratedData['profile'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['credential'])) {
            $value = $data['credential'];
            $return = (string) $value;
            $this->class->reflFields['credential']->setValue($document, $return);
            $hydratedData['credential'] = $return;
        }

        /** @Field(type="custom_id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value;
            $this->class->reflFields['identityName']->setValue($document, $return);
            $hydratedData['identityName'] = $return;
        }

        /** @Field(type="hash") */
        if (isset($data['roles'])) {
            $value = $data['roles'];
            $return = $value;
            $this->class->reflFields['roles']->setValue($document, $return);
            $hydratedData['roles'] = $return;
        }
        return $hydratedData;
    }
}