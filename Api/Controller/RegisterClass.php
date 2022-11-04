<?php

namespace Api\Controller;

use Api\Model\EntityClass;
use Api\Model\EntityCourse;
use Api\Model\EntityJobRegister;
use Api\Model\EntityMatter;
use Api\Model\EntityRegisterVacantClass;
use Api\Repository\ClassRepository;

class RegisterClass
{
  /**
   * Método responsável por cadastrar um novo curso
   */
  public static function setNewCourse($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    // BUSCA UM CURSO
    $obCourse = ClassRepository::findCourseByDescription($postVars['description']);

    // VERIFICA SE UM CURSO JÁ FOI CADASTRADO
    if ($obCourse) {
      return [
        "Error" => true,
        "message" => "Já existe um curso cadastrado com essa descrição!"
      ];
    }

    //NOVA INSTÂNCIA DE CURSO
    $obUser             = new EntityCourse();
    $obUser->descricao  = $postVars['description'];
    $obUser->registerCourse();

    return [
      "success" => true,
      "message" => "Curso cadastrado com sucesso!"
    ];
  }


  /**
   * Método responsável por cadastrar uma nova turma
   */
  public static function setNewClass($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    //NOVA INSTÂNCIA DE TURMA
    $obUser             = new EntityClass();
    $obUser->descricao  = $postVars['description'];
    $obUser->periodo    = $postVars['period'];
    $obUser->id_curso   = $postVars['id_course'];
    $obUser->registerClass();

    return [
      "success" => true,
      "message" => "Turma cadastrada com sucesso!"
    ];
  }

  /**
   * Método responsável por cadastrar uma nova matéria
   */
  public static function setNewMatter($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    //NOVA INSTÂNCIA DE MATÉRIA
    $obMatter             = new EntityMatter();
    $obMatter->descricao  = $postVars['description'];
    $obMatter->id_turma   = $postVars['id_class'];
    $obMatter->registerMatter();

    return [
      "success" => true,
      "message" => "Matéria cadastrada com sucesso!"
    ];
  }

  /**
   * Método responsável por cadastrar um novo tipo de trabalho/atividade
   */
  public static function setNewTypeJob($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    $obTypeJob = ClassRepository::getTypeJobByDescription($postVars['description']);

    // VERIFICA SE UM TIPO JÁ FOI CADASTRADO
    if ($obTypeJob) {
      return [
        "Error" => true,
        "message" => "Já existe um tipo cadastrado com essa descrição!"
      ];
    }

    //NOVA INSTÂNCIA DE MATÉRIA
    $obJob              = new EntityJobRegister();
    $obJob->description = $postVars['description'];
    $obJob->registerType();

    return [
      "success" => true,
      "message" => "Tipo de trabalho/atividade cadastrado com sucesso!"
    ];
  }

  /**
   * Método responsável por cadastrar um novo trabalho/atividade
   */
  public static function setNewJob($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    //NOVA INSTÂNCIA DE MATÉRIA
    $obJob              = new EntityJobRegister();
    $obJob->title       = $postVars['title'];
    $obJob->description = $postVars['description'];
    $obJob->deadline    = $postVars['deadline'];
    $obJob->id_type     = $postVars['id_type'];
    $obJob->id_matter   = $postVars['id_matter'];
    $obJob->note        = $postVars['note'];
    $obJob->id_classes  = $postVars['id_classes'];
    $obJob->registerJob();

    return [
      "success" => true,
      "message" => "Trabalho/Atividade cadastrado(a) com sucesso!"
    ];
  }

  /**
   * Método responsável por cadastrar um horário vago
   */
  public static function setNewVacantClass($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    $id_teacher = $request->user->id_teacher;

    //NOVA INSTÂNCIA DE HORÁRIO VAGO
    $obJob             = new EntityRegisterVacantClass;
    $obJob->id_teacher = $id_teacher;
    $obJob->id_class   = $postVars['id_class'];
    $obJob->id_matter  = $postVars['id_matter'];
    $obJob->start      = $postVars['start'];
    $obJob->end        = $postVars['end'];
    $obJob->date       = $postVars['date'];
    $obJob->registerVacantClass();

    return [
      "success" => true,
      "message" => "Horário vago cadastrado(a) com sucesso!"
    ];
  }

  /**
   * Método responsável por cadastrar um horário vago que foi pego por um professor
   */
  public static function setCaptureVacantClass($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    //NOVA INSTÂNCIA DE HORÁRIO VAGO
    $obJob                  = new EntityRegisterVacantClass;
    $obJob->id_teacher      = $postVars['id_teacher'];
    $obJob->id_matter       = $postVars['id_matter'];
    $obJob->id_vacant_class = $postVars['id_vacant_class'];
    $obJob->description     = $postVars['description'];
    $obJob->registerCapturedVacantClass();

    return [
      "success" => true,
      "message" => "Horário vago cadastrado(a) com sucesso!"
    ];
  }



  /**
   * Método responsável por cadastrar um alertas para um dia
   */
  public static function setNewAlertDay($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();

    //NOVA INSTÂNCIA DE CLASS
    $obJob              = new EntityClass;
    $obJob->dates       = $postVars['dates'];
    $obJob->description = $postVars['description'];
    $obJob->registerAlertDay();

    return [
      "success" => true,
      "message" => "Alerta(s) cadastrado(s) com sucesso!"
    ];
  }


  /**
   * Método responsável por cadastrar o horário de aula de um professor
   */
  public static function setNewHoraryClassTeacher($request)
  {
    //POST VARS
    $postVars = $request->getPostVars();
    
    //NOVA INSTÂNCIA DE CLASS
    $obHorary              = new EntityClass;
    $obHorary->id_teacher  = $postVars['id_teacher'];
    $obHorary->id_matter   = $postVars['id_matter'];
    $obHorary->day         = $postVars['day'];
    $obHorary->start       = $postVars['start'];
    $obHorary->end         = $postVars['end'];
    $obHorary->registerHoraryTeacher();

    return [
      "success" => true,
      "message" => "Horário cadastrado com sucesso!"
    ];
  }
}
