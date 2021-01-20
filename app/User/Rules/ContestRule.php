<?php

namespace Quetzal\User\Rules;

use Quetzal\Core\Rules\Rule;
use Quetzal\User\Models\Contests\Contest;

class ContestRule extends Rule
{

    public function rules()
    {
        return [
            'title' => [
                self::RULE_REQUIRED,
                [self::RULE_UNIQUE, 'class' => Contest::class]
            ],
            'details' => [
                self::RULE_REQUIRED
            ],
            'starting_date' => [
                self::RULE_REQUIRED
            ],
            'ending_date' => [
                self::RULE_REQUIRED
            ]
        ];
    }
}