<?php

namespace App\MyApplication\Services;



use App\MyApplication\RuleValidate;
use Illuminate\Validation\Rule;

class FileRuleValidation extends RuleValidate
{
    public function rules(bool $isrequired = false): array
    {
        $req = $isrequired ? "required" : "nullble";
        return [
            "name" => [$req,"string",Rule::unique("files","name")],
            "file" => [$req,"file"],
            "id_group" => ["required","numeric",Rule::exists("groups","id")],
            "id_file" => ["required","numeric",Rule::exists("files","id")],
            "ids_user" => ["required","array"],
        ];
    }
}
