@extends('skins.user')

@push('script-header')
    <style>
        .modal-maps {
            max-width: calc(100vw - 100px);
        }
    </style>
@endpush

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container">
            <form id="form-pickup" action="" method="post">
                {{ csrf_field() }}
                <div class="card shadow rounded-50 mb-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
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
                                            name="address"
                                            class="form-control border-0 form-control-lg pl-2 bg-transparent cursor-text"
                                            placeholder="Pickup di"
                                            autocomplete="off"
                                            required
                                        />
                                        <input type="hidden" id="input-lat-lng" name="lat_lng">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb2">
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
                                            name="driver_note"
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
                        <button type="button" class="btn btn-success rounded-pill btn-sm d-flex align-items-center" onclick="orderDetail.add()">
                            <i class="fa fa-plus-circle mr-md-1 py-1"></i>
                            <span class="d-none d-md-block">Tambah</span>
                        </button>
                    </h3>
                    <div id="order-detail"></div>
                </div>
                <div class="text-right pb-3">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill w-sm-50 w-md-25">
                        <span>Order</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script-footer')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqxxOFvGe8A4OM8eurAbIiwmXwN0J8ODo&libraries=places&v=weekly" async></script>
    <script src="{{ asset('dist/js/actions.js') }}"></script>
    <script src="{{ asset('dist/js/map-pickup.js') }}"></script>
    <script src="{{ asset('dist/js/order-detail.js') }}"></script>
    <script type="text/javascript">
        let maps, modal;

        const $inputPickUp = $('#input-pickup'), $inputLatLng = $('#input-lat-lng');
        $inputPickUp.click(() => {
            modal = $.createModal({
                url: '{{ url()->current() }}/maps',
                dialogClass: 'modal-dialog-centered modal-maps',
                modalSize: 'modal-lg',
                onLoadComplete: (res, modal) => {
                    maps = new MapsPickup('#map', {
                        onConfirm: (result) => {
                            modal.close();
                            $inputPickUp.val(result.formatted_address);
                            $inputLatLng.val(`${result.geometry.location.lat()},${result.geometry.location.lng()}`);
                        }
                    });

                    maps.getCurrentLocation();
                }
            });
            modal.open();
        });

        const orderDetail = new OrderDetail('#order-detail', {
            routes: { selectCategory: "{{ route(DBRoutes::configSelect) }}"},
            slugs: { rubbishCategory: "{{ DBTypes::rubbishCategory }}"}
        });
        orderDetail.add();

        const $formPickup = $('#form-pickup').formSubmit({
            beforeSubmit: () => {
                $formPickup.setDisabled(true);
            },
            data: function(params) {
                params.order_detail = orderDetail.toString();
                return params;
            },
            successCallback: (res) => {
                $formPickup.setDisabled(false);
                AlertNotif.toastr.response(res);
                if(res.result) {
                    setTimeout(() => window.location.href = "{{ url('/') }}", 500);
                }
            },
            errorCallback: () => {
                $formPickup.setDisabled(false);
            }
        })
    </script>
@endpush
