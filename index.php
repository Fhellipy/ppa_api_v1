<?php

use Api\Http\Router;

require __DIR__ . '/config/app.php';

//INICIA O ROUTER
$obRouter = new Router(URL);

//INCLUI AS ROTAS DA API
include __DIR__ . '/Api/Routes/api.php';

// IMPRIME O RESPONSE DA ROTA
$obRouter->run()
  ->sendResponse();
