<?php

use App\Helpers\Collections\Achievements\AchievementCollection;

/**
 * @var AchievementCollection $achievement
 * */

?>
<div class="modal-header">
    <h3 class="card-title">Form {{ $title }}</h3>
    <span class="close" data-dismiss="modal">&times;</span>
</div>
<div class="modal-body">
    <dl class="row dt-bold">
        <dt class="col-sm-4">Judul</dt>
        <dd class="col-sm-8">{{ $achievement->getTitle() }}</dd>
        <dt class="col-sm-4">Deskripsi</dt>
        <dd class="col-sm-8">{{ $achievement->getDesc() }}</dd>
        <dt class="col-sm-4">Status</dt>
        <dd class="col-sm-8">{{ $achievement->getStatus()->getName() }}</dd>
        <dt class="col-sm-12">Tugas</dt>
        <dd class="col-sm-12">
            <ol class="m-0 p-0 px-3 py-2">
                @foreach($achievement->getTasks()->all() as $task)
                    <li>{{ $task->payload()->getDesc(true) }}</li>
                @endforeach
            </ol>
        </dd>
        <dt class="col-sm-12">Gambar</dt>
        @if(!is_null($achievement->getUrlImage()))
        <dd class="col-sm-12">
            <div id="input-image" data-files='{"id": 1, "mime_type": "image", "preview": "{{ $achievement->getUrlImage() }}"}'></div>
        </dd>
        @endif
    </dl>
</div>
