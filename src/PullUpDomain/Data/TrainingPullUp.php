<?php

namespace PullUpDomain\Data;

class TrainingPullUp
{
    public function getNext($lastType, $repsThisRoute = 0)
    {
        switch ($lastType) {
            case 'one': {
                return $this->getSecond($repsThisRoute);
            }
            case 'two': {
                return $this->getThird($repsThisRoute);
            }
            case 'three': {
                return $this->getFourth($repsThisRoute);
            }
            case 'four': {
                return $this->getFifth($repsThisRoute);
            }
            case 'five': {
                return $this->getFirst();
            }
            default: {
                throw new \Exception("Given Type is not valid", 400);
            }
        }
    }

    public function getByType($type, $repsThisRoute = 0)
    {
        switch ($type) {
            case 'one': {
                return $this->getFirst();
            }
            case 'two': {
                return $this->getSecond($repsThisRoute);
            }
            case 'three': {
                return $this->getThird($repsThisRoute);
            }
            case 'four': {
                return $this->getFourth($repsThisRoute);
            }
            case 'five': {
                return $this->getFifth($repsThisRoute);
            }
            default: {
                throw new \Exception("Given Type is not valid", 400);
            }
        }
    }

    public function getFirst()
    {
        return [
            'interval' => $this->getIntervalBeetwenTrainings(),
            'type' => 'one',
            'texts' => [
                "Maksymalny wysiłek!",
                "5 serii, max powtórzeń w każdej serii",
                "2 minuty przerwy między seriami",
            ],
            'series' => [],
            'form' => [
                [
                    'key' => 1,
                    'type' => 'number',
                    'ref' => 'set_1',
                    'label' => "Set pierwszy",
                    'placeholder' => '?',
                    'value' => ''
                ],
                [
                    'key' => 2,
                    'type' => 'number',
                    'ref' => 'set_2',
                    'label' => "Set drugi",
                    'placeholder' => '?',
                    'value' => ''
                ],
                [
                    'key' => 3,
                    'type' => 'input',
                    'ref' => 'set_3',
                    'label' => "Set trzeci",
                    'placeholder' => '?',
                    'value' => ''
                ],
                [
                    'key' => 4,
                    'type' => 'number',
                    'ref' => 'set_4',
                    'label' => "Set czwarty",
                    'placeholder' => '?',
                    'value' => ''
                ],
                [
                    'key' => 5,
                    'type' => 'number',
                    'ref' => 'set_5',
                    'label' => "Set piąty",
                    'placeholder' => '?',
                    'value' => ''
                ],
                [
                    'key' => 6,
                    'type' => 'select',
                    'ref' => 'level',
                    'showIf' => [
                        ['field' => 'set_1', 'value' => 1, 'type' => 'minLength'],
                        ['field' => 'set_2', 'value' => 1, 'type' => 'minLength'],
                        ['field' => 'set_3', 'value' => 1, 'type' => 'minLength'],
                        ['field' => 'set_4', 'value' => 1, 'type' => 'minLength'],
                        ['field' => 'set_5', 'value' => 1, 'type' => 'minLength']
                    ],
                    'label' => "Poziom trudności?",
                    'choices' => [1 => 'bardzo łatwe', 3 => 'jest siła!', 6 => "czuje ogromne zmęczenie"],
                    'value' => 0
                ],
            ]
        ];
    }

    public function getSecond($max)
    {
        return [
            'interval' => $this->getIntervalBeetwenTrainings(),
            'type' => 'two',
            'texts' => [
                "Piramidki!",
                "Wykonaj serie od 1 do {$max} powtórzeń, lub do spalenia*",
                "20 sekund przerwy między powtórzeniami",
                "Gdy skończysz odpoczywasz 4 minuty",
                "Następnie powtarzasz to samo, ale w drugą stronę.",
                "*seria spalona - Jeżeli np. masz wykonać serie 8 powtórzeń, ale wykonałeś tylko 6, wówczas seria spalona to 7 (tyle udało Ci się zrobić poprzednio)",
                "Przykładowy scenariusz: 1, 2, 3, 4, 5, 6, 7, 8 (spalona) - 4 minuty przerwy - 7, 6, 5, 4, 3, 2, 1"
            ],
            'series' => [],
            'form' => [
                [
                    'key' => 1,
                    'type' => 'number',
                    'ref' => 'max_reps_done',
                    'label' => "Ilość powtórzeń w najwyższej serii",
                    'placeholder' => "np. {$max}",
                    'value' => ''
                ],
                [
                    'key' => 2,
                    'type' => 'select',
                    'ref' => 'level',
                    'showIf' => [
                        ['field' => 'max_reps_done', 'value' => 1, 'type' => 'minLength'],
                    ],
                    'label' => "Poziom trudności?",
                    'choices' => [1 => 'bardzo łatwe', 3 => "jest siła!", 6 => "czuje ogromne zmęczenie", 9 => 'nie zrobiłem nawet połowy'],
                    'value' => 0
                ],
            ]
        ];
    }

    public function getThird($max)
    {
        $perSeries = (int)($max * 0.5);

        return [
            'interval' => $this->getIntervalBeetwenTrainings(),
            'type' => 'three',
            'texts' => [
                "Zmienny chwyt!",
                "Łącznie będzie 3 ćwiczenia, po 3 serii po minimalnie {$perSeries} powtórzeń",
                "3x chwyt normalny (dłonie lekko szerzej niż barki)",
                "3x chwyt wąski (dłonie złączone, na wysokości twarzy)",
                "3x chwyt szeroki (dłonie maxymalnie szeroko, tak aby wykonać wymaganą ilość powtórzeń)",
                "2 minuty przerwy między setami",
            ],
            'series' => [
                ['text' => 'normalny'],
                ['text' => 'normalny'],
                ['text' => 'normalny'],
                ['text' => 'wąski'],
                ['text' => 'wąski'],
                ['text' => 'wąski'],
                ['text' => 'szeroki'],
                ['text' => 'szeroki'],
                ['text' => 'szeroki'],
            ],
            'form' => [
                [
                    'key' => 1,
                    'type' => 'number',
                    'ref' => 'reps',
                    'label' => "Ilość powtórzeń w ostatniej serii (dziewiątej, szerokie)",
                    'placeholder' => "np. 8",
                    'value' => ''
                ],
                [
                    'key' => 2,
                    'type' => 'select',
                    'ref' => 'level',
                    'showIf' => [
                        ['field' => 'reps', 'value' => 1, 'type' => 'minLength'],
                    ],
                    'label' => "Poziom trudności?",
                    'choices' => [1 => 'bardzo łatwe', 3 => "jest siła!", 6 => "czuje ogromne zmęczenie"],
                    'value' => 0
                ],
            ]
        ];
    }

    public function getFourth($max)
    {
        $perSeries = (int)($max * 0.4);
        $minimal = (int)($perSeries * 0.5);

        return [
            'interval' => $this->getIntervalBeetwenTrainings(),
            'type' => 'four',
            'texts' => [
                'Maksymalna ilość setów!',
                "Seria po {$perSeries} powtórzeń",
                "20-30 sekund przerwy",
                "Do czasu, aż ilość powtórzeń w serii zacznie drastycznie spadać (np. zamiast {$perSeries} wykonasz {$minimal})"
            ],
            'series' => [],
            'form' => [
                [
                    'key' => 1,
                    'type' => 'number',
                    'ref' => 'sets_amount',
                    'label' => "Ilość wykonanych setów:",
                    'placeholder' => "np. 8",
                    'value' => ''
                ],
                [
                    'key' => 2,
                    'type' => 'select',
                    'ref' => 'level',
                    'showIf' => [
                        ['field' => 'sets_amount', 'value' => 1, 'type' => 'minLength'],
                    ],
                    'label' => "Poziom trudności?",
                    'choices' => [1 => 'bardzo łatwe', 3 => "jest siła!", 6 => "czuje ogromne zmęczenie"],
                    'value' => 0
                ],
            ]
        ];
    }

    public function getFifth($max)
    {
        return [
            'interval' => new \DateInterval('P2DT12H'),
            'type' => 'five'
        ];
    }

    public function getRestDay($numberOfRestDay)
    {
        return [
            'type' => 'rest_' . $numberOfRestDay,
            'texts' => [
                'Odpoczynek!',
                'Dzisiaj odpoczywaj! Nie wykonuj żadnych podciągnięć na drążku.',
                "Odpoczynek jest bardzo ważny - nie tylko mięśnie potrzebują regenracji! Układ nerwowy i stawy również ulegają zmęczeniom",
                'Po każdej obiegu (5 treningach) następują dwa dni przerwy. Postaraj się w jeden z tych dni nie odwiedzać siłowni.'
            ]
        ];
    }

    /**
     * @param \DateTime $lastTrainingCreatedAt
     * @param \DateInterval $interval
     * @return array
     */
    public function getNextIsNotAvailableYetCauseOfInterval(\DateTime $lastTrainingCreatedAt, \DateInterval $interval)
    {
        $till = new \DateTime("now");
        $till->sub($interval);

        $diff = $till->diff($lastTrainingCreatedAt);

        $days = $diff->d;
        if ($days == 1) {
            $days .= ' dzień, ';
        } else {
            $days .= ' dni, ';
        }

        $hours = $diff->h;
        if ($hours == 1) {
            $hours .= ' godzina, ';
        } elseif ($hours > 1 && $hours < 5) {
            $hours .= ' godziny, ';
        } else {
            $hours .= ' godzin, ';
        }

        $minutes = $diff->i;
        if ($minutes == 1) {
            $minutes .= ' minuta';
        } elseif ($minutes > 1 && $minutes < 5) {
            $minutes .= ' minuty';
        } else {
            $minutes .= ' minut';
        }

        return [
            'type' => 'wait',
            'texts' => [
                "Kolejny trening za:",
                $days . $hours . $minutes,
                'do tego czasu postaraj się odpoczywać! :)'
            ],
            'series' => [],
            'form' => []
        ];
    }

    /**
     * @return \DateInterval
     */
    private function getIntervalBeetwenTrainings()
    {
        return new \DateInterval('PT12H');
    }
}