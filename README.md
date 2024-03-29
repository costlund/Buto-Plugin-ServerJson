# Buto-Plugin-ServerJson

Send and retrieve json content in an array.

## PHP

```
wfPlugin::includeonce('server/json');
$server = new PluginServerJson();
```

### Send

```
$result = $server->send('https://www.world.com', array('name' => 'James Smith', 'phone' => '555-5555'), 'post');
print_r(array($server->error_message, $result));
```

#### token
```
$server->token = '_my_token_';
```
Add header.
```
Authorization: Bearer (token)
```

### client_secret
```
$server->client_secret = '_client_secret_';
```
Add header.
```
Client-Secret: (client_secret)
```

### Get

```
$result = $server->get('https://www.world.com');
print_r(array($server->error_message, $result));
```

#### Basic auth
```
$server->username = '_my_username_';
$server->password = '_my_password_';
```
Add header.
```
Authorization: Basic (base64_encode-data)
```

#### Token

```
$server->token = '_my_token_';
```
Add header.
```
Authorization: Bearer (my_token)
```


#### Headers
Add extra headers as second param.
```
$result = $server->get('https://www.world.com', array('Authorization' => 'Basic (token)'));
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

### Request widget
Widget to test request.
```
  -
    type: widget
    data:
      plugin: server/json
      method: request
      data:
        type: get
        url: 'http://localhost:8080/users'
  -
    type: widget
    data:
      plugin: server/json
      method: request
      data:
        type: get
        url: 'http://localhost:8080/user/2'
  -
    type: widget
    data:
      plugin: server/json
      method: request
      data:
        type: post
        url: 'http://localhost:8080/save'
        data:
          firstName: 'James'
          lastName: 'Smith'
          age: 88
          occupation: 'Trainer'
  -
    type: widget
    data:
      plugin: server/json
      method: request
      data:
        type: delete
        url: 'http://localhost:8080/delete/13'
  -
    type: widget
    data:
      plugin: server/json
      method: request
      data:
        type: put
        url: 'http://localhost:8080/update/2'
        data:
          firstName: 'James'
          lastName: 'Andersson'
          age: 88
          occupation: 'Trainer'
```

