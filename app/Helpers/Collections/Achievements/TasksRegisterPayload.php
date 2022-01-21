<?php

namespace App\Helpers\Collections\Achievements;

class TasksRegisterPayload extends TaskPayloadCollection implements TaskPayloadContract
{

    protected $requirement = [
        'count' => null,
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

    public function getDesc()
    {
        return $this->get('description');
    }

    public function payload()
    {
        return json_encode([
            'elements' => [
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
                'count' => 1,
                'description' => '',
            ]
        ]);
    }

    public function createPayload()
    {
        return json_encode([
            'requirement' => [
                'count' => $this->getCount(),
            ]
        ]);
    }

    public function points($payload)
    {
        $compare = new TasksCollectPlasticPayload($payload);
        return $this->getCount() == $compare->getCount() ? 100 : 0;
    }

    public function messages($payload)
    {
        return '';
    }
}
