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
class AdminMarketPlaceProductsController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
    }
    public function postProcess()
    {
        parent::postProcess();
        if(Tools::isSubmit('del') && $id_product = Tools::getValue('id_product'))
        {
            $product = new Product($id_product);
            if($product->delete())
            {
                $this->context->cookie->success_message = $this->l('Deleted product successfully');
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceProducts'));
            }
            else
                $this->module->_errors[] = $this->l('An error occurred while deleting the product');
        }
        if(Tools::isSubmit('editmp_products') && $id_product = Tools::getValue('id_product'))
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$id_product)));
        if(Tools::getValue('bulk_action') && ($id_products = Tools::getValue('bulk_action_selected_products')) && is_array($id_products))
        {
            $id_product = $id_products[0];
            $errors = array();
            switch (Tools::getValue('bulk_action')) {
              case 'activate_all':
                    $product = new Product($id_product);
                    if(Validate::isLoadedObject($product))
                    {
                        $product->active=1;
                        if(!$product->update())
                            $errors[] = sprintf($this->l('An error occurred while saving the product(#%d)'),$id_product);
                    }else
                        $errors[] = sprintf($this->l('Product(#%d) is not valid'),$id_product);
                    $this->context->cookie->success_message = $this->l('Product(s) successfully activated.');
                break;
              case 'deactivate_all':
                    $product = new Product($id_product);
                    if(Validate::isLoadedObject($product) &&  $product->active)
                    {
                        $product->active=0;
                        if(!$product->update())
                            $errors[] = sprintf($this->l('An error occurred while saving the product(#%d)'),$id_product);
                    }
                    elseif(!Validate::isLoadedObject($product))
                        $errors[] = sprintf($this->l('Product(#%d) is not valid'),$id_product);
                    $this->context->cookie->success_message = $this->l('Product(s) successfully deactivated.');
              break;
              case 'duplicate_all':
                Ets_mp_defines::getInstance()->processDuplicate($id_product,$errors);
                if($errors)
                {
                    $errors[0] = sprintf($this->l('An error occurred while duplicating the product(#%d) : %s'),$id_product,$errors[0]);
                }
                $this->context->cookie->success_message = $this->l('Product(s) successfully duplicated.');
              break;
              case 'delete_all':
                $product = new Product($id_product);
                if(Validate::isLoadedObject($product))
                {
                    if(!$product->delete())
                        $errors[] = sprintf($this->l('An error occurred while deleting the product(#%d)'),$id_product);
                }
                else
                    $errors[] = sprintf($this->l('Product(#%d) is not valid'),$id_product);
                $this->context->cookie->success_message = $this->l('Product(s) successfully deleted.');
              break;
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
    }
    public function initContent()
    {
        parent::initContent();
        if($this->ajax)
            $this->renderList();
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> $this->_renderProducts(),
            )
        );
        $html ='';
        if($this->context->cookie->success_message)
        {
            $html .= $this->module->displayConfirmation($this->context->cookie->success_message);
            $this->context->cookie->success_message ='';
        }
        if($this->module->_errors)
            $html .= $this->module->displayError($this->module->_errors);
        return $html.$this->module->display(_PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.$this->module->name.'.php', 'admin.tpl');
    }
    public function _renderProducts()
    {
        if(Tools::isSubmit('change_enabled') && $id_product = Tools::getValue('id_product'))
        {
            $product = new Product($id_product);
            $product->active = (int)Tools::getValue('change_enabled');
            if($product->update())
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_product` SET approved="'.($product->active ? 1 :0).'" WHERE id_product= "'.(int)$id_product.'"');
                if(Tools::getValue('change_enabled'))
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'href' => $this->context->link->getAdminLink('AdminMarketPlaceProducts').'&id_product='.$product->id.'&change_enabled=0&field=active',
                                'title' => $this->l('Click to disable'),
                                'success' => $this->l('Updated successfully'),
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
                                'href' => $this->context->link->getAdminLink('AdminMarketPlaceProducts').'&id_product='.$product->id.'&change_enabled=1&field=active',
                                'title' => $this->l('Click to enable'),
                                'success' => $this->l('Updated successfully'),
                                'enabled' => 0,
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
                            'errors' => $this->l('An error occurred while saving the product')
                        )
                    )
                );
            }
        }
        $fields_list = array(
            'input_box' => array(
                'title' => '',
                'width' => 40,
                'type' => 'text',
                'strip_tag'=> false,
            ),
            'id_product' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'class'=>'text-center'
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'type'=>'text',
                'sort' => false,
                'filter' => false,
                'strip_tag'=> false,
            ),
            'name' => array(
                'title' => $this->l('Product name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=> false,
            ),
            'price' => array(
                'title' => $this->l('Price'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'class'=>'text-center'
            ),
            'quantity' => array(
                'title' => $this->l('Quantity'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'class'=>'text-center'
            ),
            'shop_name' => array(
                'title' => $this->l('Shop name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'total_reported' => array(
                'title' => $this->l('Reported'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'class'=>'text-center'
            ),
            'active' => array(
                'title' => $this->l('Status'),
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
                            'title' => $this->l('Pending')
                        ),
                        1 => array(
                            'active' => 1,
                            'title' => $this->l('Enabled')
                        ),
                        2 => array(
                            'active' => 0,
                            'title' => $this->l('Disabled')
                        )
                    )
                ),
                'class'=>'text-center'
            ),
            'date_add' => array(
                'title' => $this->l('Date added'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
        );
        //Filter
        $show_resset = false;
        $filter = "";
        if(Tools::getValue('id_product') && !Tools::isSubmit('del'))
        {
            $filter .= ' AND p.id_product="'.(int)Tools::getValue('id_product').'"';
            $show_resset = true;
        }
        if(Tools::getValue('name'))
        {
            $filter .=' AND pl.name LIKE "%'.pSQL(Tools::getValue('name')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('reference')))
        {
            $filter .=' AND p.reference LIKE "%'.pSQL(trim(Tools::getValue('reference'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('default_category')))
        {
            $filter .=' AND cl.name LIKE "%'.pSQL(trim(Tools::getValue('default_category'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('price_min')))
        {
            $filter .= ' AND product_shop.price >= "'.(float)Tools::getValue('price_min').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('price_max')))
        {
            $filter .= ' AND product_shop.price <= "'.(float)Tools::getValue('price_max').'"';
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
        if(trim(Tools::getValue('quantity_min')))
        {
            $filter .=' AND stock.quantity >="'.(int)Tools::getValue('quantity').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('quantity_max')))
        {
            $filter .=' AND stock.quantity <= "'.(int)Tools::getValue('quantity_max').'"';
            $show_resset= true;
        }
        if(trim(Tools::getValue('shop_name')))
        {
            $filter .= ' AND seller_lang.shop_name like "'.pSQL(Tools::getValue('shop_name')).'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('date_add_min')))
        {
            $filter .=' AND p.date_add >="'.pSQL(Tools::getValue('date_add_min')).' 00:00:00"';
            $show_resset =true;
        }
        if(trim(Tools::getValue('date_add_max')))
        {
            $filter .=' AND p.date_add <="'.pSQL(Tools::getValue('date_add_max')).' 23:59:59"';
        }
        if(trim(Tools::getValue('total_reported_min')))
        {
            $filter .=' AND seller_report.total_reported >= '.(int)Tools::getValue('total_reported_min');
            $show_resset=true;
        } 
        if(trim(Tools::getValue('total_reported_max')))
        {
            $filter .=' AND seller_report.total_reported <= '.(int)Tools::getValue('total_reported_max');
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
                case 'reference':
                    $sort .= 'p.reference';
                    break;
                case 'default_category':
                    $sort .= 'pl.name';
                    break;
                case 'price':
                    $sort .= 'product_shop.price';
                    break;
                case 'active':
                    $sort .='p.active';
                    break;
                case 'shop_name':
                    $sort .='seller_lang.shop_name';
                    break;
                case 'quantity':
                    $sort .='quantity';
                    break;
                case 'date_add':
                    $sort .='p.date_add';
                    break;
                case 'total_reported':
                    $sort .='seller_report.total_reported';
                    break;
                
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->module->getSellerProducts($filter,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink('AdminMarketPlaceProducts').'&page=_page_'.$this->module->getFilterParams($fields_list,'mp_products') ;
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $products = $this->module->getSellerProducts($filter,$page,$paggination->limit,$sort,false);
        if($products)
        {
            if(version_compare(_PS_VERSION_, '1.7', '>='))
                $type_image= ImageType::getFormattedName('home');
            else
                $type_image= ImageType::getFormatedName('home');
            foreach($products as &$product)
            {
                $product['child_view_url'] = $this->context->link->getProductLink($product['id_product']);
                $product['price'] = Tools::displayPrice($product['price']);
                if(!$product['id_image'])
                    $product['id_image'] = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$product['id_product']);
                if($product['id_image'])
                {
                    $product['image'] = '<'.'a hr'.'ef="'.$this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$product['id_product'])).'"><i'.'mg src="'.$this->context->link->getImageLink($product['link_rewrite'],$product['id_image'],$type_image).'" style="width:80px;"><'.'/'.'a'.'>';
                }
                else
                    $product['image']='';
                $product['name'] = '<'.'a  hr'.'ef="'.$this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$product['id_product'])).'">'.$product['name'].'<'.'/'.'a'.'>';
                if($product['id_seller_product'])
                {
                    if($product['id_seller'])
                    {
                        $product['shop_name'] = '<'.'a hr'.'ef="'.$this->module->getShopLink(array('id_seller'=>$product['id_seller'])).'" tar'.'get="_bl'.'ank">'.$product['shop_name'].'<'.'/'.'a'.'>'; 
                    }
                    else
                    {
                        $product['shop_name']= '<'.'sp'.'an cl'.'ass="deleted_shop row_deleted">'.$this->l('Shop deleted').'</sp'.'an'.'>';
                    }
                }
                else
                {
                    $product['shop_name']='--';
                }
                if(!$product['active'] && !$product['approved'] && $product['id_seller_product'])
                    $product['active']=-1;
                $product['input_box'] = '<'.'inp'.'ut i'.'d="bulk_action_selected_products-'.$product['id_product'].'" na'.'me="bulk_action_selected_products[]" value="'.$product['id_product'].'" ty'.'pe="chec'.'kbox" '.'>';
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'mp_products',
            'actions' => array('view','edit','delete'), //'view', 'delete',
            'icon' => 'icon-products',
            'currentIndex' => $this->context->link->getAdminLink('AdminMarketPlaceProducts'),
            'identifier' => 'id_product',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Products'),
            'fields_list' => $fields_list,
            'field_values' => $products,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'mp_products'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_product'),
            'show_add_new'=> false,
            'view_new_tab' => true,
            //'link_new' => $this->context->link->getModuleLink($this->name,'products',array('addnew'=>1)),
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return $this->_renderFormBulkProduct().$this->module->renderList($listData);
    }
    public function _renderFormBulkProduct()
    {
        $this->context->smarty->assign(
            array(
                'has_delete_product' => true,
                'is_admin'=> true,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/product/product_bulk.tpl');
    }
}