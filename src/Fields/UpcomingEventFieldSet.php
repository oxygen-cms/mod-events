<?php


namespace OxygenModule\Events\Fields;

use Oxygen\Core\Form\ContentFieldName;
use Oxygen\Core\Form\FieldSet;
use OxygenModule\Events\Entity\UpcomingEvent;

class UpcomingEventFieldSet extends FieldSet implements ContentFieldName {

    /**
     * Creates the fields in the set.
     *
     * @return array
     */
    public function createFields() {
        return $this->makeFields([
            [
                'name'      => 'id',
                'label'     => 'ID',
            ],
            [
                'name'      => 'content',
                'type'      => 'editor',
                'editable'  => true,
                'options'   => [
                    'rows' => 10,
                    'fullWidth' => true
                ]
            ],
            [
                'name'      => 'title',
                'editable'  => true
            ],
            [
                'name'      => 'author',
                'editable'  => true
            ],
            [
                'name'      => 'startDate',
                'type'      => 'date',
                'editable'  => true
            ],
            [
                'name'      => 'endDate',
                'type'      => 'date',
                'editable'  => true
            ],
            [
                'name'      => 'active',
                'type'      => 'checkbox',
                'editable'  => true
            ],
            [
                'name'      => 'createdAt',
                'type'      => 'date'
            ],
            [
                'name'      => 'updatedAt',
                'type'      => 'date'
            ],
            [
                'name'      => 'deletedAt',
                'type'      => 'date'
            ],
            [
                'name' => 'stage',
                'type' => 'select',
                'editable' => true,
                'options' => [
                    UpcomingEvent::STAGE_DRAFT => 'Draft',
                    UpcomingEvent::STAGE_PUBLISHED => 'Published'
                ]
            ]
        ]);
    }

    /**
     * Returns the name of the title field.
     *
     * @return string
     */
    public function getTitleFieldName() {
        return 'title';
    }

    /**
     * Returns the name of the field that contains the content.
     *
     * @return string
     */
    public function getContentFieldName() {
        return 'content';
    }
}