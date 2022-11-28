<?php

namespace App\MyApplication\Services;


use App\MyApplication\RuleValidate;
use Illuminate\Validation\Rule;

class GroupRuleValidation extends RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";
        return [
            "name" => [$req,"string",Rule::unique("groups","name")],
            "type" => [$req,"string",Rule::in(["private","public"])],
            "id_group" => ["required","numeric",Rule::exists("groups","id")],
        ];
    }
}
