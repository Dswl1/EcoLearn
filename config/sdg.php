<?php

return [
    'categories' => [
        'sosial' => [
            'name' => 'Sosial',
            'name_en' => 'Social',
            'color' => '#e5243b',
            'color_dim' => '#e5243b80',
            'gradient' => 'rgba(229,36,59,0.15)',
            'sdgs' => [1, 2, 3, 4, 5, 10],
        ],
        'lingkungan' => [
            'name' => 'Lingkungan',
            'name_en' => 'Environment',
            'color' => '#26bde2',
            'color_dim' => '#26bde280',
            'gradient' => 'rgba(38,189,226,0.15)',
            'sdgs' => [6, 7, 11, 12, 13, 14, 15],
        ],
        'ekonomi_inovasi' => [
            'name' => 'Ekonomi & Inovasi',
            'name_en' => 'Economy & Innovation',
            'color' => '#fcc30b',
            'color_dim' => '#fcc30b80',
            'gradient' => 'rgba(252,195,11,0.15)',
            'sdgs' => [8, 9],
        ],
        'tata_kelola' => [
            'name' => 'Tata Kelola & Kolaborasi',
            'name_en' => 'Governance & Collaboration',
            'color' => '#00689d',
            'color_dim' => '#00689d80',
            'gradient' => 'rgba(0,104,157,0.15)',
            'sdgs' => [16, 17],
        ],
    ],

    'all' => [
        1 => ['icon' => 'group_off', 'color' => '#e5243b', 'key' => 'no_poverty'],
        2 => ['icon' => 'nutrition', 'color' => '#dda63a', 'key' => 'zero_hunger'],
        3 => ['icon' => 'monitor_heart', 'color' => '#4c9f38', 'key' => 'good_health'],
        4 => ['icon' => 'school', 'color' => '#c5192d', 'key' => 'quality_education'],
        5 => ['icon' => 'diversity_3', 'color' => '#ff3a21', 'key' => 'gender_equality'],
        6 => ['icon' => 'water_drop', 'color' => '#26bde2', 'key' => 'clean_water'],
        7 => ['icon' => 'solar_power', 'color' => '#fcc30b', 'key' => 'clean_energy'],
        8 => ['icon' => 'work', 'color' => '#a21942', 'key' => 'decent_work'],
        9 => ['icon' => 'precision_manufacturing', 'color' => '#fd6925', 'key' => 'industry_innovation'],
        10 => ['icon' => 'diversity_2', 'color' => '#dd1367', 'key' => 'reduced_inequality'],
        11 => ['icon' => 'location_city', 'color' => '#fd9d24', 'key' => 'sustainable_cities'],
        12 => ['icon' => 'recycling', 'color' => '#bf8b2e', 'key' => 'responsible_consumption'],
        13 => ['icon' => 'public', 'color' => '#3f7e44', 'key' => 'climate_action'],
        14 => ['icon' => 'water', 'color' => '#0a97d9', 'key' => 'life_below_water'],
        15 => ['icon' => 'forest', 'color' => '#56c029', 'key' => 'life_on_land'],
        16 => ['icon' => 'gavel', 'color' => '#00689d', 'key' => 'peace_justice'],
        17 => ['icon' => 'handshake', 'color' => '#19486a', 'key' => 'partnerships'],
    ],

    'numbers' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
];
