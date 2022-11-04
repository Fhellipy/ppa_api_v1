<?php

namespace Api\Repository;

use Api\Database\Database;
use Api\Model\EntityClass;
use Api\Model\EntityCourse;
use Api\Model\EntityVacantClass;

class ClassRepository
{

  /**
   * Condição responsável por buscar um curso atráves do seu ID
   */
  public static function whereOneCourseByID($id_curso)
  {
    return "WHERE co.id_course = ${id_curso}";
  }

  /**
   * Condição responsável por buscar todos os dias cadastrados como alerta no banco
   */
  public static function whereAlertDayAll($startMonth, $endMonth)
  {
    return " ad.date BETWEEN '${startMonth}' AND '${endMonth}'";
  }

  /**
   * Condição responsável por buscar os dias cadastrados como alerta no banco
   */
  public static function whereAlertDay($startMonth, $endMonth, $description)
  {
    return "ad.date BETWEEN '${startMonth}' AND '${endMonth}' AND ad.description = '${description}'";
  }


  /**
   * Condição responsável por buscar uma turma atráves do seu ID
   */
  public static function whereOneClassByID($id_turma)
  {
    return "WHERE cl.id_class = ${id_turma}";
  }

  /**
   * Método responsável por buscar os cursos no banco
   */
  public static function findCourses($limit, $where)
  {
    //LIMITE DE REGISTROS POR PÁGINA
    $limit = explode(",", $limit);
    $limit = $limit[1] ? "LIMIT $limit[0], $limit[1]" : '';

    //QUERY
    $query = self::getQueryCourse($limit, $where);

    //Cria o PDO de sql que busca os cursos
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obCourse = $results->fetchObject(EntityCourse::class)) {

      $itens[] = $obCourse;
    }

    //RETORNA OS ITENS
    return $itens;
  }


  /**
   * Metódo responsável por buscar as informações de um curso atráves da sua descrição
   */
  public static function findCourseByDescription($descricao)
  {
    //QUERY DO CURSO
    $query = ClassRepository::getQueryCourse('', "WHERE co.description = '${descricao}'");

    //RETORNA OBJETO DO CURSO
    return (new Database)->execute($query)->fetchObject(EntityClass::class);
  }

  /**
   * Método responsável por retornar os dados do curso do banco através de um select
   */
  public static function getQueryCourse($limit = '', $where)
  {
    return "SELECT co.id_course,
                   co.description as course, 
                   co.created_at as created_at_course, 
                   co.updated_at as updated_at_course
                   FROM course co ${where} ${limit}";
  }


  /**
   * Método responsável por buscar as turmas no banco
   */
  public static function findClasses($limit, $where)
  {
    //LIMITE DE REGISTROS POR PÁGINA
    $limit = explode(",", $limit);
    $limit = $limit[1] ? "LIMIT $limit[0], $limit[1]" : '';

    //QUERY
    $query = self::getQueryClass($limit, $where);

    //Cria o PDO de sql que busca as turmas
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obCourse = $results->fetchObject(EntityClass::class)) {

      $itens[] = $obCourse;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Metódo responsável por buscar as informações de uma turma
   */
  public static function findClassByID($id_turma)
  {
    //QUERY DA CLASS
    $query = ClassRepository::getQueryClass('', "WHERE cl.id_class = '${id_turma}'");

    //RETORNA OBJETO DA CLASS
    return (new Database)->execute($query)->fetchObject(EntityClass::class);
  }

  /**
   * Método responsável por retornar os dados da turma do banco através de um select
   */
  public static function getQueryClass($limit = '', $where)
  {
    return "SELECT  cl.id_class,
                    cl.description as class, 
                    cl.id_course, 
                    cl.period as period_class, 
                    cl.created_at as created_at_class, 
                    cl.updated_at as updated_at_class
                    FROM class cl ${where} ${limit}";
  }

  /**
   * Método responsável por retornar o tipo do trabalho/atividade
   * @param string $descricao
   */
  public static function getTypeJobByDescription($descricao)
  {
    return (new Database("type"))->select("description = '${descricao}'")->fetchObject();
  }

  /**
   * Metódo responsável por buscar as informações de uma turma
   */
  public static function getCapturedClassVacant($id_vacant_class)
  {
    //QUERY DA CLASS
    $query = ClassRepository::getQueryCapturedClassVacant("WHERE cvc.id_vacant_class = ${id_vacant_class}");

    //Cria o PDO de sql que busca que professor pego um horário vago
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obClass = $results->fetchObject()) {

      $itens[] = $obClass;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por retornar os dados de captura de uma turma vaga do banco através de um select
   */
  public static function getQueryCapturedClassVacant($where)
  {
    return "SELECT  t.name as teacher,
                    m.description as matter,
                    cvc.description
                    FROM captured_vacant_classes cvc
                    INNER JOIN teacher t ON t.id_teacher = cvc.id_teacher
                    INNER JOIN matter m ON m.id_matter = cvc.id_matter
                    ${where}";
  }



  /**
   * Metódo responsável por buscar as informações de uma turma
   */
  public static function getClassVacant($id_teacher)
  {
    //QUERY DA CLASS
    $query = ClassRepository::getQueryClassVacant("WHERE vc.id_teacher = ${id_teacher}");

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


  /**
   * Metódo responsável por buscar as informações de uma turma
   */
  public static function getAlertsDays($where)
  {
    //QUERY DA DAYS
    $query = ClassRepository::getQueryAlerts($where);

    //Cria o PDO de sql que busca os alertas de dias
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obDays = $results->fetchObject()) {

      $itens[] = $obDays;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por criar a query que busca os alertas de um dia através de um select
   */
  public static function getQueryAlerts($where)
  {
    return "SELECT * FROM alert_day ad WHERE ${where} ORDER BY ad.description ASC";
  }
}
