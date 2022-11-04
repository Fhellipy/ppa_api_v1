<?php

namespace Api\Repository;

use Api\Database\Database;
use Api\Model\EntityCourse;
use Api\Model\EntityJob;
use Api\Model\EntityStudent;

class JobRepository
{

  /**
   * Condição responsável por buscar um curso atráves do seu ID
   */
  public static function whereOneCourseByID($id_curso)
  {
    return "WHERE co.id_course = ${id_curso}";
  }

  /**
   * Condição responsável por buscar um job atráves do ID da turma e um prazo
   */
  public static function whereJobByDeadline($id_class)
  {
    $date = date('Y-m') . '-01';
    $dateNow = date('Y-m') . '-31';

    return "WHERE j.deadline BETWEEN '${date}' AND '${dateNow}' AND m.id_class = ${id_class}";
  }

  /**
   * Método responsável por buscar os cursos no banco
   */
  public static function findJobByStudent($limit, $where)
  {
    //LIMITE DE REGISTROS POR PÁGINA
    $limit = explode(",", $limit);
    $limit = $limit[1] ? "LIMIT $limit[0], $limit[1]" : '';

    //QUERY
    $query = self::getQueryJob($limit, $where);

    //Cria o PDO de sql que busca os cursos
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obJob = $results->fetchObject(EntityCourse::class)) {

      $itens[] = $obJob;
    }

    //RETORNA OS ITENS
    return $itens;
  }


  /**
   * Método responsável por retornar os dados do curso do banco através de um select
   */
  public static function getQueryJob($limit = '', $where)
  {
    return "SELECT co.id_course,
                   co.description as course, 
                   co.created_at as created_at_course, 
                   co.updated_at as updated_at_course
                   FROM course co ${where} ${limit}";
  }

  /**
   * Método responsável por buscar o id e o prazo de um job no banco
   */
  public static function findInfoJobByTeacher($where)
  {
    //QUERY
    $query = self::getQueryInfoJob($where);
    
    //Cria o PDO de sql que busca os cursos
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obJob = $results->fetchObject(EntityJob::class)) {

      $itens[] = $obJob;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por retornar os dados do job do banco através de um select
   */
  public static function getQueryInfoJob($where)
  {
    return "SELECT j.deadline,
                   m.id_class,
                   t.id_teacher,
                   t.siape
                   FROM job j
                   INNER JOIN job_matter jm ON jm.id_job = j.id_job
                   INNER JOIN matter m ON m.id_matter = jm.id_matter 
                   INNER JOIN teacher_course tc ON tc.id_class = m.id_class
                   INNER JOIN teacher t ON t.id_teacher = tc.id_teacher
                    ${where}";
  }


  /**
   * Método responsável por buscar as informações dos trabalhos de um aluno no banco
   */
  public static function findInfoJobsByStudent($where)
  {
    //QUERY
    $query = self::getQueryInfoJobStudent($where);

    //Cria o PDO de sql que busca os alunos
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obStudent = $results->fetchObject(EntityJob::class)) {

      $itens[] = $obStudent;
    }

    //RETORNA OS ITENS
    return $itens;
  }


  /**
   * Método responsável por retornar os dados do job do banco através de um select
   */
  public static function getQueryInfoJobStudent($where)
  {
    return "SELECT s.id_class,
                   s.registration,
                   j.deadline,
                   COUNT(j2.deadline) as quantTasks
                   FROM job j 
                   INNER JOIN job_matter jm ON jm.id_job = j.id_job
                   INNER JOIN job j2 ON j2.id_job = j.id_job
                   INNER JOIN student_matter sm ON sm.id_matter = jm.id_matter
                   INNER JOIN student s ON s.id_student = sm.id_student
                   ${where}
                   GROUP BY
                   j2.deadline 
                   HAVING j2.deadline = j.deadline
                   ORDER BY j.deadline ASC";
  }
}
