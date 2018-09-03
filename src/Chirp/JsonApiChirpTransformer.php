<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Transform\InvalidJsonApiException;
use Chirper\Transform\InvalidJsonException;
use Chirper\Transform\TransformerException;

class JsonApiChirpTransformer implements JsonChirpTransformer
{
    public function toJson(Chirp $chirp): string
    {
        $object = (object)[
            'data' => (object)[
                'type'       => 'chirp',
                'id'         => $chirp->getId(),
                'attributes' => (object)[
                    'text'       => $chirp->getText(),
                    'author'     => $chirp->getAuthor(),
                    'created_at' => $chirp->getCreatedAt()
                ],
            ]
        ];
        return json_encode($object);
    }

    /**
     * @param string $json
     * @return Chirp
     *
     * @throws InvalidJsonApiException
     * @throws InvalidJsonException
     */
    public function toChirp(string $json): Chirp
    {
        //Validate
        $object = json_decode($json);
        if ($object === null) {
            throw new InvalidJsonException();
        }
        $this->checkRequiredProperty($object, 'data', 'data');
        $data = $object->data;

        $keys = ['type', 'id', 'attributes'];
        foreach ($keys AS $key) {
            $this->checkRequiredProperty($data, $key, 'data->' . $key);
        }
        $attributes = $data->attributes;

        $keys = ['text', 'author'];
        foreach ($keys AS $key) {
            $this->checkRequiredProperty($attributes, $key, 'data->attributes->' . $key);
        }

        $uuid   = $data->id;
        $text   = $attributes->text;
        $author = $attributes->author;
        $time   = (new \DateTime())->format('Y-m-d H:i:s');

        return new Chirp($uuid, $text, $author, $time);
    }

    private function checkRequiredProperty($object, $property, $key)
    {
        if (property_exists($object, $property) === false) {
            throw new InvalidJsonApiException([$key => 'Missing Data']);
        }
    }
}