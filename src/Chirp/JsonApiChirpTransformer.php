<?php declare(strict_types=1);

namespace Chirper\Chirp;

use Chirper\Http\Validation\Validator;
use Chirper\Json\InvalidJsonApiException;
use Chirper\Json\InvalidJsonException;

class JsonApiChirpTransformer implements JsonChirpTransformer
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var array
     */
    private $rules = [
        'data'                   => ['required'],
        'data.id'                => ['required', 'uuid'],
        'data.type'              => ['required', ['in', ['chirp']]],
        'data.attributes'        => ['required'],
        'data.attributes.text'   => ['required', ['lengthMax', 100]],
        'data.attributes.author' => ['required', ['lengthMax', 200]]
    ];

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        $this->validator->setRules($this->rules);
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

    /**
     * @param ChirpCollection $chirpCollection
     * @return string
     */
    public function collectionToJson(ChirpCollection $chirpCollection): string
    {
        $data = [];
        foreach ($chirpCollection AS $chirp) {
            $data[] = (object)[
                'type'       => 'chirp',
                'id'         => $chirp->getId(),
                'attributes' => (object)[
                    'text'       => $chirp->getText(),
                    'author'     => $chirp->getAuthor(),
                    'created_at' => $chirp->getCreatedAt()
                ],
            ];
        }
        $object = (object)[
            'data' => $data
        ];
        return json_encode($object);
    }
}