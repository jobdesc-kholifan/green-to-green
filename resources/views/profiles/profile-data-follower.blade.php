<?php

/**
 * @var \App\Helpers\Collections\Users\UserFollowArray $follower
 * */

?>
<div class="modal-header">
    <h3 class="card-title">Data {{ $title }}</h3>
    <span class="close" data-dismiss="modal">&times;</span>
</div>
<div class="modal-body">
    <div class="row">
        @foreach($follower->all() as $value)
            <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="mr-2" style="width: 60px;">
                    <div class="bg-light rounded-circle img-contain" style="width: 60px;height: 60px;background-image: url('{{ $value->getUserFollow()->getUrlProfile() }}')"></div>
                </div>
                <div style="width: calc(100% - 60px)">
                    <div class="text-bold text-overflow-ellipsis">{{ $value->getUserFollow()->getFullName() }}</div>
                    <div class="text-sm text-overflow-ellipsis">{{ $value->getUserFollow()->getUserName() }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
