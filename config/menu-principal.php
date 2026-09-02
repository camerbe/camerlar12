<?php
return [
    'accueil' => [
        'label' => 'Accueil',
        'href' => '/',
        'simple' => true,
    ],

    'actualite' => [
        'label' => 'Actualité',
        'sections' => [
            [
                'label' => 'Rubriques',
                'links' => [
                    'Diaspora' => '/camerounais-du-monde/diaspora',
                    'Économie' => '/actualites/economie',
                    'Politique' => '/actualites/politique',
                    'Religion' => '/actualites/religion',
                    'Sérail' => '/actualites/serail',
                    'Société' => '/actualites/societe',
                    'Sport' => '/actualites/sport',
                ],
            ],
            [
                'label' => 'Divertissement',
                'links' => [
                    'Insolite' => '/actualites/insolite',
                    'Le saviez-vous' => '/fait-curieux/le-saviez-vous',
                    'People' => '/actualites/people',
                    'Sans tabou' => '/libre-parole/sans-tabou',
                ],
            ],
            [
                'label' => 'International',
                'links' => [
                    'FrançaisCamer' => '/frananglais/francaiscamer',
                    'Françafrique' => '/liens-postcoloniaux/francafrique',
                    'Géopolitique' => '/monde-pouvoir/geopolitique',
                    'Panafricanisme' => '/actualites/panafricanisme',
                ],
            ],
            [
                'label' => 'Santé',
                'links' => [
                    'Allo Docteur' => '/le-coin-sante/allo-docteur',
                    'Santé' => '/actualites/sante',
                ],
            ],
        ],
    ],

    'culture' => [
        'label' => 'Culture',
        'links' => [
            'Art' => '/culture/art',
            'Cinéma' => '/culture/cinema',
            'Livre' => '/culture/livres',
            'Musique' => '/culture/musique',
        ],
    ],

    'expression_libre' => [
        'label' => 'Libre Voix',
        'links' => [
            'Débat' => '/tribune/le-debat',
            'Droit' => '/droit/point-du-droit',
            'Point de vue' => '/analyse/point-de-vue',
        ],
    ],


];
