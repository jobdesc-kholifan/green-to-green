<?php

namespace App\Helpers\Collections\Achievements;

class TasksCreateRequestPayload extends TaskPayloadCollection
{

    protected $requirement = [
        'count' => null,
        'description' => null,
    ];

    public function getCount()
    {
        return $this->get('count');
    }

    public function setCount($value)
    {
        $this->set('count', $value);

        return $this;
    }

    public function getDesc($formatted = false)
    {
        $description = $this->get('description');

        if(!$formatted)
            return $description;

        $pattern = [
            '{count}' => $this->getCount(),
        ];

        foreach ($pattern as $key => $value) {
            $description = str_replace($key, $value, $description);
        }

        return $description;
    }

    public function createPayload()
    {
        return json_encode([
            'requirement' => [
                'count' => $this->getCount(),
            ],
        ]);
    }

    public function payload()
    {
        return json_encode([
            'elements' => [
                [
                    'clipboard' => true,
                    'tag' => '<input>',
                    'label' => 'Jumlah',
                    'name' => 'count',
                    'attributes' => [
                        'class' => 'form-control',
                        'type' => 'number',
                        'placeholder' => 'Masukan jumlah request pickup yang diharuskan',
                        'required' => true
                    ]
                ],
                [
                    'tag' => '<textarea>',
                    'label' => 'Deskripsi',
                    'name' => 'description',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Ketikan deskripsi tugas disini',
                        'rows' => 3,
                        'required' => true
                    ]
                ]
            ],
            'requirement' => [
                'count' => $this->getCount(),
                'description' => $this->getDesc(),
            ],
        ]);
    }

    public function points($payload)
    {
        $compare = new TasksCreateRequestPayload($payload);
        $complete = $this->getCount() == $compare->getCount();

        return $complete ? 100 : 0;
    }

    public function messages($payload)
    {
        return '';
    }
}
