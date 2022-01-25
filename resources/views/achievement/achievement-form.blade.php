<form>
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Form {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="input-title" class="required">Judul</label>
            <input
                type="text"
                id="input-title"
                class="form-control"
                name="title"
                placeholder="{{ DBText::inputPlaceholder('Judul') }}"
                maxlength="100"
                required
            />
        </div>
        <div class="form-group">
            <label for="input-sequence" class="required">Urutan</label>
            <input
                type="text"
                id="input-sequence"
                class="form-control"
                name="sequence"
                placeholder="{{ DBText::inputPlaceholder('Urutan') }}"
                onkeydown="return Helpers.isNumberKey(event)"
                value="{{ $nextSequence }}"
                required
            />
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
        <div class="form-group">
            <label for="input-image">Gambar</label>
            <div id="input-image"></div>
        </div>
        <div id="form-tasks"></div>
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
