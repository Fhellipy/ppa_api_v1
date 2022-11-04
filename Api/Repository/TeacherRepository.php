<?php

namespace Api\Repository;

use Api\Database\Database;
use Api\Model\EntityTeacher;

class TeacherRepository
{
  /**
   * Condição responsável por buscar todos os professores ativos
   */
  public static function whereAllTeachers()
  {
    return "t.active = 1";
  }

  /**
   * Condição responsável por buscar um professor ativo atráves do seu ID
   */
  public static function whereOneTeacherByID($id_professor)
  {
    return "t.id_teacher = ${id_professor} AND t.active = 1";
  }

  /**
   * Condição responsável por buscar todos os professores ativos atráves do seu siape
   */
  public static function whereOneTeacherBySiape($siape)
  {
    return "t.siape = '${siape}' AND t.active = 1";
  }

  /**
   * Condição responsável por buscar todos os professores ativos atráves do seu siape
   */
  public static function whereOneTeacherBySiapeAndClass($siape, $id_class, $deadline)
  {
    return "t.siape = '${siape}' AND cl.id_class = ${id_class} AND t.active = 1 AND j.deadline = '${deadline}'";
  }

  /**
   * Método responsável por buscar os professores no banco
   */
  public static function findTeachers($limit, $where)
  {
    //QUERY
    $query = self::getQuery($limit, $where);

    //Cria o PDO de sql que busca os professores
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obTeacher = $results->fetchObject(EntityTeacher::class)) {

      $itens[] = $obTeacher;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por retornar os dados do professor do banco através de um select
   */
  public static function getQuery($limit = '', $where)
  {
    return "SELECT t.id_teacher,
                   t.name,
                   t.email, 
                   t.siape, 
                   t.created_at, 
                   t.updated_at, 
                   t.active
                   FROM teacher t WHERE ${where} ${limit}";
  }

  /**
   * Método responsável por buscar os trabalhos de um professor no banco
   */
  public static function findJobByTeachers($where)
  {
    //QUERY
    $query = self::getQueryJobByTeacher($where);

    //Cria o PDO de sql que busca os professores
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obTeacher = $results->fetchObject(EntityTeacher::class)) {

      $itens[] = $obTeacher;
    }

    //RETORNA OS ITENS
    return $itens;
  }


  /**
   * Método responsável por criar a query que busca os trabalhos de um professor
   */
  public static function getQueryJobByTeacher($where)
  {
    return "SELECT j.id_job, 
                   j.title, 
                   cl.description as class, 
                   m.description as matter, 
                   j.description, 
                   j.deadline, 
                   ty.description as type, 
                   j.note, 
                   j.created_at, 
                   j.updated_at
                   FROM teacher t 
                   INNER JOIN teacher_course tc ON tc.id_teacher = t.id_teacher
                   INNER JOIN class cl ON cl.id_class = tc.id_class
                   INNER JOIN teacher_matter tm ON tm.id_teacher = tc.id_teacher
                   INNER JOIN matter m ON m.id_matter = tm.id_matter
                   INNER JOIN job_matter jm ON jm.id_matter = m.id_matter
                   INNER JOIN job j ON j.id_job = jm.id_job
                   INNER JOIN type ty ON ty.id_type = j.id_type
                   WHERE ${where}
                   ORDER BY j.deadline ASC";
  }



  /**
   * Método responsável por buscar no banco, os cursos que um professor da aula
   */
  public static function findCoursesByTeachers($where)
  {
    //QUERY
    $query = self::getQueryCourseByTeacher($where);

    //Cria o PDO de sql que busca os professores
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obTeacher = $results->fetchObject(EntityTeacher::class)) {

      $itens[] = $obTeacher;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por criar a query que busca os cursos e turmas que um professor da aula
   */
  public static function getQueryCourseByTeacher($where)
  {
    return "SELECT t.id_teacher, 
                   co.id_course, 
                   co.description as course, 
                   cl.id_class, 
                   cl.description as class 
                   FROM teacher t 
                   INNER JOIN teacher_course tc ON tc.id_teacher = t.id_teacher
                   INNER JOIN course co ON co.id_course = tc.id_course
                   INNER JOIN class cl ON cl.id_course = tc.id_class
                   WHERE ${where}";
  }


  /**
   * Método responsável por buscar no banco, os horários de aula de um professor
   */
  public static function findHorarysTeacher($where)
  {
    //QUERY
    $query = self::getQueryHoraryTeacher($where);

    //Cria o PDO de sql que busca os professores
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obHorary = $results->fetchObject()) {

      $itens[] = $obHorary;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por criar a query que busca os horários de aula de um professor
   */
  public static function getQueryHoraryTeacher($where)
  {
    return "SELECT hc.id_teacher, 
                   t.name, m.description as matter, 
                   hc.day, hc.start, 
                   hc.end 
                   FROM horary_class hc 
                   INNER JOIN teacher t ON t.id_teacher = hc.id_teacher
                   INNER JOIN matter m ON m.id_matter = hc.id_matter
                   WHERE ${where}";
  }
}
