<?php

namespace PullUpDomain\Data;

class FirstFormData
{
    public static function getFormData()
    {
        return [
            [
                'key' => 0,
                'type' => 'number',
                'ref' => 'age',
                'label' => 'Wiek',
                'value' => '',
            ],
            [
                'key' => 1,
                'type' => 'number',
                'ref' => 'weight',
                'label' => 'Waga (kg)',
                'value' => ''
            ],
            [
                'key' => 2,
                'type' => 'select',
                'ref' => 'body_fat',
                'showIf' => [['field' => 'age', 'value' => 1, 'type' => 'minLength'], ['field' => 'weight', 'value' => 1, 'type' => 'minLength']],
                'label' => 'Budowa ciałą',
                'choices' => ['', "Chuda", "Szczupła", "Atletyczna", "Normalna", "Okrągła", "Otyła"],
                'value' => 0
            ],
            [
                'key' => 3,
                'type' => 'choice',
                'ref' => 'practice_already',
                'showIf' => [['field' => 'body_fat', 'value' => 1, 'type' => 'higherOrEq']],
                'label' => 'Czy trenujesz sporty siłowe?',
                'text' => 'Kalistenika, street workout, powerlifting itp.',
                'value' => false
            ],
            [
                'key' => 4,
                'type' => 'select',
                'ref' => 'practice_long',
                'showIf' => [['field' => 'practice_already', 'value' => true, 'type' => 'bool']],
                'label' => 'Jak długo trenujesz (regularnie)',
                'choices' => ['', "dopiero zaczynam", "do miesiąca", "do 6 miesięcu", "dłużej"],
                'value' => 0
            ],
            [
                'key' => 5,
                'type' => 'select',
                'ref' => 'frequency_training',
                'showIf' => [['field' => 'practice_long', 'value' => 2, 'type' => 'higherOrEq']],
                'label' => 'Jak często trenujesz',
                'choices' => ['', "do 3 razy w tygodniu", "3-4 razy w tygodniu", "4-5 razy w tygodniu", "częściej"],
                'value' => 0
            ],
            [
                'key' => 6,
                'type' => 'choice',
                'ref' => 'can_do_pull_up',
                'showIf' => [['field' => 'body_fat', 'value' => 1, 'type' => 'higherOrEq']],
                'label' => 'Czy potrafisz się podciągać na drażku?',
                'text' => 'Pull up - podciąganie na drążku nachwytem, prawidłowo technicznie, bez gum oporowych',
                'value' => false
            ],
            [
                'key' => 7,
                'type' => 'choice',
                'ref' => 'resistance_pull_up',
                'showIf' => [['field' => 'can_do_pull_up', 'value' => false, 'type' => 'bool'], ['field' => 'body_fat', 'value' => 1, 'type' => 'higherOrEq']],
                'label' => "Czy używałeś/aś gumy oporowej?",
                'value' => false
            ],
            [
                'key' => 8,
                'type' => 'select',
                'ref' => 'amount_pull_up',
                'showIf' => [['field' => 'can_do_pull_up', 'value' => true, 'type' => 'bool']],
                'label' => "Ile potrafisz wykonać pull up?",
                'choices' => ['', '1-5', '6-10', '11-16', '17-20', '20-24', '24+'],
                'value' => 0
            ],
            [
                'key' => 9,
                'type' => 'select',
                'ref' => 'resistance_pull_up_type',
                'showIf' => [['field' => 'resistance_pull_up', 'value' => true, 'type' => 'bool'], ['field' => 'can_do_pull_up', 'value' => false, 'type' => 'bool']],
                'label' => "Jakiej gumy? (opór)",
                'choices' => ['', 'do 55 kg', 'do 35 kg', 'do 25kg', 'do 16 kg', 'do 9 kg'],
                'value' => 0
            ],
            [
                'key' => 10,
                'type' => 'select',
                'ref' => 'resistance_pull_up_amount',
                'showIf' => [['field' => 'resistance_pull_up_type', 'value' => 1, 'type' => 'higherOrEq']],
                'label' => "Ile razy z powyższą gumą?",
                'choices' => ['', '1-5', '6-10', '11-16', '17-20', '20-24', '24+'],
                'value' => 0
            ]
        ];
    }
}