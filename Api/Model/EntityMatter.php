<?php

namespace Api\Model;

use Api\Database\Database;

class EntityMatter
{
  public ?string $id_materia;
  public ?string $id_turma;
  public ?string $id_professor;
  public ?string $id_aluno;
  public ?string $descricao;

  //Tabelas usadas
  private static $tableMatter = 'matter';
  private static $tableStudentMatter = 'student_matter';
  private static $tableTeacherMatter = 'teacher_matter';
  private static $tableVacantClass = 'vacant_classes';

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
  public function registerMatter()
  {
    $this->id_materia = (new Database(self::$tableMatter))->insert([
      'description' => $this->descricao,
      'id_class'    => $this->id_turma
    ]);

    return true;
  }

  /**
   * Método responsável por cadastrar uma matéria vinculada ao aluno no banco de dados
   * @return boolean
   */
  public function registerStudentMatter($id_student, $id_matter)
  {
    $this->id_student_matter = (new Database(self::$tableStudentMatter))->insert([
      'id_student' => $id_student,
      'id_matter'  => $id_matter
    ]);

    return true;
  }

  /**
   * Método responsável por cadastrar uma matéria vinculada ao professor no banco de dados
   * @return boolean
   */
  public function registerTeacherMatter()
  {
    $this->id_professor_materia = (new Database(self::$tableTeacherMatter))->insert([
      'id_teacher' => $this->id_professor,
      'id_matter'  => $this->id_materia
    ]);

    return true;
  }


  /**
   * Método responsável por deletar uma matéria do banco
   * @return boolean
   */
  public function deleteMatter()
  {
    $id_matter = $this->id_matter;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableMatter);

    // DELETA OS DADOS DE UMA MATÉRIA DO BANCO DE DADOS  
    $db->delete("id_matter = '${id_matter}'");

    return true;
  }


  /**
   * Método responsável por deletar uma turma vaga do banco de dados
   * @return boolean
   */
  public function deleteVacantClass()
  {
    $id_vacant_class = $this->id_vacant_class;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableVacantClass);

    // DELETA OS DADOS DE UM TRABALHO DO BANCO DE DADOS  
    $db->delete("id_vacant_class = '${id_vacant_class}'");

    return true;
  }
}
