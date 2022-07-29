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
var ctx_commision_line;
var chart_commision_line;
var ctx_turn_over_bar;
var chart_turn_over_bar;
$(document).ready(function(){
    ctx_commision_line = $('#ets_mp_stats_commision_line');
    ctx_turn_over_bar = $('#ets_mp_stats_turn-over_bar');
    chart_commision_line= ets_mp_creatDashboardChart(ctx_commision_line,commissions_line_datasets,chart_labels,'line');
    chart_turn_over_bar= ets_mp_creatDashboardChart(ctx_turn_over_bar,turn_over_bar_datasets,chart_labels,'bar');
    $(document).on('change','select[name="filter-time-stats-commissions"]',function(){
        $this=$(this);
        if($this.val()!='time_range')
        {
            $('.line-chart-commissions .box-tool-timerange').hide();
            ets_mp_ajaxActionSubmitCommissionsChart(chart_commision_line,$this.val(),false,false);
        }
        else
        {
            $('.line-chart-commissions .box-tool-timerange').show();
            if($('.date_from_commissions').val()!='' && $('.date_to_commissions').val()!='' && $('.date_from_commissions').val()!=$('.date_to_commissions').val())
                ets_mp_ajaxActionSubmitCommissionsChart(chart_commision_line,$this.val(),$('.date_from_commissions').val(),$('.date_to_commissions').val());
        }
    });
    $(document).on('change','select[name="filter-time-stats-turnover"]',function(){
        $this=$(this);
        if($this.val()!='time_range')
        {
            $('.bar-chart-turn-over .box-tool-timerange').hide();
            ets_mp_ajaxActionSubmitTurnOVerChart(chart_turn_over_bar,$this.val(),false,false);
        }
        else
        {
            $('.bar-chart-turn-over .box-tool-timerange').show();
            if($('.date_from_order').val()!='' && $('.date_to_order').val()!='' && $('.date_from_order').val()!=$('.date_to_order').val())
                ets_mp_ajaxActionSubmitTurnOVerChart(chart_turn_over_bar,'time_range',$('.date_from_order').val(),$('.date_to_order').val());
            
        }
    });
    $('.ets_mp_date_ranger_filter').on('apply.daterangepicker', function(ev, picker) {
        var date_from= picker.startDate.format('YYYY-MM-DD');
        var date_to = picker.endDate.format('YYYY-MM-DD');
        $(this).next().val(date_from);
        $(this).next().next().val(date_to);
        if($(this).closest('.box-dashboard').hasClass('line-chart-commissions')){
            ets_mp_ajaxActionSubmitCommissionsChart(chart_commision_line,'time_range',date_from,date_to);
        }
        if($(this).closest('.box-dashboard').hasClass('bar-chart-turn-over')){
            ets_mp_ajaxActionSubmitTurnOVerChart(chart_turn_over_bar,'time_range',date_from,date_to);
        }
    });
    $('.ets_mp_date_ranger_filter').on('hide.daterangepicker', function(ev, picker) {
        $(this).next().val(picker.startDate.format('YYYY-MM-DD'));
        $(this).next().next().val(picker.endDate.format('YYYY-MM-DD'));
    });  
    var ets_mpDate = new Date();
    if(typeof daterangepicker !== 'undefined'){
        $('.ets_mp_date_ranger_filter').daterangepicker({
            locale: { 
                format: 'YYYY/MM/DD'
            }
        });
        if(!$('.ets_mp_date_ranger_filter').val() && $('.ets_mp_date_ranger_filter').length > 0){
            $('.ets_mp_date_ranger_filter').data('daterangepicker').setStartDate(moment(new Date(ets_mpDate.getFullYear(), ets_mpDate.getMonth(), 1)));
            $('.ets_mp_date_ranger_filter').data('daterangepicker').setEndDate(moment(new Date(ets_mpDate.getFullYear(), ets_mpDate.getMonth() + 1, 0)));
        }
    }
});
function ets_mp_ajaxActionSubmitTurnOVerChart(chart,date_type,date_from,date_to)
{
    $('.box-dashboard.bar-chart-turn-over').addClass('loading');
    $.ajax({
        url: '',
        type: 'post',
        dataType: 'json',
        data: {
            actionSubmitTurnOVerChart: date_type,
            ajax : 1,
            date_from: date_from,
            date_to: date_to,
        },
        success: function(json)
        { 
            if(json.no_data)
            {
                $('.bar-chart-turn-over .no_data').show();
                $('#ets_mp_stats_turn-over_bar').hide();
            }
            else
            {
                $('.bar-chart-turn-over .no_data').hide();
                $('#ets_mp_stats_turn-over_bar').show();
                ets_mp_updateDashboardChart(chart,json.label_datas,json.turn_over_bar_datasets,json.labelStringx);
            }
            $('.box-dashboard.bar-chart-turn-over').removeClass('loading');
        }
    });
}
function ets_mp_ajaxActionSubmitCommissionsChart(chart,date_type,date_from,date_to)
{
    $('.box-dashboard.line-chart-commissions').addClass('loading');
    $.ajax({
        url: '',
        type: 'post',
        dataType: 'json',
        data: {
            actionSubmitCommissionsChart: date_type,
            ajax : 1,
            date_from: date_from,
            date_to: date_to,
        },
        success: function(json)
        { 
            if(json.no_data)
            {
                $('.line-chart-commissions .no_data').show();
                $('#ets_mp_stats_commision_line').hide();
            }  
            else
            {
                $('.line-chart-commissions .no_data').hide();
                $('#ets_mp_stats_commision_line').show();
                ets_mp_updateDashboardChart(chart,json.label_datas,json.commissions_line_datasets,json.labelStringx);
            }  
            $('.box-dashboard.line-chart-commissions').removeClass('loading');
        }
    });
}
function ets_mp_creatDashboardChart(ctx,datasets,labels,type)
{
    var aR = null; //store already returned tick
    var conversationLineChart = new Chart(ctx, {
        type: type,
        data: {
            datasets: datasets,
            labels: labels,
            
        },
        options: {
          scales: {
            xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: charxlabelString
				}
            }],
             yAxes: [{
                display: true,
				scaleLabel: {
					display: true,
					labelString: charylabelString
				},
                ticks: {
                   min: 0,
                   //callback: function(value) {if (value % 1 === 0) {return value;}},
                }
             }]
          },
          legend: {
                display: true,
                position:'bottom'
          },
          tooltips: {
                mode: 'point'
          },
       }
    });
    return conversationLineChart;
}
function ets_mp_updateDashboardChart(chart,label_datas,datas,labelStringx)
{
    chart.data.labels=[];
    if(label_datas)
    {
        $(label_datas).each(function(){
            chart.data.labels.push(this);
        });
    }
    var i=0;
   chart.data.datasets.forEach(function(dataset){
        dataset.data=[];
        if(datas[i])
        {
            $(datas[i]).each(function(){
                dataset.data.push(this);
            });
        }
        i++;
    });
    chart.options.scales.xAxes = [{
		display: true,
		scaleLabel: {
			display: true,
			labelString: labelStringx
		}
    }];
    chart.update();
}