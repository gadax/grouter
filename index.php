<?php

// possible SPL autoloader here

include('Grouter.php');

$router = new Grouter();

include('api.php');

try
{
	$router->respond();
}
catch(Throwable $e)
{
	json(['error' => $e->getMessage()]);
}
