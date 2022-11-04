<?php

namespace Api\Repository;

use Api\Database\Database;
use Api\Model\EntityTeacher;

class AdminRepository
{
  /**
   * Condição responsável por buscar todos os administradores ativos
   */
  public static function whereAllAdmins()
  {
    return "a.active = 1";
  }

  /**
   * Condição responsável por buscar um administrador ativo atráves do seu ID
   */
  public static function whereOneAdminByID($id_admin)
  {
    return "a.id_admin = ${id_admin} AND a.active = 1";
  }

  /**
   * Condição responsável por buscar todos os administradores ativos atráves do seu siape
   */
  public static function whereOneAdminBySiape($siape)
  {
    return "a.siape = '${siape}' AND a.active = 1";
  }

  /**
   * Condição responsável por buscar todos os administradores ativos atráves do seu siape
   */
  public static function whereOneTeacherBySiapeAndClass($siape, $id_class)
  {
    return "a.siape = '${siape}' AND cl.id_class = ${id_class} AND a.active = 1";
  }

  /**
   * Método responsável por buscar os administradores no banco
   */
  public static function findAdmin($limit, $where)
  {
    //QUERY
    $query = self::getQuery($limit, $where);

    //Cria o PDO de sql que busca os administradores
    $results = (new Database())->execute($query);

    //Transforma a variável em object e o insere no array
    while ($obTeacher = $results->fetchObject(EntityTeacher::class)) {

      $itens[] = $obTeacher;
    }

    //RETORNA OS ITENS
    return $itens;
  }

  /**
   * Método responsável por retornar os dados do administrador do banco através de um select
   */
  public static function getQuery($limit = '', $where)
  {
    return "SELECT a.id_admin,
                   a.name,
                   a.email, 
                   a.siape, 
                   a.admin, 
                   a.created_at, 
                   a.updated_at, 
                   a.active
                   FROM admin a WHERE ${where} ${limit}";
  }
}
