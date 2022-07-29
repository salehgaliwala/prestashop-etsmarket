<?php
/**
 * 2007-2020 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 *  @author ETS-Soft <etssoft.jsc@gmail.com>
 *  @copyright  2007-2020 ETS-Soft
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

if (!defined('_PS_VERSION_'))
	exit;
class Ets_mp_contact_message extends ObjectModel
{
    public $id_contact;
    public $id_customer;
    public $id_seller;
    public $id_manager;
    public $id_employee;
    public $title;
    public $message;  
    public $read;
    public $customer_read;
    public $attachment; 
    public $attachment_name;
    public $date_add;
    public static $definition = array(
		'table' => 'ets_mp_seller_contact_message',
		'primary' => 'id_message',
		'multilang' => false,
		'fields' => array(
            'id_contact' => array('type' => self::TYPE_INT),
			'id_customer' => array('type' => self::TYPE_INT),
            'id_seller' => array('type' => self::TYPE_INT),
            'id_manager' => array('type' => self::TYPE_INT),
            'id_employee' => array('type'=>self::TYPE_INT),
            'title' => array('type'=>self::TYPE_STRING),
            'message' => array('type'=>self::TYPE_STRING),
            'attachment' => array('type'=>self::TYPE_STRING),
            'attachment_name' => array('type'=>self::TYPE_STRING),
            'read' => array('type'=> self::TYPE_INT),
            'customer_read' =>array('type'=> self::TYPE_INT),
            'date_add' => array('type'=> self::TYPE_DATE)
        )
	);
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context= Context::getContext();
	}
    public function delete()
    {
        if(parent::delete())
        {
            if($this->attachment && file_exists(_PS_ETS_MARKETPLACE_UPLOAD_DIR_.'mp_attachment/'.$this->attachment))
                @unlink(_PS_ETS_MARKETPLACE_UPLOAD_DIR_.'mp_attachment/'.$this->attachment);
                
        }
    }
 }