var actions = {
	setting : {
		url_restart			: '/restart',
		url_restarttarget	: '/restartTar',
		url_shutdown		: '/shutdown',
		url_supermodel		: '/supermode',
		url_usbstate		: '/usbstate',
		url_usbset			: '/usbset',
		url_check			: '/check',
		runstate			: false
	},
	// restart all service
	restart_home : function(){
		this.sendPost( 'restart_home_success' , this.setting.url_restart );
	},
	// restart all service
	restart : function(){
		this.sendPost( 'restart_success' , this.setting.url_restart );
	},
	// restart target usb program
	restartTar : function( usb ){
		this.sendPost( 'restartTar_success' , this.setting.url_restarttarget , {'usb':usb} );
	},
	// shutdown all service
	shutdown : function(){
		this.sendPost( 'shutdown_success' , this.setting.url_shutdown );
	},
	// set super mode
	supermode : function( mode ){
		this.sendPost( 'supermode_success' , this.setting.url_supermodel , {'s':mode} );
	},
	// set normal mode
	normalmode : function( mode ){
		this.sendPost( 'normalmode_success' , this.setting.url_supermodel , {'s':mode} );
	},
	// check usb run state
	usbstate : function(){
		this.sendPost( 'usbstate_success' , this.setting.url_usbstate );
	},
	// set usb mode
	usbset : function( usb , mode ){
		this.sendPost( 'usbset_success' , this.setting.url_usbset , {'usb':usb , 'to':mode} );
	},
	// check current run state
	check : function(){
		this.sendPost( 'check_success' , this.setting.url_check );
	},
	// send post to server
	sendPost : function( callback , tourl , senddata , isouter ){
		// set default data
		if ( typeof( senddata ) == 'undefined' ) senddata = {};

		if ( isouter != 1 ) eval( "actionSuccess."+callback+"( -1 )" );

		$.ajax({
			type	: "GET",
			url		: tourl,
			data 	: senddata,
			success : function( r ){
				if ( isouter === 1 )
					eval( callback );
				else
					eval( "actionSuccess."+callback+"( r )" );
			},
			fail	: function(){
				actions.setting.runstate = false;
				alert( 'request fail!' );
			}
		});
	}
};

var actionSuccess = {
	templates : {
		// usb-port : /dev/ttyUSB0 , usb-text : 新USB挖矿设备，请选择挖矿模式。
		newusb : '<div id="newusb-{usb-port}" class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">设备: {usb-port}</h3></div><div class="panel-body">{usb-text}<br><br><button type="button" class="btn btn-sm btn-default btn-run-ltc" tar="{usb-port}">运行LTC</button>&nbsp;<button type="button" class="btn btn-sm btn-default btn-run-btc" tar="{usb-port}">运行BTC</button></div></div>',
		// usb-port : /dev/ttyUSB0 , usb-run-tip-type : default|waining , usb-text : 正在运行BTC [正常]|目标运行BTC [已停止] , usb-restart-text : 重启|立即启动
		btcstate : '<div id="btcstate-{usb-port}" class="panel panel-{usb-run-tip-type}"><div class="panel-heading"><h3 class="panel-title">设备: {usb-port}</h3></div><div class="panel-body">{usb-text}<br><br><button type="button" class="btn btn-sm btn-danger btn-run-restart" tar="{usb-port}">{usb-restart-text}</button>&nbsp;<button type="button" class="btn btn-sm btn-default btn-run-ltc" tar="{usb-port}">运行LTC</button></div></div>',
		// usb-port : /dev/ttyUSB0 , usb-run-tip-type : default|waining , usb-text : 正在运行LTC [正常]|目标运行LTC [已停止] , usb-restart-text : 重启|立即启动
		ltcstate : '<div id="ltcstate-{usb-port}" class="panel panel-{usb-run-tip-type}"><div class="panel-heading"><h3 class="panel-title">设备: {usb-port}</h3></div><div class="panel-body">{usb-text}<br><br><button type="button" class="btn btn-sm btn-danger btn-run-restart" tar="{usb-port}">{usb-restart-text}</button>&nbsp;<button type="button" class="btn btn-sm btn-default btn-run-btc" tar="{usb-port}">运行BTC</button></div></div>',
		// data-tip : 还未发现新挖矿设备!|暂无设备运行!
		nulldata : '<div class="alert alert-success important-tip">{data-tip}</div>'
	},
	restart_home_success : function( data ){
		if ( data === -1 )
		{
			$('#action-restart-tip').html( '正在执行重启操作...' );
			return;
		}
		else if ( data == 200 )
			$('#action-restart-tip').html( '重启成功！' );
		else
			$('#action-restart-tip').html( '重启失败，再试试！' );
	},
	restart_success : function( data ){
		if ( data === -1 )
		{
			$('#action-restart').html( '正在重启...' );
			return;
		}
		else if ( data == 200 )
			$('#action-restart').html( '立即重启' );
		else
			$('#action-restart').html( '再次重启(失败)' );

		actions.setting.runstate = false;
		resetTopBt.check();
	},
	restartTar_success : function( data ){
		alert( data );
	},
	shutdown_success : function( data ){
		if ( data === -1 )
		{
			$('#action-stop').html( '正在停止...' );
			return;
		}
		else if ( data == 200 )
			$('#action-stop').html( '停止运行' );
		else
			$('#action-stop').html( '重试停止(失败)' );

		actions.setting.runstate = false;
		resetTopBt.check();
	},
	supermode_success : function( data ){
		if ( data === -1 )
		{
			$('#action-super').html( '超频中...' );
			return;
		}
		else if ( data == 200 )
			$('#action-super').html( '超频运行' );
		else
			$('#action-super').html( '重试超频(失败)' );

		actions.setting.runstate = false;
		resetTopBt.check();
	},
	normalmode_success : function( data ){
		if ( data === -1 )
		{
			$('#action-run').html( '正在启动...' );
			return;
		}
		else if ( data == 200 )
			$('#action-run').html( '正常运行' );
		else
			$('#action-run').html( '重新运行(失败)' );

		actions.setting.runstate = false;
		resetTopBt.check();
	},
	usbstate_success : function( data ){
		data = eval( '('+data+')' );

		var html = '';
		if ( data.length <= 0 )
		{
			html = replaceAll( '{data-tip}' , '还未发现新挖矿设备!' , this.templates.nulldata );
		}
		else
		{
			var lines = Math.ceil( data.length / 3 );
			var last = data.length % 3;
			var circle = 0;
			for ( var i = 0; i < data.length; i++ )
			{
				if ( circle === 0 )
					html += '<div class="col-sm-4">';

				var tmp_str = replaceAll( '{usb-port}' , data[i] , this.templates.newusb );
				tmp_str = replaceAll( '{usb-text}' , '新USB挖矿设备，请选择挖矿模式。' , tmp_str );
				html += tmp_str;

				circle ++;
				if ( circle === lines && last > 0 )
				{
					html += '</div>';
					last --;
					circle = 0;
				}
				else if ( circle === lines-1 && last <= 0 )
				{
					html += '</div>';
					circle = 0;
				}
			}

			if ( circle > 0 )
				html += '</div>';
		}

		$('#new-machine-container').html( html );
	},
	usbset_success : function( data ){
		alert( data );
	},
	check_success : function( data ){
		alert( data );
	}
};

var resetTopBt = {
	check : function(){
		actions.sendPost( "resetTopBt.checkResult(r)" , actions.setting.url_check , {} , 1 );
	},
	checkResult : function( r ){
		resetTopBt.publicBtActive();
		r = eval( '('+r+')' );
		
		if ( r.alived == '' )
			this.stopBtActive();
		else if ( r.alived != '' && r.super == false )
			this.runBtActive();
		else if ( r.alived != '' && r.super == true )
			this.superBtActive();
		else
			this.stopBtActive();
	},
	stopBtActive : function(){
		$('#action-stop').parent().addClass('active');
	},
	runBtActive : function(){
		$('#action-run').parent().addClass('active');
	},
	superBtActive : function(){
		$('#action-super').parent().addClass('active');
	},
	publicBtActive : function(){
		$('#action-header li').removeClass( 'active' );
	}
};

var headerBt = {
	init : function(){
		this.restartBt();
		this.normalModelBt();
		this.superModelBt();
		this.stopBt();
	},
	restartBt : function(){
		$('#action-restart').click(function(){
			headerBt.publicClick( this );
			actions.restart();
		});
	},
	normalModelBt : function(){
		$('#action-run').click(function(){
			headerBt.publicClick( this );
			actions.normalmode(0);
		});
	},
	superModelBt : function(){
		$('#action-super').click(function(){
			headerBt.publicClick( this );
			actions.supermode(1);
		});
	},
	stopBt : function(){
		$('#action-stop').click(function(){
			headerBt.publicClick( this );
			actions.shutdown();
		});
	},
	publicClick : function( ele ){
		// if action is running
		if ( actions.setting.runstate === true )
			return;

		actions.setting.runstate = true;

		$('#action-header li').removeClass( 'active' );
		$(ele).parent().addClass('active');
	}
};

function timerResetTopBt()
{
	if ( actions.setting.runstate === false ) resetTopBt.check();
	setTimeout( function(){
		timerResetTopBt();
	} , 5000 );
}

function replaceAll(find, replace, str)
{
	return str.replace(new RegExp(find, 'g'), replace);
}

$(document).ready(function(){
	headerBt.init();
	timerResetTopBt();
});
