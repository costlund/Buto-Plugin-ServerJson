# Buto-Plugin-ServerJson

<p>Retrieve json content from server as an array.
Get image from server.</p>

<a name="key_0"></a>

## Usage

<pre><code>wfPlugin::includeonce('server/json');
$server = new PluginServerJson();</code></pre>

<a name="key_0_0"></a>

### Token

<pre><code>$server-&gt;token = '_my_token_';</code></pre>
<p>Adds header.</p>
<pre><code>Authorization: Bearer (token)</code></pre>

<a name="key_0_1"></a>

### Client-Secret

<pre><code>$server-&gt;client_secret = '_client_secret_';</code></pre>
<p>Adds header.</p>
<pre><code>Client-Secret: (client_secret)</code></pre>

<a name="key_0_2"></a>

### Basic auth

<pre><code>$server-&gt;username = '_my_username_';
$server-&gt;password = '_my_password_';</code></pre>
<p>Adds header.</p>
<pre><code>Authorization: Basic (base64_encode-data)</code></pre>

<a name="key_1"></a>

## Widgets



<a name="key_1_0"></a>

### widget_request

<p>Widget to test request.</p>
<pre><code>  -
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
          occupation: 'Trainer'</code></pre>

<a name="key_2"></a>

## Methods



<a name="key_2_0"></a>

### send

<pre><code>$result = $server-&gt;send('https://www.world.com', array('name' =&gt; 'James Smith', 'phone' =&gt; '555-5555'), 'post');
print_r(array($server-&gt;error_message, $result));</code></pre>

<a name="key_2_1"></a>

### get

<pre><code>$result = $server-&gt;get('https://www.world.com');
print_r(array($server-&gt;error_message, $result));</code></pre>
<p>Headers.
Add headers as second param.</p>
<pre><code>$result = $server-&gt;get('https://www.world.com', array('Authorization' =&gt; 'Basic (token)'));</code></pre>

<a name="key_2_2"></a>

### get_image

<p>Use this method to get image.</p>
<pre><code>public function page_image(){
  $str = $server-&gt;get_image('_url_to_jpeg_image_');
  header('Content-type: image/jpeg');
  exit($str);
}</code></pre>

<a name="key_2_3"></a>

### validate

<p>Validate json request.</p>

