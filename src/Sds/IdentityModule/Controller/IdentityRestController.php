<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\IdentityModule\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sds\JsonController\AbstractJsonRestfulController;

/**
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IdentityRestController extends AbstractJsonRestfulController {

    protected $limit = 50;

    protected $sort = 'id';

    protected $order = 'asc';

    protected $documentManager;

    protected $documentClass = 'Sds\IdentityModule\Model\Identity';

    protected $serializer;

    protected $validator;

    public function getDocumentManager() {
        return $this->documentManager;
    }

    public function setDocumentManager(DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    /**
     * Return list of identities
     *
     * @return array
     */
    public function getList() {

        $queryBuilder = $this->documentManager->createQueryBuilder();
        $queryBuilder
            ->find($this->documentClass)
            ->limit($this->getLimit())
            ->skip($this->getOffset())
            ->sort($this->getSort(), $this->getOrder())
            ->hydrate(false)
            ->eagerCursor(true);

        $results = $queryBuilder->getQuery()->execute();

        foreach ($results as $index => $result){
            $results[$index] = $this->serializer->applySerializeMetadataToArray($result, $this->documentClass);
        }

        return $results;
    }

    /**
     * Return single resource
     *
     * @param mixed $id
     * @return mixed
     */
    public function get($id) {

        $queryBuilder = $this->documentManager->createQueryBuilder();
        $queryBuilder
            ->find($this->documentClass)
            ->field('id')->equals($id)
            ->hydrate(false)
            ->eagerCursor(true);

        return $this->serializer->applySerializeMetadataToArray(
            $queryBuilder->getQuery()->getSingleResult(),
            $this->documentClass
        );
    }

    /**
     * Create a new resource
     *
     * @param mixed $data
     * @return mixed
     */
    public function create($data) {

        $document = $this->serializer->fromArray($data, null, $this->documentClass);

        if ($this->validator->isValid($document)) {
            $this->documentManager->persist($document);
        } else {
            throw new \Exception('Problem creating');
        }
    }

    /**
     * Update an existing resource
     *
     * @param mixed $id
     * @param mixed $data
     * @return mixed
     */
    public function update($id, $data) {

        $document = $this->serializer->fromArray($data, null, $this->documentClass);

        if ($this->validator->isValid($document)) {

            $queryBuilder = $this->documentManager->createQueryBuilder();
            $queryBuilder
                ->update($this->documentClass)
                ->field('id')->equals($id);


            $this->documentManager->persist($document);
        } else {
            throw new \Exception('Problem creating');
        }
    }

/**
* Delete an existing resource
*
* @param mixed $id
* @return mixed
*/
public function delete($id) {}

    protected function getLimit(){

        $range = $this->getRequest()->getHeader('Range');

        if (isset($range)) {
            $range = explode('=', $range);
            $range = explode('-', $range[1]);
            if ((string)(int)$range[0] == $range[0] && (string)(int)$range[1] == $range[1])
            {
                $limit = $range[1] - $range[0] + 1;
                if ($limit > $this->limit) {
                    return $limit;
                }
            }
        }

        return $this->limit;
    }

    protected function getOffset(){

        $range = $this->getRequest()->getHeader('Range');

        if(isset($range)){
            $range = explode('=', $range);
            $range = explode('-', $range[1]);
            return  intval($range[0]);
        } else {
            return 0;
        }
    }

    protected function getSort(){

        foreach ($options as $key=>$value)
        {
            if(substr($key, 0, 4) == 'sort')
            {
                $sortkey = $key;
                $sort = substr($key, 6, strlen($key) - 7);
                $order = substr($key, 5,1);
            }
        }
        switch ($order)
        {
            case '_':
                $order = 'ASC';
                break;
            case '-':
                $order = 'DESC';
        }
        return array($sortkey, $sort, $order);
    }
}
