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
class Ets_mp_contact extends ObjectModel
{
    public $id_customer;
    public $id_seller;
    public $id_order;
    public $id_product;
    public $name;
    public $email;
    public $phone;   
    public static $definition = array(
		'table' => 'ets_mp_seller_contact',
		'primary' => 'id_contact',
		'multilang' => false,
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT),
            'id_seller' => array('type' => self::TYPE_INT),
            'id_order' => array('type'=>self::TYPE_INT),
            'id_product' => array('type'=>self::TYPE_INT),
            'name' => array('type'=>self::TYPE_STRING),
            'email' => array('type'=>self::TYPE_STRING),
            'phone' => array('type'=> self::TYPE_STRING),
        )
	);
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context= Context::getContext();
        $message_contact = Db::getInstance()->getRow('SELECT id_message,title,attachment,attachment_name,message FROM '._DB_PREFIX_.'ets_mp_seller_contact_message WHERE id_contact='.(int)$this->id.' ORDER BY id_message ASC');
        $this->title = isset($message_contact['title']) ? $message_contact['title']:'';
        $this->id_message = isset($message_contact['id_message']) ? $message_contact['id_message'] : 0;
        $this->attachment = isset($message_contact['attachment']) ? $message_contact['attachment'] : '';
        $this->attachment_name = isset($message_contact['attachment_name']) ? $message_contact['attachment_name'] : '';
        $this->message = isset($message_contact['message']) ? $message_contact['message'] :'';
	}
    public function delete()
    {
        if(parent::delete())
        {
            $messages = Db::getInstance()->executeS('SELECT id_message FROM '._DB_PREFIX_.'ets_mp_seller_contact_message WHERE id_contact='.(int)$this->id);
            if($messages)
            {
                foreach($messages as $message)
                {
                    $contact_message = new Ets_mp_contact_message($message['id_message']);
                        $contact_message->delete();
                }
            }
            return true;
        }
    }
    public function getTitle()
    {
        $sql = 'SELECT title FROM `'._DB_PREFIX_.'ets_mp_seller_contact_message` WHERE id_contact="'.(int)$this->id.'" ORDER BY id_message ASC';
        return Db::getInstance()->getValue($sql);
    }
    public function getMessages()
    {
        $messages = Db::getInstance()->executeS('SELECT cm.*,CONCAT(c.firstname," ",c.lastname) as customer_name,CONCAT(customer.firstname," ",customer.lastname) as seller_name, CONCAT(e.firstname," ",e.lastname) as employee_name,CONCAT(manager.firstname," ",manager.lastname) as manager_name FROM `'._DB_PREFIX_.'ets_mp_seller_contact_message` cm
        LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer = cm.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_seller=cm.id_seller)
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (seller.id_customer=customer.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'customer` manager ON (manager.id_customer=cm.id_manager)
        LEFT JOIN `'._DB_PREFIX_.'employee` e ON (e.id_employee = cm.id_employee)
        WHERE id_contact="'.(int)$this->id.'" AND id_message !="'.(int)$this->id_message.'" ORDER BY date_add DESC');
        return $messages;
    }
 }