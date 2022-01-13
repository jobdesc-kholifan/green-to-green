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
                                <th data-data="title" data-name="title">Nama</th>
                                <th data-data="status.config_name" data-name="status.config_name" style="width: 200px">Status</th>
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
    <script src="{{ asset('dist/js/achievement-task.js') }}"></script>
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
        actions.callback.form.appendData = function(data) {
            data.tasks = achievementTask.toString();
            return data;
        };
        actions.callback.onCreate = function() {
            achievementTask.add();
        };
        actions.callback.onEdit = function(data) {
            achievementTask.set(data.tasks);
        };
        actions.callback.modal.onLoadComplete = function(res) {
            achievementTask = new AchievementTask('#form-tasks', {
                routes: {
                    task: "{{ route(DBRoutes::config) }}",
                    selectTask: "{{ route(DBRoutes::configSelect) }}"
                },
                types: {
                    task: "{{ DBTypes::tasks }}"
                }
            });
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
