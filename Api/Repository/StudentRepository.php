<?php

namespace Api\Repository;

use Api\Database\Database;
use Api\Model\EntityJob;
use Api\Model\EntityStudent;
use Api\Model\EntityVacantClass;

class StudentRepository
{
  /**
   * Condição responsável por buscar todos os alunos ativos
   */
  public static function whereAllStudents()
  {
    return "s.active = 1";
  }

  /**
   * Condição responsável por buscar um aluno ativo atráves do seu ID
   */
  public static function whereOneStudentByID($id_aluno)
  {
    return "s.id_student = ${id_aluno} AND s.active = 1";
  }

  /**
   * Condição responsável por buscar todos os alunos ativos atráves da sua matrícula
   */
  public static function whereOneStudentByRegistration($matricula)
  {
    return "s.registration = '${matricula}' AND s.active = 1";
  }

  /**
   * Condição responsável por buscar um job atráves da matricula do aluno e do prazo
   */
  public static function whereJobByDeadline($registration, $startMonth, $endMonth)
  {
    return "WHERE j.deadline BETWEEN '${startMonth}' AND '${endMonth}' AND s.registration = '${registration}'";
  }

  /**
   * Condição responsável por buscar um job atráves da matricula do aluno e do prazo fixo
   */
  public static function whereInfoJobByDeadline($registration, $deadline)
  {
    return "WHERE j.deadline = '${deadline}' AND s.registration = '${registration}'";
  }

  /**
   * Método responsável por buscar os alunos no banco
   */
  public static function findStudents($limit, $where)
  {
    //LIMITE DE REGISTROS POR PÁGINA
    $limit = explode(",", $limit);
    $limit = $limit[1] ? "LIMIT $limit[0], $limit[1]" : '';

    //QUERY
    $query = self::getQuery($limit, $where);

    //Cria o PDO de sql que busca os alunos
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obStudent = $results->fetchObject(EntityStudent::class)) {

      $itens[] = $obStudent;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por retornar os dados do aluno do banco através de um select
   */
  public static function getQuery($limit = '', $where)
  {
    return "SELECT s.id_student,
                   s.name,
                   s.email, 
                   s.registration, 
                   s.id_class, 
                   s.created_at, 
                   s.updated_at, 
                   s.active
                   FROM student s WHERE ${where} ${limit}";
  }


  /**
   * Método responsável por buscar as matérias de um aluno no banco
   */
  public static function findMattersByStudent($where)
  {
    //QUERY
    $query = self::getQueryMatterByStudent($where);

    //Cria o PDO de sql que busca os alunos
    $results = (new Database())->execute($query);


    //Transforma a variável em object e o insere no array
    while ($obStudent = $results->fetchObject(EntityStudent::class)) {

      $itens[] = $obStudent;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responnsável por criar a query que busca as matérias que um aluno faz
   */
  public static function getQueryMatterByStudent($where)
  {

    return "SELECT s.id_student,
                   s.name, 
                   s.registration,
                   m.id_matter, 
                   m.description as matter, 
                   m.id_class 
                   FROM student_matter sm 
                   INNER JOIN student s ON s.id_student = sm.id_student 
                   INNER JOIN matter m ON m.id_matter = sm.id_matter 
                   WHERE ${where}";
  }

  /**
   * Método responsável por buscar os trabalhos de um aluno no banco
   */
  public static function findJobsByStudent($where)
  {
    //QUERY
    $query = self::getQueryJobByStudent($where);

    //Cria o PDO de sql que busca os alunos
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obStudent = $results->fetchObject()) {

      $itens[] = $obStudent;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responnsável por criar a query que busca os trabalhos de um aluno
   */
  public static function getQueryJobByStudent($where)
  {
    return "SELECT j.id_job,
                   j.title, 
                   m.description as matter,
                   j.description,
                   j.deadline,
                   ty.description as type,
                   j.note,
                   j.created_at,
                   j.updated_at
                   FROM job j 
                   INNER JOIN job_matter jm ON jm.id_job = j.id_job
                   INNER JOIN type ty ON ty.id_type = j.id_type
                   INNER JOIN matter m ON m.id_matter = jm.id_matter
                   INNER JOIN student_matter sm ON sm.id_matter = m.id_matter
                   INNER JOIN student s ON s.id_student = sm.id_student
                   ${where}
                   ORDER BY j.deadline ASC";
  }



  /**
   * Metódo responsável por buscar as informações de uma turma vaga
   */
  public static function getClassVacant($id_class)
  {
    //QUERY DA CLASS
    $query = ClassRepository::getQueryClassVacant("WHERE vc.id_class = ${id_class}");

    //Cria o PDO de sql que busca que professor pego um horário vago
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obClass = $results->fetchObject(EntityVacantClass::class)) {

      $itens[] = $obClass;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por retornar os dados de uma turma vaga do banco através de um select
   */
  public static function getQueryClassVacant($where)
  {
    return "SELECT  vc.id_vacant_class,
                    t.name as teacher, 
                    m.description as matter, 
                    vc.start, 
                    vc.end, 
                    vc.date, 
                    vc.captured 
                    FROM vacant_classes vc 
                    INNER JOIN teacher t ON t.id_teacher = vc.id_teacher 
                    INNER JOIN matter m ON m.id_matter = vc.id_matter
                    ${where}";
  }
}
