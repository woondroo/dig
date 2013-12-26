<div class="container">
  <div class="page-header">
	<h1>新设备检测</h1>
  </div>
  <div id="new-machine-container" class="row"></div>
  <div class="page-header">
	<h1>BTC 运行状态</h1>
  </div>
  <div id="btc-machine-container" class="row"></div>

  <div class="page-header">
	<h1>LTC 运行状态</h1>
  </div>
  <div id="ltc-machine-container" class="row"></div>
<script type="text/javascript">
function refreshState()
{
	if ( actions.setting.runstate === false ) actions.usbstate();
	if ( actions.setting.runstate === false ) actions.check();
	setTimeout(function(){
		refreshState();
	},5000);
}
$(document).ready(function(){
	refreshState();
});
</script>
