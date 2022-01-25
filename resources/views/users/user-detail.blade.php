<?php

use App\Helpers\Collections\Users\UserCollection;

/**
 * @var UserCollection $user
 * */

?>
<div class="modal-header">
    <h3 class="card-title">Form {{ $title }}</h3>
    <span class="close" data-dismiss="modal">&times;</span>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-8">
            <dl class="row dt-bold">
                <dt class="col-sm-6">Nama Lengkap</dt>
                <dd class="col-sm-6">{{ $user->getFullName() }}</dd>
                <dt class="col-sm-6">Jenis Kelamin</dt>
                <dd class="col-sm-6">{{ $user->getGender()->getName() }}</dd>
                <dt class="col-sm-6">TTL</dt>
                <dd class="col-sm-6">{{ sprintf("%s, %s", $user->getPlaceOfBirth(), $user->getDateOfBirth()) }}</dd>
                <dt class="col-sm-6">Email</dt>
                <dd class="col-sm-6">{{ $user->getEmail() }}</dd>
                <dt class="col-sm-6">No. Telp</dt>
                <dd class="col-sm-6">{{ $user->getPhoneNumber() }}</dd>
                <dt class="col-sm-6">Nama Pengguna</dt>
                <dd class="col-sm-6">{{ $user->getUserName() }}</dd>
                <dt class="col-sm-6">Role</dt>
                <dd class="col-sm-6">{{ $user->getRole()->getName() }}</dd>
                <dt class="col-sm-6">Status</dt>
                <dd class="col-sm-6">{{ $user->getStatus()->getName() }}</dd>
            </dl>
        </div>
        <div class="col-4 text-center">
            <h6 class="mb-0 text-bold mb-2">Achievement</h6>
            <div class="mx-auto img-circle img-contain mb-2" style="width: 100px;height: 100px;background-image: url('{{ $user->getUserAchievement()->getAchievement()->getUrlImage() }}')"></div>
            <h5>{{ $user->getUserAchievement()->getAchievement()->getTitle() }}</h5>
        </div>
    </div>
</div>
