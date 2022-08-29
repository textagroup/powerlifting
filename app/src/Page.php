<?php

namespace {

    use Powerlifting\LifterClass;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldAddNewButton;
    use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

    class Page extends SiteTree
    {
        private static $db = [];

        private static $has_one = [];

        private static $many_many = [
            'RecordTables' => LifterClass::class
        ];

        private static $many_many_extraFields = [
            'RecordTables' => [
                'SortOrder' => 'Int'
            ]
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields();

            $config = GridFieldConfig_RelationEditor::create();
            $config->removeComponentsByType(GridFieldAddNewButton::class);
            $config->addComponent(GridFieldSortableRows::create('SortOrder'));
            $fields->addFieldToTab('Root.RecordTables',
                $gridField = GridField::create('RecordTables', 'RecordTables', $this->RecordTables())
            );
            $gridField->setConfig($config);

            return $fields;
        }

        public function getRecordTables()
        {
            return $this->getManyManyComponents('RecordTables')
                ->sort('SortOrder');
        }

        /**
         * Check if the record is for bench only
         *
         * @return int
         */
        public function getIsBenchOnly()
        {
            $recordTables = $this->getRecordTables();
            foreach ($recordTables as $recordTable) {
                if ($recordTable->StandardSquat > 0) {
                    return 0;
                }
                if ($recordTable->StandardDeadlift > 0) {
                    return 0;
                }
            }
            return 1;
        }
    }
}
