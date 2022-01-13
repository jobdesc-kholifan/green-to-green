<?php

namespace App\Helpers\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CollectionFinder
{

    /**
     * Model dari table mstype
     *
     * @var Model
     * */
    protected $model;

    /**
     * Data array key values dari finder
     *
     * @var Collection
     * */
    protected $keys;

    /**
     * Kolom yang bisa digunakan untuk mapping
     * diambil dari fillable model
     *
     * @var Collection
     * */
    protected $fillAbleKeys = [];

    /**
     * Curren key yang digunakan untuk mapping data
     *
     * @var string
     * */
    protected $key;

    /**
     * Data collection array type
     *
     * @var Collection
     * */
    protected $items;

    public function __construct($key, $keys, $items)
    {
        $this->key = $key;
        $this->keys = collect($keys);
        $this->items = $items;

        $this->fillAbleKeys = collect($this->model->getFillable())
            ->add($this->model->getKeyName());
    }

    /**
     * Setting key apa yang akan digunakan untuk mapping data
     * Jika parameter $key kosong maka akan menggunkan default key
     *
     * @throws \Exception
     * @var string $key
     * */
    public function setKey($key = null)
    {
        if(!is_null($key)) {
            if(!in_array($this->key, $this->fillAbleKeys->toArray()))
                throw new \Exception("Tidak dapat mapping data, kolom {$this->key} tidak ditemukan di tabel {$this->model->getTable()}");

            $this->key = $key;
        }
    }

    /**
     * Ambil semua data yang telah didapatkan
     *
     * @return array
     * */
    public function all()
    {
        return $this->items->all();
    }

    /**
     * Ambil data berdasarkan current key
     * Ketika terdapat data lebih dari 1 maka yang diambil hanya data pertama
     *
     * Jika $keyValue is null maka akan diambil data pertama dari $keys
     *
     * @throws \Exception
     * @var string|null $keyValue
     * @return Collection|mixed
     * */
    public function get($keyValue = null, $callback = null)
    {
        if (!is_null($callback) && $this->items->count() == 0)
            return call_user_func_array($callback, [$keyValue]);

        else if(is_null($callback) && $this->items->count() == 0)
            throw new \Exception("Finder tidak menemukan data {$keyValue}");

        if(is_null($keyValue))
            $keyValue = $this->keys->first();

        $data = $this->items->filter(function($data) use ($keyValue) {
            /* @var \App\Helpers\Collections\Collection $data */
            return $data->get($this->key) == $keyValue;
        });

        if (!is_null($callback) && $data->count() == 0)
            return call_user_func_array($callback, [$keyValue]);

        else if(is_null($callback) && $data->count() == 0)
            throw new \Exception("Data tipe {$keyValue} tidak ditemukan");

        return $data->first();
    }

    /**
     * Ambil data berdasarkan current key
     *
     * @throws \Exception
     * @var string|null $keyValue
     * @return array
     * */
    public function getArray($keyValue = null)
    {
        if(is_null($keyValue))
            $keyValue = $this->keys->first();

        $data = $this->items->filter(function($data) use ($keyValue) {
            /* @var \App\Helpers\Collections\Collection $data */
            return $data->get($this->key) == $keyValue;
        });

        return $data->toArray();
    }
}
