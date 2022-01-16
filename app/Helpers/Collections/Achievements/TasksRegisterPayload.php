<?php

namespace App\Helpers\Collections\Achievements;

class TasksRegisterPayload extends TaskPayloadCollection
{

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
                'description' => '',
            ]
        ]);
    }
}
