<?php
/**
 * Get a list of search suggestions from Google API.
 *
 * @package : Suggestions;
 * @author : Saleh Bin Homud;
 * @version : 1.0;
 * @link : https://github.com/Saleh7;
 */
 
class Suggestions{

  public $Lang;
  public $Query;

  function __construct($Lang,$Query){
    $this->query = urlencode($Query);
    $this->lang  = $Lang;
    $this->Request();
  }

  /**
   *
   */
  public function Request(){
	 	$agent = "AAPP Application/1.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4)";
	  $google = "http://suggestqueries.google.com/complete/search?output=toolbar&q=".$this->query."&hl=".$this->lang;
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $google);
	  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	  curl_setopt($ch, CURLOPT_HEADER, 0);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  $xmlInput = curl_exec($ch);
    $thisxml  = iconv('windows-1256', 'utf-8', $xmlInput); //windows1256(arabic) to utf-8
    $Suggest = $this->Suggest($thisxml);
	  curl_close($ch);
  }
  /**
   *
   */
  public function Suggest($thisxml) {
    $DOMDo = new DOMDocument();
    $DOMDo->loadxml($thisxml);
    $toplevel = $DOMDo->getElementsByTagName('toplevel');
    $Suggest = $DOMDo->getElementsByTagName('suggestion');
    $Data = array();
    foreach ($Suggest as $Suggests) {
      $Data[] = $Suggests->getAttribute('data');
    }
    return $Data;
  }
}
 ?>
