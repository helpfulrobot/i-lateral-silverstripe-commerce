<?php
/**
 * Description of Commerce_Subsite
 *
 * @author morven
 */
class Commerce_SiteConfig extends DataExtension {
    public static $db = array(
        // Commerce Configs
        'SuccessCopy'       => 'Text',
        'FailerCopy'        => 'Text',
        'OrderPrefix'       => 'Varchar(9)',
        'CartCopy'          => 'HTMLText'
    );
    
    public static $has_one = array(
        'Currency'          => 'CommerceCurrency',
        'Weight'            => 'ProductWeight'
    );
    
    public static $has_many = array(
        'PostageAreas'      => 'PostageArea',
        'PaymentMethods'    => 'CommercePaymentMethod'
    );
    
    public function updateCMSFields(FieldList $fields) {
        // Ecommerce Fields
        $fields->addFieldToTab('Root.Commerce', TextField::create('OrderPrefix', 'Short code that can appear at the start of order numbers', null, 9));
        $fields->addFieldToTab('Root.Commerce', HtmlEditorField::create('CartCopy', 'Copy to appear above shopping cart')->setRows(10));
        $fields->addFieldToTab('Root.Commerce', TextAreaField::create('SuccessCopy', 'Content to appear on order success page')->setRows(3));
        $fields->addFieldToTab('Root.Commerce', TextAreaField::create('FailerCopy', 'Content to appear on order failer page')->setRows(3));
		
    	// Add dropdown to manage currency relations
		$currency_map = CommerceCurrency::get()->map();
		$currency_map->unshift('0','Please Select');
		
    	$fields->addFieldToTab('Root.Commerce', DropdownField::create('CurrencyID', 'Currency to use', $currency_map, $this->owner->CurrencyID));
		
		// Add dropdown to manage weight relations
		$weights_map = ProductWeight::get()->map();
		$weights_map->unshift('0','Please Select');
		
    	$fields->addFieldToTab('Root.Commerce', DropdownField::create('WeightID', 'Weight to use', $weights_map, $this->owner->WeightID));
		
		// Postage
        $postage_table = GridField::create('PostageAreas','PostageArea',$this->owner->PostageAreas(), GridFieldConfig_RecordEditor::create());
		
        $fields->addFieldToTab('Root.Postage', $postage_table);
        
		// Payment Methods
        $payment_table = GridField::create('PaymentMethods','CommercePaymentMethod',$this->owner->PaymentMethods(), GridFieldConfig_RecordEditor::create());
		
        $fields->addFieldToTab('Root.Payments', $payment_table);
    }
}
