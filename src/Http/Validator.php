<?php declare(strict_types=1);

namespace Chirper\Http;

interface Validator
{
    public function setRules(array $rules): void;

    public function isValid(string $json): bool;

    public function getErrors(string $json): array;
}