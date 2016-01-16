<?php
require_once('Requests.class.php');
class TelegramBot{
    /**
     * Token API telegram
     * @example https://core.telegram.org/bots/api
     */
    private $API_Url;
    public $Requests;
    function __construct($API_Url,$Requests=null){
        $Urlapi = "https://api.telegram.org/bot".$API_Url."/";
        $this->API = $Urlapi;
        $this->R = isset($Requests) ? $Requests : new ClassRequest($this->API);
    }
    /**
     * @param integer  chat_id
     * @param string   text
     * @param boolean  disable_web_page_preview | true,false
     * @param integer|null  reply_to_message_id
     * @param integer|null  reply_markup
     * @telegram https://core.telegram.org/bots/api#sendmessage
     * Optional. For text messages, the actual UTF-8 text of the message
     */
    public function SendMessage($chat_id, $text, $disable_web_page_preview =true, $reply_to_message_id = null, $reply_markup = null){
        $data["chat_id"]= $chat_id;
        $data["text"]= $text;
        $data["disable_web_page_preview"] = $disable_web_page_preview;
        $data["reply_to_message_id"] = $reply_to_message_id;
        $data["reply_markup"] = $reply_markup;
        return $this->R->RequestWebhook("sendMessage", $data);
    }

    /**
     * @param integer  chat_id
     * @param string   text
     */
    public function CallerBot($chat_id, $text){
      $Key    = 'API keys'; // API keys | The key here is https://telegram.me/Pain7
      $Search = $text; // Numbers OR Names
      $Cntry  = ''; // 966 or 967 .. | Cntry is not currently enabled
      $Count  = ''; // Control Number of Results | Count is not currently enabled

      $uRLApi = 'https://api.igeek.io/v1/'; //
      $Results = @file_get_contents("$uRLApi?key=$Key&str=$Search");
      $Results = json_decode($Results, true);
      if($Results['status'] == 200){
        $count = 0;
        foreach ($Results['result'] as $value) {
          if($count == 1){
            $Name = @$value['Name'];
            $Phone = @$value['Phone'];
            $country_code = @$value['CountryCode'];
            $country = @$value['Country'];
            // Send Message
            $Message = "$Name = $Phone";
            $this->SendMessage($chat_id,$Message);
            break;
          }//if 1 Results
          $count++;
        }//foreach
        return true;
      }//status 200
      else{
        // Not 200
        $this->SendMessage($chat_id,'No result');
        return true;
      }
    }
    /**
     * @param string  chat_id
     * @param string   text
     */
    public function CheckALL($id_chat,$text){
       $exp =  explode(" ",$text);
        switch ($exp[0]) {
            case '/help':
            case '/start':
                $Message = "hi";
                $this->SendMessage($id_chat,$Message);
                break;
            case '/s':
                $this->CallerBot($id_chat,$exp[1]);
                break;
        }
    }
    /**
     * @param DataWebhook  Updates
     */
    public function Run($Update){
        $Updates = json_decode($Update, true);
        if (!$Updates) {
          exit;
        }

        if (isset($Updates["message"])) {
            $id_chat       = @$Updates['message']['chat']['id'];

            $text          = @$Updates['message']['text'];
            $this->CheckALL($id_chat,$text);
        }
    }

}//End Class

?>
