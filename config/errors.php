<?php

return [

    'types' => [

        'Illuminate\Database\Eloquent\ModelNotFoundException' => [
            'message'   => [
                'pt_br'             => 'Item não encontrado',
                'en'                => 'Item not found'
            ],
            'code'      => 404
        ],

        'Illuminate\Auth\AuthenticationException' => [
            'message'   => [
                'pt_br'             => "Faça login antes de utilizar essa função"
            ],
            'code'      => 403
        ]


    ]

    
];