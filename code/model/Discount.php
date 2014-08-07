<?php

class Discount extends DataObject {

    private static $db = array(
        "Title"     => "Varchar",
        "Type"      => "Enum('Fixed,Percentage','Percentage')",
        "Code"      => "Varchar(299)",
        "Amount"    => "Decimal",
        "Expires"   => "Date"
    );

    private static $has_one = array(
        "Site"      => "SiteConfig"
    );

    private static $many_many = array(
        "Groups"    => "Group"
    );

    /**
     * Generate a random string that we can use for the code by default
     *
     * @return string
     */
    private static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * Set more complex default data
     */
    public function populateDefaults() {
        $this->setField('Code', self::generateRandomString());
    }

    /**
     * Return a URL that allows this code to be added to a cart
     * automatically
     *
     * @return String
     */
    public function AddLink() {
        $link = Controller::join_links(
            Director::absoluteBaseURL(),
            ShoppingCart::config()->url_segment,
            "usediscount",
            $this->Code
        );

        return $link;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if($this->Code) {
            $fields->addFieldToTab(
                "Root.Main",
                ReadonlyField::create(
                    "DiscountURL",
                    _t("Commerce.AddDiscountURL", "Add discount URL"),
                    $this->AddLink()
                ),
                "Code"
            );
        }

        return $fields;
    }

    public function onBeforeWrite() {
        parent::onBeforeWrite();

        // Ensure that the code is URL safe
        $this->Code = Convert::raw2url($this->Code);
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
