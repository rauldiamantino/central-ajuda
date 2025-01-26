<?php
namespace app\Models;
use app\Models\Model;

class DashboardModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Dashboard');
  }
}