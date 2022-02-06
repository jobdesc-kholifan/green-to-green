<form action="" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <h3 class="card-title">Detail {{ $title }}</h3>
        <span class="close" data-dismiss="modal">&times;</span>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="input-address">Alamat Pickup</label>
            <div>
                <button type="button" class="btn bg-olive btn-sm" id="btn-pick-address">
                    <i class="fa fa-map-marker-alt mr-1"></i>
                    <span>Tentukan alamat</span>
                </button>
            </div>
            <div class="d-none" id="wrapper-map">
                <div id="form-places">
                    <label for="input-search"></label>
                    <input type="text" id="input-search" class="form-control shadow border-0 p-4 ml-2" placeholder="Cari berdasarkan alamat ..." style="width: 300px;" />
                </div>
                <div id="map" style="height: calc(100vh - 400px)">
                </div>
            </div>
            <input type="hidden" name="address">
            <input type="hidden" name="lat_lng">
        </div>
        <div class="form-group">
            <label for="input-note">Driver Note</label>
            <textarea
                class="form-control"
                id="input-note"
                placeholder="{{ DBText::inputPlaceholder("Driver note") }}"
                rows="5"
            ></textarea>
        </div>
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label>Detail Pesanan</label>
                <button type="button" class="btn btn-success rounded-pill btn-sm d-flex align-items-center" onclick="orderDetail.add()">
                    <i class="fa fa-plus-circle mr-md-1 py-1"></i>
                    <span class="d-none d-md-block">Tambah</span>
                </button>
            </div>
            <div id="order-detail"></div>
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
