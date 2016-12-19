<?php

class OL3Layer extends DataObject
{
    private static $singular_name = 'OpenLayer3 Layer';
    private static $plural_name = 'OpenLayer3 Layer';

    private static $db = [
        'Title' => 'Varchar',
        'Visible' => 'Boolean(1)',
        'Opacity' => 'Decimal(3,2,1)',
    ];

    private static $defaults = [
        'Visible' => true,
        'Opacity' => 1,
    ];

    private static $summary_fields = [
        'Title',
        'ClassName',
        'Visible',
    ];

    private static $has_one = [ 'Map' => 'OL3Map' ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab(
            'Root.Main',
            $fields->dataFieldByName('Opacity')
                ->setAttribute('type', 'range')
                ->setAttribute('min', '0')
                ->setAttribute('max', '1')
                ->setAttribute('step', '0.1'),
            'Visible'
        );

        $fields->removeByName('MapID');

        // select layer type on creation
        if (!$this->exists() && $this->ClassName = __CLASS__) {

            $subclasses = ClassInfo::subclassesFor(__CLASS__);

            if (isset($subclasses[__CLASS__])) {
                unset($subclasses[__CLASS__]);
            }

            if (count($subclasses)) {
                $fields->addFieldToTab('Root.Main', DropdownField::create('ClassName', 'Layer Type', $subclasses), 'Title');
            }
        }

        return $fields;
    }
}