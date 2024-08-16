<?php
namespace app\Models;

class MiddlewareModel extends Model
{
  protected $model;

  public function __construct($model)
  {
    $this->model = new $model();
  }
}