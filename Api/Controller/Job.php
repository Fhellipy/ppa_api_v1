<?php

namespace Api\Controller;

use Api\Database\Pagination;
use Api\Model\EntityJobRegister;
use Api\Repository\ClassRepository;

class Job
{
  /**
   * Método responsável por buscar os dados das atividades de um aluno
   */
  public static function getJobByStudent($request)
  {
    $queryParams = $request->getQueryParams();
    $currentPage = $queryParams['page'] ?? 1;

    $courses = ClassRepository::findCourses('', '');
    $qtdTotalCourses = count($courses);

    //INSTÂNCIA DE PAGINAÇÃO 
    $obPagination = new Pagination($qtdTotalCourses, $currentPage, 10);
    $limit = $obPagination->getLimit();

    return [
      'cursos'     => ClassRepository::findCourses($limit, ''),
      'pagination' => ApiPagination::getPagination($request, $obPagination)
    ];
  }

  /**
   * Método responsável por buscar os dados de um curso
   */
  public static function getOneJob($request)
  {
    $queryParams = $request->getQueryParams();
    $id_curso = $queryParams['id'];

    // VERIFICA SE OS PARÂMETROS DE BUSCAR ESTÃO CORRETOS
    if (!$id_curso) {
      return [
        "Error" => true,
        "message" => "Parâmetros incorretos! Para buscar curso, insira ?id=algum valor."
      ];
    }

    //VALIDA O ID DO CURSO
    if (!is_numeric($id_curso)) {
      throw new \Exception("O ID '" . $id_curso . "' não é de um curso válido.", 400);
    }

    // Condição para buscar um curso pelo seu ID
    $where = ClassRepository::whereOneCourseByID($id_curso);

    return [
      'curso' => ClassRepository::findCourses('', $where),
    ];
  }


  /**
   * Método responsável por deletar um trabalgo do banco de dados
   */
  public static function deleteJob($request)
  {
    $queryParams = $request->getQueryParams();
    $id_job = $queryParams['id_job'];

    $obJob         = new EntityJobRegister();
    $obJob->id_job = $id_job;
    $obJob->deleteJob();

    return [
      "success" => true,
      "message" => "Curso apagada com sucesso!"
    ];
  }
}
