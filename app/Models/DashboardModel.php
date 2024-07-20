<?php
namespace app\Models;
use app\Models\Model;

class DashboardModel extends Model
{
  public function __construct()
  {
    parent::__construct('Dashboard');
  }

  public function dashboardVer()
  {
    $resultado = [
      'titulo' => 'Dashboard',
      'dashboard' => [
        'artigos' => 9564,
        'resumo' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dignissimos corrupti nihil eligendi, impedit illum commodi odio. Nulla quisquam cum qui corrupti maiores quasi dolorum, nihil distinctio doloribus earum modi? Enim. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dignissimos corrupti nihil eligendi, impedit illum commodi odio. Nulla quisquam cum qui corrupti maiores quasi dolorum, nihil distinctio doloribus earum modi? Enim. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dignissimos corrupti nihil eligendi, impedit illum commodi odio. Nulla quisquam cum qui corrupti maiores quasi dolorum, nihil distinctio doloribus earum modi? Enim. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dignissimos corrupti nihil eligendi, impedit illum commodi odio. Nulla quisquam cum qui corrupti maiores quasi dolorum, nihil distinctio doloribus earum modi? Enim.',
      ],
    ];

    return $resultado;
  }
}