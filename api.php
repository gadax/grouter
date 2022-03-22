<?php

function json(Array $list)
{
	// In production response do not have to be pretty printed.
	echo json_encode($list, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}


$router->setBasePath('/BasePathOfYourProject');


$router->route('GET', '/building', function() {
	json( ['building' => 'Eiffel Tower'] );
});

$router->route('GET', '/building/search/{str:field}/{str:content}/{int:page}',
	function($field, $content, $page) {
		json([
			'buildings' => ['Eiffel Tower', 'Triumphal arch'],
			'search' => [$field, $content]
		]);
	}
);

$router->route('POST', '/architect', function() {
	json( ['succes' => $_POST['name'] . ' have been created in ' . $_POST['origin'] . '.'] );
});


$router->route('DELETE', '/architect/{int:id}', function($id) {
	json( ['success' => 'Contact ' . (string) $id . ' have been deleted.'] );
});



$router->setFallback(function() {
	json( ['documentation' => 'http://go_read_the_doc.now'] );
});
