<?php

namespace Api\Model;

use Api\Database\Database;

class EntityAdmin
{
  public ?string $id_admin;
  public ?string $name;
  public ?string $email;
  public ?string $siape;
  public ?string $password;

  private static $tableAdmin = 'admin';

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
  public function registerAdmin()
  {
    $this->id_admin = (new Database(self::$tableAdmin))->insert([
      'name'         => $this->name,
      'email'        => $this->email,
      'password'     => $this->password,
      'siape'        => $this->siape,
      'admin'        => '1',
      'created_at'   => date('Y-m-d H:i:s'),
      'updated_at'   => date('Y-m-d H:i:s'),
      'active'       => '1'
    ]);

    return true;
  }

  /**
   * Método responsável por retornar um administrador com base no seu siape
   * @param string $siape
   * @return EntityTeacher
   */
  public static function getAdminByRegistration($siape)
  {
    return (new Database(self::$tableAdmin))->select("siape = '${siape}' AND active = '1'")->fetchObject(self::class);
  }


  /**
   * Método responsável por desativar um administrador no banco
   * @return boolean
   */
  public function deleteAdmin()
  {
    $id_admin = $this->id_admin;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableAdmin);

    $data = ['active' => '0'];

    // ATUALIZA OS DADOS DE UM ADMIN NO BANCO DE DADOS  
    $db->update("id_admin = '${id_admin}'", $data);

    return true;
  }

  /**
   * Método responsável por atualizar os dados de um admin no banco
   * @return boolean
   */
  public function updateAdmin()
  {
    $id_admin = $this->id_admin;

    // CRIA A CONEXÃO COM O BANCO
    $db = new Database(self::$tableAdmin);

    $where = "id_teacher = '${id_admin}'";
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

    return 'Dados do admin atualizados com sucesso!';
  }
}
