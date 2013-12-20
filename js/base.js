var actions = {
	setting : {
		url_restart		: '/restart',
		url_restarttarget	: '/restartTar',
		url_shutdown		: '/shutdown',
		url_supermodel		: '/supermode',
		url_usbstate		: '/usbstate',
		url_usbset		: '/usbset',
		url_check		: '/check',
		runstate		: false
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
	sendPost : function( callback , tourl , senddata ){
		// if action is running
		if ( this.runstate === true )
			return;

		this.runstate = true;
		
		// set default data
		if ( typeof( senddata ) == 'undefined' ) senddata = {};

		eval( "actionSuccess."+callback+"( -1 )" );

		$.ajax({
			type	: "GET",
			url	: tourl,
			data 	: senddata,
			success : function( r ){
				actions.runstate = false;
				eval( "actionSuccess."+callback+"( r )" );
			},
			fail	: function(){
				actions.runstate = false;
				alert( 'request fail!' );
			}
		});
	}
};

var actionSuccess = {
	restart_home_success : function( data ){
		if ( data === -1 )
			$('#action-restart-tip').html( '正在执行重启操作...' );
		else if ( data == 200 )
			$('#action-restart-tip').html( '重启成功！' );
		else
			$('#action-restart-tip').html( '重启失败，再试试！' );
	},
	restart_success : function( data ){
		if ( data === -1 )
			$('#action-restart').html( '正在重启...' );
		else if ( data == 200 )
			$('#action-restart-tip').html( '立即重启' );
		else
			$('#action-restart-tip').html( '再次重启(失败)' );
	},
	restartTar_success : function( data ){
		alert( data );
	},
	shutdown_success : function( data ){
		if ( data === -1 )
			$('#action-restart').html( '正在停止...' );
		else if ( data == 200 )
			$('#action-restart-tip').html( '停止运行' );
		else
			$('#action-restart-tip').html( '重试停止(失败)' );
	},
	supermode_success : function( data ){
		if ( data === -1 )
			$('#action-restart').html( '超频中...' );
		else if ( data == 200 )
			$('#action-restart-tip').html( '超频运行' );
		else
			$('#action-restart-tip').html( '重试超频(失败)' );
	},
	usbstate_success : function( data ){
		alert( data );
	},
	usbset_success : function( data ){
		alert( data );
	},
	check_success : function( data ){
		alert( data );
	}
};

var headerBtActive = {
	
};
