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
		actions.check();
	},
	restartTar_success : function( data ){
		return true;
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
		actions.check();
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
		actions.check();
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
		actions.check();
	},
	usbstate_success : function( data ){
		if ( data === -1 ) return;

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
		
		$('.btn-run-btc').click(function(){
			actions.usbset( $(this).attr('tar') , 'btc' );
			$(this).parent().html( '正在设置并运行...<br><br><br>' );
		});

		$('.btn-run-ltc').click(function(){
			actions.usbset( $(this).attr('tar') , 'ltc' );
			$(this).parent().html( '正在设置并运行...<br><br><br>' );
		});
	},
	usbset_success : function( data ){
		return true;
	},
	check_success : function( data ){
		if ( data === -1 ) return;

		data = eval( '('+data+')' );

		var html_btc = '';
		var html_ltc = '';

		// null objects
		var null_data = [null,undefined,'',[],{}];
		
		// init data
		var btc_data = {count:0,lines:0,last:0,circle:0};
		var ltc_data = {count:0,lines:0,last:0,circle:0};

		// init machine object
		var btc_machine = {};
		var ltc_machine = {};

		// alived machine not empty
		if ( !in_array( data.alived , null_data ) )
		{
			for ( var key in data.alived )
			{
				var key_set = replaceAll( '/' , '_' , key );
				if ( data.alived[key] === 'btc' )
				{
					eval( 'btc_machine.'+key_set+' = 1;' );
					btc_data.count ++;
				}

				if ( data.alived[key] === 'ltc' )
				{
					eval( 'ltc_machine.'+key_set+' = 1;' );
					ltc_data.count ++;
				}
			}
		}

		// died machine not empty
		if ( !in_array( data.died , null_data ) )
		{
			for ( var key in data.died )
			{
				var key_set = replaceAll( '/' , '_' , key );
				if ( data.died[key] === 'btc' )
				{
					eval( 'btc_machine.'+key_set+' = -1;' );
					btc_data.count ++;
				}

				if ( data.died[key] === 'ltc' )
				{
					eval( 'ltc_machine.'+key_set+' = -1;' );
					ltc_data.count ++;
				}
			}
		}

		btc_data.lines = Math.ceil( btc_data.count / 3 );
		btc_data.last = btc_data.count % 3;

		ltc_data.lines = Math.ceil( ltc_data.count / 3 );
		ltc_data.last = ltc_data.count % 3;


		// start btc machine check....
		if ( Object.keys( btc_machine ).length === 0 || in_array( btc_machine , null_data ) )
		{
			html_btc = replaceAll( '{data-tip}' , '暂无BTC设备!' , this.templates.nulldata );
		}
		else
		{
			for ( var key in btc_machine )
			{
				var key_set = replaceAll( '_' , '/' , key );
				if ( btc_data.circle === 0 )
					html_btc += '<div class="col-sm-4">';

				var tmp_str = replaceAll( '{usb-port}' , key_set , this.templates.btcstate );
				tmp_str = replaceAll( '{usb-run-tip-type}' , btc_machine[key] === 1 ? 'default' : 'warning' , tmp_str );
				tmp_str = replaceAll( '{usb-text}' , btc_machine[key] === 1 ? '正在运行BTC [正常]' : '目标运行BTC [已停止]' , tmp_str );
				tmp_str = replaceAll( '{usb-restart-text}' , btc_machine[key] === 1 ? '重启' : '立即启动' , tmp_str );
				html_btc += tmp_str;

				btc_data.circle ++;
				if ( btc_data.circle === btc_data.lines && btc_data.last > 0 )
				{
					html_btc += '</div>';
					btc_data.last --;
					btc_data.circle = 0;
				}
				else if ( btc_data.circle === btc_data.lines-1 && btc_data.last <= 0 )
				{
					html_btc += '</div>';
					btc_data.circle = 0;
				}
			}

			if ( btc_data.circle > 0 )
				html_btc += '</div>';
		}


		// start ltc machine check....
		if ( Object.keys( ltc_machine ).length === 0 || in_array( ltc_machine , null_data ) )
		{
			html_ltc = replaceAll( '{data-tip}' , '暂无LTC设备!' , this.templates.nulldata );
		}
		else
		{
			for ( var key in ltc_machine )
			{
				var key_set = replaceAll( '_' , '/' , key );
				if ( ltc_data.circle === 0 )
					html_ltc += '<div class="col-sm-4">';

				var tmp_str = replaceAll( '{usb-port}' , key_set , this.templates.ltcstate );
				tmp_str = replaceAll( '{usb-run-tip-type}' , ltc_machine[key] === 1 ? 'default' : 'warning' , tmp_str );
				tmp_str = replaceAll( '{usb-text}' , ltc_machine[key] === 1 ? '正在运行LTC [正常]' : '目标运行LTC [已停止]' , tmp_str );
				tmp_str = replaceAll( '{usb-restart-text}' , ltc_machine[key] === 1 ? '重启' : '立即启动' , tmp_str );
				html_ltc += tmp_str;

				ltc_data.circle ++;
				if ( ltc_data.circle === ltc_data.lines && ltc_data.last > 0 )
				{
					html_ltc += '</div>';
					ltc_data.last --;
					ltc_data.circle = 0;
				}
				else if ( ltc_data.circle === ltc_data.lines-1 && ltc_data.last <= 0 )
				{
					html_ltc += '</div>';
					ltc_data.circle = 0;
				}
			}

			if ( ltc_data.circle > 0 )
				html_ltc += '</div>';
		}

		$('#btc-machine-container').html( html_btc );
		$('#ltc-machine-container').html( html_ltc );
		
		$('.btn-run-btc').click(function(){
			actions.usbset( $(this).attr('tar') , 'btc' );
			$(this).parent().html( '正在设置并运行...<br><br><br>' );
		});

		$('.btn-run-ltc').click(function(){
			actions.usbset( $(this).attr('tar') , 'ltc' );
			$(this).parent().html( '正在设置并运行...<br><br><br>' );
		});

		$('.btn-run-restart').click(function(){
			actions.restartTar( $(this).attr('tar') );
			$(this).parent().html( '正在启动此设备...<br><br><br>' );
		});
	}
};

// check navigation state
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

// navigation button active method
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

// timeout reset top navigation
function timerResetTopBt()
{
	if ( actions.setting.runstate === false ) resetTopBt.check();
	setTimeout( function(){
		timerResetTopBt();
	} , 5000 );
}

// replace all matched string
function replaceAll(find, replace, str)
{
	return str.replace(new RegExp(find, 'g'), replace);
}

// object is or not in array
function in_array(needle, haystack, argStrict)
{
	var key = '',
	strict = !! argStrict;

	if (strict)
	{
		for (key in haystack)
		{
			if (haystack[key] === needle)
				return true;
		}
	}
	else
	{
		for (key in haystack)
		{
			if (haystack[key] == needle)
				return true;
		}
	}

	return false;
}

$(document).ready(function(){
	headerBt.init();
	timerResetTopBt();
});
