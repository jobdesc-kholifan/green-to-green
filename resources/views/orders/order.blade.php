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
    <script src="{{ asset('dist/js/actions.js') }}"></script>
    <script type="text/javascript">
        let achievementTask;
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
            }).open();
        };
        actions.schedule = (id) => {
            $.createModal({
                url: '{{ url()->current() }}/schedule',
                onLoadComplete: (res, modal) => {
                    FormComponents.daterangepicker.init();
                    FormComponents.select2.init();
                }
            }).open();
        };
        actions.build();
    </script>
@endpush
