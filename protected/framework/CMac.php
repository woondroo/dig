<?php
/**
 * 获取机器网卡的物理（MAC）地址
 */
class CMac
{
	// 返回带有MAC地址的字串数组
	public $return_array = array();
	public $mac_addr;
	
	/**
	 * init class
	 */
	function CMac($os_type)
	{
		switch ( strtolower($os_type) )
		{
			case "linux":
				$this->forLinux();
				break;
			case "solaris":
				break;
			case "unix":
				break;
			case "aix":
				break;
			default:
				$this->forWindows();
				break;
		}

		$temp_array = array();
		foreach ( $this->return_array as $value )
		{
			if ( preg_match( "/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i", $value, $temp_array ) )
			{
				$this->mac_addr = $temp_array[0];
				break;
			}
		}

		unset($temp_array);
		return $this->mac_addr;
	}

	/**
	 * Get mac address for windows
	 */
	function forWindows()
	{
		@exec("ipconfig /all", $this->return_array);
		if ( $this->return_array )
			return $this->return_array;
		else
		{
			$ipconfig = $_SERVER["WINDIR"]."/system32/ipconfig.exe";
			if ( is_file($ipconfig) )
				@exec($ipconfig." /all", $this->return_array);
			else
				@exec($_SERVER["WINDIR"]."/system/ipconfig.exe /all", $this->return_array);
			
			return $this->return_array;
		}
	}

	/**
	 * Get mac address for linux
	 */
	function forLinux()
	{
		@exec("ifconfig -a", $this->return_array);
		return $this->return_array;
	}

// end class
}
