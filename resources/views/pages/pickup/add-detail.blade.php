<form>
    <div class="modal-header">
        <h3 class="card-title">Form {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="input-qty" class="required">Jumlah (Kg)</label>
            <input
                type="text"
                id="input-qty"
                class="form-control"
                name="qty"
                placeholder="{{ DBText::inputPlaceholder('Jumlah (Kg)') }}"
                maxlength="20"
            />
        </div>
        <div class="form-group">
            <label for="input-description">Deskripsi</label>
            <textarea
                id="input-description"
                class="form-control"
                name="description"
                rows="5"
                placeholder="{{ DBText::inputPlaceholder("Deskripsi sampah") }}"
            ></textarea>
        </div>
    </div>
</form>
