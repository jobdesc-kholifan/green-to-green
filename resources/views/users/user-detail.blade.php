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
    <dl class="row dt-bold">
        <dt class="col-sm-4">Nama Lengkap</dt>
        <dd class="col-sm-8">{{ $user->getFullName() }}</dd>
        <dt class="col-sm-4">Jenis Kelamin</dt>
        <dd class="col-sm-8">{{ $user->getGender()->getName() }}</dd>
        <dt class="col-sm-4">TTL</dt>
        <dd class="col-sm-8">{{ sprintf("%s, %s", $user->getPlaceOfBirth(), $user->getDateOfBirth()) }}</dd>
        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">{{ $user->getEmail() }}</dd>
        <dt class="col-sm-4">No. Telp</dt>
        <dd class="col-sm-8">{{ $user->getPhoneNumber() }}</dd>
        <dt class="col-sm-4">Nama Pengguna</dt>
        <dd class="col-sm-8">{{ $user->getUserName() }}</dd>
        <dt class="col-sm-4">Role</dt>
        <dd class="col-sm-8">{{ $user->getRole()->getName() }}</dd>
        <dt class="col-sm-4">Status</dt>
        <dd class="col-sm-8">{{ $user->getStatus()->getName() }}</dd>
    </dl>
</div>
