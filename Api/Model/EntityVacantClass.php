<?php

namespace Api\Model;

use Api\Database\Database;
use Api\Repository\ClassRepository;

class EntityVacantClass
{
  public ?string $id_vacant_class;
  public ?string $id_teacher;
  public ?string $teacher;
  public ?string $id_class;
  public ?string $id_matter;
  public ?string $matter;
  public ?string $start;
  public ?string $end;
  public ?string $date;
  public ?string $captured;
  public ?string $description;
  public ?array  $infosCaptured = [];

  public function __construct($values = null)
  {
    if ($values) {
      foreach ($values as $key => $value) {

        $this->$key = $value;
      }
    }

    $this->getCaptureVacantClass($this->id_vacant_class);
  }


  /**
   * Método responsável por retornar um curso de um aluno
   */
  public function getCaptureVacantClass($id_vacant_class)
  {
    if (!$this->id_vacant_class) return;    
    $this->infosCaptured = ClassRepository::getCapturedClassVacant($id_vacant_class);
  }
}
