<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\RequestValidator;
use Ramsey\Uuid\Uuid;
use Valitron\Validator;

class CreateRequestValidator implements RequestValidator
{
    public function isValid(string $json): bool
    {
        $validator = $this->setUp($json);
        return $validator->validate();
    }

    private function setUp(string $json): Validator
    {
        $data      = json_decode($json, true);
        $validator = new Validator($data);
        Validator::addRule(
            'uuid',
            function ($field, $value) {
                return Uuid::isValid($value);
            },
            'Not a valid UUID'
        );
        $rules = [
            'data'                   => ['required'],
            'data.id'                => ['required', 'uuid'],
            'data.type'              => ['required', ['in', ['chirp']]],
            'data.attributes'        => ['required'],
            'data.attributes.text'   => ['required', ['lengthMax', 100]],
            'data.attributes.author' => ['required', ['lengthMax', 200]]
        ];
        $validator->mapFieldsRules($rules);
        return $validator;
    }

    /**
     * @param string $json
     * @return array
     *
     * Returns an array of the structure:
     * [
     *  'property'=>'data.attributes',
     *  'error'=>'data.attributes is required'
     * ]
     */
    public function getErrors(string $json): array
    {
        $validator = $this->setUp($json);
        $validator->validate();
        $validatorErrors = $validator->errors();
        $flattened       = [];
        foreach ($validatorErrors AS $key => $errors) {
            if (is_array($errors) === false) {
                $errors = [$errors];
            }
            foreach ($errors AS $error) {
                $flattened[] = ['property' => $key, 'error' => $error];
            }
        }
        return $flattened;
    }
}