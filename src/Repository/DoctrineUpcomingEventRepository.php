<?php

namespace OxygenModule\Events\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Oxygen\Data\Repository\ExcludeTrashedScope;
use OxygenModule\Events\Entity\UpcomingEvent;
use DateTime;
use Doctrine\ORM\NoResultException as DoctrineNoResultException;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Data\Repository\Doctrine\Publishes;
use Oxygen\Data\Repository\Doctrine\Repository;
use Oxygen\Data\Repository\Doctrine\Versions;
use Oxygen\Data\Repository\QueryParameters;

class DoctrineUpcomingEventRepository extends Repository implements UpcomingEventRepositoryInterface {

    use Versions, Publishes {
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
     * @param int $howMany
     * @return array
     * @throws NoResultException if no results were found
     * @throws Exception
     */
    public function getLatest($howMany) {
        $currentDate = new DateTime();

        $q = $this->getQuery(
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
            new QueryParameters([new ExcludeTrashedScope()])
        );

        try {
            return $q->getResult();
        } catch(DoctrineNoResultException $e) {
            throw $this->makeNoResultException($e, $q);
        }
    }

    /**
     * Finds event by trybooking session id
     *
     * @param int $sessionId
     * @return UpcomingEvent
     * @throws NonUniqueResultException
     */
    public function findByTrybookingSessionId($sessionId){
        $queryParameters = QueryParameters::make()
            ->excludeTrashed()
            ->excludeVersions()
            ->orderBy('id', QueryParameters::DESCENDING);

        $sessionId = intval($sessionId);

        $qb = $this->createSelectQuery();
        $qb = $qb->where($qb->expr()->like('o.trybookingSessionIds', ':sessId'))
            ->setParameter(':sessId', '%' . $sessionId . '%');

        $events = $this->getQuery($qb, $queryParameters)->getResult();
        $events = array_filter($events, function($event) use($sessionId) {
            return in_array($sessionId, $event->getTrybookingSessionIds());
        });
        if(count($events) > 1) {
            throw new NonUniqueResultException(count($events) . ' events found, expecting one unique event');
        } else if(empty($events)) {
            return null;
        }
        return array_shift($events);
    }
}
