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
                        <table class="table table-striped table-hover" id="table-data">
                            <thead>
                            <tr>
                                <th data-name="no" data-orderable="false" data-searchable="false" style="width: 50px"></th>
                                <th data-data="full_name" data-name="full_name">Nama Lengkap</th>
                                <th data-data="gender.config_name" data-name="gender.config_name">Jenkel</th>
                                <th data-data="ttl" data-name="pob">TTL</th>
                                <th data-data="email" data-name="email">Email</th>
                                <th data-data="user_name" data-name="user_name">Nama Pengguna</th>
                                <th data-data="status.config_name" data-name="status.config_name">Status</th>
                                <th data-data="action" data-orderable="false" data-searchable="false" style="width: 200px">Aksi</th>
                            </tr>
                            </thead>
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
        actions.callback.form.beforeSubmit = function() {
            return FormComponents.validation.isValid();
        }
        actions.callback.onCreate = function() {
            FormComponents.validation.init();
        };
        actions.callback.form.onSetData = function(value, key, row, form) {
            const $el = form.find(`[name="${key}"]`);
            if(value != null && ['role_id', 'status_id'].includes(key)) {
                const data = row[key.replace('_id', '')];
                $el.append($('<option>', {value: data.id}).text(data.config_name));
            }

            else if(key === 'gender_id') {
                form.find(`[name=${key}]`).each((i, option) => {
                    const $option = $(option);
                    if(value !== null && $option.attr('value') === value.toString()) {
                        $option.prop('checked', true);
                    }
                });
            }

            else if(['email', 'user_name'].includes(key)) {
                $el.attr('data-id', row.id);
                FormComponents.validation.init($el);
            }
        };
        actions.detail = function(id) {
            $.createModal({
                url: '{{ url()->current() }}/detail',
                data: {id: id},
            }).open();
        };
        actions.build();
    </script>
@endpush
