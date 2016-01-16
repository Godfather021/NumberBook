<?php
/**
*@author Saleh Bin Homoud | 2015-06-27
*/

class ClassRequest
{
	/*
	*         ########
	*/
	public $API_Url;
	/*
	*         ########
	*/
	function __construct($API_Url=null)
	{
		$this->Url = $API_Url;
	}

	/*
	*         ########
	*/
	public function ExecUrl_Request($Url){
	  $Respon = curl_exec($Url);
	  if ($Respon === false) {
	    $errno = curl_errno($Url);
	    $error = curl_error($Url);
	    curl_close($Url);
	    return false;
	  }

	  $HttpCode = intval(curl_getinfo($Url, CURLINFO_HTTP_CODE));
	  curl_close($Url);

	  if ($HttpCode >= 500) {
	    sleep(5);
	    return false;
	  } else if ($HttpCode != 200) {
	    $Respon = json_decode($Respon, true);
	    if ($HttpCode == 401) {
	      throw new Exception('Invalid access token provided');
	    }

	    return false;
	  } else {
	    $Respon = json_decode($Respon, true);
	    if (isset($Respon['description'])) {
	    }
	    $Respon = $Respon['result'];
	  }
	  return $Respon;

	}

	/*
	*         ########
	*/
	public function RequestWebhook($Method, array $Parameters=array())
	{
	  if (!is_string($Method)){
	    return false;
	  }

	  $Parameters['method'] = $Method;
	  header('Content-Type: application/json');
	  echo json_encode($Parameters);
	  return true;
	}

	/*
	*         ########
	*/
	public function RequestJson($Method, array $Parameters=array())
	{
	  if (!is_string($Method)) {
	    return false;
	  }

	  $Parameters['method'] = $Method;
	  $Url = curl_init($this->Url);
	  curl_setopt($Url, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($Url, CURLOPT_CONNECTTIMEOUT, 5);
	  curl_setopt($Url, CURLOPT_TIMEOUT, 60);
	  curl_setopt($Url, CURLOPT_POSTFIELDS, json_encode($Parameters));
	  curl_setopt($Url, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	  return $this->ExecUrl_Request($Url);
	}

	/*
	*         ########
	*/
	public function Request($Method, array $Parameters=array())
	{
	  if (!is_string($Method)) {
	    return false;
	  }

	  foreach ($Parameters as $key => &$val) {
	    if (!is_numeric($val) && !is_string($val)) {
	      $val = json_encode($val);
	    }
	  }

	  $Get = $this->Url . $Method . '?' . http_build_query($Parameters);
	  $Url = curl_init($Get);
	  curl_setopt($Url, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($Url, CURLOPT_CONNECTTIMEOUT, 5);
	  curl_setopt($Url, CURLOPT_TIMEOUT, 60);
	  return $this->ExecUrl_Request($Url);
	}
}
?>
