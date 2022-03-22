<?php

/* Copyright (c) 2022 */
/* Discord [EUW] : gada#5503 */
/* Everything in this directory */

class Grouter
{
	protected $routes = [];
	protected $basePath;
	protected $fallback;

	protected $bindingTypes = [
		'int'  => '[0-9]++',
		'str'  => '[0-9A-Za-z-_\']++',
		'bool'  => 'true|false',
		'float' => '???', // flemme
		'double' => '???' // flemme
	];


	public function route(String $verb, String $path, Closure $callback)
	{
		preg_match_all('/\{([a-zA-Z]++):([0-9A-Za-z_-]++)\}/', $path, $matches);

		for($i = 0; $i < count($matches[0]); $i++)
		{
			$path = str_replace($matches[0][$i], '(' . $this->bindingTypes[$matches[1][$i]] . ')', $path);
		}

		$key = '/^' . str_replace('/', '\/', $verb . $path) . '[\/]?$/';

		$this->routes[$key] = [
			'callback' => $callback,
			'bindings' => $matches
		];
	}

	public function respond()
	{
		$path = $_SERVER['REQUEST_METHOD']
			. str_replace($this->basePath,
				'',
				strstr($_SERVER['REQUEST_URI'], '?', true) ?: $_SERVER['REQUEST_URI']);

		$this->callClosure($path);
	}

	public function setBasePath(String $basePath)
	{
		$this->basePath = $basePath;
	}

	public function setFallback(Closure $fallback)
	{
		$this->fallback = $fallback;
	}

	/**
	 * Reflexion can give order of parameter names in the callback
	 * to reorder parameters of the bindings array
	 * in order to have parameters of the closure always matching the good value.
	 * 
	 * Currently, parameters in definition have to be well ordered.
	 */
	private function callClosure(String $path)
	{
		foreach($this->routes as $regexPath => $content)
		{
			if(preg_match($regexPath, $path, $matches))
			{
				array_shift($matches);

				self::typeBindings($matches, $content['bindings'][1]);

				$content['callback'](...$matches);
				return;
			}
		}

		($this->fallback)();
	}


	static private function typeBindings(Array &$matches, Array &$types)
	{
		for($i = 0; $i < count($matches); $i++)
		{
			$matches[$i] = ($types[$i] . 'val')($matches[$i]);
		}
	}
}