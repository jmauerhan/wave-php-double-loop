<?php declare(strict_types=1);

namespace Chirper\Http;

interface RequestValidator
{
    public function isValid(string $json): bool;

    public function getErrors(string $json): array;
}