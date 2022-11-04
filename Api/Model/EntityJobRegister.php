<?php

namespace Api\Model;

use Api\Database\Database;
use Api\Repository\ClassRepository;
use Api\Repository\TeacherRepository;

class EntityJobRegister
{
  public ?string $id_job;
  public ?string $id_class;
  public ?string $id_teacher;
  public ?string $deadline;
  public ?string $siape;
  public ?string $id_matter;
  public ?string $note;
  public ?string $id_type;
  public ?string $description;
  public ?string $title;
  public ?array $id_classes = [];

  //Tabelas usadas
  private static $tableJob = 'job';
  private static $tableJobMatter = 'job_matter';
  private static $tableType = 'type';

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
  public function registerJob()
  {
    $this->id_job = (new Database(self::$tableJob))->insert([
      'title'       => $this->title,
      'description' => $this->description,
      'deadline'    => $this->deadline,
      'id_type'     => $this->id_type,
      'note'        => $this->note,
      'created_at'  => date('Y-m-d H:i:s'),
      'updated_at'  => date('Y-m-d H:i:s')
    ]);

    $this->registerJobMatter($this->id_job, $this->id_matter);

    return true;
  }

  /**
   * Método responsável por cadastrar um trabalho/atividade vinculada a uma matéria no banco de dados
   * @return boolean
   */
  public function registerJobMatter($id_job, $id_matter)
  {
    $this->id_job_matter = (new Database(self::$tableJobMatter))->insert([
      'id_job'    => $id_job,
      'id_matter' => $id_matter
    ]);

    return true;
  }

  /**
   * Método responsável por cadastrar o tipo de trabalho/atividade no banco de dados
   * @return boolean
   */
  public function registerType()
  {
    $this->id_type = (new Database(self::$tableType))->insert([
      'description' => $this->description
    ]);

    return true;
  }


  /**
   * Método responsável por deletar um trabalho do banco
   * @return boolean
   */
  public function deleteJob()
  {
    $id_job = $this->id_job;

    $this->deleteJobMatter($id_job);

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableJob);

    // DELETA OS DADOS DE UM TRABALHO DO BANCO DE DADOS  
    $db->delete("id_job = '${id_job}'");

    return true;
  }

  /**
   * Método responsável por deletar um trabalho vinculado a uma matéria do banco
   * @return boolean
   */
  public function deleteJobMatter($id_job)
  {
    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableJobMatter);

    // DELETA OS DADOS DE UM TRABALHO DO BANCO DE DADOS  
    $db->delete("id_job = '${id_job}'");

    return true;
  }
}
