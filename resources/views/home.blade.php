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
                <div class="col-lg-4">
                    <div class="card bg-olive card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Archievement</h5>

                            <p class="card-text">Look throught all of your archievement</p>
                            <a href="{{ route(DBRoutes::pageAchievement) }}" class="card-link text-white">Show Archievement</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card bg-olive card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Set Pickup</h5>

                            <p class="card-text">Set your pickup location right here</p>
                            <a href="{{ route(DBRoutes::pagePickUp) }}" class="card-link text-white">Set Pickup Location</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
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
        <div class="container mb-3">
            <div class="row">
                <div class="col-3">
                    <h5 class="mb-2">Filter</h5>
                    <div class="font-weight-normal mb-1">Status</div>
                    @foreach($statues->all() as $status)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">
                            <label for="customCheckbox5" class="custom-control-label font-weight-light">{{ $status->getName() }}</label>
                        </div>
                    @endforeach
                    <div class="font-weight-normal mt-3 mb-1">Kategori Sampah</div>
                    @foreach($categoryRubbish->all() as $category)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="checkbox" id="customCheckbox5">
                            <label for="customCheckbox5" class="custom-control-label font-weight-light">{{ $category->getName() }}</label>
                        </div>
                    @endforeach
                    <div class="mt-3">
                        <button type="button" class="btn bg-olive btn-block btn-sm">
                            <i class="fa fa-filter mr-2"></i>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
                <div class="col-9">
                    <h5 class="mb-4">Daftar Transaksi</h5>
                    <div class="w-100">
                        <table class="table table-striped table-hover" id="table-data">
                            <thead>
                            <tr>
                                <th data-data="user.full_name">Nama</th>
                                <th data-data="created_at">Tanggal</th>
                                <th data-data="address">Alamat</th>
                                <th data-data="driver_note">Catatan</th>
                                <th data-data="action" data-searchable="false" data-orderable="false" style="width: 100px">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-footer')
    <script src="{{ asset('dist/js/actions.js') }}"></script>
    <script type="text/javascript">
        const actions = new Actions("{{ url()->current() }}");
        actions.datatable.params = {
            _token: "{{ csrf_token() }}",
        };
        actions.routes.datatable = "{{ url()->current() }}/datatables-order";
        actions.build();
    </script>
@endpush
