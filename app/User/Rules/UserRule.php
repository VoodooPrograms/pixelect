<?php

namespace Quetzal\User\Rules;

use Quetzal\Core\Rules\Rule;
use Quetzal\User\Models\User\User;

class UserRule extends Rule
{
    public function rules()
    {
        return [
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 8]
            ],
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_UNIQUE, 'class' => User::class]]
        ];
    }

}