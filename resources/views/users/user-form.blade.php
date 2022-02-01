<form>
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Form {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="input-fullname" class="required">Nama Lengkap</label>
            <input
                type="text"
                id="input-fullname"
                class="form-control"
                name="full_name"
                placeholder="{{ DBText::inputPlaceholder('Nama Lengkap') }}"
                maxlength="100"
                required
            />
        </div>
        <div class="form-group">
            <label for="input-pob" class="required">Tempat Tanggal Lahir</label>
            <div class="row">
                <div class="col-sm-8">
                    <input
                        type="text"
                        id="input-pob"
                        class="form-control"
                        name="place_of_birth"
                        placeholder="{{ DBText::inputPlaceholder('Tempat Lahir') }}"
                        maxlength="100"
                    />
                </div>
                <div class="col-sm-4">
                    <label for="input-dob" class="d-none"></label>
                    <input
                        type="text"
                        id="input-dob"
                        class="form-control"
                        name="date_of_birth"
                        placeholder="{{ DBText::datePlaceholder() }}"
                        data-toggle="daterangepicker"
                        data-format="DD/MM/YYYY"
                        data-single-date="true"
                        data-auto-apply="true"
                        data-show-dropdowns="true"
                    />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="select-religion" class="required">Jenis Kelamin</label>
            <div class="d-flex">
                @foreach($genders as $gender)
                    <div class="form-check ml-1">
                        <input class="form-check-input" id="{{ $gender->getSlug() }}" type="radio" name="gender_id" value="{{ $gender->getId() }}">
                        <label class="form-check-label" for="{{ $gender->getSlug() }}">{{ $gender->getName() }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="input-address">Alamat</label>
            <textarea
                id="input-address"
                class="form-control"
                name="address"
                placeholder="{{ DBText::inputPlaceholder('Alamat') }}"
                rows="5"
            ></textarea>
        </div>
        <div class="form-group">
            <label for="input-email" class="required">Email</label>
            <div class="input-group">
                <input
                    type="text"
                    id="input-email"
                    class="form-control"
                    name="email"
                    placeholder="{{ DBText::inputPlaceholder('Email') }}"
                    maxlength="100"
                    data-toggle="validation"
                    data-url="{{ route(DBRoutes::userCheck) }}"
                    data-label="email"
                    data-field="email"
                />
                <div class="input-group-append">
                    <div class="input-group-text bg-transparent" data-action="icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                </div>
            </div>
            <small class="text-danger" data-action="message"></small>
        </div>
        <div class="form-group">
            <label for="input-phone-number" class="required">No Handphone</label>
            <input
                type="text"
                id="input-phone-number"
                class="form-control"
                name="phone_number"
                placeholder="{{ DBText::inputPlaceholder('No Handphone') }}"
                maxlength="20"
            />
        </div>
        <div class="form-group">
            <label for="select-role" class="required">Role</label>
            <select
                id="select-role"
                class="form-control"
                name="role_id"
                data-toggle="select2"
                data-url="{{ route(DBRoutes::configSelect) }}"
                data-params='{"parent_slug": ["{{ DBTypes::role }}"]}'
            ></select>
        </div>
        <div class="form-group">
            <label for="input-username" class="required">Nama Pengguna</label>
            <div class="input-group">
                <input
                    type="text"
                    id="input-username"
                    class="form-control"
                    name="user_name"
                    placeholder="{{ DBText::inputPlaceholder('Nama Pengguna') }}"
                    maxlength="100"
                    data-toggle="validation"
                    data-url="{{ route(DBRoutes::userCheck) }}"
                    data-label="nama pengguna"
                    data-field="user_name"
                    required
                />
                <div class="input-group-append">
                    <div class="input-group-text bg-transparent" data-action="icon">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
            </div>
            <small class="text-danger" data-action="message"></small>
        </div>
        <div class="form-group" data-name="password">
            <label for="input-password" class="required">Kata Sandi</label>
            <input
                type="password"
                id="input-password"
                class="form-control"
                name="password"
                placeholder="{{ DBText::inputPlaceholder('Kata Sandi') }}"
                maxlength="100"
                required
            />
        </div>
        <div class="form-group">
            <label for="select-status" class="required">Status</label>
            <select
                id="select-status"
                class="form-control"
                name="status_id"
                data-toggle="select2"
                data-url="{{ route(DBRoutes::configSelect) }}"
                data-params='{"parent_slug": ["{{ DBTypes::status }}"]}'
            ></select>
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
