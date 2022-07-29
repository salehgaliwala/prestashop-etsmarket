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
class Ets_MarketPlaceMessagesModuleFrontController extends ModuleFrontController
{
    public $seller;
    public $_errors= array();
    public $_success ='';
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
        if($this->context->cookie->_success)
        {
            $this->_success = $this->context->cookie->_success;
            $this->context->cookie->_success='';
        }
	}
    public function postProcess()
    {
        parent::postProcess();
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission','messages'));
        if(Tools::isSubmit('downloadfile') && $id_contact = (int)Tools::getValue('id_contact'))
        {
            if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_contact` WHERE id_seller="'.(int)$this->seller->id.'" AND id_contact="'.(int)$id_contact.'"'))
                die($this->module->l('You do not have permission to download this attachment','message'));
            else
            {
                $attachment = Db::getInstance()->getRow('SELECT attachment,attachment_name FROM `'._DB_PREFIX_.'ets_mp_seller_contact_message` WHERE id_contact="'.(int)$id_contact.'" AND attachment!=""');
                if($attachment)
                {
                    $filepath =_PS_ETS_MARKETPLACE_UPLOAD_DIR_.'mp_attachment/'.$attachment['attachment'];
                    if(file_exists($filepath)){
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'. ($attachment['attachment_name'] ? : $attachment['attachment']).'"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($filepath));
                        flush(); // Flush system output buffer
                        readfile($filepath);
                        exit;
                    }
                    else
                        die($this->module->l('File attachment is null','message').$filepath);
                }
                else
                    die($this->module->l('File attachment is null','message'));
            }
        }
        if(Tools::getValue('del')=='yes' && $id_contact = (int)Tools::getValue('id_contact'))
        {
            if(Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_contact` WHERE id_contact="'.(int)$id_contact.'" AND id_seller="'.(int)$this->seller->id.'"'))
            {
                $contact = new Ets_mp_contact($id_contact);
                if($contact->delete())
                {
                    $this->context->cookie->_success = $this->module->l('Deleted successfully','messages');
                    Tools::redirect($this->context->link->getModuleLink($this->module->name,'messages',array('list'=>1)));
                }
                else
                    $this->_errors[] = $this->module->l('Delete message failed','messages');
                
            }
            else
                $this->_errors[] = $this->module->l('You do not have permission to delete this message','messages');
        }
        if(Tools::isSubmit('submitMessage') && $id_order = (int)Tools::getValue('id_order'))
        {
            $order = new Order($id_order);
            if(!$this->seller->checkHasOrder($id_order) || !Validate::isLoadedObject($order))
                die($this->module->l('You do not have permission','messages'));
            $customer = new Customer(Tools::getValue('id_customer'));
            if (!Validate::isLoadedObject($customer)) {
                $this->_errors[] = $this->module->l('The customer is invalid.','messages');
            } elseif (!$message = Tools::getValue('message'))
                $this->_errors[] = $this->module->l('The message cannot be blank.','messages');
            elseif(Tools::strlen($message) > 1600)
                $this->_errors[] = $this->module->l('Message is too large. It must be between 0 and 1600 chars.');
            else {
                /* Get message rules and and check fields validity */
                $rules = call_user_func(array('Message', 'getValidationRules'), 'Message');
                foreach ($rules['required'] as $field) {
                    if (($value = Tools::getValue($field)) == false && (string) $value != '0') {
                        if (!Tools::getValue('id_' . $this->table) || $field != 'passwd') {
                            $this->_errors[] = $field.' '.$this->module->l('is required','messages');
                        }
                    }
                }
                foreach ($rules['size'] as $field => $maxLength) {
                    if (Tools::getValue($field) && Tools::strlen(Tools::getValue($field)) > $maxLength) {
                        $this->_errors[] = $field.' '.$this->module->l('is too long','messages').' '. $maxLength;
                    }
                }
                foreach ($rules['validate'] as $field => $function) {
                    if (Tools::getValue($field)) {
                        if (!Validate::$function(htmlentities(Tools::getValue($field), ENT_COMPAT, 'UTF-8'))) {
                            $this->_errors[] = $field. ' '.$this->module->l(' is not valid','messages');
                        }
                    }
                    unset($function);
                }
                if (!count($this->_errors)) {
                    //check if a thread already exist
                    $id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($customer->email, $order->id);
                    if (!$id_customer_thread) {
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int) $order->id_customer;
                        $customer_thread->id_shop = (int) $this->context->shop->id;
                        $customer_thread->id_order = (int) $order->id;
                        $customer_thread->id_lang = (int) $this->context->language->id;
                        $customer_thread->email = $customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();
                    } else {
                        $customer_thread = new CustomerThread((int) $id_customer_thread);
                    }
                    $customer_message = new CustomerMessage();
                    $customer_message->id_customer_thread = $customer_thread->id;
                    $customer_message->id_employee = 1;
                    $customer_message->message = $message;
                    $customer_message->private = Tools::getValue('visibility');
                    $add = true;
                    if (!$customer_message->add()) {
                    {
                        $add = false;
                        $this->_errors[] = $this->module->l('An error occurred while saving the message.','messages');
                    }
                    } elseif ($customer_message->private) {
                        $this->_success = $this->module->l('Message sent successfully.','messages');
                    } else {
                        $message = $customer_message->message;
                        if (Configuration::get('PS_MAIL_TYPE', null, null, $order->id_shop) != Mail::TYPE_TEXT) {
                            $message = Tools::nl2br($customer_message->message);
                        }
                        //$orderLanguage = new Language((int) $order->id_lang);
                        $varsTpl = array(
                            '{lastname}' => $customer->lastname,
                            '{firstname}' => $customer->firstname,
                            '{id_order}' => $order->id,
                            '{order_name}' => $order->getUniqReference(),
                            '{message}' => $message,
                        );
                        $subjects = array(
                            'translation' => $this->module->l('New message regarding your order','messages'),
                            'origin'=> 'New message regarding your order',
                            'specific' => 'messages'
                        );
                        if (
                            !Ets_marketplace::sendMail('order_merchant_comment',$varsTpl,$customer->email,$subjects,$customer->firstname.' '.$customer->lastname)
                        ) {
                            $this->_errors[] = $this->module->l('An error occurred while sending an email to the customer.','messages');
                        }
                    }
                    if($add)
                    {
                        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_customer_message`(id_customer,id_manager,id_customer_message) VALUES("'.(int)$this->seller->id_customer.'","'.($this->context->customer->id!=$this->seller->id_customer ? $this->context->customer->id:0).'","'.(int)$customer_message->id.'")');
                    }
                    
                }
            }
        }
        if(Tools::isSubmit('submitMessage') && $id_contact = (int)Tools::getValue('id_contact'))
        {
            $contact = new Ets_mp_contact($id_contact);
            if(!Validate::isLoadedObject($contact) || $contact->id_seller!= $this->seller->id)
                die($this->module->l('You do not have permission','messages'));
            if (!$message = Tools::getValue('message'))
                $this->_errors[] = $this->module->l('The message cannot be blank.','messages');
            if($message && !Validate::isCleanHtml($message))
                $this->_errors[] = $this->module->l('Message is not valid','messages');
            if(!$this->_errors)
            {
                $contact_message = new Ets_mp_contact_message();
                $contact_message->id_seller = (int)$this->seller->id;
                $contact_message->id_manager = $this->seller->id_customer!= $this->context->customer->id ? (int)$this->context->customer->id:0;
                $contact_message->message = $message;
                $contact_message->id_contact = $id_contact;
                $contact_message->read= 1;
                if($contact_message->add())
                {
                    $this->_success = $this->module->l('Message was sent successfully','messages');
                    if(Configuration::get('ETS_MP_EMAIL_NEW_CONTACT'))
                    {
                        if($contact->id_customer)
                        {
                            $customer = new Customer($contact->id_customer);
                            $customer_email = $customer->email;
                            $customer_name = $customer->firstname.' '.$customer->lastname;
                        }
                        else
                        {
                            $customer_email =$contact->email;
                            $customer_name = $contact->name;
                        }
                        if($customer_email)
                        {
                            $this->context->smarty->assign(
                                array(
                                    'message' => $message,  
                                )
                            );
                            $template_vars = array(
                                '{content_message}' => $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/content_message.tpl'),
                                '{customer_name}' => $customer_name,
                                '{seller_name}' => $this->seller->id_customer== $this->context->customer->id ? $this->seller->seller_name: $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                '{message_title}' => $contact->getTitle(),
                                '{link_reply}' => $this->context->link->getModuleLink($this->module->name,'contactseller',array('viewmessage'=>1,'id_contact'=> $id_contact)),
                            );
                            $subjects = array(
                                'translation' => $this->module->l('A new contact message from','messages'),
                                'origin'=> 'A new contact message from',
                                'specific' => 'messages'
                            );
                            Ets_marketplace::sendMail('seller_reply',$template_vars,$customer_email,$customer_name);
                        }
                    }
                }    
                else
                    $this->_errors[] = $this->module->l('An error occurred while saving the message.','messages');
            }    
        }
    }
    public function initContent()
	{
		parent::initContent();
        $this->context->smarty->assign(
            array(
                '_errors' =>$this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success):'',
            )
        );
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false,
                'html_content' => $this->_initContent(),
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/messages.tpl');      
        else        
            $this->setTemplate('messages_16.tpl'); 
    }
    public function _initContent()
    {
        if(Tools::isSubmit('viewmessage') && $id_order=Tools::getValue('id_order'))
        {
            $order = new Order($id_order);
            if(!$this->seller->checkHasOrder($id_order) || !Validate::isLoadedObject($order))
                die($this->module->l('You do not have permission','messages'));
            $id_customer_thread = (int)Db::getInstance()->getValue('SELECT id_customer_thread FROM `'._DB_PREFIX_.'customer_thread` WHERE id_order="'.(int)$id_order.'" ');
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'customer_message` SET `read`=1 WHERE id_customer_thread ="'.(int)$id_customer_thread.'"');
            $messages = $this->module->getCustomerMessagesOrder($order->id_customer,$order->id,10);
            $this->context->smarty->assign(
                array(
                    'order'=>$order,
                    'messages'=> $messages,
                    'customer' => new Customer($order->id_customer),
                    'customer_thread_message' => CustomerThread::getCustomerMessages($order->id_customer, null, $order->id),
                    'orderMessages' => OrderMessage::getOrderMessages($order->id_lang),
                )            
            );
            
            return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/order_messages.tpl');
        }
        if(Tools::isSubmit('viewmessage') && $id_contact= Tools::getValue('id_contact'))
        {
            $contact = new Ets_mp_contact($id_contact);
            if(!Validate::isLoadedObject($contact) || $contact->id_seller!=$this->seller->id)
                die($this->module->l('You do not have permission','messages'));                      
            if($contact->id_customer)
            {
                $customer = new Customer($contact->id_customer);
                $contact->name = $customer->firstname.' '.$customer->lastname;
                $contact->email = $customer->email;
            }
            if($contact)
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_contact_message` SET `read`=1 WHERE id_contact="'.(int)$id_contact.'"');
                $messages = $contact->getMessages();
                if($contact->id_product)
                {
                    $id_image = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$contact->id_product.' AND cover=1');
                    if(!$id_image)
                        $id_image = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$contact->id_product);
                    $product = new Product($contact->id_product,false,$this->context->language->id);
                    if($this->module->is17)
                        $type_image = ImageType::getFormattedName('small');
                    else
                        $type_image = ImageType::getFormatedName('small');
                    $this->context->smarty->assign(
                        array(
                            'product' =>$product,
                            'link_image' => $id_image ? $this->context->link->getImageLink($product->link_rewrite,$id_image,$type_image):'',
                        )
                    );
                }
                if($contact->id_order)
                {
                    $this->context->smarty->assign(
                        array(
                            'order_message' => new Order($contact->id_order),
                        )
                    );
                }
                $this->context->smarty->assign(
                    array(
                        'contact' => $contact,
                        'messages' => $messages,
                        'seller_page' => true, 
                    )
                );
                return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/message.tpl');
            }
            
        }
        $fields_list = array(
            'reference'=>array(
                'title' => $this->module->l('Order ref','orders'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
            ),
            'author'=> array(
                'title'=> $this->module->l('Contact name','messages'),
                'type'=> 'text',
                'sort' => false,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'message' => array(
                'title'=> $this->module->l('Message','messages'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'date_add' => array(
                'title' => $this->module->l('Date','messages'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
        );
        $show_resset = false;
        $filter = "";
        if(trim(Tools::getValue('reference')))
        {
            $filter .= ' AND reference LIKE "%'.pSQL(trim(Tools::getValue('reference'))).'%"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('message')))
        {
            $filter .=' AND message LIKE "%'.pSQL(trim(Tools::getValue('message'))).'%"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('author')))
        {
            $filter .=' AND (seller_min_name LIKE "%'.pSQL(trim(Tools::getValue('author'))).'%" || (customer_min_name LIKE "%'.Tools::getValue('author').'%" && id_employee_min=0) || employee_min_name LIKE "%'.Tools::getValue('author').'%"  )';
            $show_resset=true;
        }
        if(trim(Tools::getValue('date_add_min')))
        {
            $filter .=' AND date_add >= "'.pSQL(Tools::getValue('date_add_min')).' 00:00:00"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('date_add_max')))
        {
            $filter .=' AND date_add <= "'.pSQL(Tools::getValue('date_add_max')).' 23:59:59"';
            $show_resset = true;
        }
        $sort = "";
        if(Tools::getValue('sort','date_add'))
        {
            switch (Tools::getValue('sort','date_add')) {
                case 'date_add':
                    $sort .='date_add';
                    break;
                case 'reference':
                    $sort .='reference';
                    break;
                case 'message':
                    $sort .='message';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                    $sort .= ' '.trim($sort_type); 
        }
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->module->_getOrderMessages($filter,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url =$this->context->link->getModuleLink($this->module->name,'messages',array('list'=>true, 'page'=>'_page_')).$this->module->getFilterParams($fields_list,'ms_message');
        $paggination->limit =  10;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $messages = $this->module->_getOrderMessages($filter, $start,$paggination->limit,$sort,false);
        if($messages)
        {
            foreach($messages as &$message)
            {               
                if(!$message['id_contact'])
                {
                    $message['child_view_url'] = $this->context->link->getModuleLink($this->module->name,'messages',array('viewmessage'=>1,'id_order'=>$message['id_order']));
                    $message['view_order_url'] = $this->context->link->getModuleLink($this->module->name,'orders',array('id_order'=>$message['id_order']));
                    $message['action_edit'] = true;
                }
                else
                {
                    $message['child_view_url'] = $this->context->link->getModuleLink($this->module->name,'messages',array('viewmessage'=>1,'id_contact'=>$message['id_contact']));
                    $message['view_order_url'] = '';
                    $message['action_edit'] = true;
                }
                if(Tools::strlen($message['message'])>135)
                {
                    $message['message'] = Tools::substr($message['message'],0,135).'...';
                } 
                if($message['id_employee_min'])
                {
                    if($message['manager_min_name'])
                        $message['author'] = $message['manager_min_name'].' ('.$this->module->l('Seller manager','messages').')';
                    elseif($message['seller_min_name'])
                        $message['author'] = $message['seller_min_name'].' ('.$this->module->l('Seller','messages').')';
                    else
                        $message['author'] = $message['employee_min_name'].' ('.$this->module->l('Admin','messages').')';
                }
                else
                    $message['author'] = $message['customer_name'].' ('.$this->module->l('Customer','messages').')';; 
                
                if($message['id_employee'] || $message['id_seller'])
                {
                    $message['read']=1; 
                    if($message['manager_name'])
                        $message['author_message'] = $message['manager_name'].' ('.$this->module->l('Seller manager','messages').')';
                    elseif($message['seller_name'])
                        $message['author_message'] = $message['seller_name'].' ('.$this->module->l('Seller','messages').')';
                    else
                        $message['author_message'] = $message['employee_name'].' ('.$this->module->l('Admin','messages').')';
                }
                else
                    $message['author_message'] = $message['customer_name']; 
                $message['message'] = '<'.'b'.'>'.$message['title_contact'].'<'.'/'.'b'.'><'.'/br'.'>'.$message['message'].'<'.'br'.'>'.($message['author_message'] ? ' <b>'. $this->module->l('Last replied by:','messages').' '.$message['author_message'].'</'.'b'.'>':'');     
            }
        }
        $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','messages');
        $paggination->style_links = $this->module->l('links','messages');
        $paggination->style_results = $this->module->l('results','messages');
        $listData = array(
            'name' => 'ms_message',
            'actions' => array('view','vieworder','delete'),
            'currentIndex' => $this->context->link->getModuleLink($this->module->name,'messages',array('list'=>1)),
            'identifier' => 'id_contact',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->module->l('Messages','messages'),
            'fields_list' => $fields_list,
            'field_values' => $messages,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'ms_message'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','date_add'),
            'show_add_new'=> false,
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return $this->module->renderList($listData);
    }
    
 }