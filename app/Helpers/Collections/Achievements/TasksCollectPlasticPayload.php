<?php

namespace App\Helpers\Collections\Achievements;

class TasksCollectPlasticPayload extends TaskPayloadCollection
{

    protected $requirement = [
        'count' => null,
        'category_id' => null,
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

    public function getCategory()
    {
        return $this->get('category_id');
    }

    public function getCategoryId()
    {
        $category = is_null($this->getCategory()) ? (object) ['value' => null] : $this->getCategory();
        return $category->value;
    }

    public function setCategoryId($value)
    {
        $this->set('category_id', $value);
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
                'category_id' => $this->getCategory(),
            ],
        ]);
    }

    public function payload()
    {
        return json_encode([
            'elements' => [
                [
                    'tag' => '<select>',
                    'label' => 'Kategori Plastik',
                    'name' => 'category_id',
                    'attributes' => [
                        'class' => 'form-control',
                        'data-toggle' => 'select2',
                        'data-url' => route(\DBRoutes::configSelect),
                        'data-params' => json_encode(['parent_slug' => \DBTypes::rubbishCategory]),
                        'data-placeholder' => 'Kategori sampah',
                    ]
                ],
                [
                    'clipboard' => true,
                    'tag' => '<input>',
                    'label' => 'Jumlah (Kg)',
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
                'category_id' => $this->getCategory(),
                'description' => $this->getDesc(),
            ],
        ]);
    }

    public function points($payload)
    {
        $compare = new TasksCollectPlasticPayload($payload);

        $points = 0;
        if($this->getCategory() != null) {
            if($this->getCategoryId() == $compare->getCategoryId())
                $points = $compare->getCount()/$this->getCount() * 100;
        }

        else $points = $compare->getCount()/$this->getCount() * 100;

        return $points;
    }

    public function messages($payload)
    {
        $compare = new TasksCollectPlasticPayload($payload);

        $diff = $this->getCount();
        if($this->getCategory() != null) {
            if($this->getCategoryId() == $compare->getCategoryId())
                $diff = $this->getCount() - $compare->getCount();
        } else $diff = $this->getCount() - $compare->getCount();

        return $diff > 0 && $diff != $this->getCount() ? '<div class="text-xs text-danger ml-4" style="line-height: 1.3">Kumpulkan '.$diff.' kg lagi untuk menyelesaikan achievement</div>' : '';
    }
}
