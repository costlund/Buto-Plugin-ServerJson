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
