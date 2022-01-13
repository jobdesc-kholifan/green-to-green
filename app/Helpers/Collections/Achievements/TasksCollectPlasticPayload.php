<?php

namespace App\Helpers\Collections\Achievements;

class TasksCollectPlasticPayload extends TaskPayloadCollection
{

    public function getCount()
    {
        return $this->get('count');
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
                        'placeholder' => 'Masukan jumlah target plastik yang diharuskan',
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
}
