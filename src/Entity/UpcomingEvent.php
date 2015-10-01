<?php

namespace OxygenModule\Events\Entity;

use DateTime;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Oxygen\Data\Behaviour\Accessors;
use Oxygen\Data\Behaviour\CacheInvalidator;
use Oxygen\Data\Behaviour\CacheInvalidatorInterface;
use Oxygen\Data\Behaviour\Fillable;
use Oxygen\Data\Behaviour\PrimaryKey;
use Oxygen\Data\Behaviour\PrimaryKeyInterface;
use Oxygen\Data\Behaviour\Publishes;
use Oxygen\Data\Behaviour\SoftDeletes;
use Oxygen\Data\Behaviour\Timestamps;
use Oxygen\Data\Behaviour\Versions;
use Oxygen\Data\Validation\Validatable;

/**
 * @ORM\Entity
 * @ORM\Table(name="upcoming_events")
 * @ORM\HasLifecycleCallbacks
 */
class UpcomingEvent implements PrimaryKeyInterface, Validatable, CacheInvalidatorInterface {

    use PrimaryKey, Timestamps, SoftDeletes, Versions, Publishes, CacheInvalidator {
        Publishes::__clone insteadof PrimaryKey;
    }
    use Accessors, Fillable;

    const STAGE_DRAFT = 0;
    const STAGE_PUBLISHED = 1;

    /**
     * @ORM\Column(type="string")
     */

    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */

    protected $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */

    protected $content;

    /**
     * @ORM\Column(type="datetime")
     */

    protected $startDate;

    /**
     * @ORM\Column(type="datetime")
     */

    protected $endDate;

    /**
     * @ORM\Column(type="boolean")
     */

    protected $active;

    /**
     * @ORM\OneToMany(targetEntity="OxygenModule\Events\Entity\UpcomingEvent", mappedBy="headVersion", cascade={"persist", "remove", "merge"})
     * @ORM\OrderBy({ "updatedAt" = "DESC" })
     */

    private $versions;

    /**
     * @ORM\ManyToOne(targetEntity="OxygenModule\Events\Entity\UpcomingEvent",  inversedBy="versions")
     * @ORM\JoinColumn(name="head_version", referencedColumnName="id")
     */

    private $headVersion;

    /**
     * Constructs a new UpcomingEvent.
     */

    public function __construct() {
        $this->versions = new ArrayCollection();
    }

    /**
     * Returns an array of validation rules used to validate the model.
     *
     * @return array
     */

    public function getValidationRules() {
        $class = get_class($this);
        $headVersion = $this->getHeadId();

        return [
            'title'  => [
                'required',
                'max:255'
            ],
            'author' => [
                'name',
                'max:255'
            ],
            'startDate' => [
                'required',
                'date'
            ],
            'endDate' => [
                'required',
                'date'
            ]
        ];
    }

    /**
     * Returns the fields that should be fillable.
     *
     * @return array
     */

    protected function getFillableFields() {
        return ['title', 'author', 'content', 'startDate', 'endDate', 'active', 'stage'];
    }

    /**
     * Sets the start date.
     *
     * @param mixed $startDate
     * @return $this
     */

    public function setStartDate($startDate) {
        if(!($startDate instanceof DateTime)) {
            $startDate = new DateTime($startDate);
        }
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Sets the end date.
     *
     * @param mixed $endDate
     * @return $this
     */

    public function setEndDate($endDate) {
        if(!($endDate instanceof DateTime)) {
            $endDate = new DateTime($endDate);
        }
        $this->endDate = $endDate;
        return $this;
    }

}