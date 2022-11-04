<?php

namespace Api\Controller;

use Api\Database\Pagination;
use Api\Model\EntityClass;
use Api\Model\EntityCourse;
use Api\Model\EntityMatter;
use Api\Repository\ClassRepository;

class FindClass
{
  /**
   * Método responsável por buscar os dados dos cursos
   */
  public static function getCourses($request)
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
  public static function getOneCourse($request)
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
   * Método responsável por buscar os dados das turmas
   */
  public static function getClasses($request)
  {
    $queryParams = $request->getQueryParams();
    $currentPage = $queryParams['page'] ?? 1;

    $courses = ClassRepository::findClasses('', '');
    $qtdTotalCourses = count($courses);

    //INSTÂNCIA DE PAGINAÇÃO 
    $obPagination = new Pagination($qtdTotalCourses, $currentPage, 10);
    $limit = $obPagination->getLimit();

    return [
      'turmas'     => ClassRepository::findClasses($limit, ''),
      'pagination' => ApiPagination::getPagination($request, $obPagination)
    ];
  }

  /**
   * Método responsável por buscar os dados de um curso
   */
  public static function getOneClass($request)
  {
    $queryParams = $request->getQueryParams();
    $id_turma = $queryParams['id'];

    // VERIFICA SE OS PARÂMETROS DE BUSCAR ESTÃO CORRETOS
    if (!$id_turma) {
      return [
        "Error" => true,
        "message" => "Parâmetros incorretos! Para buscar turma, insira ?id=algum valor."
      ];
    }

    //VALIDA O ID DA TURMA
    if (!is_numeric($id_turma)) {
      throw new \Exception("O ID '" . $id_turma . "' não é de uma turma válida.", 400);
    }

    // Condição para buscar uma turma
    $where = ClassRepository::whereOneClassByID($id_turma);

    return [
      'turma' => ClassRepository::findClasses('', $where),
    ];
  }

  /**
   * Método responsável por buscar os alertas de dias do calendário
   */
  public static function getAlertsDay($request)
  {
    $postVars = $request->getPostVars();
    $date = $postVars['date'];
    $description = $postVars['description'];

    $startMonth = $date . '-01';
    $endMonth = $date . '-31';

    if (!$date) return;

    if ($description) {
      $where = ClassRepository::whereAlertDay($startMonth, $endMonth, $description);
    } else {
      $where = ClassRepository::whereAlertDayAll($startMonth, $endMonth);
    }

    return [
      'days' => ClassRepository::getAlertsDays($where)
    ];
  }


  /**
   * Método responsável por deletar uma turma do banco de dados
   */
  public static function deleteClass($request)
  {
    $queryParams = $request->getQueryParams();
    $id_class = $queryParams['id_class'];

    $obClass           = new EntityClass();
    $obClass->id_class = $id_class;
    $obClass->deleteClass();

    return [
      "success" => true,
      "message" => "Turma apagada com sucesso!"
    ];
  }

  /**
   * Método responsável por deletar um curso do banco de dados
   */
  public static function deleteCourse($request)
  {
    $queryParams = $request->getQueryParams();
    $id_course = $queryParams['id_course'];

    $obCourse            = new EntityCourse();
    $obCourse->id_course = $id_course;
    $obCourse->deleteCourse();

    return [
      "success" => true,
      "message" => "Curso apagada com sucesso!"
    ];
  }

  /**
   * Método responsável por deletar uma matéria do banco de dados
   */
  public static function deleteMatter($request)
  {
    $queryParams = $request->getQueryParams();
    $id_matter = $queryParams['id_matter'];

    $obMatter            = new EntityMatter();
    $obMatter->id_matter = $id_matter;
    $obMatter->deleteMatter();

    return [
      "success" => true,
      "message" => "Matéria apagada com sucesso!"
    ];
  }

  /**
   * Método responsável por deletar uma turma vaga do banco de dados
   */
  public static function deleteVacantClass($request)
  {
    $queryParams = $request->getQueryParams();
    $id_vacant_class = $queryParams['id_vacant_class'];

    $obVacantClass                  = new EntityMatter();
    $obVacantClass->id_vacant_class = $id_vacant_class;
    $obVacantClass->deleteVacantClass();

    return [
      "success" => true,
      "message" => "Turma vaga apagada com sucesso!"
    ];
  }

  /**
   * Método responsável por deletar um alerta do dia do banco de dados
   */
  public static function deleteAlertsDay($request)
  {
    $queryParams = $request->getQueryParams();
    $id_alert_day = $queryParams['id_alert_day'];

    $obAlertDay                  = new EntityClass();
    $obAlertDay->id_alert_day = $id_alert_day;
    $obAlertDay->deleteAlertsDay();

    return [
      "success" => true,
      "message" => "Alerta do dia apagado com sucesso!"
    ];
  }
}
