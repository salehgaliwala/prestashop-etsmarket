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
class Ets_MarketPlaceImportModuleFrontController extends ModuleFrontController
{
    public $seller;
    public $_errors= array();
    public $_success='';
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
        if(!Configuration::get('ETS_MP_SELLER_ALLOWED_IMPORT_EXPORT_PRODUCTS'))
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
	}
    public function postProcess()
    {
        parent::postProcess();
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller,'products'))
            die($this->module->l('You do not have permission','import'));
        if(Tools::isSubmit('submitUploadImportProduct') && Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT'))
        {
            if(isset($_FILES['file_import_product']['name']) && isset($_FILES['file_import_product']['tmp_name']) && $_FILES['file_import_product']['tmp_name'] && $_FILES['file_import_product']['name'])
            {
                $imageFileType = Tools::strtolower(pathinfo($_FILES['file_import_product']['name'],PATHINFO_EXTENSION));
                if($imageFileType=='csv')
                {
                    if(move_uploaded_file($_FILES['file_import_product']['tmp_name'], _PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv'))
                    {
                        $this->_success = $this->module->l('File uploaded','import');
                    }
                    else
                        $this->_errors[] = $this->module->l('Sorry, there was an error while uploading your file.','import');
                }   
                else
                    $this->_errors[] = $this->module->l('File is not valid','import');
            }
            else
                $this->_errors[] = $this->module->l('File is null','import');
        }
        if(Tools::isSubmit('cancelSubmitImport'))
        {
            if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv'))
                @unlink(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv');
        }
        if(Tools::isSubmit('submitImportProduct') && Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT'))
        {
            ini_set('max_execution_time', 7200);
            if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv'))
            {
                $data_cols= array();
                $field_error = false;
                $col_name= (int)Tools::getValue('col_product_name');
                if(!in_array($col_name,$data_cols))
                    $data_cols[]= $col_name;
                else
                    $field_error = true;
                $col_image= (int)Tools::getValue('col_product_image');
                if(!in_array($col_image,$data_cols))
                    $data_cols[]= $col_image;
                else
                    $field_error = true;
                $col_quantity = (int)Tools::getValue('col_product_quantity');
                if(!in_array($col_quantity,$data_cols))
                    $data_cols[]= $col_quantity;
                else
                    $field_error = true;
                $col_price= (int)Tools::getValue('col_product_price');
                if(!in_array($col_price,$data_cols))
                    $data_cols[] = $col_price;
                else
                    $field_error=true;
                $col_description= (int)Tools::getValue('col_product_description');
                if(!in_array($col_description,$data_cols))
                    $data_cols[] = $col_description;
                else
                    $field_error=true;
                $col_description_short = (int)Tools::getValue('col_product_description_short');
                if(!in_array($col_description_short,$data_cols))
                    $data_cols[]= $col_description_short;
                else
                    $field_error=true;
                $col_link_rewrite = (int)Tools::getValue('col_product_link_rewrite');
                if(!in_array($col_link_rewrite,$data_cols)) 
                    $data_cols[] = $col_link_rewrite;
                else
                    $field_error=true;
                $col_category = (int)Tools::getValue('col_product_category');
                if(!in_array($col_category,$data_cols))
                    $data_cols[] = $col_category;
                else
                    $field_error=true;
                $col_default_category= (int)Tools::getValue('col_product_default_category');
                if(!in_array($col_default_category,$data_cols))
                    $data_cols[] = $col_default_category;
                else
                    $field_error=  true;
                $col_combination = (int)Tools::getValue('col_product_combination');
                if(!in_array($col_combination,$data_cols))
                    $data_cols[] = $col_combination;
                else
                    $field_error= true;
                $col_specific_price = (int)Tools::getValue('col_specific_price');
                if(!in_array($col_specific_price,$data_cols))
                    $data_cols[] = $col_specific_price;
                else
                    $field_error= true;
                if(!$field_error)
                {
                    $handle = fopen(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv', "r");
                    $row = 0;
                    if ($handle !== FALSE) {
                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                            if($row>=1)
                            {
                                if($data)
                                {
                                    foreach($data as &$value)
                                        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
                                    if($data && $data!= array(''))
                                    {
                                        $this->_importProduct($data,$col_name,$col_link_rewrite,$col_image,$col_quantity,$col_price,$col_category,$col_default_category,$col_combination,$col_description,$col_description_short,$col_specific_price,$row);
                                    }
                                }
                                   
                            }
                            $row++;
                            //preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[9]);
                        }
                    }
                    else    
                        $this->_errors[] = $this->module->l('Data is null','import');
                    fclose($handle);

                }
                else
                {
                        $this->_errors[] = $this->module->l('Some duplicated data columns are existing','import');
                }
                if(!$this->_errors)
                {
                    $this->_success = $this->module->l('Import successfully','import');
                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv');
                }
            }
        }
    }
    public function _importProduct($data,$col_name,$col_link_rewrite,$col_image,$col_quantity,$col_price,$col_category,$col_default_category,$col_combination,$col_description,$col_description_short,$col_specific_price,$row)
    {
        $languages = Language::getLanguages(false);
        $product = new Product();
        if(!isset($data[$col_name]) || (isset($data[$col_name]) && !$data[$col_name]))
            $this->_errors[] = $this->module->l('Name in row','import').' '.$row.' '.$this->module->l('is not required','import');
        if(isset($data[$col_name]) && $data[$col_name] && Validate::isCatalogName($data[$col_name]))
        {
            foreach($languages as $language)
            {
                $product->name[$language['id_lang']] = $data[$col_name];
            }
        }
        else
            $this->_errors[] = $this->module->l('Name in row','import').' '.$row.' '.$this->module->l('is not valid','import'); 
        if(!isset($data[$col_link_rewrite]) || (isset($data[$col_link_rewrite]) && !$data[$col_link_rewrite]))
            $this->_errors[] = $this->module->l('Link rewrite in row','import').' '.$row.' '.$this->module->l('is required','import');
        if(isset($data[$col_link_rewrite]) && $data[$col_link_rewrite] && Validate::isLinkRewrite($data[$col_link_rewrite]))
        {
            foreach($languages as $language)
            {
                if(Db::getInstance()->getValue('SELECT id_product FROM `'._DB_PREFIX_.'product_lang` WHERE link_rewrite="'.pSQL($data[$col_link_rewrite]).'"'))
                {
                    $maxId = Db::getInstance()->getValue('SELECT MAX(id_product) FROM `'._DB_PREFIX_.'product`');
                    $data[$col_link_rewrite] .= '-'.($maxId+1);
                }
                $product->link_rewrite[$language['id_lang']] = $data[$col_link_rewrite];
            }
        }
        else
        {
            $this->_errors[] = $this->module->l('Rewrite link in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        } 
        if(!isset($data[$col_default_category]) || (isset($data[$col_default_category]) && !$data[$col_default_category]))
            $this->_errors[] = $this->module->l('Category default in row','import').' '.$row.' '. $this->module->l('is required');
        if(isset($data[$col_default_category]) && $data[$col_default_category] && $this->_checkValidateCategory($data[$col_default_category]))
        {
            $product->id_category_default = (int)$data[$col_default_category];
        }
        else
          $this->_errors[] = $this->module->l('Default category in row','import').' '.$row.' '.$this->module->l('is not valid','import');     
        if(isset($data[$col_description]) && $data[$col_description] && !Validate::isCleanHtml($data[$col_description]))
            $this->_errors[] = $this->module->l('Description in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        elseif(isset($data[$col_description]))
        {
            foreach($languages as $language)
            {
                $product->description[$language['id_lang']] = $data[$col_description];
            }
        }
        if(isset($data[$col_description_short]) && $data[$col_description_short] && !Validate::isCleanHtml($data[$col_description_short]))
            $this->_errors[] = $this->module->l('Short description in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        elseif(isset($data[$col_description_short]))
        {
            foreach($languages as $language)
            {
                $product->description_short[$language['id_lang']] = $data[$col_description_short];
            }
        }
        if(isset($data[$col_price]) && $data[$col_price] && !Validate::isPrice($data[$col_price]))
            $this->_errors[] = $this->module->l('Price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        elseif(isset($data[$col_price]))
            $product->price= $data[$col_price];
        else
            $product->price= 0;
        if(isset($data[$col_quantity]) && $data[$col_quantity] && !Validate::isInt($data[$col_quantity]))
            $this->_errors[] = $this->module->l('Quantity in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        elseif($data[$col_quantity])
            $quantity= (int)$data[$col_quantity];
        else
            $quantity=0;
        if(isset($data[$col_combination]) && $data[$col_combination])
        {
            $combinations = Tools::jsonDecode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data[$col_combination]),true);
            if($combinations && is_array($combinations))
            {
                foreach($combinations as $combination)
                {
                    if(is_array($combination) && isset($combination['attributes']))
                    {
                        $attributes = $combination['attributes'];
                        if(!($attributes && is_array($attributes)))
                        {
                            $this->_errors[] = $this->module->l('Combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            break;
                        }
                    }
                    else{
                        $this->_errors[] = $this->module->l('Combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                        break;
                    }
                    if(isset($combination['specific_prices']) && $specific_prices = $combination['specific_prices'] )
                    {
                        $ok=true;
                        if(is_array($specific_prices))
                        {
                           foreach($specific_prices as $specific_price)
                           {
                                if(!(is_array($specific_price) && isset($specific_price['id_currency']) && isset($specific_price['id_group']) && isset($specific_price['id_customer']) && isset($specific_price['id_country']) && isset($specific_price['price']) && isset($specific_price['from_quantity']) && isset($specific_price['reduction']) && isset($specific_price['reduction_tax']) && isset($specific_price['reduction_type']) && isset($specific_price['from']) && isset($specific_price['to'])))
                                {
                                    $this->_errors[] = $this->module->l('Combination in row','import').' '.$row.' '.$this->module->l('is valid','import');
                                    $ok=false;
                                    break;
                                }
                           } 
                        }
                        else
                        {
                            $this ->_errors[] = $this->module->l('Combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            $ok= false;
                        }
                        if(!$ok)
                            break;
                    }
                }
            }
            else
                $this->_errors[] = $this->module->l('Combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        }
        if(isset($data[$col_image]) && $data[$col_image])
        {
            $images = explode(',',$data[$col_image]);
            if($images && is_array($images))
            {
                foreach($images as $image)
                {
                    if(Tools::strpos(trim($image),'http')!==0 && Tools::strpos(trim($image),'https')!==0)
                       $this->_errors[] =  $this->module->l('Image in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                }
            }
            else
                $this->_errors[] = $this->module->l('Image in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        }
        if(isset($data[$col_specific_price]) && $data[$col_specific_price])
        {
            $specific_prices = Tools::jsonDecode($data[$col_specific_price],true);
                                    
            if(is_array($specific_prices))
            {
               foreach($specific_prices as $specific_price)
               {               
                    if(!(is_array($specific_price) && isset($specific_price['id_currency']) && isset($specific_price['id_group']) && isset($specific_price['id_customer']) && isset($specific_price['id_country']) && isset($specific_price['price']) && isset($specific_price['from_quantity']) && isset($specific_price['reduction']) && isset($specific_price['reduction_tax']) && isset($specific_price['reduction_type']) && isset($specific_price['from']) && isset($specific_price['to'])))
                    {
                                                
                        $this->_errors[] = $this->module->l('Specific price in row','import').' '.$row.' '.$this->module->l('is valid','import');
                    }
               } 
            }
            else
                $this ->_errors[] = $this->module->l('Specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
        }
        if($this->seller->vacation_mode && $this->seller->vacation_type=='disable_product')
            $product->active=0;
        else
        {
            if($this->seller->auto_enabled_product=='yes')
                $product->active=1;
            elseif($this->seller->auto_enabled_product=='no')
                $product->active=0;
            elseif(Configuration::get('ETS_MP_SELLER_PRODUCT_APPROVE_REQUIRED'))
                $product->active=0;
            else
                $product->active=1;
        }
        if($this->_errors)
        {
            return false;
        }
        else
        {
            if($product->add())
            {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_product`(id_customer,id_product,approved,active) VALUES("'.(int)$this->seller->id_customer.'","'.(int)$product->id.'","'.(int)$product->active.'","'.(int)$product->active.'")');
                StockAvailable::setQuantity($product->id, 0, $quantity);
                if($data[$col_category])
                {
                   $categories = explode(',',$data[$col_category]);
                   foreach($categories as $id_category)
                   {
                        if($this->_checkValidateCategory($id_category))
                        {
                            if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'category_product` WHERE id_category="'.(int)$id_category.'" AND id_product="'.(int)$product->id.'"'))
                                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'category_product`(id_category,id_product) VALUES("'.(int)$id_category.'","'.(int)$product->id.'")');
                        }
                   } 
                   if(!in_array($product->id_category_default,$categories))
                   {
                        if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'category_product` WHERE id_category="'.(int)$product->id_category_default.'" AND id_product="'.(int)$product->id.'"'))
                            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'category_product`(id_category,id_product) VALUES("'.(int)$product->id_category_default.'","'.(int)$product->id.'")');
                   }
                }
                if(isset($data[$col_image]) && $data[$col_image])
                {
                    $images = explode(',',$data[$col_image]);
                    if($images)
                    {
                        $cover= false;
                        foreach($images as $image)
                        {
                            $image_class = new Image();
                            if(!$cover)
                            {
                                $image_class->cover=1;
                                $cover=1;
                            }
                            $image_class->id_product = $product->id;
                            if($image_class->add())
                            {
                                if(!$this->copyImg($product->id,$image_class->id,$image))
                                    $image_class->delete();
                            }
                        }
                    }
                    
                }
                if(isset($data[$col_specific_price]) && $data[$col_specific_price] && $specific_prices = Tools::jsonDecode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data[$col_specific_price]),true))
                {
                    if(is_array($specific_prices))
                    {
                        foreach($specific_prices as $specific_price)
                        {
                            $errors_specific_prices = array();
                            if(!Validate::isNegativePrice($specific_price['price']))
                                $errors_specific_prices[] = $this->module->l('Price of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isUnsignedInt($specific_price['from_quantity']))
                                $errors_specific_prices[] = $this->module->l('From quantity of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isPrice(($specific_price['reduction'])))
                                $errors_specific_prices[] = $this->module->l('Reduction of specific price in row','import').' '.$row.' '. $this->module->l('is not valid','import');
                            if(!Validate::isBool($specific_price['reduction_tax']))
                                $errors_specific_prices[] = $this->module->l('Reduction tax of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isReductionType($specific_price['reduction_type']))
                                $errors_specific_prices[] = $this->module->l('Reduction type of specific price in row','import').' '.$row.' '. $this->module->l('is not valid','import');
                            if(!Validate::isDateFormat($specific_price['from']))
                                $errors_specific_prices[] = $this->module->l('From of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isDateFormat($specific_price['to']))
                                $errors_specific_prices[] = $this->module->l('To of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isUnsignedId($specific_price['id_currency']))
                                $errors_specific_prices[] = $this->module->l('ID currency of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isUnsignedInt($specific_price['id_group']))
                                $errors_specific_prices[] = $this->module->l('ID group of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isUnsignedInt($specific_price['id_customer']))
                                $errors_specific_prices[] = $this->module->l('ID customer of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!Validate::isUnsignedInt($specific_price['id_country']))
                                $errors_specific_prices[] = $this->module->l('ID country of specific price in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                            if(!$errors_specific_prices)
                            {
                                $specificPrice = new SpecificPrice();
                                $specificPrice->id_shop_group=0;
                                $specificPrice->id_shop=0;
                                $specificPrice->id_cart=0;
                                $specificPrice->id_attribute_product =0;
                                $specificPrice->id_product= $product->id;
                                $specificPrice->id_specific_price_rule=0;
                                $specificPrice->id_group = (int)$specific_price['id_group'];
                                $specificPrice->id_country = (int)$specific_price['id_country'];
                                $specificPrice->id_currency = (int)$specific_price['id_currency'];
                                $specificPrice->id_customer = (int)$specific_price['id_customer'];
                                $specificPrice->price = (float)$specific_price['price'];
                                $specificPrice->from_quantity = (int)$specific_price['from_quantity'];
                                $specificPrice->reduction= (float)$specific_price['reduction'];
                                $specificPrice->reduction_tax = (int)$specific_price['reduction_tax'];
                                $specificPrice->reduction_type = $specific_price['reduction_type'];
                                $specificPrice->from = $specific_price['from'];
                                $specificPrice->to = $specific_price['to'];
                                $specificPrice->add();
                            }
                            else
                            {
                                $this->_errors = array_merge($this->_errors,$errors_specific_prices);
                            }
                        }
                    }
                }
                if(isset($data[$col_combination]) && $data[$col_combination] && $combinations = Tools::jsonDecode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '',$data[$col_combination]),true))
                {
                    if($combinations)
                    {
                        foreach($combinations as $combination)
                        {
                            if(is_array($combination) && isset($combination['attributes']))
                            {
                                $attributes = $combination['attributes'];
                                if($attributes && is_array($attributes))
                                {
                                    $combination_class= new Combination();
                                    $combination_class->quantity= isset($combination['quantity']) ? (int)$combination['quantity']:0;
                                    $combination_class->price= isset($combination['price']) ? (float)$combination['price']:0;
                                    if(Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_attribute` WHERE default_on=1 AND id_product='.(int)$product->id))
                                        $combination_class->default_on=0;
                                    else
                                        $combination_class->default_on =isset($combination['default_on']) ? $combination['default_on']:0 ;
                                    $combination_class->minimal_quantity=1;
                                    $combination_class->id_product= $product->id;
                                    if($combination_class->add())
                                    {
                                        $ok=false;
                                        foreach($attributes as $attribute)
                                        {
                                            if(isset($attribute['name']) && isset($attribute['name_group']) && isset($attribute['color']))
                                            {
                                                $sql = 'SELECT a.id_attribute FROM `'._DB_PREFIX_.'attribute` a
                                                INNER JOIN `'._DB_PREFIX_.'attribute_group` ag ON (ag.id_attribute_group=a.id_attribute_group)
                                                INNER JOIN `'._DB_PREFIX_.'attribute_group_shop` ags ON(ag.id_attribute_group=ags.id_attribute_group AND ags.id_shop="'.(int)$this->context->shop->id.'")
                                                LEFT JOIN `'._DB_PREFIX_.'ets_mp_attribute_group_seller` attribute_group_seller ON (ag.id_attribute_group=attribute_group_seller.id_attribute_group)
                                                LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (al.id_attribute= al.id_attribute ANd id_lang="'.(int)$this->context->language->id.'")
                                                LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (agl.id_attribute_group=ag.id_attribute_group AND agl.id_lang="'.(int)$this->context->language->id.'")
                                                WHERE attribute_group_seller.id_customer="'.(int)$this->seller->id_customer.'" AND al.name="'.pSQL($attribute['name']).'" AND a.color="'.pSQL($attribute['color']).'" AND agl.name="'.pSQL($attribute['name_group']).'"';
                                                $id_attribute = Db::getInstance()->getValue($sql);
                                                if(!$id_attribute)
                                                {
                                                    $sql = 'SELECT ag.id_attribute_group FROM `'._DB_PREFIX_.'attribute_group` ag
                                                    INNER JOIN `'._DB_PREFIX_.'attribute_group_shop` ags ON(ag.id_attribute_group=ags.id_attribute_group AND ags.id_shop="'.(int)$this->context->shop->id.'")
                                                    LEFT JOIN `'._DB_PREFIX_.'ets_mp_attribute_group_seller` attribute_group_seller ON (ag.id_attribute_group=attribute_group_seller.id_attribute_group)
                                                    LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (agl.id_attribute_group=ag.id_attribute_group AND agl.id_lang="'.(int)$this->context->language->id.'")
                                                    WHERE attribute_group_seller.id_customer="'.(int)$this->seller->id_customer.'" AND agl.name="'.pSQL($attribute['name_group']).'"';
                                                    if($id_attribute_group = Db::getInstance()->getValue($sql))
                                                    {
                                                        $attribute_class = new Attribute();
                                                        foreach($languages as $language)
                                                        {
                                                            $attribute_class->name[$language['id_lang']] = $attribute['name'];
                                                        }
                                                        $attribute_class->color= $attribute['color'];
                                                        $attribute_class->id_attribute_group = $id_attribute_group;
                                                        if($attribute_class->add())
                                                            $id_attribute = $attribute_class->id;
                                                    }
                                                    else
                                                    {
                                                        $attributeGroupClass = new AttributeGroup();
                                                        foreach($languages as $language)
                                                        {
                                                            $attributeGroupClass->name[$language['id_lang']] = $attribute['name_group'];
                                                            $attributeGroupClass->public_name [$language['id_lang']] = $attribute['name_group'];
                                                        }
                                                        if($attribute['color'])
                                                        {
                                                            $attributeGroupClass->group_type='color';
                                                            $attributeGroupClass->is_color_group=1;
                                                        }
                                                        else
                                                        {
                                                            $attributeGroupClass->group_type='select';
                                                            $attributeGroupClass->is_color_group=0;
                                                        }
                                                        if($attributeGroupClass->add())
                                                        {
                                                            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_attribute_group_seller`(id_attribute_group,id_customer) VALUES("'.(int)$attributeGroupClass->id.'","'.(int)$this->seller->id_customer.'")');
                                                            $attribute_class = new Attribute();
                                                            foreach($languages as $language)
                                                            {
                                                                $attribute_class->name[$language['id_lang']] = $attribute['name'];
                                                            }
                                                            $attribute_class->color= $attribute['color'];
                                                            $attribute_class->id_attribute_group = $attributeGroupClass->id;
                                                            if($attribute_class->add())
                                                                $id_attribute = $attribute_class->id;
                                                        }
                                                    }
                                                }
                                                if($id_attribute)
                                                {
                                                    $ok=true;
                                                    if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE id_attribute="'.(int)$id_attribute.'" AND id_product_attribute="'.(int)$combination_class->id.'"'))
                                                    {
                                                        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_combination`(id_product_attribute,id_attribute) VALUES("'.(int)$combination_class->id.'","'.(int)$id_attribute.'")');
                                                    }
                                                }
                                            }
                                            
                                        }
                                        if(!$ok)
                                            $combination_class->delete();
                                        else
                                        {
                                            StockAvailable::setQuantity((int)$product->id, (int)$combination_class->id, (int)$combination_class->quantity);
                                            if(isset($combination['specific_prices']) && $combination['specific_prices'] && $specific_prices = $combination['specific_prices'])
                                            {
                                                if(is_array($specific_prices))
                                                {
                                                    foreach($specific_prices as $specific_price)
                                                    {
                                                        $errors_specific_prices = array();
                                                        if(!Validate::isNegativePrice($specific_price['price']))
                                                            $errors_specific_prices[] = $this->module->l('Price of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isUnsignedInt($specific_price['from_quantity']))
                                                            $errors_specific_prices[] = $this->module->l('From quantity of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isPrice(($specific_price['reduction'])))
                                                            $errors_specific_prices[] = $this->module->l('Reduction of specific price of combination in row','import').' '.$row.' '. $this->module->l('is not valid','import');
                                                        if(!Validate::isBool($specific_price['reduction_tax']))
                                                            $errors_specific_prices[] = $this->module->l('Reduction tax of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isReductionType($specific_price['reduction_type']))
                                                            $errors_specific_prices[] = $this->module->l('Reduction type of specific price of combination in row','import').' '.$row.' '. $this->module->l('is not valid','import');
                                                        if(!Validate::isDateFormat($specific_price['from']))
                                                            $errors_specific_prices[] = $this->module->l('From of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isDateFormat($specific_price['to']))
                                                            $errors_specific_prices[] = $this->module->l('To of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isUnsignedId($specific_price['id_currency']))
                                                            $errors_specific_prices[] = $this->module->l('ID currency of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isUnsignedInt($specific_price['id_group']))
                                                            $errors_specific_prices[] = $this->module->l('ID group of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isUnsignedInt($specific_price['id_customer']))
                                                            $errors_specific_prices[] = $this->module->l('ID customer of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!Validate::isUnsignedInt($specific_price['id_country']))
                                                            $errors_specific_prices[] = $this->module->l('ID country of specific price of combination in row','import').' '.$row.' '.$this->module->l('is not valid','import');
                                                        if(!$errors_specific_prices)
                                                        {
                                                            $specificPrice = new SpecificPrice();
                                                            $specificPrice->id_shop_group=0;
                                                            $specificPrice->id_shop=0;
                                                            $specificPrice->id_cart=0;
                                                            $specificPrice->id_product_attribute = $combination_class->id;
                                                            $specificPrice->id_product= $combination_class->id_product;
                                                            $specificPrice->id_specific_price_rule=0;
                                                            $specificPrice->id_group = (int)$specific_price['id_group'];
                                                            $specificPrice->id_country = (int)$specific_price['id_country'];
                                                            $specificPrice->id_currency = (int)$specific_price['id_currency'];
                                                            $specificPrice->id_customer = (int)$specific_price['id_customer'];
                                                            $specificPrice->price = (float)$specific_price['price'];
                                                            $specificPrice->from_quantity = (int)$specific_price['from_quantity'];
                                                            $specificPrice->reduction= (float)$specific_price['reduction'];
                                                            $specificPrice->reduction_tax = (int)$specific_price['reduction_tax'];
                                                            $specificPrice->reduction_type = $specific_price['reduction_type'];
                                                            $specificPrice->from = $specific_price['from'];
                                                            $specificPrice->to = $specific_price['to'];
                                                            $specificPrice->add();
                                                        }
                                                        else
                                                        {
                                                            $this->_errors = array_merge($this->_errors,$errors_specific_prices);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    
                                }
                            }
                            
                        }
                    }
                }
                return true;
            }
            else
            {
                $this->_errors[] = $this->module->l('Add row','import').' '.$row.' '.$this->module->l('failed','import');
                return false;
            }
        }
    }
    public function copyImg($id_entity, $id_image = null, $url = '', $entity = 'products', $regenerate = true, $thumb = false)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));
        switch ($entity) {
            default:
            case 'products':
                if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '.jpg');
                }
                if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg');
                }
                $image_obj = new Image($id_image);
                $path = $image_obj->getPathForCreation();
                break;
            case 'Category':
                $entity = 'categories';
                $path = _PS_CAT_IMG_DIR_ . (int)$id_entity;
                if (is_file(_PS_TMP_IMG_DIR_ . 'category_mini_' . (int)$id_entity . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'category_mini_' . (int)$id_entity . '.jpg');
                }
                if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg');
                }
                break;
            case 'Manufacturer':
                $entity = 'manufacturers';
                $path = _PS_MANU_IMG_DIR_ . (int)$id_entity;
                if (is_file(_PS_TMP_IMG_DIR_ . 'manufacturer_mini_' . (int)$id_entity . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'manufacturer_mini_' . (int)$id_entity . '.jpg');
                }
                if (is_file(_PS_TMP_IMG_DIR_ . 'manufacturer_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'manufacturer_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg');
                }
                break;
            case 'Supplier':
                $entity = 'suppliers';
                $path = _PS_SUPP_IMG_DIR_ . (int)$id_entity;
                if (is_file(_PS_TMP_IMG_DIR_ . 'supplier_mini_' . (int)$id_entity . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'supplier_mini_' . (int)$id_entity . '.jpg');
                }
                if (is_file(_PS_TMP_IMG_DIR_ . 'supplier_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg')) {
                    @unlink(_PS_TMP_IMG_DIR_ . 'supplier_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg');
                }
                break;
            case 'stores':
                $path = _PS_STORE_IMG_DIR_ . (int)$id_entity;
                break;
        }
        $url = urldecode(trim($url));
        $parced_url = parse_url($url);
        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/' . implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = array();
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }
        if (!function_exists('http_build_url')) {
            if (version_compare(_PS_VERSION_, '1.6', '<'))
                include_once(_PS_MODULE_DIR_ . 'ets_marketplace/classes/http_build_url.php');
            else
                require_once(_PS_TOOL_DIR_ . 'http_build_url/http_build_url.php');
        }
        $url = http_build_url('', $parced_url);
        $orig_tmpfile = $tmpfile;
        $context = stream_context_create(array('http' => array('header' => 'User-Agent: Mozilla compatible')));
        if (self::copy($url, $tmpfile, $context)) {
            //Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);
                return false;
            }
            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            if (file_exists($path . '.jpg') && !$thumb)
                @unlink($path . '.jpg');
            if ($thumb)
                ImageManager::resize($tmpfile, $path . '_thumb.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
            else
                ImageManager::resize($tmpfile, $path . '.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
            $images_types = ImageType::getImagesTypes($entity, true);
            if ($regenerate) {
                //$path_infos = array();
//                $path_infos[] = array($tgt_width, $tgt_height, $path.'.jpg');
                foreach ($images_types as $image_type) {
                    if (version_compare(_PS_VERSION_, '1.7', '<'))
                        $formatted_small = ImageType::getFormatedName('small');
                    else
                        $formatted_small = ImageType::getFormattedName('small');
                    if (($thumb && $formatted_small != $image_type['name']))
                        continue;
                    //$tmpfile = self::get_best_path($image_type['width'], $image_type['height'], $path_infos);
                    if (file_exists($path . '-' . Tools::stripslashes($image_type['name']) . '.jpg'))
                        @unlink($path . '-' . Tools::stripslashes($image_type['name']) . '.jpg');

                    if (ImageManager::resize(
                        $tmpfile,
                        $path . '-' . Tools::stripslashes($image_type['name']) . '.jpg',
                        $image_type['width'],
                        $image_type['height'],
                        'jpg',
                        false,
                        $error,
                        $tgt_width,
                        $tgt_height,
                        5,
                        $src_width,
                        $src_height
                    )) {
                        if ($entity == 'products') {
                            if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '.jpg')) {
                                @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '.jpg');
                            }
                            if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg')) {
                                @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int)$id_entity . '_' . (int)Context::getContext()->shop->id . '.jpg');
                            }
                        }
                    }
                    if (in_array($image_type['id_image_type'], $watermark_types)) {
                        Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
                    }
                }
            }
        } else {
            @unlink($orig_tmpfile);
            return false;
        }
        @unlink($orig_tmpfile);
        return true;
    }
    public static function copy($source, $destination, $stream_context = null)
    {
        if (is_null($stream_context) && !preg_match('/^https?:\/\//', $source)) {
            return @copy($source, $destination);
        }
        return @file_put_contents($destination, self::file_get_contents($source, false, $stream_context));
    }
    public static function file_get_contents($url, $use_include_path = false, $stream_context = null, $curl_timeout = 5)
    {
        if ($stream_context == null && preg_match('/^https?:\/\//', $url)) {
            $stream_context = @stream_context_create(array('http' => array('timeout' => $curl_timeout)));
        }
        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => html_entity_decode($url),
                CURLOPT_USERAGENT => 'cURL Request',
                CURLOPT_SSL_VERIFYPEER => false,
            ));
            $content = curl_exec($curl);
            curl_close($curl);
            return $content;
        } elseif (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
            return Tools::file_get_contents($url, $use_include_path, $stream_context);
        } else {
            return false;
        }
    }
    public function _checkValidateCategory($id_category)
    {
        if(Configuration::get('ETS_MP_APPLICABLE_CATEGORIES')=='specific_product_categories' && Configuration::get('ETS_MP_SELLER_CATEGORIES'))
        {
            $seller_categories = explode(',',Configuration::get('ETS_MP_SELLER_CATEGORIES'));
        }
        $sql = 'SELECT c.id_category FROM `'._DB_PREFIX_.'category` c
        INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
        WHERE c.id_parent!=0 AND c.id_category = "'.(int)$id_category.'" AND c.is_root_category!=1 '.(isset($seller_categories) && $seller_categories ? ' AND c.id_category IN ('.explode(',',array_map('intval',$seller_categories)).')':'');
        return (int)Db::getInstance()->getValue($sql);
    }
    public function initContent()
	{
        parent::initContent();
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
                '_errors' => $this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success) :'',
                'html_content' => $this->_initContent(),
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/import.tpl');      
        else        
            $this->setTemplate('import_16.tpl'); 
    }
    public function _initContent()
    {
        if(!Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT'))
            return $this->module->displayWarning($this->module->l('You do not have permission to create new product','import'));
        if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv'))
        {
            $data = array();
            $handle = fopen(_PS_IMG_DIR_.'mp_seller/'.$this->seller->id.'.csv', "r");
            $row = 1;
            $datas = array();
            if ($handle !== FALSE) {
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    if($row>5)
                        break;
                    $data[0] = str_replace(array(chr(255),chr(254)),'',$data[0]);
                    $datas[] = $data;
                    $row++;
                }
            }
            fclose($handle);
            $this->context->smarty->assign(
                array(
                    'datas' => $datas,
                )
            );
            return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/import.tpl');
        }
        if(Configuration::get('ETS_MP_APPLICABLE_CATEGORIES')=='specific_product_categories' && Configuration::get('ETS_MP_SELLER_CATEGORIES'))
        {
            $seller_categories = explode(',',Configuration::get('ETS_MP_SELLER_CATEGORIES'));
        }
        $sql = 'SELECT c.id_category,cl.name FROM `'._DB_PREFIX_.'category` c
        INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category=cl.id_category AND cl.id_lang="'.(int)$this->context->language->id.'")
        WHERE c.id_parent!=0 AND c.is_root_category!=1 '.(isset($seller_categories) && $seller_categories ? ' AND c.id_category IN ('.implode(',',array_map('intval',$seller_categories)).')':'').' ORDER BY cl.name ASC';
        $categories = Db::getInstance()->executeS($sql);
        $this->context->smarty->assign(
            array(
                'link_sample' => $this->module->getBaseLink().'/modules/'.$this->module->name.'/sample-import-file.csv',
                'categories'=> $categories
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/import_upload.tpl');
    }
 }