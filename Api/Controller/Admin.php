<?php

namespace Api\Controller;

use Api\Database\Pagination;
use Api\Model\EntityAdmin;
use Api\Repository\AdminRepository;

class Admin
{
    /**
     * Metódo responsável por buscar os dados de todos os administradores ainda ativos
     */
    public static function getAdmins($request)
    {
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        $where = AdminRepository::whereAllAdmins();
        $admins = AdminRepository::findAdmin('', $where);
        $qtdTotalAdmins = count($admins);

        //INSTÂNCIA DE PAGINAÇÃO 
        $obPagination = new Pagination($qtdTotalAdmins, $currentPage, 10);
        $limit = $obPagination->getLimit();

        return [
            'administradores' => AdminRepository::findAdmin($limit, $where),
            'pagination'      => ApiPagination::getPagination($request, $obPagination)
        ];
    }


    /**
     * Metódo responsável por buscar os dados de um administrador ainda ativo
     */
    public static function getOneAdmin($request)
    {
        $queryParams = $request->getQueryParams();
        $id_admin = $queryParams['id'];
        $siape = $queryParams['siape'];

        // VERIFICA SE OS PARÂMETROS DE BUSCAR ESTÃO CORRETOS
        if (!$id_admin && !$siape) {
            return [
                "Error" => true,
                "message" => "Parâmetros incorretos! Para buscar administrador, insira ?id=algum valor ou ?siape=algum valor"
            ];
        }

        if ($siape) {
            $where = AdminRepository::whereOneAdminBySiape($siape);
        } else if ($id_admin) {

            //VALIDA O ID DO PROFESSOR
            if (!is_numeric($id_admin)) {
                throw new \Exception("O ID '" . $id_admin . "' não é de um usuário válido.", 400);
            }

            $where = AdminRepository::whereOneAdminByID($id_admin);
        }

        return [
            'admin' => AdminRepository::findAdmin('', $where)
        ];
    }

     /**
     * Método responsável por deletar um administrador
     */
    public static function deleteAdmin($request)
    {
        $queryParams = $request->getQueryParams();
        $id_admin = $queryParams['id_admin'];

        $obAdmin           = new EntityAdmin();
        $obAdmin->id_admin = $id_admin;
        $obAdmin->deleteAdmin();

        return [
            "success" => true,
            "message" => "Administrador apagado com sucesso!"
          ];
    }
}
