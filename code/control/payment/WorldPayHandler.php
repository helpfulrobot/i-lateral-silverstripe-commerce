<?php

class WorldPayHandler extends CommercePaymentHandler {

    protected function gateway_fields() {
        $order = Session::get('Order');
        $site = SiteConfig::current_site_config();

        $fields = new FieldList(
            // Account details
            HiddenField::create('instId', null, $this->payment_gateway->InstallID),
            HiddenField::create('cartId', null, $order->OrderNumber),
            HiddenField::create('MC_callback', null, Director::absoluteBaseURL() . Payment_Controller::$url_segment),

            // Amount and Currency details
            HiddenField::create('amount', null, $order->getOrderTotal()),
            HiddenField::create('currency', null, $site->Currency()->GatewayCode),

            // Payee details
            HiddenField::create('name', null, $order->BillingFirstnames . " " . $order->BillingSurname),
            HiddenField::create('address1', null, $order->BillingAddress1),
            HiddenField::create('address2', null, $order->BillingAddress2),
            HiddenField::create('town', null, $order->BillingCity),
            HiddenField::create('region', null, $order->BillingState),
            HiddenField::create('postcode', null, $order->BillingPostCode),
            HiddenField::create('country', null, $order->BillingCountry),
            HiddenField::create('email', null, $order->BillingEmail)
        );

        if($this->payment_gateway->GatewayMessage)
            $fields->add(HiddenField::create('desc', null, $this->payment_gateway->GatewayMessage));

        if(Director::isDev())
            $fields->add(HiddenField::create('testMode', null, '100'));

        return $fields;
    }

    public function ProcessCallback($data = null) {
        // Check if CallBack data exists and install id matches the saved ID
        if(
            isset($data) && // Data and order are set
            (isset($data['instId']) && isset($data['cartId']) && isset($data['transStatus'])) && // required$
            $this->payment_gateway->InstallID == $data['instId'] // The current install ID matches the postback ID
        ) {
            $order = Order::get()->filter('OrderNumber',$data['cartId'])->first();
            $order_status = $data['transStatus'];

            if($order) {
                $order->Status = ($order_status == 'Y') ? 'paid' : 'failed';
                $order->write();

                if($order_status == 'Y')
                    return true;
                else
                    return false;
            }
        }

        return false;
    }

}
