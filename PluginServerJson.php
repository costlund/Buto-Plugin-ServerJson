<?php
class PluginServerJson{
  public $error_message = null;
  public $error_content = null;
  public $username = null;
  public $password = null;
  public function send($url, $data, $method = 'post'){
    $method = strtolower($method);
    $this->error_message = null;
    /**
     * Curl request.
     */
    $ch = curl_init($url);
    $payload = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    if($method=='post'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    }elseif($method=='delete'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }elseif($method=='put'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($payload)));
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if($this->username && $this->password){
      curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    /**
     * Validate json.
     */
    $error_message = $this->validate($result);
    /*
     * Return.
     */
    if($error_message){
      $this->error_message = $error_message;
      $this->error_content = $result;
      return array();
    }else{
      return json_decode($result, true);
    }
  }
  public function get($url){
    $this->error_message = null;
    if($this->username && $this->password){
      $auth = base64_encode($this->username.":".$this->password);
      $context = stream_context_create([
          "http" => [
              "header" => "Authorization: Basic $auth"
          ]
      ]);
      $data = @file_get_contents($url, false, $context);
    }else{
      $data = @file_get_contents($url);
    }
    if($data===false){
      $this->error_message = __CLASS__.' says: Could not get data from url '.$url.'!';
      return array();
    }else{
      return json_decode($data, true);
    }
  }
  public function get_image($url){
    $this->error_message = null;
    if($this->username && $this->password){
      $auth = base64_encode($this->username.":".$this->password);
      $context = stream_context_create([
          "http" => [
              "header" => "Authorization: Basic $auth"
          ]
      ]);
      $data = @file_get_contents($url, false, $context);
    }else{
      $data = @file_get_contents($url);
    }
    if($data===false){
      $this->error_message = 'Could not get data from url '.$url.'!';
      return array();
    }else{
      return $data;
    }
  }
  private function validate($str){
    $json = json_decode($str);
    $message = '';
    if($json===false){
      $message = 'Result is FALSE.';
    }else{
      switch (json_last_error()) {
        case JSON_ERROR_NONE:
          $message = '';
          break;
        case JSON_ERROR_DEPTH:
          $message = 'The maximum stack depth has been exceeded.';
          break;
        case JSON_ERROR_STATE_MISMATCH:
          $message = 'Invalid or malformed JSON.';
          break;
        case JSON_ERROR_CTRL_CHAR:
          $message = 'Control character error, possibly incorrectly encoded.';
          break;
        case JSON_ERROR_SYNTAX:
          $message = 'Syntax error, malformed JSON.';
          break;
        case JSON_ERROR_UTF8:
          // PHP >= 5.3.3
          $message = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
          break;
        case JSON_ERROR_RECURSION:
          // PHP >= 5.5.0
          $message = 'One or more recursive references in the value to be encoded.';
          break;
        case JSON_ERROR_INF_OR_NAN:
          // PHP >= 5.5.0
          $message = 'One or more NAN or INF values in the value to be encoded.';
          break;
        case JSON_ERROR_UNSUPPORTED_TYPE:
          $message = 'A value of a type that cannot be encoded was given.';
          break;
        default:
          $message = 'Unknown JSON error occured.';
          break;
      }
    }
    return $message;
  }
}
