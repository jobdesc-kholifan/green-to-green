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
        $payloads = json_decode($payload);

        $points = 0;
        $count = 0;
        if(!is_null($payloads)) {
            foreach($payloads as $d) {
                $compare = new TasksRegisterPayload(json_encode($d));
                $complete = $this->getCount() == $compare->getCount();

                $points += $complete ? 100 : 0;
                $count++;
            }
        }

        return $count > 0 ? $points/$count : 0;
    }

    public function messages($payload)
    {
        return '';
    }
}
