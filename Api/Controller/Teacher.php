<?php

namespace Api\Controller;

use Api\Database\Pagination;
use Api\Model\EntityTeacher;
use Api\Repository\JobRepository;
use Api\Repository\TeacherRepository;

class Teacher
{
    /**
     * Metódo responsável por buscar os dados de todos os professores ainda ativos
     */
    public static function getTeachers($request)
    {
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        $where = TeacherRepository::whereAllTeachers();
        $teachers = TeacherRepository::findTeachers('', $where);
        $qtdTotalTeachers = count($teachers);

        //INSTÂNCIA DE PAGINAÇÃO 
        $obPagination = new Pagination($qtdTotalTeachers, $currentPage, 10);
        $limit = $obPagination->getLimit();

        return [
            'professores' => TeacherRepository::findTeachers($limit, $where),
            'pagination' => ApiPagination::getPagination($request, $obPagination)
        ];
    }


    /**
     * Metódo responsável por buscar os dados de um professor ainda ativo
     */
    public static function getOneTeacher($request)
    {
        $queryParams = $request->getQueryParams();
        $id_professor = $queryParams['id'];
        $siape = $queryParams['siape'];

        // VERIFICA SE OS PARÂMETROS DE BUSCAR ESTÃO CORRETOS
        if (!$id_professor && !$siape) {
            return [
                "Error" => true,
                "message" => "Parâmetros incorretos! Para buscar professor, insira ?id=algum valor ou ?siape=algum valor"
            ];
        }

        if ($siape) {
            $where = TeacherRepository::whereOneTeacherBySiape($siape);
        } else if ($id_professor) {

            //VALIDA O ID DO PROFESSOR
            if (!is_numeric($id_professor)) {
                throw new \Exception("O ID '" . $id_professor . "' não é de um usuário válido.", 400);
            }

            $where = TeacherRepository::whereOneTeacherByID($id_professor);
        }

        return [
            'professor' => TeacherRepository::findTeachers('', $where)
        ];
    }

    /**
     * Metódo responsável por buscar os dados de um professor ainda ativo
     */
    public static function getCoursesByTeacher($request)
    {
        $where = TeacherRepository::whereOneTeacherBySiape($request->user->siape);

        return [
            'cursos' => TeacherRepository::findCoursesByTeachers($where)
        ];
    }

    /**
     * Método responsável por retornar um curso que um professor da aula
     */
    public static function getJobOfTeacher($request)
    {
        $siape = $request->user->siape;

        $queryParams = $request->getQueryParams();
        $id_class = $queryParams['id_class'];

        // VERIFICA SE OS PARÂMETROS DE BUSCAR ESTÃO CORRETOS
        if (!$id_class) {
            return [
                "Error" => true,
                "message" => "Parâmetros incorretos! Para buscar tasks, insira ?id_class=algum valor"
            ];
        }

        $where = JobRepository::whereJobByDeadline($siape, $id_class);

        return [
            'info' => JobRepository::findInfoJobByTeacher($where)
        ];
    }

    /**
     * Método responsável por deletar um professor
     */
    public static function deleteTeacher($request)
    {
        $queryParams = $request->getQueryParams();
        $id_teacher = $queryParams['id_teacher'];

        $obTeacher             = new EntityTeacher();
        $obTeacher->id_teacher = $id_teacher;
        $obTeacher->deleteTeacher();

        return [
            "success" => true,
            "message" => "Professor apagado com sucesso!"
        ];
    }

    /**
     * Método responsável por buscar os horários de aula deum professor
     */
    public static function getHoraryTeacher($request)
    {
        $queryParams = $request->getQueryParams();
        $id_teacher = $queryParams['id_teacher'];

        $where = TeacherRepository::whereOneTeacherByID($id_teacher);

        return [
            "horary" => TeacherRepository::findHorarysTeacher($where)
        ];
    }
}
