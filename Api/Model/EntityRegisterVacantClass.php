<?php

namespace Api\Model;

use Api\Database\Database;
use Api\Repository\ClassRepository;

class EntityRegisterVacantClass
{
  public ?string $id_vacant_class;
  public ?string $id_teacher;
  public ?string $id_class;
  public ?string $id_matter;
  public ?string $start;
  public ?string $end;
  public ?string $date;
  public ?string $captured;
  public ?string $description;

  //Tabelas usadas
  private static $tableVacantClass = 'vacant_classes';
  private static $tableCapturedVacantClass = 'captured_vacant_classes';

  public function __construct($values = null)
  {
    if ($values) {
      foreach ($values as $key => $value) {

        $this->$key = $value;
      }
    }
  }


  /**
   * Método responsável por cadastrar uma matéria no banco de dados
   * @return boolean
   */
  public function registerVacantClass()
  {
    $this->id_vacant_class = (new Database(self::$tableVacantClass))->insert([
      'id_teacher' => $this->id_teacher,
      'id_class'   => $this->id_class,
      'id_matter'  => $this->id_matter,
      'start'      => $this->start,
      'end'        => $this->end,
      'date'       => $this->date,
      'captured'   => 0
    ]);

    return true;
  }

  /**
   * Método responsável por cadastrar um horário vago no banco de dados
   * @return boolean
   */
  public function registerCapturedVacantClass()
  {
    $this->id_capture_class = (new Database(self::$tableCapturedVacantClass))->insert([
      'id_teacher'      => $this->id_teacher,
      'id_matter'       => $this->id_matter,
      'id_vacant_class' => $this->id_vacant_class,
      'description'     => $this->description
    ]);

    $this->updateCapturedVacantClass($this->id_vacant_class);

    return true;
  }

  /**
   * Método responsável por atualizar o status da aula vaga no banco de dados
   * @return boolean
   */
  public function updateCapturedVacantClass($id_vacant_class)
  {
    $data = [
      'captured' => 1
    ];

    $database = new Database(self::$tableVacantClass);
    $database->update("id_vacant_class = '${id_vacant_class}'", $data);

    return true;
  }
}
