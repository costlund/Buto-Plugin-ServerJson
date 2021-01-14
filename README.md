# Buto-Plugin-ServerJson

Send and retrieve json content in an array.

## PHP

```
wfPlugin::includeonce('server/json');
$server = new PluginServerJson();
```

### Basic auth

```
$server->username = '_my_username_';
$server->password = '_my_password_';
```

### Token

```
$server->token = '_my_token_';
```

### Send

```
$result = $server->send('https://www.world.com', array('name' => 'James Smith', 'phone' => '555-5555'), 'post');
print_r(array($server->error_message, $result));
```

### Get

```
$result = $server->get('https://www.world.com');
print_r(array($server->error_message, $result));
```

### get_image($url)
Use this method to get image.

```
public function page_image(){
  $str = $server->get_image('_url_to_jpeg_image_');
  header('Content-type: image/jpeg');
  exit($str);
}
```
