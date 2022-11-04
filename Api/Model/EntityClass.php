<?php

namespace Api\Model;

use Api\Database\Database;

class EntityClass
{
  public ?string $id_turma;
  public ?string $id_curso;
  public ?string $descricao;
  public ?string $periodo;

  private static $tableClass = 'class';
  private static $tableAlertDay = 'alert_day';
  private static $tableHoraryClass = 'horary_class';

  public function __construct($values = null)
  {
    if ($values) {
      foreach ($values as $key => $value) {

        $this->$key = $value;
      }
    }
  }

  /**
   * Método responsável por cadastrar uma turma no banco de dados
   * @return boolean
   */
  public function registerClass()
  {
    $this->id_turma = (new Database(self::$tableClass))->insert([
      'description' => $this->descricao,
      'period'      => $this->periodo,
      'id_course'   => $this->id_curso,
      'created_at'  => date('Y-m-d H:i:s'),
      'updated_at'  => date('Y-m-d H:i:s'),
    ]);

    return true;
  }


  /**
   * Método responsável por cadastrar um alerta de dias no banco de dados
   * @return boolean
   */
  public function registerAlertDay()
  {
    foreach ($this->dates as $date) {
      $this->id_alert_day = (new Database(self::$tableAlertDay))->insert([
        'description' => $this->description,
        'date'        => $date
      ]);
    }

    return true;
  }


  /**
   * Método responsável por cadastrar o horaŕio de aula de um professor no banco de dados
   * @return boolean
   */
  public function registerHoraryTeacher()
  {
    $this->id_horary_teacher = (new Database(self::$tableHoraryClass))->insert([
      'id_teacher' => $this->id_teacher,
      'id_matter'  => $this->id_matter,
      'day'        => $this->day,
      'start'      => $this->start,
      'end'        => $this->end
    ]);

    return true;
  }


  /**
   * Método responsável por deletar uma turma no banco
   * @return boolean
   */
  public function deleteClass()
  {
    $id_class = $this->id_class;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableClass);

    // DELETA OS DADOS DE UMA TURMA NO BANCO DE DADOS  
    $db->delete("id_class = '${id_class}'");

    return true;
  }


  /**
   * Método responsável por deletar um alerta do dia do banco de dados
   * @return boolean
   */
  public function deleteAlertsDay()
  {
    $id_alert_day = $this->id_alert_day;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableAlertDay);

    // DELETA OS DADOS DE UM TRABALHO DO BANCO DE DADOS  
    $db->delete("id_alert_day = '${id_alert_day}'");

    return true;
  }
}