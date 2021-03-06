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
                                <th data-name="no" data-orderable="false" data-searchable="false" style="width: 50px">No</th>
                                <th data-data="title" data-name="title">Nama</th>
                                <th data-data="sequence" data-name="sequence" class="text-center" style="width: 100px">Urutan</th>
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
    <script src="{{ asset('dist/js/upload.js') }}"></script>
    <script type="text/javascript">
        let achievementTask, fileAchievement;
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
            },
            {
                targets: 2,
                render: (data) => {
                    return `<div class="text-center">${data}</div>`;
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
            if(data.preview !== null)
                fileAchievement.set({
                    id: 1,
                    mime_type: 'image',
                    preview: data.preview
                });
        };
        actions.callback.modal.onLoadComplete = function(res) {
            fileAchievement = $('#input-image').upload({
                name: 'image',
                allowed: ['image/*'],
                getMimeType: (file) => file.mime_type,
                getPreview: (file) => file.preview
            });

            achievementTask = new AchievementTask('#form-tasks', {
                routes: {
                    task: "{{ route(DBRoutes::configInfo) }}",
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
                onLoadComplete: () => {
                    fileAchievement = $('#input-image').upload({
                        readOnly: true,
                        name: 'image',
                        allowed: ['image/*'],
                        getMimeType: (file) => file.mime_type,
                        getPreview: (file) => file.preview
                    });
                }
            }).open();
        };
        actions.build();
    </script>
@endpush
