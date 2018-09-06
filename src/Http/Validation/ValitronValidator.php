<?php declare(strict_types=1);

namespace Chirper\Http\Validation;

use Chirper\Http\Validation\Validator;
use Valitron\Validator AS Valitron;
use Ramsey\Uuid\Uuid;

class ValitronValidator implements Validator
{
    /**
     * @var Valitron
     */
    private $valitron;

    public function __construct(Valitron $valitron)
    {
        $this->valitron = $valitron;
        Valitron::addRule(
            'uuid',
            function ($field, $value) {
                return Uuid::isValid($value);
            },
            'Not a valid UUID'
        );
    }

    public function setRules(array $rules): void
    {
        $this->valitron->mapFieldsRules($rules);
    }

    public function isValid(string $json): bool
    {
        $data           = json_decode($json, true);
        $this->valitron = $this->valitron->withData($data);
        return $this->valitron->validate();
    }

    public function getErrors(string $json): array
    {
        if ($this->isValid($json) === true) {
            return [];
        }
        $validatorErrors = $this->valitron->errors();
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