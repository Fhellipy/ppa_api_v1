<?php

namespace Api\Model;

use Api\Database\Database;
use Api\Repository\ClassRepository;
use Api\Repository\StudentRepository;
use Api\Repository\TeacherRepository;

class EntityJob
{
  public ?string $id_job;
  public ?string $id_class;
  public ?string $id_teacher;
  public ?string $siape;
  public ?string $id_matter;
  public ?string $note;
  public ?string $id_type;
  public ?string $description;
  public ?string $title;
  public ?string $quantTasks;
  public ?string $deadline;
  public ?string $registration;
  public ?array  $tasks = [];
  public ?array  $vacantClasses = [];

  public function __construct($values = null)
  {
    if ($values) {
      foreach ($values as $key => $value) {

        $this->$key = $value;
      }
    }


    if (isset($this->siape)) {

      $this->getJobsByTeacher($this->siape, $this->id_class, $this->deadline);
      $this->getVacantClassTeacher($this->id_teacher);
    } else if (isset($this->registration)) {

      $this->getTasksByStudent();
      $this->getVacantClassStudent($this->id_class);
    }
  }



  /**
   * Método responsável por retornar as tasks de um aluno
   */
  public function getTasksByStudent()
  {
    $where = StudentRepository::whereInfoJobByDeadline($this->registration, $this->deadline);

    $this->tasks = StudentRepository::findJobsByStudent($where);
  }


  /**
   * Método responsável por retornar um curso que um professor da aula
   */
  public function getJobsByTeacher($siape, $id_class, $deadline)
  {
    $where = TeacherRepository::whereOneTeacherBySiapeAndClass($siape, $id_class, $deadline);

    $this->tasks = TeacherRepository::findJobByTeachers($where);
    $this->quantTasks = count($this->tasks);
  }


  /**
   * Método responsável por retornar as informações de uma turma que está vaga
   */
  public function getVacantClassTeacher($id_teacher)
  {
    $this->vacantClasses = ClassRepository::getClassVacant($id_teacher);
  }

  /**
   * Método responsável por retornar as informações de uma turma que está vaga
   */
  public function getVacantClassStudent($id_class)
  {
    $this->vacantClasses = StudentRepository::getClassVacant($id_class);
  }
}
