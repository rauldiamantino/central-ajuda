<?php
namespace app\Models;
use app\Models\Model;

class PublicoModel extends Model
{
  public function __construct($usuarioLogado, $empresaPadraoId)
  {
    parent::__construct($usuarioLogado, $empresaPadraoId, 'Publico');
  }
}