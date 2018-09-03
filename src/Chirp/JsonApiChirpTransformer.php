<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Transform\InvalidJsonApiException;
use Chirper\Transform\InvalidJsonException;
use Chirper\Transform\TransformerException;

class JsonApiChirpTransformer implements JsonChirpTransformer
{

    public function toJson(Chirp $chirp): string
    {
        return '';
    }

    public function toChirp(string $json): Chirp
    {
        //Validate
        $object = json_decode($json);
        if ($object === null) {
            throw new InvalidJsonException();
        }
        if (property_exists($object, 'data') === false) {
            throw new InvalidJsonApiException('Missing data');
        }

        $data = $object->data;
        if (property_exists($data, 'type') === false) {
            throw new InvalidJsonApiException('Missing data->type');
        }
        if (property_exists($data, 'id') === false) {
            throw new InvalidJsonApiException('Missing data->id');
        }
        if (property_exists($data, 'attributes') === false) {
            throw new InvalidJsonApiException('Missing data->attributes');
        }
        $attributes = $data->attributes;
        if (property_exists($attributes, 'text') === false) {
            throw new InvalidJsonApiException('Missing data->attributes->text');
        }
        if (property_exists($attributes, 'author') === false) {
            throw new InvalidJsonApiException('Missing data->attributes->author');
        }

        $uuid   = $data->id;
        $text   = $attributes->text;
        $author = $attributes->author;
        $time   = (new \DateTime())->format('Y-m-d H:i:s');

        return new Chirp($uuid, $text, $author, $time);
    }
}