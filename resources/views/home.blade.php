<?php

/**
 * @var \App\Helpers\Collections\Configs\ConfigArray $statues
 * */

?>
@extends('skins.user')

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container mb-3">
            <div class="row">
                <div class="col-sm-6 col-lg-4">
                    <div class="card bg-olive card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Archievement</h5>

                            <p class="card-text">Look throught all of your archievement</p>
                            <a href="{{ route(DBRoutes::pageAchievement) }}" class="card-link text-white">Show Archievement</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card bg-olive card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Set Pickup</h5>

                            <p class="card-text">Set your pickup location right here</p>
                            <a href="{{ route(DBRoutes::pagePickUp) }}" class="card-link text-white">Set Pickup Location</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card bg-olive card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Set Up Your Profile</h5>

                            <p class="card-text">Customize your own profile right here!</p>
                            <a href="{{ route(DBRoutes::profile) }}" class="card-link text-white">Cuztomize Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pb-4">
            <div class="row">
                <div class="col-12 col-md-3">
                    <h5 class="mb-2">Filter</h5>
                    <div class="font-weight-normal mb-1">Status</div>
                    @foreach($statues->all() as $status)
                        <div class="custom-control custom-checkbox">
                            <input name="filter_status[]" class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="{{ $status->getSlug() }}" value="{{ $status->getId() }}">
                            <label for="{{ $status->getSlug() }}" class="custom-control-label font-weight-light">{{ $status->getName() }}</label>
                        </div>
                    @endforeach
                    <div class="font-weight-normal mt-3 mb-1">Kategori Sampah</div>
                    @foreach($categoryRubbish->all() as $category)
                        <div class="custom-control custom-checkbox">
                            <input name="filter_category[]" class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="{{ $category->getSlug() }}" value="{{ $category->getId() }}">
                            <label for="{{ $category->getSlug() }}" class="custom-control-label font-weight-light">{{ $category->getName() }}</label>
                        </div>
                    @endforeach
                    <div class="mt-3 mb-3">
                        <button type="button" class="btn bg-olive btn-block btn-sm" onclick="actions.datatable.reload()">
                            <i class="fa fa-filter mr-2"></i>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-9">
                    <h5 class="mb-4">Daftar Transaksi</h5>
                    <div class="w-100">
                        <table class="table table-striped table-hover" id="table-data">
                            <thead>
                            <tr>
                                <th data-data="created_at">Tanggal</th>
                                <th data-data="address" style="width: 400px">Alamat</th>
                                <th data-data="status.config_name">Status</th>
                                <th data-data="action" data-searchable="false" data-orderable="false" style="width: 100px">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pb-4">
            <div id="list-results" class="mb-4"></div>
            <div class="text-center w-100">
                <a href="{{ route(DBRoutes::searchFriends) }}">
                    <i class="fa fa-plus-circle mr-1"></i>
                    <span>Cari Teman lebih banyak teman</span>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('script-footer')
    <script src="{{ asset('dist/js/list-friends.js') }}"></script>
    <script src="{{ asset('dist/js/actions.js') }}"></script>
    <script type="text/javascript">
        const actions = new Actions("{{ url()->current() }}");
        actions.datatable.params = {
            _token: "{{ csrf_token() }}",
            filter_category: () => $('[name="filter_category[]"]').filter(function(i, item) { return $(item).prop('checked'); })
                .map(function(i, item) {
                    return $(item).val();
                }).get(),
            filter_status: () => $('[name="filter_status[]"]').filter(function(i, item) { return $(item).prop('checked'); })
                .map(function(i, item) {
                    return $(item).val();
                }).get(),
        }
        actions.routes.datatable = "{{ url()->current() }}/datatables-order";
        actions.detail = function(id) {
            $.createModal({
                url: '{{ url()->current() }}/detail',
                data: {id: id},
            }).open();
        };
        actions.build();

        const list = new ListFriends('#list-results', {
            token: "{{ csrf_token() }}",
            routes: {
                search: "{{ route(DBRoutes::searchFriendsList) }}",
                follow: "{{ route(DBRoutes::searchFriendsFollow) }}",
                info: "{{ route(DBRoutes::searchFriendsInfo) }}"
            },
            user_id: {{ auth()->id() }}
        });
        list.suggest(6);
    </script>
@endpush
