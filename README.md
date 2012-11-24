# nano-php
v0.1

[![Build Status](https://secure.travis-ci.org/alrik11es/nano-php.png?branch=master)](https://travis-ci.org/alrik11es/nano-php)

minimalistic couchdb driver for PHP >5.3 it's intended to be exactly the same as
https://github.com/dscape/nano but in PHP (I'm avoiding the use of callbacks because
I think PHP people doesn't need to mess with that).

`nano-php` features:

* **minimalistic** - there is only a minimum of abstraction between you and 
  couchdb
* **errors** - errors are proxied directly from couchdb: if you know couchdb 
  you already know `nano-php`

Remember that this is a stable preview version not all options are ready to go.

Things that should work:

- Create DB
- Delete DB
- DB list command
- Use a DB to create a Document
- Inserting a document
- List documents in DB
- Show a view

There's still a lot of work to be done here.

## installation

### Composer way [HIGHLY RECOMMENDED]
1. install [Composer][1]
2. create a file called composer.json in your project folder add {} inside
3. `composer require`
4. `nano-php`
5. select desired version then push intro
6. Include or require autoloader.php in your project
7. Enjoy

### Uncompress way
1. Clone or download this repo
2. Copy the src folder where you want or call as you wish.
3. Include or require Nano.php
4. You need the Guzzle library
5. Enjoy

Are any of this installation methods bad? Add an issue if you encounter any problems.

## getting started

to use `nano-php` you need to connect it to your couchdb install, to do that:

``` php
$nano = new Nano('http://localhost:5984');
```

to create a new database:

``` php
$nano->db->create('alice');
```

and to use it:

``` php
$alice = $nano->db->use('alice');
```

in this examples we didn't specify a `result` from the returning value, the absence of a 
result means _"do this, ignore what happens"_.
in `nano-php` the returning value is a stdClass directly from the couchDB JSON result

a simple but complete example is:

``` php
$nano = new Nano('http://localhost:5984');

// clean up the database we created previously
$nano->db->destroy('alice');

// create a new database
$nano->db->create('alice');

// specify the database we are going to use
$alice = $nano->use('alice');

// and insert a document in it
$rabbit = new stdClass(); // This is the standard empty class could be one of your own classes
$rabbit->crazy = true;
$result = $alice->insert($rabbit, 'rabbit');

if(!isset($result->error)){
  echo 'you have inserted the rabbit.<br/>';
  echo $result->body;
}

```

if you run this example(after starting couchdb) you will see:

    you have inserted the rabbit.
    { ok: true,
      id: 'rabbit',
      rev: '1-6e4cb465d49c0368ac3946506d26335d' }

you can also see your document in [futon](http://localhost:5984/_utils).

## configuration

configuring nano to use your database server is as simple as:

``` php
$server = new Nano('http://localhost:5984');
$server->use('foo');
```
to specify further configuration options:

``` php
```
please check [request] for more information on the defaults. they support features like cookie jar, proxies, ssl, etc.

### pool size

a very important configuration parameter if you have a high traffic website and are using nano is setting up the `pool.size`. by default the node.js http agent (client) has a certain size of active connections that can run simultaneously, while others are kept in a queue. 

you can increase the size using `request_options` if this is problematic, and refer to the [request] documentation and examples for further clarification

## database functions

### $nano->db->create(name);

creates a couchdb database with the given `name`.

``` php
$result = $nano->db->create('alice');
if (!$result->error) {
  echo ('database alice created!');
}
```

### $nano->db->get(name);

get informations about `name`.

``` php
$result = $nano->db->get('alice');
if (!$result->error) {
  echo ($result->body);
}
```

### $nano->db->destroy(name);

destroys `name`.

``` php
$nano->db->destroy('alice');
```

even though this examples looks sync it is an async function.

### $nano->db->list();

lists all the databases in couchdb

``` php
$result = $nano->db->list();
```

### $nano->db->compact(name, [designname]);

compacts `name`, if `designname` is specified also compacts its
views.

### $nano->db->replicate(source, target, [opts]);

replicates `source` on `target` with options `opts`. `target`
has to exist, add `create_target:true` to `opts` to create it prior to
replication.

``` php
$result = $nano->db->replicate('alice', 'http://admin:password@otherhost.com:5984/alice',
     array("create_target"=>true));
});
```

### $nano->db->changes(name, [params])

asks for the changes feed of `name`, `params` contains additions
to the query string.

``` php
$result = nano->db->changes('alice');
```

### $nano->db->follow(name, [params], [callback]);

uses [follow] to create a solid changes feed. please consult follow documentation for more information as this is a very complete api on it's own

``` js
var feed = db.follow({since: "now"});
feed.on('change', function (change) {
  console.log("change: ", change);
});
feed.follow();
process.nextTick(function () {
  db.insert({"bar": "baz"}, "bar");
});
```

### $nano->use(name);

creates a scope where you operate inside `name`.

``` php
$alice = $nano->use('alice');
$alice->insert(array("crazy"=>true), 'rabbit');
```

### $nano->db->use(name);

alias for `$nano->use`

### nano.db.scope(name);

alias for `$nano->use`

### $nano->scope(name);

alias for `$nano->use`

### $nano->request(opts);

makes a request to couchdb, the available `opts` are:

* `opts.db` – the database name
* `opts.method` – the http method, defaults to `get`
* `opts.path` – the full path of the request, overrides `opts.doc` and
  `opts.att`
* `opts.doc` – the document name
* `opts.att` – the attachment name
* `opts.content_type` – the content type of the request, default to `json`
* `opts.headers` – additional http headers, overrides existing ones
* `opts.body` – the document or attachment body
* `opts.encoding` – the encoding for attachments

### $nano->relax(opts);

alias for `$nano->request`

### $nano->dinosaur(opts);

alias for `$nano->request`

                    _
                  / '_)  WAT U SAY!
         _.----._/  /
        /          /
      _/  (   | ( |
     /__.-|_|--|_l

### $nano->config;

method containing the nano configurations, possible keys are:

* `url` - the couchdb url
* `db` - the database name

## document functions

### $db->insert(doc, [params]);

inserts `doc` in the database with  optional `params`. if params is a string, its assumed as the intended document name. if params is an object, its passed as query string parameters and `doc_name` is checked for defining the document name.

``` php
$alice = $nano->use('alice');
$alice->insert(array('crazy'=>true), 'rabbit');
```

### $db->destroy(docname, rev);

removes revision `rev` of `docname` from couchdb.

``` js
alice.destroy('alice', '3-66c01cdf99e84c83a9b3fe65b88db8c0', function(err, body) {
  if (!err)
    console.log(body);
});
```

### db.get(docname, [params], [callback])

gets `docname` from the database with optional query string
additions `params`.

``` js
alice.get('rabbit', { revs_info: true }, function(err, body) {
  if (!err)
    console.log(body);
});
```

### db.head(docname, [callback])

same as `get` but lightweight version that returns headers only.

``` js
alice.head('rabbit', function(err, _, headers) {
  if (!err)
    console.log(headers);
});
```

### db.copy(src_doc, dest_doc, opts, [callback])

`copy` the contents (and attachments) of a document
to a new document, or overwrite an existing target document

``` js
alice.copy('rabbit', 'rabbit2', { overwrite: true }, function(err, _, headers) {
  if (!err)
    console.log(headers);
});
```


### db.bulk(docs, [params], [callback])

bulk operations(update/delete/insert) on the database, refer to the 
[couchdb doc](http://wiki.apache.org/couchdb/HTTP_Bulk_Document_API).

### db.list([params], [callback])

list all the docs in the database with optional query string additions `params`.  

``` js
alice.list(function(err, body) {
  if (!err) {
    body.rows.forEach(function(doc) {
      console.log(doc);
    });
  }
});
```

### db.fetch(docnames, [params], [callback])

bulk fetch of the database documents, `docnames` are specified as per 
[couchdb doc](http://wiki.apache.org/couchdb/HTTP_Bulk_Document_API).
additional query string `params` can be specified, `include_doc` is always set
to `true`.  

## views and design functions

### db.view(designname, viewname, [params], [callback])

calls a view of the specified design with optional query string additions
`params`. if you're looking to filter the view results by key(s) pass an array of keys, e.g
`{ keys: ['key1', 'key2', 'keyN'] }`, as `params`.

``` js
alice.view('characters', 'crazy_ones', function(err, body) {
  if (!err) {
    body.rows.forEach(function(doc) {
      console.log(doc.value);
    });
  }
});
```

### db.show(designname, showname, docId, [params], [callback])

calls a show function of the specified design for the document specified by docId with 
optional query string additions `params`.  

``` js
alice.show('characters', 'formatDoc', '3621898430' function(err, doc) {
  if (!err) {
    console.log(doc);
  }
});
```
take a look at the [couchdb wiki](http://wiki.apache.org/couchdb/Formatting_with_Show_and_List#Showing_Documents)
for possible query paramaters and more information on show functions.

### db.atomic(designname, updatename, docname, [body], [callback])

calls the design's update function with the specified doc in input.

``` js
db.atomic("update", "inplace", "foobar", 
{field: "foo", value: "bar"}, function (error, response) {
  assert.equal(error, undefined, "failed to update");
  assert.equal(response.foo, "bar", "update worked");
});
```

check out the tests for a fully functioning example.

## using cookie authentication

nano supports making requests using couchdb's [cookie authentication](http://guide.couchdb.org/editions/1/en/security.html#cookies) functionality. there's a [step-by-step guide here](http://mahoney.eu/2012/05/23/couchdb-cookie-authentication-nodejs-nano/), but essentially you just:

``` js
var nano     = require('nano')('http://localhost:5984')
  , username = 'user'
  , userpass = 'pass'
  , callback = console.log // this would normally be some callback
  , cookies  = {} // store cookies, normally redis or something
  ;

nano.auth(username, userpass, function (err, body, headers) {
  if (err) { 
    return callback(err);
  }

  if (headers && headers['set-cookie']) {
    cookies[user] = headers['set-cookie'];
  }

  callback(null, "It worked");
});
```

reusing a cookie:

``` js
var auth = "some stored cookie"
  , callback = console.log // this would normally be some callback
  , alice = require('nano')(
    { url : 'http://localhost:5984/alice', cookie: 'AuthSession=' + auth });
  ;

alice.insert(doc, function (err, body, headers) {
  if (err) {
    return callback(err);
  }

  // change the cookie if couchdb tells us too
  if (headers && headers['set-cookie']) {
    auth = headers['set-cookie'];
  }

  callback(null, "It worked");
});
```

## tutorials & screencasts

* [Using Composer with FuelPHP 1.x](http://tomschlick.com/2012/11/01/composer-with-fuelphp/)

## roadmap

check [issues][2]

## tests

to run (and configure) the test suite simply:

``` sh
cd nano-php
composer install
phpunit
```

after adding a new test you can run it individually (with verbose output) using:

## meta

                    _
                  / _) roar! i'm a vegan!
           .-^^^-/ /
        __/       /
       /__.|_|-|_|     cannes est superb

* code: `git clone git://github.com/alrik11es/nano-php.git`
* home: <http://github.com/alrik11es/nano-php>
* bugs: <http://github.com/alrik11es/nano-php/issues>

[1]: http://getcomposer.org/
[2]: http://github.com/alrik11es/nano-php/issues
>>>>>>> Dev
