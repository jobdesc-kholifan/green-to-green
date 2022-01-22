<?php

/**
 * @var \App\Helpers\Collections\Users\UserCollection $profile
 * */

?>
<form action="{{ url()->current() }}" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Form {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        @if($profile->getPassword() != null)
        <div class="form-group">
            <label for="input-old-password" class="required">Kata Sandi Lama</label>
            <div class="input-group">
                <input
                    type="password"
                    id="input-old-password"
                    class="form-control"
                    name="old_password"
                    placeholder="{{ DBText::inputPlaceholder('Kata Sandi Lama') }}"
                    maxlength="100"
                    required
                />
                <div class="input-group-append">
                    <div class="input-group-text bg-transparent" data-action="icon">
                        <i class="fa fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="form-group">
            <label for="input-new-password" class="required">Kata Sandi Baru</label>
            <div class="input-group">
                <input
                    type="password"
                    id="input-new-password"
                    class="form-control"
                    name="new_password"
                    placeholder="{{ DBText::inputPlaceholder('Kata Sandi Baru') }}"
                    maxlength="100"
                    required
                />
                <div class="input-group-append">
                    <div class="input-group-text bg-transparent" data-action="icon">
                        <i class="fa fa-lock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" id="validation-password">
            <label for="input-confirm-password" class="required">Konfirmasi Kata Sandi</label>
            <div class="input-group">
                <input
                    type="password"
                    id="input-confirm-password"
                    class="form-control"
                    name="confirm_password"
                    placeholder="{{ DBText::inputPlaceholder('Konfirmasi Kata Sandi Baru') }}"
                    maxlength="100"
                    required
                />
                <div class="input-group-append">
                    <div class="input-group-text bg-transparent" data-action="icon">
                        <i class="fa fa-lock"></i>
                    </div>
                </div>
            </div>
            <small class="text-danger" data-action="message"></small>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">
            <i class="fa fa-times-circle mr-1"></i>
            <span>Batal</span>
        </button>
        <button type="submit" class="btn btn-outline-primary btn-sm">
            <i class="fa fa-save mr-1"></i>
            <span>Simpan</span>
        </button>
    </div>
</form>
