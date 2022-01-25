<?php

/* @var \App\Helpers\Collections\Configs\ConfigCollection $type */

?>
<form>
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Form {{ $type->getName() }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body ">
        <div class="form-group">
            <label for="input-name" class="required">{{ $type->getName() }}</label>
            <input
                type="text"
                id="input-name"
                class="form-control"
                name="config_name"
                placeholder="{{ DBText::inputPlaceholder('Nama ' . $type->getName()) }}"
                maxlength="100"
                required
            />
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">
            <span>Batal</span>
        </button>
        <button type="submit" class="btn btn-outline-primary btn-sm">
            <span>Simpan</span>
        </button>
    </div>
</form>
