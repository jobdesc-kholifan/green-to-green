@extends('skins.user')

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container">
            <div class="card shadow rounded-pill mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="">
                                <label for="input-pickup" class="d-none"></label>
                                <div class="input-group border rounded-pill bg-light border-olive">
                                    <div class="input-group-prepend pl-2">
                                        <span class="input-group-text bg-transparent border-0 text-olive">
                                            <i class="fa fa-map-marker-alt"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        id="input-pickup"
                                        class="form-control border-0 form-control-lg pl-2 bg-transparent"
                                        placeholder="Pickup di"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="">
                                <label for="input-pickup" class="d-none"></label>
                                <div class="input-group border rounded-pill bg-light border-olive">
                                    <div class="input-group-prepend pl-2">
                                        <span class="input-group-text bg-transparent border-0 text-olive">
                                            <i class="fa fa-file"></i>
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        id="input-pickup"
                                        class="form-control border-0 form-control-lg pl-2 bg-transparent"
                                        placeholder="Catatan untuk driver"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <h3 class="d-flex justify-content-between align-items-center mb-4">
                    <span>Detail Pemesanan</span>
                    <button class="btn btn-success rounded-pill btn-sm">
                        <i class="fa fa-plus-circle mr-1"></i>
                        <span>Tambah</span>
                    </button>
                </h3>
                <div class="row">
                    <div class="col-3">
                        <div class="card shadow">
                            <div class="px-3 py-2 card-text">
                                <h5 class="text-bold mb-2">Sampah Plastik</h5>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                                <div class="mb-2"></div>
                            </div>
                            <div class="btn btn-block btn-success rounded-bottom rounded-top-0">
                                <span>Lihat Detail</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-success btn-lg rounded-pill w-25">
                    <span>Order</span>
                </button>
            </div>
        </div>
    </div>
@endsection
