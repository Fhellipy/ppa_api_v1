<?php

use Api\Controller\Teacher;
use Api\Http\Response;

// ROTA DE BUSCAR PROFESSORES
$obRouter->get('/teachers', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Teacher::getTeachers($request));
  }
]);

// ROTA DE BUSCAR UM PROFESSOR, ATRÁVES DE ID OU SIAPE
$obRouter->get('/teacher', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Teacher::getOneTeacher($request));
  }
]);


// ROTA DE BUSCAR OS CURSOS QUE UM PROFESSOR DA AULA
$obRouter->get('/courses/teacher', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Teacher::getCoursesByTeacher($request));
  }
]);


// ROTA DE BUSCAR OS CURSOS QUE UM PROFESSOR DA AULA
$obRouter->get('/jobs/teacher', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Teacher::getJobOfTeacher($request));
  }
]);

// ROTA DE DELETAR PROFESSOR
$obRouter->get('/delete/teacher', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Teacher::deleteTeacher($request));
  }
]);

// ROTA DE BUSCAR OS HORÁRIO DE AULA DE UM PROFESSOR
$obRouter->get('/horary/teacher', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Teacher::getHoraryTeacher($request));
  }
]);
