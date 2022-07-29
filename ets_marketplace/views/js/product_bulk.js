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
$(document).ready(function(){
    var closable = true;
    $('#catalog_deletion_modal').modal({
        backdrop: (closable ? true : 'static'),
        keyboard: closable,
        closable: closable,
        show: false
    }); 
     $('#catalog_delete_all_modal').modal({
        backdrop: (closable ? true : 'static'),
        keyboard: closable,
        closable: closable,
        show: false
    });
    $('#catalog_deactivate_all_modal').modal({
        backdrop: (closable ? true : 'static'),
        keyboard: closable,
        closable: closable,
        show: false
    });
     $('#catalog_activate_all_modal').modal({
        backdrop: (closable ? true : 'static'),
        keyboard: closable,
        closable: closable,
        show: false
    });
     $('#catalog_duplicate_all_modal').modal({
        backdrop: (closable ? true : 'static'),
        keyboard: closable,
        closable: closable,
        show: false
    });
    $(document).on('click','#list-mp_front_products input[type="checkbox"],#list-mp_products input[type="checkbox"]',function(){
        ets_mp_updateBulkMenu();
    });
    if($('#list-mp_front_products').length>0 && $('#list-mp_front_products input[type="checkbox"]').length==0)
        $('#catalog-actions').hide();
    if($('#list-mp_products').length>0 && $('#list-mp_products input[type="checkbox"]').length==0)
        $('#catalog-actions').hide();    
});
function ets_mp_updateBulkMenu()
{
    
    if($('#list-mp_products').length)
    {
        $('#list-mp_products input[type="checkbox"]').parent().removeClass('checked');
        $('#list-mp_products input[type="checkbox"]:checked').parent().addClass('checked');
        if($('#list-mp_products input[type="checkbox"]:checked').length) {
            $('#product_bulk_menu').removeAttr('disabled').parents('.d-inline-block').removeClass('hide');
        } else {
            $('#product_bulk_menu').attr('disabled','disabled').parents('.d-inline-block').addClass('hide');
        }
    }
    if($('#list-mp_front_products').length)
    {
        $('#list-mp_front_products input[type="checkbox"]').parent().removeClass('checked');
        $('#list-mp_front_products input[type="checkbox"]:checked').parent().addClass('checked');
        if($('#list-mp_front_products input[type="checkbox"]:checked').length) {
            $('#product_bulk_menu').removeAttr('disabled').parents('.d-inline-block').removeClass('hide');
        } else {
            $('#product_bulk_menu').attr('disabled','disabled').parents('.d-inline-block').addClass('hide');
        }
    }
}
function ets_mp_bulkProductAction(element, action) {
  var form = $('#list-mp_front_products').length ? $('#list-mp_front_products') : $('#list-mp_products');
  var postUrl = '';
  var redirectUrl = '';
  var urlHandler = null;

  var items = $('input:checked[name="bulk_action_selected_products[]"]', form);
  if (items.length === 0) {
    return false;
  } else {
    urlHandler = $(element).closest('[bulkurl]');
  }

  switch (action) {
    case 'delete_all':
      postUrl = urlHandler.attr('bulkurl').replace(/activate_all/, action);
      redirectUrl = urlHandler.attr('redirecturl');

      // Confirmation popup and callback...
      $('#catalog_deletion_modal').modal('show');
      $('#catalog_deletion_modal button[value="confirm"]').off('click');
      $('#catalog_deletion_modal button[value="confirm"]').on('click', function () {
        $('#catalog_deletion_modal').modal('hide');
        return ets_mp_bulkModalAction(items, postUrl, redirectUrl, action);
      });

      return; // No break, but RETURN, to avoid code after switch block :)

    case 'activate_all':
      postUrl = urlHandler.attr('bulkurl');
      redirectUrl = urlHandler.attr('redirecturl');

      return ets_mp_bulkModalAction(items, postUrl, redirectUrl, action);

      break;

    case 'deactivate_all':
      postUrl = urlHandler.attr('bulkurl').replace(/activate_all/, action);
      redirectUrl = urlHandler.attr('redirecturl');

      return ets_mp_bulkModalAction(items, postUrl, redirectUrl, action);

      break;

    case 'duplicate_all':
      postUrl = urlHandler.attr('bulkurl').replace(/activate_all/, action);
      redirectUrl = urlHandler.attr('redirecturl');

      return ets_mp_bulkModalAction(items, postUrl, redirectUrl, action);

      break;
    default:
      return false;
  }
  return false;
}
function ets_mp_bulkModalAction(allItems, postUrl, redirectUrl, action) {
  var itemsCount = allItems.length;
  var currentItemIdx = 0;
  if (itemsCount < 1) {
    return;
  }

  var targetModal = $('#catalog_' + action + '_modal');
  targetModal.modal('show');

  var details = targetModal.find('#catalog_' + action + '_progression .progress-details-text');
  var progressBar = targetModal.find('#catalog_' + action + '_progression .progress-bar');
  var failure = targetModal.find('#catalog_' + action + '_failure');

  // re-init popup
  details.html(details.attr('default-value'));

  progressBar.css('width', '0%');
  progressBar.find('span').html('');
  progressBar.removeClass('progress-bar-danger');
  progressBar.addClass('progress-bar-success');

  failure.hide();

  // call in ajax. Recursive with inner function
  var bulkCall = function (items, successCallback, errorCallback) {
    if (items.length === 0) {
      return;
    }
    var item0 = $(items.shift()).val();
    currentItemIdx++;

    details.html(details.attr('default-value').replace(/\.\.\./, '') + ' (#' + item0 + ')');
    $.ajax({
      type: 'POST',
      url: postUrl,
      data: {bulk_action_selected_products: [item0]},
      success: function (data, status) {
        if(data.error || data.errors)
        {
            failure.html(data.error ? data.error : data.errors).show();
            setTimeout(function(){  window.location.href = redirectUrl; }, 3000);
        }
        else
        {
            progressBar.css('width', (currentItemIdx * 100 / itemsCount) + '%');
            progressBar.find('span').html(currentItemIdx + ' / ' + itemsCount);
    
            if (items.length > 0) {
              bulkCall(items, successCallback, errorCallback);
            } else {
              successCallback();
            }
        }
      },
      error: errorCallback,
      dataType: 'json'
    });
  };

  bulkCall(allItems.toArray(), function () {
    window.location.href = redirectUrl;
  }, function () {
    progressBar.removeClass('progress-bar-success');
    progressBar.addClass('progress-bar-danger');
    failure.show();
    setTimeout(function(){  window.location.href = redirectUrl; }, 3000);
   
  });
}