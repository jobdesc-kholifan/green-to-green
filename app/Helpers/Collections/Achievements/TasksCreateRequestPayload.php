<?php

namespace App\Helpers\Collections\Achievements;

class TasksCreateRequestPayload extends TaskPayloadCollection implements TaskPayloadContract
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
        $payloads = json_decode($payload);

        $points = 0;
        $count = 0;
        if(!is_null($payloads)) {
            foreach($payloads as $d) {
                $compare = new TasksCreateRequestPayload(json_encode($d));
                $complete = $this->getCount() == $compare->getCount();

                $points += $complete ? 100 : 0;
                $count++;
            }
        }

        return $count > 0 ? $points/$count : 0;
    }

    public function messages($payload)
    {
        $payloads = json_decode($payload);

        $diff = $this->getCount();
        if(!is_null($payloads)) {
            foreach($payloads as $d) {
                $compare = new TasksCollectPlasticPayload(json_encode($d));
                $diff -= $compare->getCount();
            }
        }

        return $diff > 0 && $diff != $this->getCount() ? '<div class="text-xs text-danger ml-4" style="line-height: 1.3">Buat '.$diff.' request pickup lagi untuk menyelesaikan achievement</div>' : '';
    }
}
