<?php
/*
@author Oscar Alderete <oscaralderete@gmail.com>
@website http://oscaralderete.com
@generator NetBeans IDE 8.2
*/
class OA_Shipping_Custom extends WC_Shipping_Method{

//Constructor
public function __construct(){
	$this->id='oashippingcustom';
	$this->icon=apply_filters('woocommerce_cod_icon','');
	$this->method_title=__('Método de entrega personalizado','oa-shipping-custom');
	$this->method_description=__('Permite la entrega a sus clientes con un método personalizado.','oa-shipping-custom');
	$this->has_fields=false;

	//Load the settings.
	$this->init_form_fields();
	$this->init_settings();

	//Define user set variables
	$this->enabled=$this->get_option('enabled');
	$this->title=$this->get_option('title');

	add_action('woocommerce_update_options_shipping_'.$this->id,[$this,'process_admin_options']);
}

//Initialise Shipping Method Settings Form Fields
public function init_form_fields(){
	$this->form_fields=[
		'enabled'=>[
			'title'=>__('Activar/Desactivar','oa-shipping-custom'),
			'type'=>'checkbox',
			'label'=>__('Activar método de entrega personalizado','oa-shipping-custom'),
			'default'=>'yes'
		],
		'title'=>[
			'title'=>__('Título','oa-shipping-custom'),
			'type'=>'text',
			'description'=>__('Método de entrega personalizado.','oa-shipping-custom'),
			'default'=>__('OA Custom Shipping','oa-shipping-custom'),
		]
	];
}

//If we want to make the shipping method not available for specific conditions
public function is_available($package){
	//For example, we disable this shipping method when weight is greater than 10
	foreach($package['contents'] as $item_id=>$values){
		$_product=$values['data'];
		$weight=$_product->get_weight();
		if($weight>10){
			return false;
		}
	}
	return true;
}

//Calculate Shipping Rate
public function calculate_shipping($package = Array()){
	//Get the total weight and dimensions
	$weight=0;
	$dimensions=0;
	foreach($package['contents'] as $item_id=>$values){
		$_product=$values['data'];
		$weight=$weight+$_product->get_weight()*$values['quantity'];
		$dimensions=$dimensions+(($_product->length*$values['quantity'])*$_product->width*$_product->height);
	}

	//Calculate the cost according to the table
	switch($weight){
		case($weight<1):
			switch($dimensions){	
				case($dimensions<=1000):
					$cost=3;
				break;
				case($dimensions>1000):
					$cost=4;
				break;
			}
		break;
		case($weight>=1&&$weight<3):
			switch($dimensions){	
				case($dimensions<=3000):
					$cost=10;
				break;
			}
		break;
		case($weight>=3&&$weight<10):
			switch($dimensions){	
				case($dimensions<=5000):
					$cost=25;
				break;
				case($dimensions>5000):
					$cost=50;
				break;
			}
		break;
	}

	//Send the final rate to the user. 
	$this->add_rate([
		'id'=>$this->id,
		'label'=>$this->title,
		'cost'=>$cost
	]);
}

}