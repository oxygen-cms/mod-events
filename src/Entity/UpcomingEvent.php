<?php

namespace OxygenModule\Events\Entity;

use DateTime;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use LaravelDoctrine\ORM\Contracts\UrlRoutable;
use Oxygen\Core\Templating\Templatable;
use Oxygen\Data\Behaviour\Accessors;
use Oxygen\Data\Behaviour\CacheInvalidator;
use Oxygen\Data\Behaviour\CacheInvalidatorInterface;
use Oxygen\Data\Behaviour\Fillable;
use Oxygen\Data\Behaviour\HasUpdatedAt;
use Oxygen\Data\Behaviour\PrimaryKey;
use Oxygen\Data\Behaviour\PrimaryKeyInterface;
use Oxygen\Data\Behaviour\Publishable;
use Oxygen\Data\Behaviour\Publishes;
use Oxygen\Data\Behaviour\SoftDeletes;
use Oxygen\Data\Behaviour\Timestamps;
use Oxygen\Data\Behaviour\Versionable;
use Oxygen\Data\Behaviour\Versions;
use Oxygen\Data\Validation\Validatable;
use Oxygen\Data\Behaviour\Searchable;

/**
 * @ORM\Entity
 * @ORM\Table(name="upcoming_events")
 * @ORM\HasLifecycleCallbacks
 */
class UpcomingEvent implements PrimaryKeyInterface, Validatable, CacheInvalidatorInterface, Searchable, Templatable, Versionable, HasUpdatedAt, UrlRoutable, Publishable {

    use PrimaryKey, Timestamps, SoftDeletes, Versions, Publishes, CacheInvalidator;
    use Accessors, Fillable;
    
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
     * @ORM\Column(type="simple_array", name="trybooking_session_id", nullable=true)
     */
    protected $trybookingSessionIds;

    /**
     * One event takes many bookings.
     * This is the inverse side.
     *
     * @ORM\OneToMany(targetEntity="TeamOfPianists\People\Entity\Booking", mappedBy="event", cascade={"persist", "remove"})
     */
    private $bookings;

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
        return [
            'title'  => [
                'required',
                'max:255'
            ],
            'author' => [
                'nullable',
                'name',
                'max:255'
            ],
            'content' => [
                'twig_template'
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
     * @return mixed
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Returns the fields that should be fillable.
     *
     * @return array
     */
    protected function getFillableFields() {
        return ['title', 'author', 'content', 'startDate', 'endDate', 'active', 'trybookingSessionIds'];
    }

    /**
     * Convert this model to a JSON-friendly format.
     */
    public function toArray() {
        return [
            'id' => $this->getId(),
            'title' => $this->title,
            'author' => $this->author,
            'content' => $this->content,
            'startDate' => $this->startDate->format(\DateTimeInterface::ATOM),
            'endDate' => $this->endDate->format(\DateTimeInterface::ATOM),
            'active' => $this->active,
            'trybookingSessionIds' => $this->trybookingSessionIds === null ? [] : $this->trybookingSessionIds
        ];
    }

    /**
     * Sets the start date.
     *
     * @param mixed $startDate
     * @return $this
     * @throws \Exception
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
     * @throws \Exception
     */
    public function setEndDate($endDate) {
        if(!($endDate instanceof DateTime)) {
            $endDate = new DateTime($endDate);
        }
        $this->endDate = $endDate;
        return $this;
    }

    public static function getSearchableFields() {
        return ['title'];
    }

    /**
     * @return string
     */
    public function getResourceType() {
        return 'events';
    }

    /**
     * @return string
     */
    public function getResourceKey() {
        return $this->endDate->format(\DateTimeInterface::ATOM);
    }

    /**
     * @return string
     */
    public function getTemplateCode() {
        return $this->content;
    }

    /**
     * Returns an array of trybooking session ids corresponding to this 'event'
     *
     * @return array
     */
    public function getTrybookingSessionIds() {
        return $this->trybookingSessionIds === null ? [] : $this->trybookingSessionIds;
    }

}
