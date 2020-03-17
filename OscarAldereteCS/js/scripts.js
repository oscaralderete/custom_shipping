/*
 @author Oscar Alderete <oscaralderete@gmail.com>
 @website http://oscaralderete.com
 @generator NetBeans IDE 8.2
 */
jQuery.fn.datepicker.language['es']={
    days:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
    daysShort:['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
    daysMin:['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
    months:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Augosto','Septiembre','Octubre','Noviembre','Diciembre'],
    monthsShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    today:'Hoy',
    clear:'Limpiar',
    dateFormat:'dd/mm/yyyy',
    timeFormat:'hh:ii aa',
    firstDay:0
};

jQuery(function($){
	var disabledDays=[0,6];
	$('#shipping_datetime').datepicker({
		language:'es',
		minDate:new Date(),
		timepicker:true,
		onRenderCell:function(date,cellType){
			if(cellType=='day'){
			var day=date.getDay(),
				isDisabled=disabledDays.indexOf(day)!=-1;
				return{
					disabled:isDisabled
				}
			}
		}
	});
});

