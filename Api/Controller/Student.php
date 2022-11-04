<?php

namespace Api\Controller;

use Api\Database\Pagination;
use Api\Model\EntityStudent;
use Api\Repository\JobRepository;
use Api\Repository\StudentRepository;

class Student
{
    /**
     * Metódo responsável por buscar os dados de todos os alunos ainda ativos
     */
    public static function getStudents($request)
    {
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        $where = StudentRepository::whereAllStudents();
        $students = StudentRepository::findStudents('', $where);
        $qtdTotalStudents = count($students);

        //INSTÂNCIA DE PAGINAÇÃO 
        $obPagination = new Pagination($qtdTotalStudents, $currentPage, 10);
        $limit = $obPagination->getLimit();

        return [
            'alunos'     => StudentRepository::findStudents($limit, $where),
            'pagination' => ApiPagination::getPagination($request, $obPagination)
        ];
    }


    /**
     * Metódo responsável por buscar os dados de um aluno ainda ativo
     */
    public static function getOneStudent($request)
    {
        $queryParams = $request->getQueryParams();
        $id_aluno = $queryParams['id'];
        $matricula = $queryParams['registration'];

        // VERIFICA SE OS PARÂMETROS DE BUSCAR ESTÃO CORRETOS
        if (!$id_aluno && !$matricula) {
            return [
                "Error" => true,
                "message" => "Parâmetros incorretos! Para buscar aluno, insira ?id=algum valor ou ?registration=algum valor"
            ];
        }

        if ($matricula) {
            $where = StudentRepository::whereOneStudentByRegistration($matricula);
        } else if ($id_aluno) {

            //VALIDA O ID DO ALUNO
            if (!is_numeric($id_aluno)) {
                throw new \Exception("O ID '" . $id_aluno . "' não é de um usuário válido.", 400);
            }

            $where = StudentRepository::whereOneStudentByID($id_aluno);
        }

        return [
            'aluno' => StudentRepository::findStudents('', $where)
        ];
    }

    /**
     * Método responsável por buscar as informações das matérias que um aluno faz
     */
    public static function getMattersByStudent($request)
    {
        $queryParams = $request->getQueryParams();
        $id_aluno = $queryParams['id'];

        // VERIFICA SE O PARÂMETRO DE BUSCAR ESTA CORRETO
        if (!$id_aluno) {
            return [
                "Error" => true,
                "message" => "Parâmetro incorreto! Para buscar aluno, insira ?id=algum valor"
            ];
        }

        $where = StudentRepository::whereOneStudentByID($id_aluno);

        return [
            "aluno" => StudentRepository::findMattersByStudent($where)
        ];
    }


    /**
     * Método responsável por retornar as informações dos trabalhos de um aluno
     */
    public static function getInfoJobsByStudent($request)
    {
        $queryParams = $request->getQueryParams();
        $date = $queryParams['date'];

        $registration = $request->user->registration;

        $startMonth = $date . '-01';
        $endMonth = $date . '-31';

        $where = StudentRepository::whereJobByDeadline($registration, $startMonth, $endMonth);

        return [
            'info' => JobRepository::findInfoJobsByStudent($where)
        ];
    }


    /**
     * Método responsável por deletar um estudante
     */
    public static function deleteStudent($request)
    {
        $queryParams = $request->getQueryParams();
        $id_student = $queryParams['id_student'];

        $obStudent             = new EntityStudent;
        $obStudent->id_student = $id_student;
        $obStudent->deleteStudent();

        return [
            "success" => true,
            "message" => "Estudante apagado com sucesso!"
        ];
    }
}
