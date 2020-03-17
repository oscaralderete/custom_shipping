<?php
/*
Plugin Name: WooCommerce OA - Custom Shipping
Plugin URI: http://oscaralderete.com/
Description: Custom shipping method
Version: 1.0.0
Author: Oscar Alderete
Author URI: http://oscaralderete.com/
*/

if(!defined('ABSPATH')){exit;}

//Include our Shipping Class and register Shipping Method with WooCommerce
add_action('plugins_loaded','oa_shipping_custom_init',0);
function oa_shipping_custom_init(){
	//Check if WooCommerce is active
	if(!class_exists('WC_Shipping_Method')){return;}

	require 'class/OA_Shipping_Custom.php';

	add_filter('woocommerce_shipping_methods','oa_shipping_custom_addshipping');
	function oa_shipping_custom_addshipping($methods){
		$methods[]='OA_Shipping_Custom';
		return $methods;
	}
}

//Custom field
add_filter('woocommerce_checkout_fields','oa_shipping_custom_customfields');
function oa_shipping_custom_customfields($fields){
	/*$fields['billing']['shipping_date']=[
		'label'=>__('Día de entrega', 'oa-shipping-custom'),
		'type'=>'select',
		'required'=>true, 
		'class'=>array('form-row-wide','address-field','update_totals_on_change'),
		'clear'=>true,
		'options'=>[
			''=>_('Seleccionar día'),
			'option_1'=>'Territory 1',
			'option_2'=>'Territory 2'
		]
	];
	$fields['billing']['shipping_time']=[
		'label'=>__('Hora de entrega', 'oa-shipping-custom'),
		'type'=>'select',
		'required'=>true, 
		'class'=>array('form-row-wide','address-field','update_totals_on_change'),
		'clear'=>true,
		'options'=>[
			''=>_('Seleccionar hora'),
			'option_1'=>'Territory 1',
			'option_2'=>'Territory 2'
		]
	];*/
	$fields['billing']['shipping_datetime']=[
		'label'=>__('Día y hora de entrega', 'oa-shipping-custom'),
		'type'=>'text',
		'required'=>true, 
		'class'=>array('form-row-wide','address-field','update_totals_on_change'),
		'clear'=>true
	];

	return $fields;
}

//Register jquery and style on initialization
add_action('init','oa_shipping_custom_registration');
function oa_shipping_custom_registration(){
	wp_register_script('oa_shipping_custom_datetime',plugins_url('/js/jquery.datetimepicker.js',__FILE__),array(),'',$in_footer=true);
	wp_register_script('oa_shipping_custom_js',plugins_url('/js/scripts.js',__FILE__),array(),'11.0',$in_footer=true);
	wp_register_style('oa_shipping_custom_css',plugins_url('/css/styles.css',__FILE__),false,'1.0','all');
}
 
//Use the registered script and style above
add_action('wp_enqueue_scripts','oa_shipping_custom_enqueue');
function oa_shipping_custom_enqueue(){
	wp_enqueue_script('oa_shipping_custom_datetime');
	wp_enqueue_script('oa_shipping_custom_js');
}
add_action('get_footer','oa_shipping_custom_footer');
function oa_shipping_custom_footer(){
	wp_enqueue_style('oa_shipping_custom_css');
};

//
add_action('woocommerce_checkout_update_order_meta','oa_shipping_custom_orderupdate');
function oa_shipping_custom_orderupdate($order_id){
	if(!empty($_POST['billing']['shipping_datetime'])){
		update_post_meta($order_id,'Día y fecha de entrega',sanitize_text_field($_POST['billing']['shipping_datetime']));
	}
}