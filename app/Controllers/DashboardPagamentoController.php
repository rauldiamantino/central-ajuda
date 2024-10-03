<?php
namespace app\Controllers;
use app\Models\DashboardPagamentoModel;

class DashboardPagamentoController extends DashboardController
{
  protected $pagamentoModel;

  public function __construct()
  {
    parent::__construct();

    $this->pagamentoModel = new DashboardPagamentoModel();
  }

  public function reprocessar()
  {

  }
}