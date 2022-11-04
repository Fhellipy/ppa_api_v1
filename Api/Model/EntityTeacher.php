<?php

namespace Api\Model;

use Api\Database\Database;

class EntityTeacher
{
  public ?string $id_teacher;
  public ?string $name;
  public ?string $email;
  public ?string $siape;
  public ?string $password;
  public ?string $id_class;
  public ?string $id_course;

  private static $tableTeacher = 'teacher';
  private static $tableTeacherCourse = 'teacher_course';
  private static $tableTeacherMatter = 'teacher_matter';

  public function __construct($values = null)
  {
    if ($values) {
      foreach ($values as $key => $value) {

        $this->$key = $value;
      }
    }        
  }


  /**
   * Método responsável por cadastrar um professor no banco de dados
   * @return boolean
   */
  public function registerTeacher()
  {
    $this->id_teacher = (new Database(self::$tableTeacher))->insert([
      'name'         => $this->name,
      'email'        => $this->email,
      'password'     => $this->password,
      'siape'        => $this->siape,
      'created_at'   => date('Y-m-d H:i:s'),
      'updated_at'   => date('Y-m-d H:i:s'),
      'active'       => '1'
    ]);

    foreach ($this->classMatter as $class) {
      $this->registerTeacherCourse($this->id_teacher, $class['id_class'], $class['id_course']);
      $this->registerTeacherMatter($this->id_teacher, $class['id_matter']);
    }

    return true;
  }

  /**
   * Método responsável por cadastrar o id, id_curso e id_turma do professor no banco
   */
  public function registerTeacherCourse($id_teacher, $id_class, $id_course)
  {
    $this->id_teacher_course = (new Database(self::$tableTeacherCourse))->insert([
      'id_teacher' => $id_teacher,
      'id_class'   => $id_class,
      'id_course'  => $id_course
    ]);

    return true;
  }


  /**
   * Método responsável por cadastrar o id do professor e as matérias dele no banco
   */
  public function registerTeacherMatter($id_teacher, $id_matter)
  {

    $this->id_teacher_matter = (new Database(self::$tableTeacherMatter))->insert([
      'id_teacher' => $id_teacher,
      'id_matter' => $id_matter
    ]);

    return true;
  }

  /**
   * Método responsável por retornar um professor com base no seu siape
   * @param string $siape
   * @return EntityTeacher
   */
  public static function getTeacherByRegistration($siape)
  {
    return (new Database(self::$tableTeacher))->select("siape = '${siape}' AND active = '1'")->fetchObject(self::class);
  }

  /**
   * Método responsável por desativar um professor no banco
   * @return boolean
   */
  public function deleteTeacher()
  {
    $id_teacher = $this->id_teacher;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableTeacher);

    $data = ['active' => '0'];

    // ATUALIZA OS DADOS DE UM PROFESSOR NO BANCO DE DADOS  
    $db->update("id_teacher = '${id_teacher}'", $data);

    return true;
  }

  /**
   * Método responsável por atualizar os dados de um professor no banco
   * @return boolean
   */
  public function updateTeacher()
  {
    $id_professor = $this->id_teacher;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableTeacher);

    $where = "id_teacher = '${id_professor}'";
    $data = [
      'name'       => $this->name,
      'siape'      => $this->siape,
      'email'      => $this->email,
      'password'   => $this->password,
      'active'     => $this->ativo,
      'updated_at' => date('Y-m-d H:i:s')
    ];

    // ATUALIZA OS DADOS DE UM PROFESSOR NO BANCO DE DADOS  
    $db->update($where, $data);

    return 'Dados do professor atualizados com sucesso!';
  }
}