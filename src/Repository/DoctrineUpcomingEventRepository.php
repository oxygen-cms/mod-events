<?php

namespace OxygenModule\Events\Repository;

use OxygenModule\Events\Entity\UpcomingEvent;
use DateTime;
use Doctrine\ORM\NoResultException as DoctrineNoResultException;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Data\Repository\Doctrine\Publishes;
use Oxygen\Data\Repository\Doctrine\Repository;
use Oxygen\Data\Repository\Doctrine\SoftDeletes;
use Oxygen\Data\Repository\Doctrine\Versions;
use Oxygen\Data\Repository\QueryParameters;

class DoctrineUpcomingEventRepository extends Repository implements UpcomingEventRepositoryInterface {

    use SoftDeletes, Versions, Publishes {
        Publishes::persist insteadof Versions;
    }

    /**
     * The name of the entity.
     *
     * @var string
     */

    protected $entityName = UpcomingEvent::class;

    /**
     * Returns the latest upcoming events.
     *
     * @param $howMany
     * @return array
     * @throws NoResultException if no results were found
     */

    public function getLatest($howMany) {
        $currentDate = new DateTime();

        try {
            $qb = $this->getQuery(
                $this->createSelectQuery()
                   ->andWhere('o.stage = :stage')
                   ->andWhere('o.startDate <= :currentDate')
                   ->andWhere('o.endDate >= :currentDate')
                   ->andWhere('o.active = :active')
                   ->setParameter('stage', UpcomingEvent::STAGE_PUBLISHED)
                   ->setParameter('currentDate', $currentDate)
                   ->setParameter('active', true)
                   ->orderBy('o.endDate', 'ASC')
                   ->setMaxResults($howMany),
                new QueryParameters(['excludeTrashed'])
            );

            return $qb->getResult();
        } catch(DoctrineNoResultException $e) {
            throw new NoResultException($e, $this->replaceQueryParameters($qb->getDQL(), $qb->getParameters()));
        }
    }
}