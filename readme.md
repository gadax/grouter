# Grouter


Base path have to be added as below :

```php

$router->setBasePath('/BasePathOfYourProject');

```


Route can be added as below :

```php

// handle http://basePath/building/2

$router->route('GET', '/building/{id:int}', function($id) {
	// use whatever controller you need.
	json_encode( ['building' => 'Eiffel Tower'] );
});

```


And fallback as below :

```php

// handle http://basePath/somebullshitofhackers

$router->setFallback(function() {
	json( ['documentation' => 'http://go_read_the_doc.now'] );
});


```
