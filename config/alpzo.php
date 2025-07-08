<?php
return [
    'downloads' => [
        'php'=> [
            'url'=>'https://windows.php.net/downloads/releases/archives/',
            'regex'=>'/php-(.*)-nts-Win32-VC15-.*\.zip/',
        ],
        'composer'=> [
            'url'=>'https://getcomposer.org/download/',
            'regex'=>'/composer-(.*)-win32\.zip/',
        ],
        'nodejs'=> [
            'url'=>'https://nodejs.org/dist/index.json',
        ]
    ],
];
