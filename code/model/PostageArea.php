<?php
/**
 * Postage objects list available postage costs and destination locations
 *
 * @author morven
 */
class PostageArea extends DataObject {

    private static $db = array(
        "Title"         => "Varchar",
        "Country"       => "Varchar",
        "ZipCode"       => "Varchar",
        "Calculation"   => "Enum('Price,Weight,Items','Weight')",
        "Unit"          => "Decimal",
        "Cost"          => "Decimal"
    );

    private static $has_one = array(
        "Site"          => "SiteConfig"
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        return $fields;
    }

    public function canCreate($member = null) {
        return true;
    }

    public function canEdit($member = null) {
        return true;
    }

    public function canDelete($member = null) {
        return true;
    }
}
