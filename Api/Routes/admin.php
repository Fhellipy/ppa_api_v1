<?php

use Api\Controller\Admin;
use Api\Http\Response;

// ROTA DE BUSCAR ADMINISTRADORES
$obRouter->get('/admins', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Admin::getAdmins($request));
  }
]);

// ROTA DE BUSCAR UM ADMINISTRADOR, ATRÁVES DE ID OU SIAPE
$obRouter->get('/adminr', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Admin::getOneAdmin($request));
  }
]);


// ROTA DE DELETAR ADMIN
$obRouter->get('/delete/admin', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Admin::deleteAdmin($request));
  }
]);