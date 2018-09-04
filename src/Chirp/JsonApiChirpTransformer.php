<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\RequestValidator;
use Chirper\Json\InvalidJsonApiException;
use Chirper\Json\InvalidJsonException;

class JsonApiChirpTransformer implements JsonChirpTransformer
{
    private $validator;

    public function __construct(RequestValidator $requestValidator)
    {
        $this->validator = $requestValidator;
    }

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
        if ($this->validator->isValid($json) === false) {
            $errors = $this->validator->getErrors($json);
            throw new InvalidJsonApiException($errors);
        }

        $object     = json_decode($json);
        $data       = $object->data;
        $attributes = $data->attributes;

        $uuid   = $data->id;
        $text   = $attributes->text;
        $author = $attributes->author;
        $time   = (new \DateTime())->format('Y-m-d H:i:s');

        return new Chirp($uuid, $text, $author, $time);
    }
}