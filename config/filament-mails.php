<?php



return [

 
    'resource' => [
        'class' =>\Vormkracht10\FilamentMails\Resources\MailResource::class,
        'enabled' => true,
        'panel' => ['super-admin', 'admin', 'employee'], 
     'navigation_group' => 'المراسلات الخارجية',
    'navigation_label' => 'البريد الصادر',
    'navigation_icon' => 'heroicon-o-paper-airplane',
    ],


    'navigation' => [
        'group' => 'null',
    ],
  
];