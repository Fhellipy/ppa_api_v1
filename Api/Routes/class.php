<?php

use Api\Controller\FindClass;
use Api\Controller\Job;
use Api\Http\Response;

// ROTA DE BUSCAR CURSOS
$obRouter->get('/courses', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::getCourses($request));
  }
]);

// ROTA DE BUSCAR UM CURSO, ATRÁVES DE ID
$obRouter->get('/course', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::getOneCourse($request));
  }
]);

// ROTA DE BUSCAR TURMAS
$obRouter->get('/classes', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::getClasses($request));
  }
]);

// ROTA DE BUSCAR UMA TURMA, ATRÁVES DE ID
$obRouter->get('/class', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::getOneClass($request));
  }
]);

// ROTA DE BUSCAR OS TIPOS DOS TRABALHOS
$obRouter->get('/types/job', [
  'middlewares' => [
    'jwt-auth'
  ],
  function () {
    return new Response(200, Job::getTypesJob());
  }
]);

// ROTA DE BUSCAR UMA TURMA, ATRÁVES DE ID
$obRouter->post('/alerts/day', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::getAlertsDay($request));
  }
]);



// ROTA DE DELETAR TURMA
$obRouter->get('/delete/class', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::deleteClass($request));
  }
]);


// ROTA DE DELETAR CURSO
$obRouter->get('/delete/course', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::deleteCourse($request));
  }
]);

// ROTA DE DELETAR TRABALHO
$obRouter->get('/delete/job', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Job::deleteJob($request));
  }
]);


// ROTA DE DELETAR MATÉRIA
$obRouter->get('/delete/matter', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::deleteMatter($request));
  }
]);


// ROTA DE DELETAR MATÉRIA
$obRouter->get('/delete/vacant/class', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::deleteVacantClass($request));
  }
]);

// ROTA DE DELETAR ALERTA DO DIA
$obRouter->get('/delete/alert/day', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, FindClass::deleteAlertsDay($request));
  }
]);
