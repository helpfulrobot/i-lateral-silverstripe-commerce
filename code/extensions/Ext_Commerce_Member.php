<?php

class Ext_Commerce_Member extends DataExtension {
    private static $db = array(
        "PhoneNumber" => "Varchar",
        "Company" => "Varchar(99)"
    );

    private static $has_many = array(
        "Orders" => "Order"
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->remove("PhoneNumber");

        $fields->addFieldToTab(
            "Root.Main",
            TextField::create("PhoneNumber"),
            "Password"
        );

        $fields->addFieldToTab(
            "Root.Main",
            TextField::create("Company"),
            "FirstName"
        );

        return $fields;
    }

    /**
     * Get all orders that have been generated and are marked as paid or
     * processing
     *
     * @return DataList
     */
    public function getOutstandingOrders() {
        $orders = $this
            ->owner
            ->Orders()
            ->filter(array(
                "Status" => array("paid","processing")
            ));

        return $orders;
    }

    /**
     * Get all orders that have been generated and are marked as dispatched or
     * canceled
     *
     * @return DataList
     */
    public function getHistoricOrders() {
        $orders = $this
            ->owner
            ->Orders()
            ->filter(array(
                "Status" => array("dispatched","canceled")
            ));

        return $orders;
    }
}