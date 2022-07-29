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
class Ets_MarketPlaceProductsModuleFrontController extends ModuleFrontController
{
    public $product_tabs=array();
    public $product;
    public $errors = array();
    public $seller;
    public $combinations;
    public $seller_product_information =array();
    public $seller_product_types = array();
    public function __construct()
	{
		parent::__construct();                
        $this->display_column_right=false;
        $this->display_column_left =false;
        $this->seller_product_information = explode(',',Configuration::get('ETS_MP_SELLER_ALLOWED_INFORMATION_SUBMISSION'));
        $this->seller_product_types = explode(',',Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'));
        $this->product_tabs = array(
            array(
                'tab' => 'BaseSettings',
                'name' => $this->module->l('Base settings','products'),
            ),
            array(
                'tab'=> 'Quantities',
                'name' => $this->module->l('Quantities','products'),
            ),
            array(
                'tab'=>'Combinations',
                'name' => $this->module->l('Combinations','products'),
            ),
            array(
                'tab'=>'Shipping',
                'name' => $this->module->l('Shipping','products'),
            )
        );
        if(in_array('specific_price',$this->seller_product_information))
            $this->product_tabs[] = array(
                'tab'=>'Price',
                'name' => $this->module->l('Pricing','products'),
            );
        if(in_array('seo',$this->seller_product_information))
            $this->product_tabs[] = array(
                'tab'=>'Seo',
                'name' => $this->module->l('SEO','products'),
            );
        $this->product_tabs[] = array(
            'tab'=>'Options',
            'name' => $this->module->l('Options','products'),
        );
        $this->seller = $this->module->_getSeller(true);
        if($this->seller)
        {
            if(($id_product = Tools::getValue('id_product')) && !Tools::isSubmit('ets_mp_submit_mp_front_products'))
            {
                if(($seller_product = $this->seller->checkHasProduct($id_product)) && isset($seller_product['id_product']) && ($id_product = $seller_product['id_product']))
                    $this->product = new Product($id_product);
                else
                    die($this->module->l('You do not have permission','products'));
                $this->product->loadStockData();
            }
            else
                $this->product = new Product();
        }
        else
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
	}
    public function postProcess()
    {
        parent::postProcess();
        $this->_validateFormSubmit();
        if(!$this->context->customer->logged || !$this->seller)
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission to access this page','products'));
        if(Tools::isSubmit('submitProductAttachment'))
        {
            $this->_submitProductAttachment();
        }
        if(Tools::isSubmit('duplicatemp_front_products')&& ($id_product = Tools::getValue('id_product')))
        {
            $product = new Product($id_product);
            if(!Validate::isLoadedObject($product) || !$this->seller->checkHasProduct($id_product))
                $this->errors[] = $this->module->l('Product is not valid','products');
            elseif($id_new = Ets_mp_defines::getInstance()->processDuplicate($id_product,$this->errors,$this->seller))
                Tools::redirect($this->context->link->getModuleLink($this->module->name,'products',array('editmp_front_products'=>1,'id_product'=>$id_new)));
        }
        if(Tools::getValue('bulk_action') && ($id_products = Tools::getValue('bulk_action_selected_products')) && is_array($id_products))
        {
            $this->_submitBulkActionProduct($id_products);
        }
        if(Tools::getValue('action')=='updateImageOrdering' && $images=Tools::getValue('images'))
        {
            $this->updateImageOrdering($images);
        }
        if(Tools::isSubmit('export'))
            $this->_processExportProduct();
        if(Tools::isSubmit('downloadfilesample'))
            $this->_processExportProduct(true);
        if(Tools::isSubmit('submitDeleteSpecificPrice') && $id_specific_price = Tools::getValue('id_specific_price'))
        {
            $this->submitDeleteSpecificPrice($id_specific_price);
        }
        if(Tools::isSubmit('submitSavePecificPrice'))
        {
            if($this->_checkValidateSpecificPrice())
            {
                $this->_submitSavePecificPrice();
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->errors),
                        )
                    )
                );
            }
        }
        if(Tools::isSubmit('submitDeleteProductAttribute') && $id_product_attribute = Tools::getValue('id_product_attribute'))
        {
            $this->_submitDeleteProductAttribute($id_product_attribute);
        }
        if(Tools::isSubmit('submitDeletecombinations') && $attributes= Tools::getValue('list_product_attributes'))
        {
            $this->submitDeletecombinations($attributes);
            
        }
        if(Tools::isSubmit('submitSavecombinations') && $attributes= Tools::getValue('list_product_attributes'))
        {
            $this->_submitSavecombinations($attributes);
        }
        if(Tools::isSubmit('submitSaveProduct'))
        {
            $this->_processSaveProduct();
        }
        if(Tools::isSubmit('submitCreateCombination'))
        {
            $this->_submitCreateCombination();
        }
        if(Tools::isSubmit('deletefileproduct') && $this->product->id)
        {
            $downloads= Db::getInstance()->executeS('SELECT id_product_download FROM `'._DB_PREFIX_.'product_download` WHERE id_product='.(int)$this->product->id);
            if($downloads)
            {
                foreach($downloads as $download)
                {
                    $obj = new ProductDownload($download['id_product_download']);
                    $obj->delete(true);
                } 
            }
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->module->l('Deleted successfully','products'),
                    )
                )
            );
        }
        if(Tools::isSubmit('downloadfileproduct') && $this->product->id)
        {
            $this->downloadfileproduct();
        }
        if(Tools::isSubmit('getFromImageProduct') && $id_image=Tools::getValue('id_image'))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'form_image' => $this->_getFromImageProduct($id_image),
                    )
                )
            );
        }
        if(Tools::isSubmit('submitImageProduct') && $id_image= Tools::getValue('id_image'))
        {
            $this->_submitSaveImageProduct($id_image);
        }
        if(Tools::isSubmit('deleteImageProduct') && $id_image=Tools::getValue('id_image'))
        {
            $this->_submitdeleteImageProduct($id_image);
        }
        if(Tools::isSubmit('submitUploadImageSave'))
        {
            $this->submitUploadImageSave();
        }
        if(Tools::isSubmit('getPriceInclTax'))
        {
            $this->getPriceInclTax();
        }
        if(Tools::isSubmit('getPriceExclTax'))
        {
            $this->getPriceExclTax();
        }
        if(Tools::isSubmit('getFormSpecificPrice'))
        {
            $this->getFormSpecificPrice();
        }
        if(Tools::getValue('del')=='yes' && $id_product = Tools::getValue('id_product'))
        {
            $this->_submitDeleteProduct();
        }
        if(Tools::isSubmit('change_enabled') && $id_product = Tools::getValue('id_product'))
        {
            $this->_submitChangeEnabled($id_product);
        }
        if(Tools::isSubmit('refreshProductSupplierCombinationForm') && ($id_supplier = Tools::getValue('id_supplier')))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'html_form' => $this->refreshProductSupplierCombinationForm($id_supplier),
                    )
                )
            );
        }
    }
    public function submitDeletecombinations($attributes)
    {
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product_attribute IN ('.implode(',',array_map('intval',$attributes)).') AND id_product='.(int)$this->product->id);
        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_supplier WHERE id_product_attribute IN ('.implode(',',array_map('intval',$attributes)).') AND id_product='.(int)$this->product->id);
        die(
            Tools::jsonEncode(
                array(
                    'success' => $this->module->l('Deleted successfully','products'),
                    'list_combinations' => $this->displayListCombinations(),
                    'html_form_supplier' => $this->renderFormSupplier(),
                )
            )
        );
    }
    public function _submitDeleteProductAttribute($id_product_attribute)
    {
        $proudctAttribute = new Combination($id_product_attribute);
        if($this->seller->checkHasProduct($proudctAttribute->id_product) && $proudctAttribute->id_product== $this->product->id)
        {
            if($proudctAttribute->delete())
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->module->l('Deleted successfully','products'),
                            'html_form_supplier' => $this->renderFormSupplier(),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->l('An error occurred while deleting the attribute','products'),
                        )  
                    )
                );
            }    
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->l('You do not have permission','products'),
                    )  
                )
            );
        }
    }
    public function submitDeleteSpecificPrice($id_specific_price)
    {
        $specific_price = new SpecificPrice($id_specific_price);
        if(Validate::isLoadedObject($specific_price) && $specific_price->id_product== $this->product->id)
        {
            if($specific_price->delete())
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->module->l('Deleted successfully','products'),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->l('An error occurred while deleting the specific price','products'),
                        )
                    )
                );
            }
            
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->l('Specific price is not valid','products'),
                    )
                )
            );
        }
    }
    public function _submitBulkActionProduct($id_products)
    {
        $id_product = $id_products[0];
        $errors = array();
        if($seller_product = $this->seller->checkHasProduct($id_product))
        {
            switch (Tools::getValue('bulk_action')) {
              case 'activate_all':
                    if($this->seller->vacation_mode && $this->seller->vacation_type=='disable_product')
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'error' => sprintf($this->module->l('You do not have permission to activate this product #%d','products'),$id_product),
                                )
                            )
                        );
                    }
                    $product = new Product($id_product);
                    if(Validate::isLoadedObject($product) &&  $seller_product['approved'] && !$product->active)
                    {
                        $product->active=1;
                        if(!$product->update())
                            $errors[] = sprintf($this->module->l('An error occurred while saving the product(#%d)','products'),$id_product);
                    }elseif(!Validate::isLoadedObject($product))
                        $errors[] = sprintf($this->module->l('Product(#%d) is not valid','products'),$id_product);
                    $this->context->cookie->success_message = $this->module->l('Product(s) successfully activated.','products');
                break;
              case 'deactivate_all':
                    if($this->seller->vacation_mode && $this->seller->vacation_type=='disable_product')
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'error' => sprintf($this->module->l('You do not have permission to deactivate this product #%d','products'),$id_product),
                                )
                            )
                        );
                    }
                    $product = new Product($id_product);
                    if(Validate::isLoadedObject($product) &&  $product->active)
                    {
                        $product->active=0;
                        if(!$product->update())
                            $errors[] = sprintf($this->module->l('An error occurred while saving the product(#%d)','products'),$id_product);
                    }
                    elseif(!Validate::isLoadedObject($product))
                        $errors[] = sprintf($this->module->l('Product(#%d) is not valid','products'),$id_product);
                    $this->context->cookie->success_message = $this->module->l('Product(s) successfully deactivated.','products');
              break;
              case 'duplicate_all':
                Ets_mp_defines::getInstance()->processDuplicate($id_product,$errors,$this->seller);
                if($errors)
                {
                    $errors[0] = sprintf($this->module->l('An error occurred while duplicating the product(#%d) : %s','products'),$id_product,$errors[0]);
                }
                $this->context->cookie->success_message = $this->module->l('Product(s) successfully duplicated.','products');
              break;
              case 'delete_all':
                $product = new Product($id_product);
                if(Validate::isLoadedObject($product))
                {
                    if(!$product->delete())
                        $errors[] = sprintf($this->module->l('An error occurred while deleting the product(#%d)','products'),$id_product);
                }
                else
                    $errors[] = sprintf($this->module->l('Product(#%d) is not valid','products'),$id_product);
                $this->context->cookie->success_message = $this->module->l('Product(s) successfully deleted.','products');
              break;
            } 
            
        }
        else
        {
            $errors[] = sprintf($this->module->l('You do not have permission to edit this product #%d','products'),$id_product);
        }
        if($errors)
        {
            $this->context->cookie->success_message='';
            die(
                Tools::jsonEncode(
                    array(
                        'error' => $errors[0],
                    )
                )
            );
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'result' => 'ok',
                    )
                )
            );
        }
    }
    public function updateImageOrdering($images)
    {
        foreach($images as $position=>$id_image)
        {
            $image_class = new Image($id_image);
            if($image_class->id_product== $this->product->id)
            {
                $image_class->position = (int)$position;
                $image_class->update();
            }
            else
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->l('You do not permission update this image'),
                    )
                )
            );
        }
        die(
            Tools::jsonEncode(
                array(
                    'success' => $this->module->l('Updated position successfully','products'),
                )
            )
        );
    }
    public function _submitSavecombinations($attributes)
    {
        $data = Tools::getValue('product_combination_bulk');
        if($data['quantity'] && !Validate::isInt($data['quantity']))
            $this->errors[] = $this->module->l('Quantity is not valid','products');
        if($data['cost_price'] && !Validate::isPrice($data['cost_price']))
            $this->errors[] = $this->module->l('Cost price is not valid','products');
        if($data['impact_on_price_te'] && !Validate::isPrice($data['impact_on_price_te']))
            $this->errors[] = $this->module->l('Impact on price is not valid','products');
        if($data['impact_on_weight'] && !Validate::isFloat($data['impact_on_weight']))
            $this->errors[] = $this->module->l('Impact on weight is not valid','products');
        if($data['date_availability'] && !Validate::isDate($data['date_availability']))
            $this->errors[] = $this->module->l('Availability date is not valid','products');
        if($data['reference'] && !Validate::isReference($data['reference']))
            $this->errors[] = $this->module->l('Reference is not valid','products');
        if($data['minimal_quantity'] && !Validate::isUnsignedInt($data['minimal_quantity']))
            $this->errors[] = $this->module->l('Minimum quantity is not valid','products');
        if($this->module->is17)
            if($data['low_stock_threshold'] && !Validate::isInt($data['low_stock_threshold']))
                $this->errors[] = $this->module->l('Low stock level is not valid','products');
        if(!$this->errors)
        {
            foreach($attributes as  $id_product_attribute)
            {
                $combination = new Combination($id_product_attribute);
                if($combination->id_product==$this->product->id)
                {
                    $combination->quantity = (int)$data['quantity'];
                    $combination->minimal_quantity = (int)$data['minimal_quantity'];
                    $combination->cost_price = (float)$data['cost_price'];
                    $combination->price= (float)$data['impact_on_price_te'];
                    $combination->weight = (float)$data['impact_on_weight'];
                    $combination->available_date = $data['date_availability'];
                    $combination->reference = $data['reference'];
                    if($this->module->is17)
                    {
                        $combination->low_stock_threshold = (int)$data['low_stock_threshold'];
                        $combination->low_stock_alert = isset($data['low_stock_alert'])? (int)$data['low_stock_alert']:0;
                    }    
                    if($combination->update())
                        StockAvailable::setQuantity($this->product->id, (int)$id_product_attribute, $combination->quantity);
                }
            }
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->module->l('Updated successfully','products'),
                        'list_combinations' => $this->displayListCombinations(),
                    )
                )
            );
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->errors),
                    )
                )
            );
        }
    }
    public function _processSaveProduct()
    {
        if($this->_checkValidateProduct())
        {
            if($this->_submitSaveProduct())
            {
                $languages = Language::getLanguages(false);
                $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
                if(Tools::getValue('product_type')==0 && Tools::getValue('show_variations') && Tools::getValue('combinations_id_product_attribute'))
                {
                    $combinations_id_product_attribute = Tools::getValue('combinations_id_product_attribute');
                    $combinations_attribute_default = Tools::getValue('combinations_attribute_default');
                    $combinations_attribute_quantity = Tools::getValue('combinations_attribute_quantity');
                    $combinations_attribute_available_date = Tools::getValue('combinations_attribute_available_date');
                    $combinations_attribute_minimal_quantity= Tools::getValue('combinations_attribute_minimal_quantity');
                    $combinations_attribute_reference = Tools::getValue('combinations_attribute_reference');
                    $combinations_attribute_location = Tools::getValue('combinations_attribute_location');
                    if($this->module->is17)
                        $combinations_attribute_low_stock_threshold = Tools::getValue('combinations_attribute_low_stock_threshold');
                    $combinations_attribute_wholesale_price= Tools::getValue('combinations_attribute_wholesale_price');
                    $combinations_attribute_price = Tools::getValue('combinations_attribute_price');
                    $combinations_attribute_unity = Tools::getValue('combinations_attribute_unity');
                    $combinations_attribute_weight = Tools::getValue('combinations_attribute_weight');
                    $combinations_attribute_isbn = Tools::getValue('combinations_attribute_isbn');
                    $combinations_attribute_ean13= Tools::getValue('combinations_attribute_ean13');
                    $combinations_attribute_upc = Tools::getValue('combinations_attribute_upc');
                    $combination_id_image_attr = Tools::getValue('combination_id_image_attr');
                    $combination_attribute_low_stock_alert = Tools::getValue('combination_attribute_low_stock_alert');
                    foreach($combinations_id_product_attribute as $id_product_attribute)
                    {
                        $combination = new Combination($id_product_attribute);
                        if(Validate::isLoadedObject($combination) && $combination->id_product == $this->product->id)
                        {
                            if(isset($combinations_attribute_default[$id_product_attribute]))
                            {
                                $combination->default_on = (int)$combinations_attribute_default[$id_product_attribute];
                                if($combination->default_on)
                                    Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_attribute SET default_on=0 WHERE default_on=1 AND id_product="'.(int)$this->product->id.'" AND id_product_attribute!="'.(int)$id_product_attribute.'"');
                            }
                            else
                                $combination->default_on=0; 
                            if(isset($combinations_attribute_quantity[$id_product_attribute]) && Validate::isUnsignedInt($combinations_attribute_quantity[$id_product_attribute]))
                                $combination->quantity = (int)$combinations_attribute_quantity[$id_product_attribute];
                            else
                                $combination->quantity=0;
                            if(isset($combinations_attribute_available_date[$id_product_attribute]) && Validate::isDate($combinations_attribute_available_date[$id_product_attribute]))
                                $combination->available_date = $combinations_attribute_available_date[$id_product_attribute];
                            else
                                $combination->available_date ='0000-00-00';
                            if(isset($combinations_attribute_minimal_quantity[$id_product_attribute]) && Validate::isUnsignedInt($combinations_attribute_minimal_quantity[$id_product_attribute]))
                                $combination->minimal_quantity = (int)$combinations_attribute_minimal_quantity[$id_product_attribute];
                            else
                                $combination->minimal_quantity=0;
                            if(isset($combinations_attribute_reference[$id_product_attribute]) && Validate::isReference($combinations_attribute_reference[$id_product_attribute]))
                                $combination->reference = $combinations_attribute_reference[$id_product_attribute];
                            else
                                $combination->reference='';
                            if(isset($combinations_attribute_location[$id_product_attribute]) && Validate::isGenericName($combinations_attribute_location[$id_product_attribute]))
                                $combination->location = $combinations_attribute_location[$id_product_attribute];
                            else
                                $combination->location='';
                            if($this->module->is17)
                            {
                                if(isset($combinations_attribute_low_stock_threshold[$id_product_attribute]) && Validate::isInt($combinations_attribute_low_stock_threshold[$id_product_attribute]))
                                    $combination->low_stock_threshold = (int)$combinations_attribute_low_stock_threshold[$id_product_attribute];
                                else
                                    $combination->low_stock_threshold =0;
                                if(isset($combination_attribute_low_stock_alert[$id_product_attribute]) && Validate::isInt($combination_attribute_low_stock_alert[$id_product_attribute]))
                                    $combination->low_stock_alert = (int)$combination_attribute_low_stock_alert[$id_product_attribute];
                                else
                                    $combination->low_stock_alert=0;
                            }
                            if(isset($combinations_attribute_wholesale_price[$id_product_attribute]) && Validate::isPrice($combinations_attribute_wholesale_price[$id_product_attribute]))
                                $combination->wholesale_price = (float)$combinations_attribute_wholesale_price[$id_product_attribute];
                            else
                                $combination->wholesale_price =0;
                            if(isset($combinations_attribute_price[$id_product_attribute]) && Validate::isNegativePrice($combinations_attribute_price[$id_product_attribute]))
                                $combination->price = (float)$combinations_attribute_price[$id_product_attribute];
                            else
                                $combination->price = 0;
                            if(isset($combinations_attribute_unity[$id_product_attribute]) && Validate::isNegativePrice($combinations_attribute_unity[$id_product_attribute]))
                                $combination->unit_price_impact = $combinations_attribute_unity[$id_product_attribute];
                            else
                                $combination->unit_price_impact = 0; 
                            if(isset($combinations_attribute_weight[$id_product_attribute]) && Validate::isUnsignedFloat($combinations_attribute_weight[$id_product_attribute]))
                                $combination->weight = (float)$combinations_attribute_weight[$id_product_attribute]; 
                            else
                                $combination->weight =0; 
                            if(isset($combination->isbn) && isset($combinations_attribute_isbn[$id_product_attribute]) && Validate::isIsbn($combinations_attribute_isbn[$id_product_attribute]))
                                $combination->isbn = $combinations_attribute_isbn[$id_product_attribute];
                            else
                                $combination->isbn=''; 
                            if(isset($combinations_attribute_ean13[$id_product_attribute]) && Validate::isEan13($combinations_attribute_ean13[$id_product_attribute]))
                                $combination->ean13 = $combinations_attribute_ean13[$id_product_attribute];
                            else
                                $combination->ean13='';
                            if(isset($combinations_attribute_upc[$id_product_attribute]) && Validate::isUpc($combinations_attribute_upc[$id_product_attribute]))
                                $combination->upc = $combinations_attribute_upc[$id_product_attribute];
                            else
                                $combination->upc = '';
                            if($combination->update())
                            {
                                StockAvailable::setQuantity($this->product->id, (int)$id_product_attribute, $combination->quantity);
                                if($this->module->is17 && method_exists('StockAvailable','setLocation'))
                                    StockAvailable::setLocation($this->product->id,$combination->location,null,$combination->id);
                                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_image` WHERE id_product_attribute='.(int)$id_product_attribute);
                                if(isset($combination_id_image_attr[$id_product_attribute]) && $combination_id_image_attr[$id_product_attribute])
                                {
                                    foreach($combination_id_image_attr[$id_product_attribute] as $id_image)
                                    {
                                        if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_attribute_image` WHERE id_product_attribute="'.(int)$id_product_attribute.'" AND id_image="'.(int)$id_image.'"'))
                                            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_image`(id_product_attribute,id_image) VALUES("'.(int)$id_product_attribute.'","'.(int)$id_image.'")');
                                    }
                                }
                            }               
                        }
                    } 
                }
                else
                {
                    $this->product->deleteProductAttributes();
                }
                if(Tools::getValue('product_type')==1)
                {
                    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'pack` WHERE id_product_pack='.(int)$this->product->id);
                    if($inputPackItems = Tools::getValue('inputPackItems'))
                    {
                        foreach($inputPackItems as $inputPackItem)
                        {
                            $packItem = explode('x',$inputPackItem);
                            $id_product_item = $packItem[0];
                            $id_product_attribute_item = $packItem[1];
                            $quantity_item = $packItem[2];
                            if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'pack` WHERE id_product_pack="'.(int)$this->product->id.'" AND id_product_item="'.(int)$id_product_item.'" AND id_product_attribute_item="'.(int)$id_product_attribute_item.'"'))
                            {
                                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'pack`(id_product_pack,id_product_item,id_product_attribute_item,quantity) VALUES("'.(int)$this->product->id.'","'.(int)$id_product_item.'","'.(int)$id_product_attribute_item.'","'.(int)$quantity_item.'")');
                            }
                        }
                    }

                }
                else
                {
                    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'pack` WHERE id_product_pack='.(int)$this->product->id);
                }
                if(Tools::getValue('product_type')==2)
                {
                    $virtual = $this->updateDownloadProduct($this->product);
                }
                elseif($product_download = Db::getInstance()->getRow('SELECT id_product_download,filename FROM `'._DB_PREFIX_.'product_download` WHERE id_product="'.(int)$this->product->id.'"'))
                {
                    $productDownload = new ProductDownload($product_download['id_product_download']);
                    $productDownload->delete($product_download['filename']? true:false);
                }
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_carrier` WHERE id_product='.(int)$this->product->id);
                if(Tools::getValue('product_type')!=2 && ($selectedCarriers = Tools::getValue('selectedCarriers')) )
                {
                    foreach($selectedCarriers as $selectedCarrier)
                    {
                        if($selectedCarrier)
                        {
                            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_carrier`(id_product,id_carrier_reference,id_shop) VALUES("'.(int)$this->product->id.'","'.(int)$selectedCarrier.'","'.(int)$this->context->shop->id.'")');
                        }
                    }
                }
                $id_customization_fields = array();
                $uploadable_files =0;
                $text_fields = 0;
                if($custom_fields = Tools::getValue('custom_fields'))
                {
                    Configuration::updateValue('PS_CUSTOMIZATION_FEATURE_ACTIVE',1);
                    foreach($custom_fields as $custom_field)
                    {
                        if($id_customization_field = $custom_field['id_customization_field'])
                            $customizationField = new CustomizationField($id_customization_field);
                        else
                        {
                            $customizationField = new CustomizationField();
                            $customizationField->id_product=  $this->product->id;
                        }
                        foreach($languages as $language)
                        {
                            $customizationField->name[$language['id_lang']] = $custom_field['label'][$language['id_lang']] ? : $custom_field['label'][$id_lang_default];
                        }
                        $customizationField->type = (int)$custom_field['type'];
                        if($customizationField->type==1)
                            $text_fields +=1;
                        else
                            $uploadable_files +=1;
                        if(isset($custom_field['required']))
                            $customizationField->required = $custom_field['required'];
                        else    
                            $customizationField->required = 0;
                        if($customizationField->id)
                        {
                            $id_customization_fields[] = $customizationField->id;
                            $customizationField->update();
                        }
                        elseif($customizationField->add())
                            $id_customization_fields[] = $customizationField->id;
                    }
                }
                if(!$this->module->is17 && ($this->product->uploadable_files!=$uploadable_files || $this->product->text_fields!= $text_fields))
                {
                    $this->product->uploadable_files = $uploadable_files;
                    $this->product->text_fields = $text_fields;
                    $this->product->update();
                }
                if($this->module->is17)
                    Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'customization_field SET is_deleted=1 WHERE id_product="'.(int)$this->product->id.'"'.($id_customization_fields ? ' AND id_customization_field NOT IN ('.implode(',',array_map('intval',$id_customization_fields)).')':''));
                else
                {
                   $ids =  Db::getInstance()->executeS('SELECT id_customization_field FROM '._DB_PREFIX_.'customization_field WHERE id_product="'.(int)$this->product->id.'"'.($id_customization_fields ? ' AND id_customization_field NOT IN ('.implode(',',array_map('intval',$id_customization_fields)).')':''));
                   if($ids)
                   {
                        foreach($ids as $id)
                        {
                            $customizationField = new CustomizationField($id['id_customization_field']);
                            $customizationField->delete();
                        }
                   }
                }
                if($this->seller->auto_enabled_product=='yes')
                    $allow_active=1;
                elseif($this->seller->auto_enabled_product=='no')
                    $allow_active=0;
                elseif(!Configuration::get('ETS_MP_SELLER_PRODUCT_APPROVE_REQUIRED'))
                    $allow_active=1;
                else
                    $allow_active=0;
                $this->_submitProductSupplier();
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->product->active==0 && !$allow_active ? $this->module->l('Your new product has just been submitted successfully. It is waiting to be approved by Administrator','products'): $this->module->l('Submitted successfully','products'),
                            'virtual' => isset($virtual) && $virtual ? array('link_download_file' => $this->context->link->getModuleLink($this->module->name,'products',array('id_product'=>$this->product->id,'downloadfileproduct'=>1)),'link_delete_file' => $this->context->link->getModuleLink($this->module->name,'products',array('id_product'=>$this->product->id,'deletefileproduct'=>1)),):false,
                            'list_combinations' => $this->displayListCombinations(),
                            'id_product'=> $this->product->id,
                            'link_product' => $this->context->link->getProductLink($this->product->id),
                            'preview_text' => $this->module->l('Preview','products'),
                            'save_text' => $this->module->l('Save','products'),
                            'html_form_supplier' => $this->renderFormSupplier(),                            
                        )
                    )
                );
            }
            
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->errors),
                    )
                )
            );
        }
    }
    public function _submitProductSupplier()
    {
        if($id_suppliers = Tools::getValue('id_suppliers'))
        {
            $product_supplier_reference = Tools::getValue('product_supplier_reference');
            $product_supplier_price = Tools::getValue('product_supplier_price');
            $product_supplier_price_currency = Tools::getValue('product_supplier_price_currency');
            foreach($id_suppliers as $id_supplier)
            {
                $references = isset($product_supplier_reference[$id_supplier]) ? $product_supplier_reference[$id_supplier] : array();
                $supplier_prices = isset($product_supplier_price[$id_supplier]) ? $product_supplier_price[$id_supplier] :array() ;
                $currencies = isset($product_supplier_price_currency[$id_supplier]) ? $product_supplier_price_currency[$id_supplier] : array();
                foreach($currencies as $id_product_attribute=> $id_currency)
                {
                    if(isset($references[$id_product_attribute]))
                        $reference = $references[$id_product_attribute];
                    else
                        $reference ='';
                    if(isset($supplier_prices[$id_product_attribute]))
                        $supplier_price = (float)$supplier_prices[$id_product_attribute];
                    else
                        $supplier_price =0;
                    if($id_product_supplier = Db::getInstance()->getValue('SELECT id_product_supplier FROM '._DB_PREFIX_.'product_supplier WHERE id_product_attribute="'.(int)$id_product_attribute.'" AND id_product="'.(int)$this->product->id.'" AND id_supplier="'.(int)$id_supplier.'"'))
                        Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_supplier SET product_supplier_reference="'.pSQL($reference).'",product_supplier_price_te="'.(float)$supplier_price.'",id_currency ="'.(int)$id_currency.'" WHERE id_product_supplier="'.(int)$id_product_supplier.'"');
                    else   
                        Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'product_supplier(id_product,id_product_attribute,id_supplier,product_supplier_reference,product_supplier_price_te,id_currency) VALUES("'.(int)$this->product->id.'","'.(int)$id_product_attribute.'","'.(int)$id_supplier.'","'.pSQL($reference).'","'.(float)$supplier_price.'","'.(int)$id_currency.'")');
                }
            }
            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_supplier WHERE id_product="'.(int)$this->product->id.'" AND id_supplier NOT IN ('.implode(',',array_map('intval',$id_suppliers)).')');
        }
        else
            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_supplier WHERE id_product="'.(int)$this->product->id.'"');
    }
    
    public function downloadfileproduct()
    {
        if($filename = Db::getInstance()->getRow('SELECT display_filename,filename FROM `'._DB_PREFIX_.'product_download` WHERE id_product='.(int)$this->product->id))
        {
            $filepath =_PS_DOWNLOAD_DIR_.$filename['filename'];
            if(file_exists($filepath)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.($filename['display_filename'] ? $filename['display_filename'] : basename($filepath) ).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                flush(); // Flush system output buffer
                readfile($filepath);
                exit;
            }
            else
            {
                die('Product file does not exist');
            }
        }
    }
    public function _submitSaveImageProduct($id_image)
    {
        $image = new Image($id_image);
        if(Validate::isLoadedObject($image) &&  $image->id_product == $this->product->id)
        {
            $languages = Language::getLanguages(true);
            $errors = array();
            foreach($languages as $language)
            {
                if(Tools::strlen(strip_tags(Tools::getValue('legend_'.$language['id_lang'])))<128 && Validate::isCleanHtml(strip_tags(Tools::getValue('legend_'.$language['id_lang']))))
                    $image->legend[$language['id_lang']] = strip_tags(Tools::getValue('legend_'.$language['id_lang']));
                else
                    $errors[] = $this->module->l('Legend is not valid in','products').' '.$language['iso_code'];
            }
            if(Tools::getValue('image_cover'))
            {
                Image::deleteCover($this->product->id);
                $image->cover=1;
            }
            if(!$errors)
            {
                if($image->update())
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'success' => $this->module->l('Updated image successfully','products'),
                                'id_image' => $image->id,
                                'cover'=> Tools::getValue('image_cover') ? 1: 0,
                                'list_combinations' => $this->displayListCombinations(),
                            )
                        )
                    );
                }
                else
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'errors' => $this->module->displayError($this->module->l('An error occurred while updating the image','products')),
                            )
                        )
                    );
                }
            }
            else
                die(
                        Tools::jsonEncode(
                            array(
                                'errors' => $this->module->displayError($errors),
                            )
                        )
                    );
            
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->module->l('Image is not valid','products')),
                    )
                )
            );
        }
    }
    public function _submitdeleteImageProduct($id_image)
    {
        $image = new Image($id_image);
        if(Validate::isLoadedObject($image) && $image->id_product== $this->product->id)
        {
            if($image->delete())
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->module->l('Deleted image successfully','products'),
                            'id_image' => $image->id,
                            'list_combinations' => $this->displayListCombinations(),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->module->l('An error occurred while deleting the image','products')),
                        )
                    )
                );
            }
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->module->l('Image is not valid','products')),
                    )
                )
            );
        }
    }
    public function submitUploadImageSave()
    {
        if($this->product->id)
        {
            $this->_submitUploadImageSave($this->product->id,'upload_image');
            if($this->errors)
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->errors),
                        )
                    )
                );
            }
        }
        else{
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->module->l('Product is null','products')),
                    )
                )
            );   
           
            

        }
    }
    public function getPriceInclTax()
    {
        $id_tax_group = (int)Tools::getValue('id_tax_group');
        $tax = $this->module->getTaxValue($id_tax_group);
        $price = Tools::getValue('price');
        die(
            Tools::jsonEncode(
                array(
                    'price' => Tools::ps_round($price + ($price*$tax),6)
                )
            )
        );
    }
    public function getPriceExclTax()
    {
        $id_tax_group = (int)Tools::getValue('id_tax_group');
        $tax = $this->module->getTaxValue($id_tax_group);
        $price = Tools::getValue('price');
        die(
            Tools::jsonEncode(
                array(
                    'price' => Tools::ps_round($price/(1+$tax),6)
                )
            )
        );
    }
    public function getFormSpecificPrice()
    {
        if($id_specific_price = Tools::getValue('id_specific_price'))
        {
            $specific_price = new SpecificPrice($id_specific_price);
            if(!Validate::isLoadedObject($specific_price) || $specific_price->id_product != $this->product->id)
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->l('Specific price is not valid','products'),
                        )
                    )
                );
            }                
        }
        die(
            Tools::jsonEncode(
                array(
                    'form_html' => $this->renderSpecificPrice(Tools::getValue('id_specific_price')),
                )
            )
        );
    }
    public function _submitDeleteProduct()
    {
        if(!$this->checkDeleteProduct())
            die($this->module->l('You do not have permission','products'));
        if($this->product->delete())
        {
            $this->context->cookie->success_message = $this->module->l('Deleted successfully','products');
            Tools::redirectLink($this->context->link->getModuleLink($this->module->name,'products',array('list'=>1)));
        }
    }
    public function _submitChangeEnabled($id_product)
    {
        if((int)Tools::getValue('change_enabled') && $this->seller->vacation_mode && $this->seller->vacation_type=='disable_product')
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->l('You do not have permission to enable this product','products'),
                    )
                )  
            );
        }
        $product = new Product($id_product);
        $product->active = (int)Tools::getValue('change_enabled');
        $product->update();
        if(Tools::getValue('change_enabled'))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'href' => $this->context->link->getModuleLink($this->module->name,'products',array('id_product'=> $id_product,'change_enabled'=>0,'field'=>'active')),
                        'title' => $this->module->l('Click to disable','products'),
                        'success' => $this->module->l('Updated successfully','products'),
                        'enabled' => 1,
                    )
                )  
            );
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'href' => $this->context->link->getModuleLink($this->module->name,'products',array('id_product'=> $id_product,'change_enabled'=>1,'field'=>'active')),
                        'title' => $this->module->l('Click to enable','products'),
                        'success' => $this->module->l('Updated successfully','products'),
                        'enabled' => 0,
                    )
                )  
            );
        }
    }
    public function initContent()
	{
		parent::initContent();
        if(Tools::getValue('addnew') )
        {
            // Initiate the product here
             $e = new Product();
             $id = $e->add();       
                             
             DB::getInstance()->Execute('
              INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_product` (`id_customer`, `id_product`, `approved`, `active`) VALUES ('.$this->context->customer->id.', '.$e->id.', 1, 1)');
             Tools::redirect('https://www.onceagain.ch/seller-products?list=1&editmp_front_products=1&id_product='.$e->id);
                            

        }

        if(Tools::getValue('addnew') || (Tools::isSubmit('editmp_front_products') && Tools::getValue('id_product')) )
        {
            $ok = true;
            if(Tools::getValue('addnew'))
            {
                if(!Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT'))
                {
                    $html_content = $this->module->displayWarning($this->module->l('You do not have permission to create new product','products'));
                    $ok = false;
                }
                else
                    $html_content = '';
            }    
            else
            {
                if(!Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT'))
                {
                    $html_content = $this->module->displayWarning($this->module->l('You do not have permission to edit product','products'));
                    $ok = false;
                }
                else
                    $html_content = '';
            }
            if($ok)
                $html_content .= ($this->context->cookie->success_message ? $this->module->displayConfirmation($this->context->cookie->success_message):'').$this->renderProductForm();
            $this->context->cookie->success_message='';
        }
        else
        {
            $fields_list = array(
                'input_box' => array(
                    'title' => '',
                    'width' => 40,
                    'type' => 'text',
                    'strip_tag'=> false,
                ),
                'id_product' => array(
                    'title' => $this->module->l('ID','products'),
                    'width' => 40,
                    'type' => 'text',
                    'sort' => true,
                    'filter' => true,
                ),
                'image' => array(
                    'title' => $this->module->l('Image','products'),
                    'type'=>'text',
                    'sort' => false,
                    'filter' => false,
                    'strip_tag'=> false,
                ),
                'name' => array(
                    'title' => $this->module->l('Name','products'),
                    'type' => 'text',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag'=>false,
                ),
                'stock_quantity' => array(
                    'title' => $this->module->l('Quantity','products'),
                    'type' => 'int',
                    'sort' => true,
                    'filter' => true
                ),
                'default_category' => array(
                    'title' => $this->module->l('Default category','products'),
                    'type' => 'text',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag'=>false,
                ),
                'price' => array(
                    'title' => $this->module->l('Price','products'),
                    'type' => 'int',
                    'sort' => true,
                    'filter' => true
                ),
                /* _ARM_ Does not show inactive products
                'active' => array(
                    'title' => $this->module->l('Status','products'),
                    'type' => 'active',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag' => false,
                    'filter_list' => array(
                        'id_option' => 'active',
                        'value' => 'title',
                        'list' => array(
                            0 => array(
                                'active' => -1,
                                'title' => $this->module->l('Pending','products')
                            ),
                            1 => array(
                                'active' => 0,
                                'title' => $this->module->l('Disabled','products')
                            ),
                            2 => array(
                                'active' => 1,
                                'title' => $this->module->l('Enabled','products')
                            )
                        )
                    )
                ),*/
            );
            
            //if(!Configuration::get('ETS_MP_SELLER_ALLOWED_INFORMATION_SUBMISSION') || (Configuration::get('ETS_MP_SELLER_ALLOWED_INFORMATION_SUBMISSION') && !in_array('product_reference',explode(',',Configuration::get('ETS_MP_SELLER_ALLOWED_INFORMATION_SUBMISSION')))))
            //    unset($fields_list['reference']);
            //Filter
            $show_resset = false;
            /* _ARM_ Does not show inactive products */
            $filter = " AND p.`active` = 1 ";
            if(Tools::getValue('id_product') && !Tools::isSubmit('del') && !Tools::isSubmit('duplicatemp_front_products'))
            {
                $filter .= ' AND p.id_product="'.(int)Tools::getValue('id_product').'"';
                $show_resset = true;
            }
            if(Tools::getValue('name'))
            {
                $filter .=' AND pl.name LIKE "%'.pSQL(Tools::getValue('name')).'%"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('stock_quantity_min')))
            {
                $filter .=' AND stock.quantity >= "'.(int)Tools::getValue('stock_quantity_min').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('stock_quantity_max')))
            {
                $filter .=' AND stock.quantity <= "'.(int)Tools::getValue('stock_quantity_max').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('default_category')))
            {
                $filter .=' AND cl.name LIKE "%'.pSQL(trim(Tools::getValue('default_category'))).'%"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('price_min')))
            {
                $filter .= ' AND product_shop.price >= "'.(float)Tools::convertPrice(Tools::getValue('price_min'),null,false).'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('price_max')))
            {
                $filter .= ' AND product_shop.price <= "'.(float)Tools::convertPrice(Tools::getValue('price_max'),null,false).'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('active'))!='')
            {
                if(trim(Tools::getValue('active'))==1)
                    $filter .= ' AND product_shop.active="1"';
                elseif(trim(Tools::getValue('active'))==0)
                    $filter .= ' AND product_shop.active="0" AND sp.approved="1"';
                else
                    $filter .= ' AND product_shop.active="0" AND sp.approved="0"';
                $show_resset=true;
            }
            //Sort
            $sort = "";
            if(Tools::getValue('sort','id_product'))
            {
                switch (Tools::getValue('sort','id_product')) {
                    case 'id_product':
                        $sort .='p.id_product';
                        break;
                    case 'name':
                        $sort .='pl.name';
                        break;
                    case 'stock_quantity':
                        $sort .= 'stock.quantity';
                        break;
                    case 'default_category':
                        $sort .= 'pl.name';
                        break;
                    case 'shop_name':
                        $sort .= 'r.shop_name';
                        break;
                    case 'price':
                        $sort .= 'product_shop.price';
                        break;
                    case 'active':
                        $sort .='p.active';
                        break;
                }
                if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                    $sort .= ' '.trim($sort_type);  
            }
            //Paggination
            $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
            $totalRecords = (int) $this->seller->getProducts($filter,0,0,'',true);
            $paggination = new Ets_mp_paggination_class();            
            $paggination->total = $totalRecords;
            $paggination->url =$this->context->link->getModuleLink($this->module->name,'products',array('list'=>true, 'page'=>'_page_')).$this->module->getFilterParams($fields_list,'mp_front_products');
            $paggination->limit =  10;
            $totalPages = ceil($totalRecords / $paggination->limit);
            if($page > $totalPages)
                $page = $totalPages;
            $paggination->page = $page;
            $products = $this->seller->getProducts($filter,$page,$paggination->limit,$sort,false,false,false,false);
            if($products)
            {
                if(version_compare(_PS_VERSION_, '1.7', '>='))
                    $type_image= ImageType::getFormattedName('home');
                else
                    $type_image= ImageType::getFormatedName('home');
                foreach($products as &$product)
                {
                    $product['price'] = Tools::displayPrice($product['price'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                    $product['child_view_url'] = $this->context->link->getProductLink($product['id_product']);
                    if(!$product['id_image'])
                        $product['id_image'] = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$product['id_product']);
                    if($product['id_image'])
                    {
                        
                        $product['image'] = '<'.'a hr'.'ef="'.$product['child_view_url'].'" target="_blank"><i'.'mg src="'.$this->context->link->getImageLink($product['link_rewrite'],$product['id_image'],$type_image).'" style="width:80px;"><'.'/'.'a'.'>';
                    }
                    else
                        $product['image']='';
                    $product['name'] = '<'.'a  hr'.'ef="'.$product['child_view_url'].'" target="_blank">'.$product['name'].'<'.'/'.'a'.'>';
                    if($product['id_category_default'])
                        $product['default_category'] = '<'.'a  hr'.'ef="'.$this->context->link->getCategoryLink($product['id_category_default']).'" tar'.'get="_bla'.'nk">'.$product['default_category'].'<'.'/'.'a'.'>';
                    if(!$product['active'] && !$product['approved'])
                        $product['active']=-1;
                    //if(!Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT'))
                    //    $product['action_edit'] = false;
                    $product['input_box'] = '<'.'inp'.'ut i'.'d="bulk_action_selected_products-'.$product['id_product'].'" na'.'me="bulk_action_selected_products[]" value="'.$product['id_product'].'" ty'.'pe="chec'.'kbox" '.'>';
                }
            }
            $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','products');
            $paggination->style_links = $this->module->l('links','products');
            $paggination->style_results = $this->module->l('results','products');
            $actions = array('view');
            if(Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT'))
                $actions[] = 'edit';
            /* _ARM_ Do not duplicate products
            if(Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT'))
                $actions[] = 'duplicate';
            */
            if($this->checkDeleteProduct())
                $actions[] = 'delete';
            $listData = array(
                'name' => 'mp_front_products',
                'actions' => $actions,
                'currentIndex' => $this->context->link->getModuleLink($this->module->name,'products',array('list'=>1)),
                'identifier' => 'id_product',
                'show_toolbar' => true,
                'show_action' => true,
                'title' => $this->module->l('Products','products'),
                'fields_list' => $fields_list,
                'field_values' => $products,
                'paggination' => $paggination->render(),
                'filter_params' => $this->module->getFilterParams($fields_list,'mp_front_products'),
                'show_reset' =>$show_resset,
                'totalRecords' => $totalRecords,
                'sort'=> Tools::getValue('sort','id_product'),
                'show_add_new'=> Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT') ? true:false,
                'view_new_tab' => true,
                'link_new' => $this->context->link->getModuleLink($this->module->name,'products',array('addnew'=>1)),
                'link_export' => Configuration::get('ETS_MP_SELLER_ALLOWED_IMPORT_EXPORT_PRODUCTS')? $this->context->link->getModuleLink($this->module->name,'products',array('export'=>1)): false,
                'link_import' =>Configuration::get('ETS_MP_SELLER_ALLOWED_IMPORT_EXPORT_PRODUCTS') && Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT') ? $this->context->link->getModuleLink($this->module->name,'import'): false,
                'sort_type' => Tools::getValue('sort_type','desc'),
            );          
            $html_content = ($this->context->cookie->success_message ? $this->module->displayConfirmation($this->context->cookie->success_message):'').$this->_renderFormBulkProduct().($this->seller->vacation_mode && $this->seller->vacation_type=='disable_product'  ? $this->module->displayWarning($this->module->l('Your shop is in vacation mode. All your products have been disabled and cannot be enabled until your shop is back to online','products')):'').$this->module->renderList($listData);
            $this->context->cookie->success_message ='';
        }
        $this->context->smarty->assign(
            array(
                'html_content' => $html_content,
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/products.tpl');      
        else        
            $this->setTemplate('products_16.tpl'); 
    }
    public function checkDeleteProduct()
    {
        if(!Configuration::get('ETS_MP_ALLOW_SELLER_DELETE_PRODUCT'))
            return false;
        if($this->seller->id_customer == $this->context->customer->id)
            return true;
        elseif((int)Db::getInstance()->getValue('SELECT delete_product FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email="'.pSQL($this->context->customer->email).'" AND id_customer="'.(int)$this->seller->id_customer.'"'))
            return true;
        else
            return false;
            
    }
    public function renderProductForm()
    {
        $valueFieldPost= array();
        $valueFieldPost['product_type'] = $this->product->getType();
        $valueFieldPost['show_variations'] = $valueFieldPost['product_type']==0 && Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product="'.(int)$this->product->id.'"') && $this->product->id ? 1 :0;
        foreach(Language::getLanguages(true) as $language)
        {
            $valueFieldPost['name'][$language['id_lang']] = Tools::getValue('name_'.(int)$language['id_lang'],$this->product->name[$language['id_lang']]);
            $valueFieldPost['description'][$language['id_lang']] = Tools::getValue('description_'.(int)$language['id_lang'],$this->product->description[$language['id_lang']]);
            $valueFieldPost['description_short'][$language['id_lang']] = Tools::getValue('description_short_'.(int)$language['id_lang'],$this->product->description_short[$language['id_lang']]);
            $valueFieldPost['meta_title'][$language['id_lang']] = Tools::getValue('meta_title_'.(int)$language['id_lang'],$this->product->meta_title[$language['id_lang']]);
            $valueFieldPost['meta_keywords'][$language['id_lang']] = Tools::getValue('meta_keywords_'.(int)$language['id_lang'],$this->product->meta_keywords[$language['id_lang']]);
            $valueFieldPost['meta_description'][$language['id_lang']] = Tools::getValue('meta_description_'.(int)$language['id_lang'],$this->product->meta_description[$language['id_lang']]);
            $valueFieldPost['link_rewrite'][$language['id_lang']] = Tools::getValue('link_rewrite_'.(int)$language['id_lang'],$this->product->link_rewrite[$language['id_lang']]);
            $valueFieldPost['redirect_type'] = Tools::getValue('redirect_type',$this->product->redirect_type);
            if(isset($this->product->delivery_in_stock))
                $valueFieldPost['delivery_in_stock'][$language['id_lang']]=Tools::getValue('delivery_in_stock_'.(int)$language['id_lang'],$this->product->delivery_in_stock[$language['id_lang']]);
            $valueFieldPost['delivery_in_stock'][$language['id_lang']]=Tools::getValue('delivery_in_stock_'.(int)$language['id_lang']);
            if(isset($this->product->delivery_out_stock))                            
                $valueFieldPost['delivery_out_stock'][$language['id_lang']]=Tools::getValue('delivery_out_stock_'.(int)$language['id_lang'],$this->product->delivery_out_stock[$language['id_lang']]);
            else
                $valueFieldPost['delivery_out_stock'][$language['id_lang']]=Tools::getValue('delivery_out_stock_'.(int)$language['id_lang']);                            
        }
        $valueFieldPost['id_tax_rules_group'] = $this->product->id_tax_rules_group;
        if($this->product->id)
        {
            $valueFieldPost['price_excl'] = $this->product->price;
            $valueFieldPost['price_incl'] = Tools::ps_round($this->product->price +$this->product->price*$this->module->getTaxValue($this->product->id_tax_rules_group),6);
        }
        else
        {
            $valueFieldPost['price_excl'] ='';
            $valueFieldPost['price_incl'] ='';
        }
        $valueFieldPost['reference'] = $this->product->reference;
        $valueFieldPost['quantity'] = $this->product->id ? $this->product->getQuantity($this->product->id):999;
        $valueFieldPost['active'] = $this->product->active;
        $valueFieldPost['id_manufacturer'] = $this->product->id_manufacturer;
        $valueFieldPost['condition'] = $this->product->condition;
        if(isset($this->product->show_condition))
            $valueFieldPost['show_condition'] = $this->product->show_condition;
        if(isset($this->product->isbn))
            $valueFieldPost['isbn'] = $this->product->isbn;
        $valueFieldPost['ean13'] = $this->product->ean13;
        $valueFieldPost['upc'] = $this->product->upc;
        $valueFieldPost['customizationFields'] = $this->getCustomizationFields($this->product->id);
        $valueFieldPost['attachments'] = $this->getProductAttachments($this->product->id);
        $valueFieldPost['shipping_cost'] = $this->product->additional_shipping_cost;
        $default_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $this->context->smarty->assign(
            array(
                'languages' => Language::getLanguages(true),
                'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
                'valueFieldPost' => $valueFieldPost,
                'default_currency' => $default_currency ,
                'seller_product_information' => $this->seller_product_information,
                'seller_product_types' => $this->seller_product_types,
                'url_path' => $this->module->getBaseLink().'/modules/'.$this->module->name.'/',
                'ets_mp_url_search_product' => $this->context->link->getModuleLink($this->module->name,'ajax',array('ajaxSearchProduct'=>1)),
                'ets_mp_url_search_customer' => $this->context->link->getModuleLink($this->module->name,'ajax',array('ajaxSearchCustomer'=>1)),
                'ets_mp_url_search_related_product' => $this->context->link->getModuleLink($this->module->name,'ajax',array('ajaxSearchProduct'=>1,'disableCombination'=>1)),
            )
        );
        if($this->product_tabs)
        {
            foreach($this->product_tabs as $key=> &$tab)
            {
                if (method_exists($this, 'renderForm' . $tab['tab'])) {
                    $tab['content_html'] = $this->{'renderForm' . $tab['tab']}();
                }
                else
                    unset($this->product_tabs[$key]);
            }
        }
        $tax_rule_groups = TaxRulesGroup::getTaxRulesGroupsForOptions();
        if($tax_rule_groups)
        {
            foreach($tax_rule_groups as &$tax_rule_group)
            {
                $tax_rule_group['value_tax'] = $this->module->getTaxValue($tax_rule_group['id_tax_rules_group']);
            }
        }
        $this->context->smarty->assign(
            array(
                'product_tabs' => $this->product_tabs,
                'tax_rule_groups' => $tax_rule_groups,
                'current_tab' => Tools::getValue('current_tab','BaseSettings'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/form.tpl');
    }
    public function renderFormBaseSettings()
    {
        $categories = Db::getInstance()->executeS('SELECT id_category FROM `'._DB_PREFIX_.'category_product` WHERE id_product='.(int)$this->product->id);
        $selected_categories = array();
        $disabled_categories = array();
        if(Configuration::get('ETS_MP_APPLICABLE_CATEGORIES')=='specific_product_categories' && $seller_categories = Configuration::get('ETS_MP_SELLER_CATEGORIES'))
        {
            $seller_not_categories = Db::getInstance()->executeS('SELECT c.id_category FROM `'._DB_PREFIX_.'category` c
            INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
            WHERE c.id_category NOT IN ('.pSQL(trim($seller_categories,',')).')');
            if($seller_not_categories)
            {
                foreach($seller_not_categories as $category)
                    $disabled_categories[] = $category['id_category'];
            }
        }
        else
        {
            $roots = Db::getInstance()->executeS('SELECT id_category FROM `'._DB_PREFIX_.'category` WHERE is_root_category=1');
            if($roots)
            {
                foreach($roots as $root)
                    $disabled_categories[] = $root['id_category'];
            }
        }
        $currency_default = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        if($categories)
        {
            foreach($categories as $category)
                $selected_categories[]=$category['id_category'];
        }
        $manufacturers = $this->seller->getManufacturers(' AND m.active=1',false,false,false);
        $show_variations = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product="'.(int)$this->product->id.'"') ? 1 :0;
        $fields = array(
            array(
                'type' => 'custom_form',
                'form_group_class'=> 'ets_mp_form_pack_product',
                'html_form' => $this->renderFormPackProduct(),
            ),
            array(
                'type' => 'custom_form',
                'form_group_class'=> '',
                'html_form' => $this->renderFormImageProduct(),
            ),
            array(
                'type' => 'radio',
                'name' => 'show_variations',
                'label' => $this->module->l('Combinations','products'),
                'form_group_class' => 'ets_mp_show_variations'.(!$show_variations && (!in_array('standard_product',$this->seller_product_types) || !$this->module->_use_attribute) ?' hide':''),
                'values' => array(
                    array(
                        'id'=>0,
                        'name' => $this->module->l('Simple product','products'),
                    ),
                    array(
                        'id'=>1,
                        'name' => $this->module->l('Product with combinations','products'),
                    ),
                )
            )
            
        );
        if(in_array('short_description',$this->seller_product_information))
            $fields[]= array(
                'type' => 'textarea',
                'name' => 'description_short',
                'label' => $this->module->l('Summary','products'),
                'autoload_rte' => true,
                'lang'=> true,
                'placeholder' => $this->module->l('Fill in a striking short description of the product (displayed on product page and product list as abstract for customers and search engines). For detailed informations use the description tab.','products'),
                'max_text' => Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT') ?: 800,
                'small_text' => $this->module->l('characters allowed','products'),
        );
        $fields[] = array(
                'type' => 'textarea',
                'name' => 'description',
                'label' => $this->module->l('Description','products'),
                'autoload_rte' => true,
                'lang'=> true,
                'max_text' => 21844,
                'small_text' => $this->module->l('characters allowed','products'),
                'placeholder' => $this->module->l('ex : porté quelques fois, taille correctement','products'),
            );
        if(in_array('product_reference',$this->seller_product_information))
            $fields[] = array(
                'type' => 'text',
                'name' => 'reference',
                'label' => $this->module->l('Reference','products'),
            );
        $fields[] = array(
                'type' =>'input_group',
                'label' =>$this->module->l('Price','products'),
                'placeholder' => $this->module->l('CHF 0,00','products'),
                'required' => true,
                'inputs' => array(
                    array(
                        'type'=> 'text',
                        'name'=>'price_excl',
                        'label'=> $this->module->l('Minimum 5 CHF ','products'),
                        'placeholder' => $this->module->l('CHF 0,00','products'),
                        'col' => 'col-lg-6',
                        'suffix' => $currency_default->sign,
                    ),
                    array(
                        'type'=> 'text',
                        'name'=>'price_incl',
                        'label'=> $this->module->l('Price (tax incl.)','products'),
                        'placeholder' => $this->module->l('Minimum 5 CHF','products'),
                        'col' => 'col-lg-6',
                        'suffix' => $currency_default->sign,
                    ),
                    array(
                        'type'=> 'select',
                        'name'=>'id_tax_rules_group',
                        'label'=> $this->module->l('Tax rule','products'),
                        'col' => 'col-lg-12',
                        'values' => array(
                            'query' => TaxRulesGroup::getTaxRulesGroupsForOptions(),
                            'id'=> 'id_tax_rules_group',
                            'name' => 'name',
                        ),
                    )
                ),
        );
        $fields[] = array(
                'type' => 'categories',
                'name'=>'id_categories',
                'required' => true,
                'label' => $this->module->l('Categories','products'),
                'placeholder' => $this->module->l('Sélectionne une catégorie','products'),
                'categories_tree'=> $this->module->displayProductCategoryTre($this->module->getCategoriesTree(),$selected_categories,'',$disabled_categories,$this->product->id_category_default),
            );
        if($manufacturers)
            $fields[] = array(
                'type' => 'select',
                'name' => 'id_manufacturer',
                'label' => $this->module->l('Brand','products'),
                'placeholder' => $this->module->l('Sélectionne la marque','products'),
                'values' => array_merge(array(array('id'=>'','name'=> $this->module->l('--'))), $manufacturers),
            );
        if($this->module->_use_feature && ($product_features = $this->module->displayProductFeatures($this->product->id)))
        {
            $fields[] = array(
                'type' => 'product_features',
                'name'=> 'features',
                'label' => $this->module->l('Features','products'),
                'list_features' => $product_features,
            );
        }
        $fields[] = array(
                'type' => 'custom_form',
                'form_group_class'=> 'ets_mp_form_related_product',
                'html_form' => $this->renderFormRelatedProduct(),
        );
        if(!$this->seller->vacation_mode || $this->seller->vacation_type!='disable_product')
        {
            if($this->seller->auto_enabled_product=='yes')
                $allow_active=1;
            elseif($this->seller->auto_enabled_product=='no')
                $allow_active=0;
            elseif(!Configuration::get('ETS_MP_SELLER_PRODUCT_APPROVE_REQUIRED'))
                $allow_active=1;
            else
                $allow_active=0;
            if($allow_active || (int)Db::getInstance()->getValue('SELECT approved FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$this->product->id))
            {
                $fields[] = array(
                    'type' => 'switch',
                    'name'=> 'active',
                    'label' => $this->module->l('Enabled','products'),
                );
            }
        }
        $this->context->smarty->assign(
            array(
                'fields' => $fields,
                'product_class' => $this->product,
                'newMessage' => (Tools::isSubmit('addnew') ?  $this->module->displayConfirmation($this->module->l('Photo uploading form will be displayed when the new product is successfully added','products')).'<br>':'')
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/form.tpl');
    }
    public function renderFormQuantities()
    {
        $this->context->smarty->assign(
            array(
                'product_class' => $this->product,
                'link_download_file' => $this->context->link->getModuleLink($this->module->name,'products',array('id_product'=>$this->product->id,'downloadfileproduct'=>1)),
                'link_delete_file' => $this->context->link->getModuleLink($this->module->name,'products',array('id_product'=>$this->product->id,'deletefileproduct'=>1)),
                'productDownload' => Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'product_download` WHERE id_product='.(int)$this->product->id),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/quantities.tpl');
    }
    
    public function renderFormCombinations()
    {
        $product_types = Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT') ? explode(',',Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT')):array();
        if($this->module->_use_attribute && in_array('standard_product',$product_types))
        {
            $attributeGroups = $this->seller->getAttributeGroups('',false,false);
            if($attributeGroups)
            {
                foreach($attributeGroups as &$attributeGroup)
                {
                    $attributeGroup['attributes'] = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'attribute` a
                    INNER JOIN `'._DB_PREFIX_.'attribute_shop` ash ON (a.id_attribute = ash.id_attribute AND ash.id_shop="'.(int)$this->context->shop->id.'")
                    LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.id_attribute=al.id_attribute AND al.id_lang="'.(int)$this->context->language->id.'")
                    WHERE a.id_attribute_group = "'.(int)$attributeGroup['id_attribute_group'].'"
                    GROUP BY a.id_attribute');
                    if($attributeGroup['is_color_group'] && $attributeGroup['attributes'])
                    {
                        foreach($attributeGroup['attributes'] as &$attribute)
                        {
                            if(file_exists(_PS_COL_IMG_DIR_.$attribute['id_attribute'].'.jpg'))
                                $attribute['image']=$this->module->getBaseLink().'/img/co/'.$attribute['id_attribute'].'.jpg';
                        }
                    }
                }
            }
        }
        else
            $attributeGroups = array();
        $this->context->smarty->assign(
            array(
                'attributeGroups'=>$attributeGroups,
            )
        );
        $this->context->smarty->assign(
            'list_product_attributes', $this->displayListCombinations()
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/combinations.tpl');
    }
    public function renderFormPackProduct()
    {
        $sql = 'SELECT p.*,pl.name,pl.link_rewrite, pa.* FROM `'._DB_PREFIX_.'product` p
        INNER JOIN `'._DB_PREFIX_.'product_shop` ps ON (p.id_product=ps.id_product AND ps.id_shop="'.(int)$this->context->shop->id.'")
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.id_product=pl.id_product AND pl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'pack` pa ON (p.id_product=pa.id_product_item)
        WHERE id_product_pack = "'.(int)$this->product->id.'" GROUP BY p.id_product';
        $pack_products = Db::getInstance()->executeS($sql);
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $type_image= ImageType::getFormattedName('home');
        else
            $type_image= ImageType::getFormatedName('home');
        if($pack_products)
        {
            foreach($pack_products as &$pack_product)
            {
                $id_image =0;
                if($pack_product['id_product_attribute_item'])
                {
                    $pack_product['attribute_name'] = $this->module->getProductAttributeName($pack_product['id_product_attribute_item']);
                    $id_image = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'product_attribute_image` WHERE id_product_attribute='.(int)$pack_product['id_product_attribute_item']);
                }
                else
                    $pack_product['attribute_name']='';
                if(!$id_image)
                    $id_image = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$pack_product['id_product_item'].' AND cover=1');
                if(!$id_image)
                    $id_image = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$pack_product['id_product_item']);
                $pack_product['url_image'] = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($pack_product['link_rewrite'], $id_image, $type_image));
            }
        }
        $this->context->smarty->assign(
            array(
                'pack_products' => $pack_products,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/pack.tpl');
    }
    public function renderFormRelatedProduct()
    {
        $sql ='SELECT p.*,pl.name,pl.link_rewrite,image_shop.`id_image` id_image, il.`legend` FROM '._DB_PREFIX_.'product p
        INNER JOIN '._DB_PREFIX_.'product_shop ps ON (p.id_product=ps.id_product AND ps.id_shop="'.(int)$this->context->shop->id.'")
        INNER JOIN '._DB_PREFIX_.'accessory a ON (a.id_product_2 = p.id_product AND a.id_product_1="'.(int)$this->product->id.'")
        LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $this->context->shop->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $this->context->language->id . ')
        ';
        $related_products = Db::getInstance()->executeS($sql);
        if($related_products)
        {
            if(version_compare(_PS_VERSION_, '1.7', '>='))
                $type_image= ImageType::getFormattedName('home');
            else
                $type_image= ImageType::getFormatedName('home');
            foreach($related_products as &$related_product)
            {
                if(!$related_product['id_image'])
                    $related_product['id_image'] = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$related_product['id_product']);
                if($related_product['id_image'])
                    $related_product['img'] = $this->context->link->getImageLink($related_product['link_rewrite'], $related_product['id_image'], $type_image);
            }
        }
        $this->context->smarty->assign(
            array(
                'related_products' => $related_products,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/related.tpl');
    }
    public function renderFormImageProduct()
    {
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $type_image= ImageType::getFormattedName('home');
        else
            $type_image= ImageType::getFormatedName('home');
        $images = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$this->product->id.' ORDER BY position ASC');
        if($images)
        {
            foreach($images as &$image)
            {
                $image['link'] = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($this->product->link_rewrite[$this->context->language->id], $image['id_image'], $type_image));
            }
        }
        $this->context->smarty->assign(
            array(
                'product_class' => $this->product,
                'images' => $images
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/images.tpl');
    }
    public function displayListCombinations()
    {
        if($this->product->id)
        {
            $productAttributes = Db::getInstance()->executeS('SELECT pa.*,sa.quantity FROM `'._DB_PREFIX_.'product_attribute` pa
            LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON (pa.id_product_attribute=sa.id_product_attribute)
            WHERE pa.id_product='.(int)$this->product->id.' ORDER BY pa.id_product_attribute ASC');
            if($productAttributes)
            {
                foreach($productAttributes as &$productattribute)
                {
                    
                    $productattribute['name_attribute'] = $this->module->getProductAttributeName($productattribute['id_product_attribute']);
                    $attribute_images = Db::getInstance()->executeS('SELECT id_image FROM `'._DB_PREFIX_.'product_attribute_image` WHERE id_product_attribute='.(int)$productattribute['id_product_attribute']);
                    $productattribute['images'] = array();
                    if($attribute_images)
                    {
                        foreach($attribute_images as $attribute_image)
                            $productattribute['images'][] = $attribute_image['id_image'];
                    }
                    if($this->product->id_tax_rules_group)
                    {
                        $tax = $this->module->getTaxValue($this->product->id_tax_rules_group);
                        $productattribute['price_tax_incl'] = Tools::ps_round($productattribute['price'] + ($productattribute['price']*$tax),6);
                    }
                    else
                        $productattribute['price_tax_incl']= $productattribute['price'];
                }
            }
            $product_images = Db::getInstance()->executeS('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$this->product->id);
            if($product_images)
            {
                if(version_compare(_PS_VERSION_, '1.7', '>='))
                    $type_image= ImageType::getFormattedName('small');
                else
                    $type_image= ImageType::getFormatedName('small');
                foreach($product_images as &$image)
                {
                    $image['link'] = $this->context->link->getImageLink($this->product->link_rewrite[$this->context->language->id],$image['id_image'],$type_image);
                }
            }
        }
        else
        {
            $product_images =array();
            $productAttributes = array();
        }
        $this->context->smarty->assign(
            array(
                'product_images' => $product_images,
                'default_currency' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT')),
                'product_class' => $this->product,
                'productAttributes' => $productAttributes,
                'is17' => $this->module->is17,
            )
        );
        return  $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/list_combinations.tpl');
    }
    public function renderFormShipping()
    {
        $selected_carriers = array();
        $product_carriers = Db::getInstance()->executeS('SELECT id_carrier_reference FROM `'._DB_PREFIX_.'product_carrier` WHERE id_product='.(int)$this->product->id);
        if($product_carriers)
        {
            foreach($product_carriers as $product_carrier)
            {
                $selected_carriers[]=$product_carrier['id_carrier_reference'];
            }
        }
        if($carriers = $this->seller->getListCarriersUser())
        {
            foreach($carriers as &$carrier)
            {
                if(!$carrier['name'])
                    $carrier['name'] = Configuration::get('PS_SHOP_NAME');
            }
        }
        $this->context->smarty->assign(
            array(
                'carriers' => $carriers,
                'selected_carriers' => $selected_carriers,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/shipping.tpl');
    }
    public function renderFormPrice()
    {
        $specific_prices = Db::getInstance()->executeS('
            SELECT sp.*,cul.name as currency_name, col.name as country_name, gl.name as group_name,CONCAT(c.firstname," ",c.lastname) as customer_name FROM `'._DB_PREFIX_.'specific_price` sp
            LEFT JOIN '._DB_PREFIX_.(version_compare(_PS_VERSION_, '1.7.6.0', '>=')? 'currency_lang':'currency').' cul ON (cul.id_currency= sp.id_currency '.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? ' AND cul.id_lang ="'.(int)$this->context->language->id.'"':'').')
            LEFT JOIN `'._DB_PREFIX_.'country_lang` col ON (col.id_country= sp.id_country AND col.id_lang="'.(int)$this->context->language->id.'") 
            LEFT JOIN `'._DB_PREFIX_.'group_lang` gl ON (gl.id_group=sp.id_group AND gl.id_lang="'.(int)$this->context->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=sp.id_customer)
            WHERE sp.id_product='.(int)$this->product->id.' ORDER BY sp.id_specific_price asc');
        if($specific_prices)
        {
            foreach($specific_prices as &$specific_price)
            {
                if($specific_price['id_product_attribute'])
                {
                    $specific_price['attribute_name'] = $this->module->getProductAttributeName($specific_price['id_product_attribute']);
                    
                }
                if($specific_price['price']>=0)
                {
                    $specific_price['price_text'] = Tools::displayPrice($specific_price['price'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                }
                else
                    $specific_price['price_text'] ='--';
                if($specific_price['reduction_type']=='amount')
                {
                    $specific_price['reduction'] = Tools::displayPrice($specific_price['reduction'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))).($specific_price['reduction_tax'] ? ' ('.$this->module->l('Tax incl.','products').')':' ('.$this->module->l('Tax excl.','products').')');
                }
                else
                    $specific_price['reduction'] = Tools::ps_round($specific_price['reduction']*100,2).'%';
            }
        }
        $this->context->smarty->assign(
            array(
                'specific_prices' => $specific_prices,
                'specific_prices_from'=> $this->renderSpecificPrice(),
                'tax_rules_groups' =>  TaxRulesGroup::getTaxRulesGroupsForOptions(),
                'currency_default' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT')),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/price.tpl');
    }
    public function renderSpecificPrice($id_specific_price=0)
    {
        $currencies = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'currency` c
            INNER JOIN `'._DB_PREFIX_.'currency_shop` cs ON (c.id_currency = cs.id_currency AND cs.id_shop="'.(int)$this->context->shop->id.'")
            LEFT JOIN '._DB_PREFIX_.(version_compare(_PS_VERSION_, '1.7.6.0', '>=')? 'currency_lang':'currency').' cl ON (c.id_currency = cl.id_currency '.(version_compare(_PS_VERSION_, '1.7.6.0', '>=')? 'AND cl.id_lang="'.(int)$this->context->language->id.'"':'').')
            WHERE c.active=1');
        $countries = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'country` c
            INNER JOIN `'._DB_PREFIX_.'country_shop` cs ON (c.id_country = cs.id_country AND cs.id_shop="'.(int)$this->context->shop->id.'")
            LEFT JOIN `'._DB_PREFIX_.'country_lang` cl ON (c.id_country= cl.id_country AND cl.id_lang ="'.(int)$this->context->language->id.'")
            WHERE c.active=1    
        ');
        $groups = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'group` g
            INNER JOIN `'._DB_PREFIX_.'group_shop` gs ON (g.id_group = gs.id_group AND gs.id_shop="'.(int)$this->context->shop->id.'")
            LEFT JOIN `'._DB_PREFIX_.'group_lang` gl ON (g.id_group=gl.id_group AND gl.id_lang="'.(int)$this->context->language->id.'")
        ');
        $productAttributes = Db::getInstance()->executeS('SELECT pa.*,sa.quantity FROM `'._DB_PREFIX_.'product_attribute` pa
        LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON (pa.id_product_attribute=sa.id_product_attribute)
        WHERE pa.id_product='.(int)$this->product->id.' ORDER BY pa.id_product_attribute ASC');
        if($productAttributes)
        {
            foreach($productAttributes as &$productattribute)
            {
                
                $productattribute['name_attribute'] = $this->module->getProductAttributeName($productattribute['id_product_attribute']);
                $attribute_images = Db::getInstance()->executeS('SELECT id_image FROM `'._DB_PREFIX_.'product_attribute_image` WHERE id_product_attribute='.(int)$productattribute['id_product_attribute']);
                $productattribute['images'] = array();
                if($attribute_images)
                {
                    foreach($attribute_images as $attribute_image)
                        $productattribute['images'][] = $attribute_image['id_image'];
                }
            }
        }
        $specific_price= new SpecificPrice($id_specific_price);
        $this->context->smarty->assign(
            array(
                'currencies' => $currencies,
                'countries' => $countries,
                'groups' => $groups,
                'productAttributes' => $productAttributes,
                'default_currency' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT')),
                'specific_price' => $specific_price,
                'specific_price_customer' => new Customer($specific_price->id_customer),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/specific_price.tpl');
    }
    public function renderFormSeo()
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/seo.tpl');
    }
    public function renderFormOptions()
    {
        $this->context->smarty->assign(
            array(
                '_is17' => $this->module->is17,
                'product_class' => $this->product,
                'html_form_supplier' => $this->renderFormSupplier(),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/options.tpl');
    }
    public function renderFormSupplier()
    {
        $suppliers = $this->seller->getSuppliers(' AND s.active=1','',false,false);
        if($suppliers)
        {
            foreach($suppliers as &$supplier)
            {
                $supplier['checked'] = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'product_supplier WHERE id_product="'.(int)$this->product->id.'" AND id_supplier="'.(int)$supplier['id_supplier'].'"') ? true:false;
                if($supplier['checked'])
                {
                    
                    $supplier['product_suppliers'] =$this->refreshProductSupplierCombinationForm($supplier['id_supplier']);
                }
                else
                    $supplier['product_suppliers'] = '';
            }
            $this->context->smarty->assign(
                array(
                    'suppliers' => $suppliers,
                    'id_supplier_default' => $this->product->id_supplier,
                    'currencies' => Db::getInstance()->executeS('SELECT c.*,cl.name,'.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'cl.symbol':'cl.sign').' as symbol FROM '._DB_PREFIX_.'currency c
                    INNER JOIN '._DB_PREFIX_.'currency_shop cs ON (c.id_currency=cs.id_currency AND cs.id_shop="'.(int)$this->context->shop->id.'")
                    LEFT JOIN '._DB_PREFIX_.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'currency_lang':'currency').' cl ON (cl.id_currency=c.id_currency'.(version_compare(_PS_VERSION_, '1.7.6.0', '>=')? ' AND cl.id_lang="'.(int)$this->context->language->id.'"':'').')
                    '),
                    'currency_default' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))
                )
            );
            return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/supplier.tpl');
        }
    }
    public function refreshProductSupplierCombinationForm($id_supplier)
    {
        $has_attribute = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'product_attribute WHERE id_product="'.(int)$this->product->id.'"');
        $currency_default = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $product_suppliers = Db::getInstance()->executeS('SELECT p.id_product,'.($has_attribute ? ' pa.id_product_attribute':'0').' as id_product_attribute,pl.name as product_name,ps.product_supplier_reference,ps.product_supplier_price_te,IF(ps.id_currency,ps.id_currency,"'.(int)$currency_default->id.'") as id_currency,IF('.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'cl.symbol':'cl.sign').','.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'cl.symbol':'cl.sign').',"'.pSQL($currency_default->sign).'") as symbol FROM '._DB_PREFIX_.'product p
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = "'.(int)$this->context->language->id.'")
            '.($has_attribute ? ' LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON(p.id_product=pa.id_product)':'').'
            LEFT JOIN '._DB_PREFIX_.'product_supplier ps ON (ps.id_product = p.id_product'.($has_attribute ? ' AND pa.id_product_attribute=ps.id_product_attribute':'').' AND ps.id_supplier="'.(int)$id_supplier.'")
            LEFT JOIN '._DB_PREFIX_.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'currency_lang':'currency').' cl ON (cl.id_currency=ps.id_currency'.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ?' AND cl.id_lang="'.(int)$this->context->language->id.'"':'').')
            WHERE p.id_product="'.(int)$this->product->id.'"
            GROUP BY p.id_product'.($has_attribute ? ',pa.id_product_attribute':'').'
        ');
        if($product_suppliers)
        {
            foreach($product_suppliers as &$product_supplier)
            {
                if($product_supplier['id_product_attribute'])
                $product_supplier['product_name'] = $this->module->getProductAttributeName($product_supplier['id_product_attribute']);
            }
        }
        $this->context->smarty->assign(
            array(
                'product_suppliers' => $product_suppliers,
                'supplier_class' => new Supplier($id_supplier),
                'currencies' => Db::getInstance()->executeS('SELECT c.*,cl.name,'.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'cl.symbol' :'cl.sign').' as symbol FROM '._DB_PREFIX_.'currency c
                INNER JOIN '._DB_PREFIX_.'currency_shop cs ON (c.id_currency=cs.id_currency AND cs.id_shop="'.(int)$this->context->shop->id.'")
                LEFT JOIN '._DB_PREFIX_.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? 'currency_lang':'currency').' cl ON (cl.id_currency=c.id_currency'.(version_compare(_PS_VERSION_, '1.7.6.0', '>=') ? ' AND cl.id_lang="'.(int)$this->context->language->id.'"':'').')
                '),
                'currency_default' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))
                
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/product_supplier_combination_form.tpl');
    }
    public function _checkValidateSpecificPrice()
    {
        if(!$this->product->id)
            $this->errors[] = $this->module->l('Product is null','products');
        elseif(($id_specific_price= Tools::getValue('id_specific_price')) && ($specific_price = new SpecificPrice($id_specific_price)) && (!Validate::isLoadedObject($specific_price) || $specific_price->id_product!= $this->product->id))
        {
            $this->errors[] = $this->module->l('Specific price is not valid','products');
        }
        else
        {
            $sql ='SELECT * FROM `'._DB_PREFIX_.'specific_price` 
            WHERE `id_product`="'.(int)$this->product->id.'" 
            AND `id_currency`="'.(int)Tools::getValue('specific_price_id_currency').'" 
            AND `id_group`="'.(int)Tools::getValue('specific_price_id_group').'" 
            AND `id_country`="'.(int)Tools::getValue('specific_price_id_country').'" 
            AND `id_product_attribute`= "'.(int)Tools::getValue('specific_price_id_product_attribute').'"
            AND `id_customer`= "'.(int)Tools::getValue('specific_price_id_customer').'" 
            AND `from` = "'.(Tools::getValue('specific_price_from') ? pSQL(Tools::getValue('specific_price_from')):'0000-00-00 00:00:00' ).'"
            AND `to`= "'.(Tools::getValue('specific_price_to') ? pSQL(Tools::getValue('specific_price_to')):'0000-00-00 00:00:00' ).'"
            AND `from_quantity`="'.(int)Tools::getValue('specific_price_from_quantity').'"';
            if(!(float)Tools::getValue('specific_price_sp_reduction'))
                $this->errors[] = $this->module->l('No reduction value has been submitted','products');
            elseif((float)Tools::getValue('specific_price_sp_reduction')<=0)
                $this->errors[] = $this->module->l('Reduction value is not valid','products');
            elseif((int)Tools::getValue('specific_price_from_quantity') < 1 || !Validate::isUnsignedInt(Tools::getValue('specific_price_from_quantity')))
                $this->errors[] = $this->module->l('From quantity is not valid','products');
            elseif(Db::getInstance()->getRow($sql) && !$id_specific_price)
                $this->errors[] = $this->module->l('A specific price already exists for these parameters.','products');
            if(!Tools::getValue('specific_price_leave_bprice') &&  (!(float)Tools::getValue('specific_price_product_price') || !Validate::isUnsignedFloat(Tools::getValue('specific_price_product_price'))))
                $this->errors[] = $this->module->l('Product price is not valid','products');
            if(Tools::getValue('specific_price_from') && Tools::getValue('specific_price_from')!='0000-00-00 00:00:00' && !Validate::isDate(Tools::getValue('specific_price_from')))
                $this->errors[] = $this->module->l('Available from is not valid','products');
            if(Tools::getValue('specific_price_to') && Tools::getValue('specific_price_to')!='0000-00-00 00:00:00' && !Validate::isDate(Tools::getValue('specific_price_to')))
                $this->errors[] = $this->module->l('Available to is not valid','products');
        }
        if(!$this->errors)
            return true;
        else
            return false;
    }
    public function _submitSavePecificPrice()
    {
        if($id_specific_price = Tools::getValue('id_specific_price'))
        {
            $specific_price = new SpecificPrice($id_specific_price);
        }
        else
            $specific_price = new SpecificPrice();
        $specific_price->id_product = $this->product->id;
        $specific_price->id_product_attribute = (int)Tools::getValue('specific_price_id_product_attribute');
        $specific_price->id_currency = (int)Tools::getValue('specific_price_id_currency');
        $specific_price->id_country = (int)Tools::getValue('specific_price_id_country');
        $specific_price->id_group = (int)Tools::getValue('specific_price_id_group');
        $specific_price->id_customer = (int)Tools::getValue('specific_price_id_customer');
        $specific_price->from_quantity = (int)Tools::getValue('specific_price_from_quantity');
        $specific_price->from = Tools::getValue('specific_price_from') ? Tools::getValue('specific_price_from'):'0000-00-00 00:00:00';
        $specific_price->to = Tools::getValue('specific_price_to') ? Tools::getValue('specific_price_to'):'0000-00-00 00:00:00';
        $specific_price->id_shop = $this->context->shop->id;
        if(Tools::getValue('specific_price_leave_bprice'))
            $specific_price->price=-1;
        else
            $specific_price->price = (int)Tools::getValue('specific_price_product_price');
        $specific_price->reduction_type= Tools::getValue('specific_price_sp_reduction_type');
        if($specific_price->reduction_type=='amount')
            $specific_price->reduction = (float)Tools::getValue('specific_price_sp_reduction');
        else
            $specific_price->reduction = (float)Tools::getValue('specific_price_sp_reduction')/100;
        $specific_price->reduction_tax = (int)Tools::getValue('specific_price_sp_reduction_tax');
        if($specific_price->id)
        {
            if($specific_price->update())
                $success = $this->module->l('Updated specific price successfully','products');
            else
                $this->errors[] = $this->module->l('An error occurred while updating the specific price','products');
        }
        else
        {
            if($specific_price->add())
                $success = $this->module->l('Added specific price successfully','products');
            else
                $this->errors[] = $this->module->l('An error occurred while creating the specific price','products');
        }
        if(!$this->errors)
        {
            $specific = Db::getInstance()->getRow('
                SELECT sp.*,cul.name as currency_name, col.name as country_name, gl.name as group_name,CONCAT(c.firstname," ",c.lastname) as customer_name FROM `'._DB_PREFIX_.'specific_price` sp
                LEFT JOIN '._DB_PREFIX_.(version_compare(_PS_VERSION_, '1.7.6.0', '>=')? 'currency_lang':'currency').' cul ON (cul.id_currency= sp.id_currency '.(version_compare(_PS_VERSION_, '1.7.6.0', '>=')? 'AND cul.id_lang="'.(int)$this->context->language->id.'"':'').')
                LEFT JOIN `'._DB_PREFIX_.'country_lang` col ON (col.id_country= sp.id_country AND col.id_lang="'.(int)$this->context->language->id.'") 
                LEFT JOIN `'._DB_PREFIX_.'group_lang` gl ON (gl.id_group=sp.id_group AND gl.id_lang="'.(int)$this->context->language->id.'")
                LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=sp.id_customer)
                WHERE sp.id_product='.(int)$this->product->id.' AND sp.id_specific_price = "'.(int)$specific_price->id.'"');
            if($specific['id_product_attribute'])
            {
                $specific['attribute_name'] = $this->module->getProductAttributeName($specific['id_product_attribute']);
                
            }
            if($specific['price']>=0)
            {
                $specific['price_text'] = Tools::displayPrice($specific['price'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
            }
            else
                $specific['price_text'] ='--';
           
            if($specific['reduction_type']=='amount')
            {
                $specific['reduction'] = Tools::displayPrice($specific['reduction'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))).($specific['reduction_tax'] ? ' ('.$this->module->l('Tax incl.','products').')':' ('.$this->module->l('Tax excl.','products').')');
            }
            else
                $specific['reduction'] = Tools::ps_round($specific['reduction']*100,2).'%';
            $specific['form'] = Tools::displayDate($specific_price->from,$this->context->language->id,true);
            $specific['to'] = Tools::displayDate($specific_price->to,$this->context->language->id,true);
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $success,
                        'specific' => $specific,
                    )
                )
            );
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->errors)
                    )
                )
            );
        }
            
    }
    public function _checkValidateProduct()
    {
        $id_lang_default =Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(true);
       
        if(!trim(Tools::getValue('name_'.$id_lang_default)))
        {
            $this->errors[] = $this->module->l('Product name is required','products');
        }
        else
        {
            foreach($languages as $language)
            {
                if(Tools::getValue('name_'.$language['id_lang']) && !Validate::isCatalogName(Tools::getValue('name_'.$language['id_lang'])))
                    $this->errors[] = $this->module->l('Product name is not valid in','proudcts').' '.$language['iso_code'];
            }
        }
        if(in_array('seo',$this->seller_product_information))
        {
            if(!trim(Tools::getValue('link_rewrite_'.$id_lang_default)))
                $this->errors[] = $this->module->l('Product link rewrite is required','products');
            else{
                foreach($languages as $language)
                {
                    if(Tools::getValue('link_rewrite_'.$language['id_lang']) && !Validate::isLinkRewrite(Tools::getValue('link_rewrite_'.$language['id_lang'])))
                        $this->errors[] = $this->module->l('Product link rewrite is not valid in','proudcts').' '.$language['iso_code'];
                }
            }
        }
        if(!$id_categories = Tools::getValue('id_categories'))
            $this->errors[] = $this->module->l('Category is required','products');
        elseif(!is_array($id_categories))
            $this->errors[] = $this->module->l('Category is not valid','products');
        elseif(!$id_category_default =Tools::getValue('id_category_default'))
            $this->errors[] = $this->module->l('Default category is required','products');
        elseif(!in_array($id_category_default,$id_categories))
            $this->errors[] = $this->module->l('Default category is not valid','products');
        elseif(Configuration::get('ETS_MP_APPLICABLE_CATEGORIES')=='specific_product_categories')
        {
           $seller_categories = ($categories = Configuration::get('ETS_MP_SELLER_CATEGORIES')) ? explode(',',$categories) : array();
           foreach($id_categories as $id_category)
           {
                if(!in_array($id_category,$seller_categories))
                    $this->errors[] = sprintf($this->module->l('Category #%d is not valid','products'),$id_category);
           }
        }
        foreach($languages as $language)
        {
            if(in_array('short_description',$this->seller_product_information))
            {
                if(Tools::getValue('description_short_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('description_short_'.$language['id_lang'])))
                    $this->errors[] = $this->module->l('Summary is not valid in','products').' '.$language['iso_code'];
                $short_description_limit= Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT') ? :800;
                if(Tools::strlen(strip_tags(Tools::getValue('description_short_'.$language['id_lang'])))>$short_description_limit)
                    $this->errors[]= '['.$language['iso_code'].'] '.$this->module->l('Summary is too long. It should have 800 characters or less.','products');
            }
            if(Tools::getValue('description_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('description_'.$language['id_lang'])))
                $this->errors[] = $this->module->l('Description is not valid in','products').' '.$language['iso_code'];
            if(Tools::strlen(strip_tags(Tools::getValue('description_'.$language['id_lang'])))>21844)
                $this->errors[]= '['.$language['iso_code'].'] '.$this->module->l('Description is too long. It should have 21844 characters or less.','products');
            if(Tools::getValue('meta_title_'.$language['id_lang']) && (!Validate::isCleanHtml(Tools::getValue('meta_title_'.$language['id_lang'])) || Tools::strlen(Tools::getValue('meta_title_'.$language['id_lang'])) >70))
                $this->errors[] = $this->module->l('Meta title is not valid in','products').' '.$language['iso_code'];
            if(Tools::getValue('meta_description_'.$language['id_lang']) && (!Validate::isCleanHtml(Tools::getValue('meta_description_'.$language['id_lang'])) || Tools::strlen(Tools::getValue('meta_description_'.$language['id_lang'])) >512))
                $this->errors[] = $this->module->l('Meta description is not valid in','products').' '.$language['iso_code'];
            if(Tools::getValue('delivery_in_stock_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('delivery_in_stock_'.$language['id_lang'])))
                $this->errors[] = $this->module->l('Time delivery in stock is not valid in','products').' '.$language['iso_code'];  
            if(Tools::getValue('delivery_out_stock_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('delivery_out_stock_'.$language['id_lang'])))
                $this->errors[] = $this->module->l('Time delivery out stock is not valid in','products').' '.$language['iso_code'];      
        }
        if(trim(Tools::getValue('price_excl'))==='')
        {
            $this->errors[]= $this->module->l('Product price is required','products');
        }
        elseif(!Validate::isUnsignedFloat(Tools::getValue('price_excl')))
            $this->errors[]= $this->module->l('Product price is not valid','products');
        if(trim(Tools::getValue('width'))!=='' && (!Validate::isUnsignedFloat(Tools::getValue('width')) || Tools::getValue('width')==0))
            $this->errors[] = $this->module->l('Product width is not valid','products');
        if(trim(Tools::getValue('height'))!=='' && (!Validate::isUnsignedFloat(Tools::getValue('height')) || Tools::getValue('height')==0))
            $this->errors[] = $this->module->l('Product height is not valid','products');
        if(trim(Tools::getValue('depth'))!=='' && (!Validate::isUnsignedFloat(Tools::getValue('depth')) || Tools::getValue('depth') ==0 ))
            $this->errors[] = $this->module->l('Product depth is not valid','products');
        if(trim(Tools::getValue('weight'))!=='' &&  (!Validate::isUnsignedFloat(Tools::getValue('weight')) || Tools::getValue('weight')==0 ))
            $this->errors[] = $this->module->l('Product weight is not valid','products');
        if(trim(Tools::getValue('additional_shipping_cost'))!=='' &&  (!Validate::isUnsignedFloat(Tools::getValue('additional_shipping_cost')) || Tools::getValue('additional_shipping_cost')==0 ))
            $this->errors[] = $this->module->l('Shipping fee is not valid','products');
        if(($condition = Tools::getValue('condition')) && !Validate::isGenericName($condition))
            $this->errors[] = $this->module->l('Condition is not valid','products');
        if($this->module->is17)
        {
            if(($show_condition = Tools::getValue('show_condition')) && !Validate::isBool($show_condition))
                $this->errors[] = $this->module->l('Show condition is not valid','products');
        }
        if(($isbn = Tools::getValue('isbn')) && !Validate::isIsbn($isbn))
            $this->errors[] = $this->module->l('ISBN is not valid','products');
        if(($ean13 = Tools::getValue('ean13')) && !Validate::isEan13($ean13))
            $this->errors[] = $this->module->l('Ean13 is not valid','products');
        if(($upc = Tools::getValue('upc')) && !Validate::isUpc($upc))
            $this->errors[] = $this->module->l('Upc is not valid','products');
        if($custom_fields = Tools::getValue('custom_fields'))
        {
            foreach($custom_fields as $custom_field)
            {
                if(!$custom_field['label'][$id_lang_default])
                {
                    $this->errors[] = $this->module->l('Customization label is required','products');
                    break;
                }
                if(!Validate::isUnsignedInt($custom_field['type']))
                {
                    $this->errors[] = $this->module->l('Customization type is not valid','products');
                    break;
                }
                foreach($languages as $language)
                {
                    if($custom_field['label'][$language['id_lang']] && !Validate::isCleanHtml($custom_field['label'][$language['id_lang']]))
                    {
                        $this->errors[] = $this->module->l('Customization label is not valid','products');
                        break;
                    }
                }
                if($id_customization_field = (int)$custom_field['id_customization_field'])
                {
                    $customizationField = new CustomizationField($id_customization_field);
                    if(!Validate::isLoadedObject($customizationField) || $customizationField->id_product != $this->product->id)
                    {
                        $this->errors[] = $this->module->l('Customization field is not valid','products');
                    }
                }
            }
        }
        if(Tools::getValue('product_type')==1)
        {
            if(!$inputPackItems = Tools::getValue('inputPackItems'))
                $this->errors[] = $this->module->l('This pack is empty. You must add at least one product item.','products');
            else{
                foreach($inputPackItems as $inputPackItem)
                {
                    $packItem = explode('x',$inputPackItem);
                    if(!isset($packItem[0]) || !isset($packItem[1]) || !isset($packItem[2]))
                    {
                        $this->errors[] = sprintf($this->module->l('Pack item #%s is not valid','products'),$inputPackItem);
                    }
                    else
                    {
                        if(!$id_product_item = $packItem[0])
                            $this->errors[] = $this->module->l('Id product pack item is required','products');
                        elseif(($productPackItem = new Product($id_product_item)) && (!Validate::isLoadedObject($productPackItem) || !$this->seller->checkHasProduct($id_product_item)))
                            $this->errors[] = sprintf($this->module->l('Product of pack item #%s is not valid','products'),$inputPackItem);
                        if(($id_product_attribute_item = $packItem[1]) && ($combination = new Combination($id_product_attribute_item)) && (!Validate::isLoadedObject($combination) || $combination->id_product!=$id_product_item))
                            $this->errors[] = sprintf($this->module->l('Combination if pack item #%s is not valid','products'),$inputPackItem);
                        if(!$quantity_item = $packItem[2])
                            $this->errors[] = sprintf($this->module->l('Quantity of pack item #%s is required','products'),$inputPackItem);
                        elseif($quantity_item && !Validate::isUnsignedInt($quantity_item))
                            $this->errors[] = sprintf($this->module->l('Quantity of pack item #%s is not valid','products'),$inputPackItem);
                        
                    }
                    
                }
            }
        }
        if($id_manufacturer = (int)Tools::getValue('id_manufacturer'))
        {
            $manufacture = new Manufacturer($id_manufacturer);
            if(!Validate::isLoadedObject($manufacture) || !$this->seller->checkHasManufacturer($manufacture->id))
               $this->errors[] = $this->module->l('Brand is not valid','products');
        }
        if(($id_features = Tools::getValue('id_features')) && ($id_feature_values = Tools::getValue('id_feature_values')))
        {
            foreach($id_features as $index=> $id_feature)
            {
                if($id_feature && ($feature = new Feature($id_feature)) && (!Validate::isLoadedObject($feature) || !$this->seller->checkHasFeature($id_feature)))
                    $this->errors[] = sprintf($this->module->l('Feature #%d is not valid','products'),$id_feature);
                elseif($id_feature && isset($id_feature_values[$index]) && ($id_feature_value = $id_feature_values[$index]))
                {
                    if(($featureValue = new FeatureValue($id_feature_value)) && (!Validate::isLoadedObject($featureValue) || $featureValue->id_feature!= $id_feature))
                        $this->errors[] = sprintf($this->module->l('Feature value #%d is not valid','products'),$id_feature_value);
                }
            }
             
        }
        if(Tools::isSubmit('submitCreateCombination') && ($attribute_options = Tools::getValue('attribute_options')))
        {
            $check_attribute = true;
            if(!is_array($attribute_options))
                $this->errors[] = $this->module->l('Attribute options is not valid','products');
            else
            {
                foreach($attribute_options as $id_attribute_group => $id_attributes)
                {
                    
                    if(!$id_attribute_group || !$id_attributes)
                    {
                        $this->errors[] = $this->module->l('Attribute options is not valid','products');
                        $check_attribute= false;
                        break;
                    }
                }
                if($check_attribute)
                {
                    foreach($attribute_options as $id_attribute_group => $id_attributes)
                    {
                        foreach($id_attributes as $id_attribute)
                        {
                            if(($attributeGroup = new AttributeGroup($id_attribute_group)) && ($attribute = new Attribute($id_attribute)) && (!Validate::isLoadedObject($attributeGroup) || !Validate::isLoadedObject($attribute) || $attribute->id_attribute_group!= $id_attribute_group || !$this->seller->checkHasAttributeGroup($id_attribute_group)))
                                $this->errors[] = sprintf($this->module->l('Attribute #%d is not valid','products'),$attribute->id_attribute_group);
                        }
                        
                            
                    }
                }
            }
        }
        if(Tools::getValue('product_type')!=2 && $selectedCarriers = Tools::getValue('selectedCarriers'))
        {
            if(!is_array($selectedCarriers))
                $this->errors[] = $this->module->l('Available carriers are not valid','products');
            else
            {
                foreach($selectedCarriers as $selectedCarrier)
                {
                    if($selectedCarrier && ($carrier = new Carrier($selectedCarrier)) && (!Validate::isLoadedObject($carrier) || !$this->seller->getListCarriersUser(0,$selectedCarrier)))
                        $this->errors[] = sprintf($this->module->l('Carrier (#%d) is not valid','products'),$selectedCarrier);
                    
                }
            }
        }
        if($id_suppliers = Tools::getValue('id_suppliers'))
        {
            $product_supplier_reference = Tools::getValue('product_supplier_reference');
            $product_supplier_price = Tools::getValue('product_supplier_price');
            $product_supplier_price_currency = Tools::getValue('product_supplier_price_currency');
            foreach($id_suppliers as $id_supplier)
            {
                $supplier = new Supplier($id_supplier);
                if(!Validate::isLoadedObject($supplier) || !$this->seller->checkHasSupplier($supplier->id,true))
                {
                    $this->errors[] = sprintf($this->module->l('Supplier (#%d) is not valid','products'),$id_supplier);
                }
                else
                {
                    if(isset($product_supplier_reference[$id_supplier]) && ($references = $product_supplier_reference[$id_supplier]))
                    {
                        foreach($references as $reference)
                            if($reference && !Validate::isReference($reference))
                                $this->errors[] = printf($this->module->l('Supplier reference (%s) is not valid','products'),$reference);
                    }
                    if(isset($product_supplier_price[$id_supplier]) && ($prices = $product_supplier_price[$id_supplier]))
                    {
                        foreach($prices as $price)
                        {
                            if($price && !Validate::isPrice($price))
                                $this->errors[] = printf($this->module->l('Product price from supplier : (%s) is not valid','products'),$price);
                        }
                    }
                    if(isset($product_supplier_price_currency[$id_supplier]) && ($currencies = $product_supplier_price_currency[$id_supplier]))
                    {
                        foreach($currencies as $id_currency)
                        {
                            $currency_class = new Currency($id_currency);
                            if(!Validate::isLoadedObject($currency_class))
                                $this->errors[] = printf($this->module->l('Product currency from supplier : (%d) is not valid','products'),$id_currency);
                        }
                    }
                }
            }
        }
        if($related_products = Tools::getValue('related_products'))
        {
            foreach($related_products as $related_product)
            {
                $related_product_obj = new Product($related_product);
                if(!Validate::isLoadedObject($related_product_obj) || !$this->seller->checkHasProduct($related_product))
                {
                    $this->errors[] = $this->module->l('Related product is not valid','products');
                    break;
                }    
            }
        }
        if($this->errors)
            return false;
        else
            return true;
    }
    public function _submitSaveProduct()
    {
        $languages = Language::getLanguages(false);
        if(Configuration::get('ETS_MP_APPLICABLE_CATEGORIES')=='specific_product_categories' && Configuration::get('ETS_MP_SELLER_CATEGORIES'))
        {
            $seller_categories = explode(',',Configuration::get('ETS_MP_SELLER_CATEGORIES'));
        }
        $id_lang_default =Configuration::get('PS_LANG_DEFAULT');
        if($languages)
        {
            foreach($languages as $language)
            {
                $this->product->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']) ? Tools::getValue('name_'.$language['id_lang']) : Tools::getValue('name_'.$id_lang_default);
                if(in_array('short_description',$this->seller_product_information))
                    $this->product->description_short[$language['id_lang']] = Tools::getValue('description_short_'.$language['id_lang']) && Tools::strpos(Tools::getValue('description_short_'.$language['id_lang']),'etsmp_imThePlaceholder')===false ? Tools::getValue('description_short_'.$language['id_lang']) : (Tools::strpos(Tools::getValue('description_short_'.$id_lang_default),'etsmp_imThePlaceholder')===false ? Tools::getValue('description_short_'.$id_lang_default):'');
                /** _ARM_ Delete placeholder from description */
                $this->product->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']) && Tools::strpos(Tools::getValue('description_'.$language['id_lang']),'etsmp_imThePlaceholder')===false ? Tools::getValue('description_'.$language['id_lang']) : (Tools::strpos(Tools::getValue('description_'.$id_lang_default),'etsmp_imThePlaceholder')===false ? Tools::getValue('description_'.$id_lang_default):'');
                if(in_array('seo',$this->seller_product_information))
                {
                    $this->product->meta_title[$language['id_lang']] = Tools::getValue('meta_title_'.$language['id_lang']) ? Tools::getValue('meta_title_'.$language['id_lang']) : Tools::getValue('meta_title_'.$id_lang_default);
                    $this->product->meta_description[$language['id_lang']] = Tools::getValue('meta_description_'.$language['id_lang']) ? Tools::getValue('meta_description_'.$language['id_lang']) : Tools::getValue('meta_description_'.$id_lang_default);
                    $this->product->link_rewrite[$language['id_lang']] = Tools::getValue('link_rewrite_'.$language['id_lang']) ? Tools::getValue('link_rewrite_'.$language['id_lang']) : Tools::getValue('link_rewrite_'.$id_lang_default);
                }
                elseif(!isset($this->product->link_rewrite[$language['id_lang']]) || !$this->product->link_rewrite[$language['id_lang']])
                    $this->product->link_rewrite[$language['id_lang']] = Tools::link_rewrite($this->product->name[$language['id_lang']]);
                $this->product->delivery_in_stock[$language['id_lang']] = Tools::getValue('delivery_in_stock_'.$language['id_lang']);
                $this->product->delivery_out_stock[$language['id_lang']] = Tools::getValue('delivery_out_stock_'.$language['id_lang']);
            }
        }
        
        if(Tools::getValue('product_type')==2)
            $this->product->is_virtual=1;
        else
            $this->product->is_virtual=0;
        if(Tools::getValue('product_type')==1 && Tools::getValue('inputPackItems'))
            $this->product->cache_is_pack=1;
        else
            $this->product->cache_is_pack=0;
        if(in_array('product_reference',$this->seller_product_information))    
        {
            $this->product->reference = Tools::getValue('reference');
        }
        
        $this->product->price = (float)Tools::getValue('price_excl');
        $this->product->id_tax_rules_group = Tools::getValue('id_tax_rules_group');
        $this->product->width =(float)Tools::getValue('width');
        $this->product->height = (float)Tools::getValue('height');
        $this->product->depth = (float)Tools::getValue('depth');
        $this->product->weight =(float)Tools::getValue('weight');
        if($this->module->is17)
            $this->product->additional_delivery_times = Tools::getValue('additional_delivery_times');
        if(empty(Tools::getValue('shipping_cost'))) 
            $this->product->additional_shipping_cost = 0;
        else
            $this->product->additional_shipping_cost =(float)Tools::getValue('shipping_cost');
        
        $this->product->id_category_default = (int)Tools::getValue('id_category_default');
        $this->product->minimal_quantity =(int)Tools::getValue('product_minimal_quantity');
        $this->product->location = Tools::getValue('product_location');
        if($this->module->is17)
        {
            $this->product->low_stock_threshold = (int)Tools::getValue('product_low_stock_threshold');
            $this->product->low_stock_alert = (int)Tools::getValue('product_low_stock_alert');
        }
        if(in_array('out_of_stock_behavior',$this->seller_product_information))
            $this->product->out_of_stock = (int)Tools::getValue('out_of_stock');
        $this->product->id_manufacturer = (int)Tools::getValue('id_manufacturer');
        $this->product->redirect_type ='404';
        $this->product->condition = Tools::getValue('condition');
        if($this->module->is17)
            $this->product->show_condition = (int)Tools::getValue('show_condition');
        $this->product->isbn = Tools::getValue('isbn');
        $this->product->ean13 = Tools::getValue('ean13');
        $this->product->upc = Tools::getValue('upc');
        if(Tools::getValue('custom_fields') && isset($this->product->customizable))
        {
            $this->product->customizable=1;
        }
        if(($id_supplier_default = Tools::getValue('id_supplier_default')) && ($id_suppliers = Tools::getValue('id_suppliers')) && in_array($id_supplier_default,$id_suppliers))
            $this->product->id_supplier = $id_supplier_default;
        else
            $this->product->id_supplier=0;
        if(!$this->product->id)
        {
            if(Tools::isSubmit('active') && (!$this->seller->vacation_mode || $this->seller->vacation_type!='disable_product'))
            {
                $this->product->active=(int)Tools::getValue('active');
                $approved=1;
            }
            else
            {
                if($this->seller->auto_enabled_product=='yes')
                {
                    $this->product->active=1;
                    $approved=1;
                }
                elseif($this->seller->auto_enabled_product=='no')
                {
                    $this->product->active=0;
                    $approved=0;
                }
                elseif(Configuration::get('ETS_MP_SELLER_PRODUCT_APPROVE_REQUIRED'))
                {
                    $this->product->active=0;
                    $approved=0;
                }
                else
                {
                    $this->product->active=1;
                    $approved=1;
                }
                if($this->seller->vacation_mode && $this->seller->vacation_type=='disable_product')
                    $this->product->active=0;
            }
            $this->product->indexed =0;
            if($this->product->add())
            {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_product`(id_customer,id_product,approved,active) VALUES("'.(int)$this->seller->id_customer.'","'.(int)$this->product->id.'","'.(int)$approved.'","'.(int)$this->product->active.'")');
                if (Configuration::get('PS_SEARCH_INDEXATION')) {
                    Search::indexation(false, $this->product->id);
                }
                if(Configuration::get('ETS_MP_EMAIL_ADMIN_NEW_PRODUCT_UPLOADED'))
                {
                    $data= array(
                       '{seller_name}' => $this->seller->seller_name,
                       '{seller_email}' => $this->seller->seller_email,
                       '{shop_seller}'=> $this->seller->shop_name,
                       '{shop_seller_url}' => $this->module->getShopLink(array('id_seller'=>$this->seller->id)),
                       '{product_id}' => $this->product->id,
                       '{product_name}' => $this->product->name[$this->context->language->id],
                       '{product_link}' => $this->context->link->getProductLink($this->product->id),
                    );
                    $subjects = array(
                        'translation' => $this->module->l('A new product has been uploaded','products'),
                        'origin'=>'A new product has been uploaded',
                        'specific'=>'products'
                    );
                    Ets_marketplace::sendMail('to_admin_new_product_uploaded',$data,'',$subjects);
                    
                    
                }
            }
        }
        else
        {
            if(Tools::isSubmit('active') && (!$this->seller->vacation_mode || $this->seller->vacation_type!='disable_product'))
            {
                $this->product->active=(int)Tools::getValue('active');
            }
            $this->product->update();
        }

       
        if($this->product->id)
        {
            StockAvailable::setQuantity($this->product->id, 0, Tools::getValue('product_quantity'));
            StockAvailable::setProductOutOfStock($this->product->id,$this->product->out_of_stock);
            if($this->module->is17 && method_exists('StockAvailable','setLocation'))
                StockAvailable::setLocation($this->product->id,Tools::getValue('product_location'));
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'category_product` WHERE id_product='.(int)$this->product->id.(isset($seller_categories) && $seller_categories ? ' AND id_category IN ('.implode(',',array_map('intval',$seller_categories)).')':''));
            if($id_categories = Tools::getValue('id_categories'))
            {
                foreach($id_categories as $id_category)
                if(!isset($seller_categories) || (isset($seller_categories) && in_array($id_category,$seller_categories)))
                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'category_product` (id_product,id_category,position) VALUES("'.(int)$this->product->id.'","'.(int)$id_category.'","1")');
            }
            if($related_products = Tools::getValue('related_products'))
            {
                foreach($related_products as $related_product)
                {
                    if($related_product!=$this->product->id && !Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'accessory WHERE id_product_1="'.(int)$this->product->id.'" AND id_product_2="'.(int)$related_product.'"'))
                    {
                        Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'accessory(id_product_1,id_product_2) VALUES("'.(int)$this->product->id.'","'.(int)$related_product.'")');
                    }    
                }
                Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'accessory WHERE id_product_1="'.(int)$this->product->id.'" AND id_product_2 NOT IN ('.implode(',',array_map('intval',$related_products)).')');
            }
            else
                Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'accessory WHERE id_product_1="'.(int)$this->product->id.'"');
            if($this->module->_use_feature)
            {
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'feature_product` WHERE id_product='.(int)$this->product->id);
                if($id_features = Tools::getValue('id_features'))
                {
                    $id_feature_values = Tools::getValue('id_feature_values');
                    $feature_value_custom = Tools::getValue('feature_value_custom');
                    foreach($id_features as $key=> $id_feature)
                    {
                        if($id_feature)
                        {
                            if(isset($id_feature_values[$key]) && $id_feature_values[$key])
                            {
                                if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'feature_product` WHERE id_product="'.(int)$this->product->id.'" AND id_feature = "'.(int)$id_feature.'" AND id_feature_value="'.(int)$id_feature_values[$key].'"'))
                                {
                                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'feature_product`(id_product,id_feature,id_feature_value) VALUES("'.(int)$this->product->id.'","'.(int)$id_feature.'","'.(int)$id_feature_values[$key].'")');
                                }
                            }
                            elseif(isset($feature_value_custom[$key]) && $feature_value_custom[$key])
                            {
                                $feature_value = new FeatureValue();
                                $feature_value->id_feature = $id_feature;
                                $feature_value->custom=1;
                                foreach($languages as $language)
                                    $feature_value->value[$language['id_lang']] = $feature_value_custom[$key];
                                if($feature_value->add())
                                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'feature_product`(id_product,id_feature,id_feature_value) VALUES("'.(int)$this->product->id.'","'.(int)$id_feature.'","'.(int)$feature_value->id.'")');
                            }
                        }
                    }
                }
            }
            return $this->product->update();
        }    
        return $this->product->id;
    }
    public function _submitCreateCombination()
    {
        if(!$this->product->id)
        {
            if($this->_checkValidateProduct())
            {
                $this->_submitSaveProduct();
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->errors),
                        )
                    )
                );
            }
        }elseif(Tools::getValue('id_suppliers'))
        {
            if(!$this->_checkValidateProduct())
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->errors),
                        )
                    )
                );
        }
        if($this->product->id)
        {
            $this->product->is_virtual =0;
            $this->product->update();
            if($product_download = Db::getInstance()->getRow('SELECT id_product_download,filename FROM `'._DB_PREFIX_.'product_download` WHERE id_product="'.(int)$this->product->id.'"'))
            {
                $productDownload = new ProductDownload($product_download['id_product_download']);
                $productDownload->delete($product_download['filename']? true:false);
            }
            if (!is_array(Tools::getValue('attribute_options'))) {
                $this->errors[] = $this->module->l('Please select at least one attribute.','products');
            } else {
                $tab = array_values(Tools::getValue('attribute_options'));
                if (count($tab) && Validate::isLoadedObject($this->product)) {
                    
                    Ets_MarketPlaceProductsModuleFrontController::setAttributesImpacts($this->product->id, $tab);
                    $this->combinations = array_values(Ets_MarketPlaceProductsModuleFrontController::createCombinations($tab));
                    $values = array_values(array_map(array($this, 'addAttribute'), $this->combinations));
                    // @since 1.5.0
                    if ($this->module->is15 && $this->product->depends_on_stock == 0) {
                        $attributes = Product::getProductAttributesIds($this->product->id, true);
                        foreach ($attributes as $attribute) {
                            StockAvailable::removeProductFromStockAvailable($this->product->id, $attribute['id_product_attribute'], Context::getContext()->shop);
                        }
                    }
    
                    SpecificPriceRule::disableAnyApplication();
    
                    //$this->product->deleteProductAttributes();
                    if(!$this->module->is17)
                    {
                        Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_attribute SET default_on=NULL WHERE id_product="'.(int)$this->product->id.'" AND default_on=1');
                        Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_attribute_shop SET default_on=NULL WHERE id_product="'.(int)$this->product->id.'" AND default_on=1');
                    }    
                    $this->product->generateMultipleCombinations($values, $this->combinations,false);
                    // Reset cached default attribute for the product and get a new one
                    Product::getDefaultAttribute($this->product->id, 0, true);
                    Product::updateDefaultAttribute($this->product->id);
                    // @since 1.5.0
                    if ($this->module->is15 && $this->product->depends_on_stock == 0) {
                        $attributes = Product::getProductAttributesIds($this->product->id, true);
                        $quantity = (int)Tools::getValue('quantity');
                        foreach ($attributes as $attribute) {
                            if (Shop::getContext() == Shop::CONTEXT_ALL) {
                                $shops_list = Shop::getShops();
                                if (is_array($shops_list)) {
                                    foreach ($shops_list as $current_shop) {
                                        if (isset($current_shop['id_shop']) && (int)$current_shop['id_shop'] > 0) {
                                            StockAvailable::setQuantity($this->product->id, (int)$attribute['id_product_attribute'], $quantity, (int)$current_shop['id_shop']);
                                        }
                                    }
                                }
                            } else {
                                StockAvailable::setQuantity($this->product->id, (int)$attribute['id_product_attribute'], $quantity);
                            }
                        }
                    } else {
                        StockAvailable::synchronize($this->product->id);
                    }
    
                    SpecificPriceRule::enableAnyApplication();
                    SpecificPriceRule::applyAllRules(array((int)$this->product->id));
                } else {
                    $this->errors[] = $this->module->l('Unable to initialize these parameters. A combination is missing or an object cannot be loaded.','products');
                }
            }
            $this->_submitProductSupplier();
            if(!$this->errors)
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->module->l('Generated attribute successfully','products'),
                            'list_combinations' => $this->displayListCombinations(),
                            'id_product'=> $this->product->id,
                            'html_form_supplier' => $this->renderFormSupplier(),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->errors),
                        )
                    )
                );
            }
        }
        else
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($this->module->l('Product is null','products')),
                    )
                )
            );
        }
    }
    protected static function setAttributesImpacts($id_product, $tab)
    {
        $attributes = array();
        foreach ($tab as $group) {
            foreach ($group as $attribute) {
                $price = 0;
                $weight = 0;
                $attributes[] = '('.(int)$id_product.', '.(int)$attribute.', '.(float)$price.', '.(float)$weight.')';
            }
        }

        return Db::getInstance()->execute('
		INSERT INTO `'._DB_PREFIX_.'attribute_impact` (`id_product`, `id_attribute`, `price`, `weight`)
		VALUES '.implode(',', $attributes).'
		ON DUPLICATE KEY UPDATE `price` = VALUES(price), `weight` = VALUES(weight)');
    }
    protected static function createCombinations($list)
    {
        if (count($list) <= 1) {
            return count($list) ? array_map(function ($v) { return array($v); }, $list[0]) : $list;
        }
        $res = array();
        $first = array_pop($list);
        foreach ($first as $attribute) {
            $tab = Ets_MarketPlaceProductsModuleFrontController::createCombinations($list);
            foreach ($tab as $to_add) {
                $res[] = is_array($to_add) ? array_merge($to_add, array($attribute)) : array($to_add, $attribute);
            }
        }
        return $res;
    }
    protected function addAttribute($attributes, $price = 0, $weight = 0)
    {
        if ($this->product->id) {
            return array(
                'id_product' => (int)$this->product->id,
                'price' => (float)$price,
                'weight' => (float)$weight,
                'ecotax' => 0,
                'quantity' => (int)Tools::getValue('quantity'),
                'reference' => pSQL(Tools::getValue('reference')),
                'default_on' => 0,
                'available_date' => '0000-00-00'
            );
        }
        unset($attributes);
        return array();
    }
    public function updateDownloadProduct($product)
    {
        if ((int)Tools::getValue('is_virtual_file') == 1) {
            if (isset($_FILES['virtual_product_file_uploader']) && $_FILES['virtual_product_file_uploader']['size'] > 0) {
                $virtual_product_filename = ProductDownload::getNewFilename();
                $helper = new HelperUploader('virtual_product_file_uploader');
                $helper->setPostMaxSize(Tools::getOctets(ini_get('upload_max_filesize')))
                    ->setSavePath(_PS_DOWNLOAD_DIR_)->upload($_FILES['virtual_product_file_uploader'], $virtual_product_filename);
            } else {
                $virtual_product_filename = Tools::getValue('virtual_product_filename', ProductDownload::getNewFilename());
            }

            $product->deleteProductAttributes();//reset cache_default_attribute
            if (Tools::getValue('virtual_product_expiration_date') && !Validate::isDate(Tools::getValue('virtual_product_expiration_date'))) {
                return false;
            }
            $id_product_download = (int)ProductDownload::getIdFromIdProduct((int)$product->id);
            if (!$id_product_download) {
                $id_product_download = (int)Tools::getValue('virtual_product_id');
            }
            $is_shareable = Tools::getValue('virtual_product_is_shareable');
            $virtual_product_name = Tools::getValue('virtual_product_name');
            $virtual_product_nb_days = Tools::getValue('virtual_product_nb_days');
            $virtual_product_nb_downloable = Tools::getValue('virtual_product_nb_downloable');
            $virtual_product_expiration_date = Tools::getValue('virtual_product_expiration_date');
            $download = new ProductDownload((int)$id_product_download);
            $download->id_product = (int)$product->id;
            $download->display_filename = $virtual_product_name;
            $download->filename = $virtual_product_filename;
            $download->date_add = date('Y-m-d H:i:s');
            $download->date_expiration = $virtual_product_expiration_date ? $virtual_product_expiration_date.' 23:59:59' : '';
            $download->nb_days_accessible = (int)$virtual_product_nb_days;
            $download->nb_downloadable = (int)$virtual_product_nb_downloable;
            $download->active = 1;
            $download->is_shareable = (int)$is_shareable;
            if ($download->save()) {
                return $download->filename;
            }
        } else {
            $id_product_download = (int)ProductDownload::getIdFromIdProduct((int)$product->id);
            if (!$id_product_download) {
                $id_product_download = (int)Tools::getValue('virtual_product_id');
            }
            if (!empty($id_product_download)) {
                $product_download = new ProductDownload((int)$id_product_download);
                $product_download->date_expiration = date('Y-m-d H:i:s', time() - 1);
                $product_download->active = 0;
                return '';
            }
        }
        return false;
    }
    public function _getFromImageProduct($id_image)
    {
        $image_class = new Image($id_image);
        if($image_class->id_product == $this->product->id)
        {
            $languages = Language::getLanguages(true);
            $legends = array();
            foreach($languages as $language)
            {
                $legends[$language['id_lang']] = $image_class->legend[$language['id_lang']];
            }
            $folders = str_split((string)$image_class->id);
            $path = implode('/', $folders) . '/';
            $url_image = $this->module->getBaseLink() . '/img/p/' . $path . $image_class->id . '.jpg';
            $this->context->smarty->assign(
                array(
                    'image_class' => $image_class,
                    'legends' => $legends,
                    'languages' => $languages,
                    'url_image'=> $url_image,
                    'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
                )
            );
            return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/form_image.tpl');
        }
        else
            return '';
    }
    public function _submitUploadImageSave($idProduct = null, $inputFileName = 'upload_image', $die = true)
    {
        $idProduct = $idProduct ? $idProduct : Tools::getValue('id_product');
        if(!$idProduct)
            $this->errors[] = $this->module->l('Product is required','products');
        elseif(!Validate::isLoadedObject(new Product($idProduct)) || !$this->seller->checkHasProduct($idProduct))
            $this->errors[] = $this->module->l('Product is not valid','products');
        $image_uploader = new HelperImageUploader($inputFileName);

        /** _ARM_ SBA Concept */
        $_FILES[$inputFileName]['name'] = preg_replace('/[^a-zA-Z0-9_.-]/', '-', $_FILES[$inputFileName]['name']);

        $this->module->validateFile($_FILES[$inputFileName]['name'],$_FILES[$inputFileName]['size'],$this->errors,array('jpeg', 'gif', 'png', 'jpg'),Configuration::get('PS_LIMIT_UPLOAD_IMAGE_VALUE')*1024*1024);
        if($this->errors)
            return false;
        $image_uploader->setAcceptTypes(array('jpeg', 'gif', 'png', 'jpg'))->setMaxSize(null);
        $files = $image_uploader->process();
      
        foreach ($files as &$file) {
            $image = new Image();
            if($file['error'])
            {
                $this->errors[] = $file['error'];
                return false;
            }
            else
            {
                $image->id_product = (int) ($this->product->id);
                $image->position = Image::getHighestPosition($this->product->id) + 1;
                if (!Image::getCover($image->id_product)) {
                    $image->cover = 1;
                } else {
                    $image->cover = 0;
                }
                if (($validate = $image->validateFieldsLang(false, true)) !== true) {
                    $this->errors[] = $validate;
                }
    
                if ($this->errors) {
                    continue;
                }
                if (!$image->add()) {
                    $this->errors[] = $this->module->l('An error occurred while creating additional image','products');
                } else {
                    if (!$new_path = $image->getPathForCreation()) {
                        $this->errors[] = $this->module->l('An error occurred while attempting to create a new folder.','products');
                        continue;
                    }
                    $error = 0;
                    if (!ImageManager::resize($file['save_path'], $new_path . '.' . $image->image_format, null, null, 'jpg', false, $error)) {
                        switch ($error) {
                            case ImageManager::ERROR_FILE_NOT_EXIST:
                                $this->errors[] = $this->module->l('An error occurred while copying image, the file does not exist anymore.','products');
                                break;
                            case ImageManager::ERROR_FILE_WIDTH:
                                $this->errors[] = $this->module->l('An error occurred while copying image, the file width is 0px.','products');
                                break;
                            case ImageManager::ERROR_MEMORY_LIMIT:
                                $this->errors[] = $this->module->l('An error occurred while copying image, check your memory limit.','products');
                                break;
                            default:
                                $this->errors[] = $this->module->l('An error occurred while copying the image.','products');
                                break;
                        }
    
                        continue;
                    } else {
                        $imagesTypes = ImageType::getImagesTypes('products');
                        $generate_hight_dpi_images = (bool) Configuration::get('PS_HIGHT_DPI');
                        foreach ($imagesTypes as $imageType) {
                            if (!ImageManager::resize($file['save_path'], $new_path . '-' . Tools::stripslashes($imageType['name']) . '.' . $image->image_format, $imageType['width'], $imageType['height'], $image->image_format)) {
                                $this->errors[] =$this->module->l('An error occurred while copying this image:','products').' ' . Tools::stripslashes($imageType['name']);
                                continue;
                            }
    
                            if ($generate_hight_dpi_images) {
                                if (!ImageManager::resize($file['save_path'], $new_path . '-' . Tools::stripslashes($imageType['name']) . '2x.' . $image->image_format, (int) $imageType['width'] * 2, (int) $imageType['height'] * 2, $image->image_format)) {
                                    $this->errors[] = $this->module->l('An error occurred while copying this image:','products') . ' ' . Tools::stripslashes($imageType['name']);
                                    continue;
                                }
                            }
                        }
                    }
    
                    unlink($file['save_path']);
                    unset($file['save_path']);
                    Hook::exec('actionWatermark', array('id_image' => $image->id, 'id_product' => $this->product->id));
                    if (!$image->update()) {
                        $this->module->l('An error occurred while updating the status.','products');
                        continue;
                    }
                    $shops = Shop::getContextListShopID();
                    $image->associateTo($shops);
                    $json_shops = array();
                    foreach ($shops as $id_shop) {
                        $json_shops[$id_shop] = true;
                    }
                    $file['status'] = 'ok';
                    $file['id'] = $image->id;
                    $file['position'] = $image->position;
                    $file['cover'] = $image->cover;
                    $file['legend'] = $image->legend;
                    $file['path'] = $image->getExistingImgPath();
                    $file['shops'] = $json_shops;
                    @unlink(_PS_TMP_IMG_DIR_ . 'product_' . (int) $this->product->id . '.jpg');
                    @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $this->product->id . '_' . $this->context->shop->id . '.jpg');
                    @unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $this->product->id . '_0.jpg');
                    if($die)
                    {
                        if(version_compare(_PS_VERSION_, '1.7', '>='))
                            $type_image= ImageType::getFormattedName('home');
                        else
                            $type_image= ImageType::getFormatedName('home');
                        die(
                            Tools::jsonEncode(
                                array(
                                    'success' => true,
                                    'id_image' => $image->id,
                                    'link' => $this->context->link->getImageLink($this->product->link_rewrite[$this->context->language->id],$image->id, $type_image),
                                    'list_combinations' => $this->displayListCombinations(),
                                )
                            )
                        );
                    }
                }
            }
            
        }
        return $files;
    }
    public function _processExportProduct($sample=false)
    {
        $sql = 'SELECT p.id_product,stock.quantity,ps.price,ps.id_category_default,pl.name,pl.description,pl.description_short,pl.link_rewrite FROM `'._DB_PREFIX_.'product` p
        INNER JOIN `'._DB_PREFIX_.'product_shop` ps ON (p.id_product=ps.id_product)
        '.(!$sample ? 'INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product)':'').'
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product=p.id_product AND pl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'stock_available` stock ON (stock.id_product=p.id_product AND stock.id_product_attribute=0)
        WHERE ps.id_shop="'.(int)$this->context->shop->id.'"'.(!$sample ? ' AND seller_product.id_customer="'.(int)$this->seller->id_customer.'"':'');
        if($sample)
            $sql .=' ORDER BY p.id_product ASC LIMIT 0,10';
        $products = Db::getInstance()->executeS($sql);
        if($products)
        {
            foreach($products as &$product)
            {
                $sql ='SELECT pas.price,pas.default_on,stock.quantity,pas.id_product_attribute FROM `'._DB_PREFIX_.'product_attribute` pa
                INNER JOIN `'._DB_PREFIX_.'product_attribute_shop` pas ON (pa.id_product_attribute=pas.id_product_attribute)
                LEFT JOIN `'._DB_PREFIX_.'stock_available` stock ON (stock.id_product_attribute=pa.id_product_attribute)
                WHERE pas.id_shop="'.(int)$this->context->shop->id.'" AND pa.id_product="'.(int)$product['id_product'].'"';
                $product_attributes = Db::getInstance()->executeS($sql);
                if($product_attributes)
                {
                    foreach($product_attributes as &$product_attribute)
                    {
                        $sql = 'SELECT agl.name as name_group, al.name,a.color FROM `'._DB_PREFIX_.'attribute` a
                        INNER JOIN `'._DB_PREFIX_.'attribute_group` ag ON (a.id_attribute_group=ag.id_attribute_group)
                        INNER JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON (pac.id_attribute=a.id_attribute)
                        LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.id_attribute=al.id_attribute AND al.id_lang="'.(int)$this->context->language->id.'")
                        LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.id_attribute_group = agl.id_attribute_group AND agl.id_lang="'.(int)$this->context->language->id.'")
                        WHERE pac.id_product_attribute='.(int)$product_attribute['id_product_attribute'];
                        $attributes = Db::getInstance()->executeS($sql);
                        $product_attribute['attributes'] = $attributes;
                        $attribute_specific_price = Db::getInstance()->executeS('SELECT id_currency,id_group,id_customer,id_country, price,from_quantity,reduction,reduction_tax,reduction_type,`from`,`to` FROM `'._DB_PREFIX_.'specific_price` WHERE id_product='.(int)$product['id_product'].' AND id_product_attribute='.(int)$product_attribute['id_product_attribute']);
                        if($attribute_specific_price)
                            $product_attribute['specific_prices'] = $attribute_specific_price;
                    }
                    unset($product_attribute);
                    $product['product_attributes'] = Tools::jsonEncode($product_attributes);
                }
                else
                    $product['product_attributes'] ='';
                $specific_prices = Db::getInstance()->executeS('SELECT id_currency,id_group,id_customer,id_country, price,from_quantity,reduction,reduction_tax,reduction_type,`from`,`to` FROM `'._DB_PREFIX_.'specific_price` WHERE id_product='.(int)$product['id_product'].' AND id_product_attribute=0');
                if($specific_prices)
                    $product['specific_prices'] = Tools::jsonEncode($specific_prices);
                else
                    $product['specific_prices'] = '';
                $sql = 'SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$product['id_product'];
                $images = Db::getInstance()->executeS($sql);
                $list_images=array();
                if($images)
                {
                    foreach($images as $image)
                    {
                        $folders = str_split((string)$image['id_image']);
                        $path = implode('/', $folders) . '/';
                        $url = $this->module->getBaseLink() . '/img/p/' . $path . $image['id_image'] . '.jpg';
                        $list_images[]= $url;
                    }
                    $product['images'] = implode(',',$list_images);
                }
                else
                    $product['images']='';
                $sql = 'SELECT c.id_category FROM `'._DB_PREFIX_.'category` c
                INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
                INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_category=c.id_category)
                WHERE cp.id_product="'.(int)$product['id_product'].'"';
                $categories= Db::getInstance()->executeS($sql);
                if($categories)
                {
                    $list_categories = array();
                    foreach($categories as $category)
                        $list_categories[] = $category['id_category'];
                    $product['categories'] = implode(',',$list_categories);
                }
                else
                    $product['categories'] ='';
            }
            unset($product);
        }
        if($products)
        {
            ob_get_clean();
            ob_start(); 
            $file =dirname(__FILE__).'/../../'.date('Y-m-d').'-list-products.csv';    
            $fp = fopen($file, 'w');
            $header = array(
                $this->module->l('Name','products'),
                $this->module->l('Image','products'),
                $this->module->l('Quantity','products'),
                $this->module->l('Price','products'),
                $this->module->l('Description','products'),
                $this->module->l('Summary','products'),
                $this->module->l('Link rewrite','products'),
                $this->module->l('Categories','products'),
                $this->module->l('Default category','products'),
                $this->module->l('Combinations','products'),
                $this->module->l('Specific price')
            );
            fputcsv($fp, $header);
            foreach($products as $row) {
                $product=array();
                $product[]=trim($row['name']);
                $product[] = trim($row['images']);
                $product[]=trim($row['quantity']);
                $product[]=trim($row['price']);
                $product[]=trim(str_replace(array("\t","\r\n","  "),' ',$row['description']));
                $product[]= trim(str_replace(array("\t","\r\n","  "),' ',$row['description_short']));
                $product[]=trim($row['link_rewrite']); 
                $product[] = trim($row['categories']);
                $product[]= trim($row['id_category_default']);
                $product[]=trim($row['product_attributes']);
                $product[]=trim($row['specific_prices']);
                fputcsv($fp, $product);             
            }
            fclose($fp);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            @unlink($file);
            exit;
        }
    }
    public function _renderFormBulkProduct()
    {
        $this->context->smarty->assign(
            array(
                'has_edit_product' => Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT') && (!$this->seller->vacation_mode || $this->seller->vacation_type!='disable_product'),
                'has_delete_product' => $this->checkDeleteProduct(),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/product_bulk.tpl');
    }
    public function getCustomizationFields($id_product)
    {
        $sql = 'SELECT id_customization_field FROM '._DB_PREFIX_.'customization_field WHERE id_product='.(int)$id_product.($this->module->is17 ? ' AND is_deleted=0':'');
        $customizationFields = Db::getInstance()->executeS($sql);
        $objects = array();
        if($customizationFields)
        {
            foreach($customizationFields as $customizationField)
            {
                $objects[] = new CustomizationField($customizationField['id_customization_field']);
            }
        }
        return $objects;
    }
    public function getProductAttachments($id_product)
    {
        $sql = 'SELECT a.*,al.name,al.description FROM '._DB_PREFIX_.'attachment a
        INNER JOIN '._DB_PREFIX_.'product_attachment pa ON (a.id_attachment = pa.id_attachment AND pa.id_product="'.(int)$id_product.'")
        LEFT JOIN '._DB_PREFIX_.'attachment_lang al ON (a.id_attachment = al.id_attachment AND id_lang = "'.(int)$this->context->language->id.'")';
        return Db::getInstance()->executeS($sql);
    }
    public function _submitProductAttachment()
    {
        $errors = array();
        if(isset($_FILES['product_attachment_file']['name']) && $_FILES['product_attachment_file']['name'] && isset($_FILES['product_attachment_file']['tmp_name']) && $_FILES['product_attachment_file']['tmp_name'])
        {
            /** _ARM_ SBA Concept */
            $_FILES['product_attachment_file']['name'] = preg_replace('/[^a-zA-Z0-9_.-]/', '-', $_FILES['product_attachment_file']['name']);

            $this->module->validateFile($_FILES['product_attachment_file']['name'],$_FILES['product_attachment_file']['size'],$errors);
        }
        else
            $errors[] = $this->module->l('File attachment is required','products');
        if(!($product_attachment_name = Tools::getValue('product_attachment_name')))
            $errors[] = $this->module->l('Title attachment is required','products');
        elseif(!Validate::isGenericName($product_attachment_name))
            $errors[] = $this->module->l('Title attachment is not valid','products');
        if(($product_attachment_description = Tools::getValue('product_attachment_description')) && !Validate::isCleanHtml($product_attachment_description))
            $errors[] = $this->module->l('Description attachment is not valid','products');
        if(!($id_product = Tools::getValue('id_product')))
        {
            $errors[] = $this->module->l('Product is required','products');
        }
        elseif(!Validate::isLoadedObject( new Product($id_product)) || !$this->seller->checkHasProduct($id_product))
            $errors[] = $this->module->l('Product is not valid','products');
        if(!$errors)
        {
            $file = Tools::passwdGen(40);
            $file_name = $_FILES['product_attachment_file']['name'];
            if(move_uploaded_file($_FILES['product_attachment_file']['tmp_name'], _PS_DOWNLOAD_DIR_.$file))
            {
                $attachment = new Attachment();
                $attachment->file = $file;
                $attachment->file_name = $file_name;
                $attachment->mime = $_FILES['product_attachment_file']['type'];
                foreach(Language::getLanguages(false) as $language)
                {
                    $attachment->name[$language['id_lang']] = $product_attachment_name;
                    $attachment->description[$language['id_lang']] = $product_attachment_description;
                }
                if($attachment->add())
                {
                    if($attachment->attachProduct($id_product))
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'success' => $this->module->l('Added attachment successfully','products'),
                                    'real_name' => $product_attachment_name,
                                    'file_name' => $file_name,
                                    'mime' => $attachment->mime,
                                    'id'=>$attachment->id,
                                )
                            )
                        );
                    }
                    else
                    {
                        $attachment->delete();
                        $errors[] = $this->module->l('An error occurred while saving the attachment');
                    }    
                }
                else
                    $errors[] = $this->module->l('An error occurred while saving the attachment');
            }
            else
                $errors[] = $this->module->l('An error occurred while uploading the attachment');
        }
        if($errors)
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->displayError($errors),
                    )
                )
            );
        }
    }
    public function _validateFormSubmit()
    {
        $type_error = 'die_array';
        $submit = false;
        $error = false;
        if(Tools::isSubmit('submitDeletecombinations') || Tools::isSubmit('deleteImageProduct') ||Tools::isSubmit('submitUploadImageSave') || Tools::isSubmit('submitImageProduct') ||  Tools::isSubmit('submitCreateCombination')|| Tools::isSubmit('submitSaveProduct') || Tools::isSubmit('submitSavecombinations') || Tools::isSubmit('submitProductAttachment') || Tools::isSubmit('submitSavePecificPrice'))
            $submit = true;
        if(Tools::isSubmit('change_enabled') || Tools::isSubmit('deletefileproduct') || Tools::getValue('bulk_action') || Tools::getValue('action')=='updateImageOrdering' || Tools::isSubmit('submitDeleteSpecificPrice') || Tools::isSubmit('submitDeleteProductAttribute'))
        {
            $submit = true;
            $type_error = 'die_text';
        }
        if(Tools::isSubmit('duplicatemp_front_products'))
        {
            $submit = true;
            $type_error ='array';
        }
        if($submit)
        {
            if(!Tools::isSubmit('addnew') && !Tools::isSubmit('editmp_front_products') && !Tools::isSubmit('duplicatemp_front_products') && !Tools::isSubmit('bulk_action') && !Tools::isSubmit('change_enabled') && !Tools::isSubmit('deletefileproduct'))
            {
                $error = $this->module->l('Data form submit is not valid','products');
            }
            if(!Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT') && (Tools::isSubmit('addnew') || Tools::isSubmit('duplicatemp_front_products')))
            {
                $error = $this->module->l('You do not have permission to create new product','products');
            }
            if(!Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT') && (Tools::isSubmit('editmp_front_products') || Tools::isSubmit('change_enabled')))
            {
                $error = $this->module->l('You do not have permission to edit product','products');
            }
            if(Tools::getValue('bulk_action'))
            {
                switch (Tools::getValue('bulk_action')) {
                  case 'activate_all':
                        if(!Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT'))
                            $error = $this->module->l('You do not have permission to edit product','products');
                    break;
                  case 'deactivate_all':
                       if(!Configuration::get('ETS_MP_ALLOW_SELLER_EDIT_PRODUCT'))
                            $error = $this->module->l('You do not have permission to edit product','products');
                  break;
                  case 'duplicate_all':
                        if(!Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT'))
                            $error = $this->module->l('You do not have permission to create new product','products');
                  break;
                  case 'delete_all':
                        if(!Configuration::get('ETS_MP_ALLOW_SELLER_DELETE_PRODUCT'))
                            $error = $this->module->l('You do not have permission to delete product','products');
                  break;
                } 
            }
            if($error)
            {
                if($type_error =='die_array' || $type_error=='die_text')
                {
                    die(
                        json_encode(
                            array(
                                'errors' => $type_error =='die_array' ? $this->module->displayError($error) : $error,
                            )
                        )
                    );
                }
                else
                    $this->errors[] = $error;
            }
        }
        
    }
 }