<?php

/**
 * @var \App\Helpers\Collections\Users\UserCollection $user
 * */

?>
@extends('skins.user')

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="card card-olive card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center"></div>
                            <h3 class="profile-username text-center">{{ $user->getFullName() }}</h3>
                            <p class="text-muted text-center">{{ $user->getRole()->getName() }}</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Followers</b> <a class="float-right text-olive">1,322</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Following</b> <a class="float-right text-olive">543</a>
                                </li>
                            </ul>

                            <a href="#" class="btn bg-olive btn-block"><b>Follow</b></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="card shadow card-olive card-outline">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-olive">
                                <li class="nav-item"><a class="nav-link{{ $active == 'datadiri' ? ' active' : '' }}" href="#activity" data-toggle="tab">Informasi Akun</a></li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content">
                                <div class="tab-pane{{ $active == 'datadiri' ? ' active' : '' }}" id="activity">
                                    <div class="list-information p-3">
                                        <dl class="row dt-bold mb-0">
                                            <dt class="col-sm-3">Nama Lengkap</dt>
                                            <dd class="col-sm-9">{{ $user->getFullName() }}</dd>
                                            <dt class="col-sm-3">Jenis Kelamin</dt>
                                            <dd class="col-sm-9">{{ $user->getGender()->getName() }}</dd>
                                            <dt class="col-sm-3">TTL</dt>
                                            <dd class="col-sm-9">{{ $user->getPlaceOfBirth() }}, {{ $user->getDateOfBirth() }}</dd>
                                            <dt class="col-sm-3">Alamat</dt>
                                            <dd class="col-sm-9">{{ $user->getAddress() }}</dd>
                                            <dt class="col-sm-3">No. Telp</dt>
                                            <dd class="col-sm-9">{{ $user->getPhoneNumber() }}</dd>
                                            <dt class="col-sm-3">Email</dt>
                                            <dd class="col-sm-9">{{ $user->getEmail() }}</dd>
                                            <dt class="col-sm-3">Nama Pengguna</dt>
                                            <dd class="col-sm-9">{{ $user->getUserName() }}</dd>
                                        </dl>
                                    </div>
                                    <hr class="m-0">
                                    <div class="px-3 py-2">
                                        <div class="mb-0">
                                            <a href="#" class="text-sm">Ubah password</a>
                                        </div>
                                        <div class="mb-1">
                                            <a href="#" class="text-sm">Ubah informasi akun</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
