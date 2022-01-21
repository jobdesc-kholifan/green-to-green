<form method="post" action="{{ url()->current() }}">
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Form {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="input-dob">Tanggal</label>
            <input
                type="text"
                id="input-dob"
                class="form-control"
                name="schedule_date"
                placeholder="{{ DBText::datePlaceholder() }}"
                data-toggle="daterangepicker"
                data-format="DD/MM/YYYY"
                data-single-date="true"
                data-auto-apply="true"
                data-show-dropdowns="true"
                autocomplete="off"
            />
        </div>
        <div class="form-group">
            <label for="select-role" class="required">Staff</label>
            <select
                id="select-role"
                class="form-control"
                name="staff_id"
                data-toggle="select2"
                data-url="{{ route(DBRoutes::userSelect) }}"
                data-params='{"role_slug": ["{{ DBTypes::roleStaff }}"]}'
            ></select>
        </div>
        <div class="form-group">
            <label for="input-description">Deskripsi</label>
            <textarea
                id="input-description"
                class="form-control"
                name="description"
                placeholder="{{ DBText::inputPlaceholder('Deskripsi') }}"
                rows="5"
            ></textarea>
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
