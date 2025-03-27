<?php
class PluginServerJson{
  public $error_message = null;
  public $error_content = null;
  public $username = null;
  public $password = null;
  public $token = null;
  public $client_secret = null;
  public $http_response_header = null;
  public function send($url, $data = null, $method = 'post', $header = array()){
    $method = strtolower($method);
    $this->error_message = null;
    /**
     * Curl request.
     */
    $ch = curl_init($url);
    /**
     * http header
     */
    $httpheader = new PluginWfArray();
    $httpheader->set(true, 'Content-Type: application/json');
    $httpheader->set(true, "Accept: application/json");
    if($this->token){
      /**
       * token
       */
      $httpheader->set(true, "Authorization: Bearer ".$this->token);
    }
    if($this->client_secret){
      /**
       * client_secret
       */
      $httpheader->set(true, "Client-Secret: ".$this->client_secret);
    }
    /**
     * headers
     */
    if($header){
      foreach($header as $k => $v){
        $httpheader->set(true, "$k: $v");
      }
    }
    /**
     * 
     */
    $payload = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    if($method=='post'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader->get());
    }elseif($method=='delete'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader->get());
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }elseif($method=='put'){
      $httpheader->set(true, 'Content-Length: ' . wfPhpfunc::strlen($payload));
      curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader->get());
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    }elseif($method=='get'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader->get());
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
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
      return array('error' => array('message' => $error_message, 'content' => $result)) ;
    }else{
      return json_decode($result, true);
    }
  }
  /**
   * Get data via file_get_contents.
   * 
   * @param string $url Api url.
   * @param array $header Add headers.
   * @return array
   */
  public function get($url, $header = array()){
    $this->error_message = null;
    $context = new PluginWfArray();
    $header_array = array();
    /**
     * auth
     */
    if($this->username && $this->password){
      $auth = base64_encode($this->username.":".$this->password);
      $header_array['Authorization'] = "Basic $auth";
    }elseif($this->token){
      $header_array['Authorization'] = "Bearer ".$this->token;
    }
    /**
     * header
     */
    if($header){
      $header_array = array_merge($header, $header_array);
    }
    /**
     * header
     */
    $temp = '';
    foreach($header_array as $k => $v){
      $temp .= "\r\n$k: $v";
    }
    $context->set('http/header', $temp);
    /**
     * get contents
     */
    $data = @file_get_contents($url, false, stream_context_create((array)$context->get()));
    /**
     * http_response_header
     */
    $this->http_response_header = $http_response_header;
    /**
     * handle error
     */
    if($data===false){
      $this->error_message = __CLASS__.' says: Could not get data from url '.$url.'!';
      return array('error' => array('message' => $this->error_message, 'content' => $data));
    }else{
      $return = json_decode($data, true);
      if(!is_null($return)){
        return $return;
      }else{
        return array('message' => 'Content is not a json string!', 'content' => $data);
      }
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
  public function widget_request($data){
    $data = new PluginWfArray($data);
    /*
     * params
     */
    if($data->get('data/params')){
      foreach($data->get('data/params') as $v){
        $data->set('data/url', wfPhpfunc::str_replace('['.$v.']', wfRequest::get($v), $data->get('data/url')));      }
    }
    /*
     *
     */
    if($data->get('data/type')=='get'){
      $result = $this->get($data->get('data/url'));
    }elseif($data->get('data/type')=='post'){
      $result = $this->send($data->get('data/url'), $data->get('data/data'), 'post');
    }elseif($data->get('data/type')=='delete'){
      $result = $this->send($data->get('data/url'), null, 'delete');
    }elseif($data->get('data/type')=='put'){
      $result = $this->send($data->get('data/url'), $data->get('data/data'), 'put');
    }
    /*
     *
     */
    $element = wfDocument::createHtmlElement('pre', wfHelp::getYmlDump($data->get('data')));
    wfDocument::renderElement(array($element));
    /*
     *
     */
    $element = wfDocument::createHtmlElement('pre', wfHelp::getYmlDump($result));
    wfDocument::renderElement(array($element));
  }
}
