<?php

/**
 * @var \App\Helpers\Collections\Users\UserCollection $user
 * @var \App\Helpers\Collections\Achievements\AchievementCollection[] $achievements
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
                            <div class="text-center mb-3">
                                <div class="profile-user-img img-fluid img-circle img-contain" style="height: 100px;background-image: url('{{ $user->getUrlProfile() }}');"></div>
                            </div>
                            <h3 class="profile-username text-center block-ellipsis-2">{{ $user->getFullName() }}</h3>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <b>Achievement</b>
                                    <div class="text-olive">
                                        @if(!is_null($user->getAchievements()->first()->getAchievement()->getTitle()))
                                        <div data-toggle="tooltip" title="{{ $user->getAchievements()->first()->getAchievement()->getTitle() }}" class="img-circle img-contain" style="width: 15px; height: 15px;background-image: url('{{ $user->getAchievements()->first()->getAchievement()->getUrlImage() }}')"></div>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <b>Pengikut</b> <a href="javascript:actions.follower()" class="float-right text-olive">{{ IDR($user->getFollowers()->count(), '') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mengikuti</b> <a href="javascript:actions.following()" class="float-right text-olive">{{ IDR($user->getFollowing()->count(), '') }}</a>
                                </li>
                            </ul>

                            <a href="{{ route(DBRoutes::searchFriends) }}" class="btn bg-olive btn-block mb-2">Cari Teman</a>
                            <form id="form-update" method="post" action="{{ route(DBRoutes::profileChangeProfile) }}">
                                {{ csrf_field() }}
                                <button onclick="actions.changeImageProfile()" type="button" class="btn bg-olive btn-block">Ganti Foto</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="card shadow card-olive card-outline">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-olive">
                                <li class="nav-item"><a class="nav-link{{ $active == 'datadiri' ? ' active' : '' }}" href="#activity" data-toggle="tab">Informasi Akun</a></li>
                                <li class="nav-item"><a class="nav-link{{ $active == 'achievement' ? ' active' : '' }}" href="#achievement" data-toggle="tab">Achievements</a></li>
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
                                            <a href="javascript:actions.changePassword()" class="text-sm">Ubah password</a>
                                        </div>
                                        <div class="mb-1">
                                            <a href="javascript:actions.editProfile()" class="text-sm">Ubah informasi akun</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane{{ $active == 'achievement' ? ' active' : '' }}" id="achievement">
                                    <div class="p-3">
                                        <div class="d-flex align-items-center overflow-auto">
                                            @foreach($achievements as $i => $achievement)
                                                <div class="text-center px-5 {{$achievement->getUserAchievement()->getId() == null ? 'lock' : ''}}">
                                                    @if($achievement->getUserAchievement()->getId() == null)
                                                        <div class="lock-icon text-olive" style="top: 30%">
                                                            <i class="fa fa-lock fa-3x"></i>
                                                        </div>
                                                    @endif
                                                    <div class="border px-3 py-2 border-width-5 border-olive rounded-circle">
                                                        <div class="img-circle img-contain mb-2" style="width: 150px;height: 150px;background-image: url('{{ $achievement->getUrlImage() }}')"></div>
                                                    </div>
                                                    <h4>{{ $achievement->getTitle() }}</h4>
                                                </div>
                                                @if($i < count($achievements) - 1)
                                                    <i class="fa fa-angle-right fa-2x mb-5"></i>
                                                @endif
                                            @endforeach
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

@push('script-footer')
    <script src="{{ asset('dist/js/actions.js') }}"></script>
    <script type="text/javascript">
        const actions = new Actions("");
        actions.editProfile = function() {
            $.createModal({
                url: "{{ route(DBRoutes::profileChange) }}",
                onLoadComplete: (res, modal) => {
                    FormComponents.validation.init();
                    modal.form().submit({
                        beforeSubmit: (form) => {
                            form.setDisabled(false);

                            return FormComponents.validation.isValid();
                        },
                        successCallback: (res, form) => {
                            form.setDisabled(false);
                            AlertNotif.toastr.response(res);

                            if(res.result) {
                                modal.close();
                                setTimeout(() => window.location.reload(), 500);
                            }
                        },
                        errorCallback: (xhr, form) => {
                            form.setDisabled(false);
                            AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                                title: DBMessage.ERROR_NETWORK_TITLE
                            });
                        }
                    })
                }
            }).open();
        };
        actions.changePassword = function() {
            $.createModal({
                url: "{{ route(DBRoutes::profileChangePassword) }}",
                onLoadComplete: (res, modal) => {
                    const $validationPassword = $(modal.find('#validation-password'));
                    const $newPassword = $(modal.find('#input-new-password'));
                    const $confirmPassword = $($validationPassword.find('#input-confirm-password'));

                    $confirmPassword.donetyping(() => {
                        const $icon = $validationPassword.find('[data-action=icon]');
                        const $messages = $validationPassword.find('[data-action=message]');
                        $messages.empty();

                        if($newPassword.val().trim() !== $confirmPassword.val().trim()) {
                            $icon.html($('<i>', {class: 'fa fa-times text-danger'}));
                            $messages.html("Konfirmasi kata sandi tidak sama");
                        }

                        else $icon.html($('<i>', {class:' fa fa-check-circle text-success'}));

                    });

                    modal.form().submit({
                        beforeSubmit: (form) => {
                            form.setDisabled(true);

                            return $newPassword.val().trim() === $confirmPassword.val().trim();
                        },
                        successCallback: (res, form) => {
                            form.setDisabled(false);
                            AlertNotif.toastr.response(res);

                            if(res.result)
                                modal.close();
                        },
                        errorCallback: (xhr, form) => {
                            form.setDisabled(false);

                            AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                                title: DBMessage.ERROR_NETWORK_TITLE
                            });
                        },
                    });
                }
            }).open();
        };
        actions.following = () => {
            $.createModal({
                url: "{{ route(DBRoutes::profileFollowing) }}",
            }).open();
        };
        actions.follower = () => {
            $.createModal({
                url: "{{ route(DBRoutes::profileFollower) }}",
            }).open();
        };
        actions.changeImageProfile = () => {
            const $formUpload = $('#form-update');
            const $input = $('<input>', {class: 'd-none', name: 'profile',type: 'file', 'accept': 'image/*'});
            $formUpload.append($input);
            $input.click();
            $input.change(() => $formUpload.submit());

            $formUpload.formSubmit({
                successCallback: (res) => {
                    AlertNotif.toastr.response(res);

                    if(res.result)
                        setTimeout(() => window.location.reload(), 500);
                }
            })
        };
        actions.build();

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
