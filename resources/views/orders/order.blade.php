@extends('skins.template')

@section('content')
    <x-content-header :title='$title' :breadcrumbs="$breadcrumbs"/>
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title">Data {{ $title }}</h3>
                    <div class="card-actions">
                        <div type="button" class="btn btn-outline-primary btn-sm" onclick="actions.create()">
                            <i class="fa fa-plus"></i>
                            <span>Tambah</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="w-100">
                        <table class="table table-hover table-striped" id="table-data">
                            <thead>
                            <tr>
                                <th data-orderable="false" data-searchable="false">No</th>
                                <th data-data="user.full_name" data-name="user.full_name">Nama</th>
                                <th data-data="address" data-name="address">Address</th>
                                <th data-data="driver_note" data-name="driver_note">Driver Note</th>
                                <th data-data="status.config_name" data-name="status.config_name">Status</th>
                                <th data-data="action" data-searchable="false" data-orderable="false">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-footer')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqxxOFvGe8A4OM8eurAbIiwmXwN0J8ODo&libraries=places&v=weekly" async></script>
    <script src="{{ asset('dist/js/actions.js') }}"></script>
    <script src="{{ asset('dist/js/map-pickup.js') }}"></script>
    <script src="{{ asset('dist/js/order-detail.js') }}"></script>
    <script src="{{ asset('dist/js/custom-select.js') }}"></script>
    <script type="text/javascript">
        let achievementTask, map, btnPickAddress, wrapperMap, orderDetail;
        const actions = new Actions("{{ url()->current() }}");
        actions.datatable.params = {
            _token: "{{ csrf_token() }}",
        };
        actions.datatable.columnDefs = [
            {
                targets: 0,
                width: 20,
                render: (data, type, row, meta) => {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            }
        ];
        actions.detail = (id) => {
            $.createModal({
                url: '{{ url()->current() }}/detail',
                data: {id: id}
            }).open();
        };
        actions.create = () => {
            $.createModal({
                url: '{{ url()->current() }}/form',
                modalSize: 'modal-lg',
                onLoadComplete: (res, modal) => {
                    btnPickAddress = $('#btn-pick-address');
                    wrapperMap = $('#wrapper-map');

                    btnPickAddress.click(() => {
                        if(wrapperMap.hasClass('d-none')) {
                            maps = new MapsPickup('#map', {
                                onConfirm: (result) => {
                                    wrapperMap.addClass('d-none');
                                    btnPickAddress.removeClass('d-none');
                                    btnPickAddress.find('span').html(result.formatted_address);

                                    $('[name=address]').val(result.formatted_address);
                                    $('[name=lat_lng]').val(`${result.geometry.location.lat()},${result.geometry.location.lng()}`);
                                }
                            });
                            maps.getCurrentLocation();

                            wrapperMap.removeClass('d-none');
                            btnPickAddress.addClass('d-none');
                        } else {
                            wrapperMap.addClass('d-none');
                            btnPickAddress.removeClass('d-none');
                        }
                    });

                    orderDetail = new OrderDetail('#order-detail', {
                        routes: { selectCategory: "{{ route(DBRoutes::dataConfigSelect) }}"},
                        slugs: { rubbishCategory: "{{ DBTypes::rubbishCategory }}"}
                    });
                    orderDetail.add();

                    modal.form().submit({
                        beforeSubmit: () => {
                            modal.form().setDisabled(true);
                        },
                        data: function(params) {
                            params.order_detail = orderDetail.toString();
                            return params;
                        },
                        successCallback: (res) => {
                            modal.form().setDisabled(false);
                            AlertNotif.toastr.response(res);
                            if(res.result) {
                                modal.close();
                                actions.datatable.reload();
                            }
                        },
                        errorCallback: () => {
                            modal.form().setDisabled(false);
                        }
                    })
                }
            }).open();
        };
        actions.schedule = (id) => {
            const modal = $.createModal({
                url: '{{ url()->current() }}/schedule',
                onLoadComplete: (res, modal) => {
                    FormComponents.daterangepicker.init();
                    FormComponents.select2.init();

                    modal.form().submit({
                        data: {id: id},
                        successCallback: (res) => {
                            AlertNotif.toastr.response(res);

                            if(res.result) {
                                modal.close();
                                actions.datatable.reload();
                            }
                        },
                        errorCallback: () => {
                            AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                                title: DBMessage.ERROR_NETWORK_TITLE,
                            });
                        }
                    })
                }
            });
            modal.open();
        };
        actions.done = (id) => {
            $.confirmModal({
                onChange: (value, modal) => {
                    if(value) {
                        modal.disabled(true);
                        ServiceAjax.post('{{ url()->current() }}/done', {
                            data: {id: id},
                            success: (res) => {
                                modal.disabled(false);
                                AlertNotif.toastr.response(res);
                                if(res.result) {
                                    modal.close();
                                    actions.datatable.reload(false);
                                }
                            },
                            error: () => {
                                modal.disabled(false);
                                AlertNotif.adminlte.error(DBMessage.ERROR_NETWORK_MESSAGE, {
                                    titke: DBMessage.ERROR_NETWORK_TITLE,
                                })
                            }
                        });
                    } else modal.close();
                },
            }).show();
        };
        actions.build();
    </script>
@endpush
