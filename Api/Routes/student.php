<?php

use Api\Controller\Student;
use Api\Http\Response;

// ROTA DE BUSCAR ALUNOS
$obRouter->get('/students', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Student::getStudents($request));
  }
]);

// ROTA DE BUSCAR UM ALUNO, ATRÁVES DE ID OU MATRICULA
$obRouter->get('/student', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Student::getOneStudent($request));
  }
]);

// ROTA DE BUSCAR AS MATÉRIAS DE UM ALUNO, ATRÁVES DO SEU ID
$obRouter->get('/matters/student', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Student::getMattersByStudent($request));
  }
]);

// ROTA DE BUSCAR AS INFORMAÇÕES DOS TRABALHOS DE UM ALUNO
$obRouter->get('/jobs/student', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Student::getInfoJobsByStudent($request));
  }
]);

// ROTA DE DELETAR ALUNO
$obRouter->get('/delete/student', [
  'middlewares' => [
    'jwt-auth'
  ],
  function ($request) {
    return new Response(200, Student::deleteStudent($request));
  }
]);