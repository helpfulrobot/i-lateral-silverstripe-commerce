<?php

/**
 * Overwrite group object so we can setup some more default groups
 */
class Ext_Commerce_Group extends DataExtension {
    public function requireDefaultRecords() {
        parent::requireDefaultRecords();

        // Add default author group if no other group exists
        $curr_group = Group::get()->filter("Code","commerce-customers");

        if(!$curr_group->exists()) {
            $group = new Group();
            $group->Code = 'commerce-customers';
            $group->Title = "Commerce Customers";
            $group->Sort = 1;
            $group->write();
            Permission::grant($group->ID, 'COMMERCE_MANAGE_ACCOUNT');

            DB::alteration_message('Commerce customers group created', 'created');
        }
    }
}

