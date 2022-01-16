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
                            <a href="#" class="card-link text-white">Cuztomize Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-3">
            <h3 class="mb-3">Daftar Request Pickup</h3>
            <div class="row">
                <div class="col-3">
                    <div class="position-relative rounded bg-lightblue shadow cursor-pointer">
                        <div class="px-3 py-2 card-text">
                            <h4 class="text-bold mb-0">Nama Pengguna</h4>
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <p class="mb-0">Note dari pelanggan</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa fa-calendar mr-2"></i>
                                <p class="mb-0">17 Januari 2022</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa fa-map-marker-alt mr-2"></i>
                                <p class="mb-0">Jl Abdul Gani GG III No 19</p>
                            </div>
                            <div class="mb-4"></div>
                        </div>
                        <div class="text-center py-2 bg-lightblue rounded-bottom rounded-top-0">
                            <span>Lihat Detail</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="position-relative rounded bg-warning shadow cursor-pointer">
                        <div class="px-3 py-2 card-text">
                            <h4 class="text-bold mb-0">Nama Pengguna</h4>
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <p class="mb-0">Note dari pelanggan</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa fa-calendar mr-2"></i>
                                <p class="mb-0">17 Januari 2022</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa fa-map-marker-alt mr-2"></i>
                                <p class="mb-0">Jl Abdul Gani GG III No 19</p>
                            </div>
                            <div class="mb-4"></div>
                        </div>
                        <div class="text-center py-2 bg-warning rounded-bottom rounded-top-0">
                            <span>Selesaikan</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="position-relative rounded bg-success shadow cursor-pointer">
                        <div class="px-3 py-2 card-text">
                            <h4 class="text-bold mb-0">Nama Pengguna</h4>
                            <div class="d-flex justify-content-start align-items-center mb-3">
                                <p class="mb-0">Note dari pelanggan</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa fa-calendar mr-2"></i>
                                <p class="mb-0">17 Januari 2022</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa fa-map-marker-alt mr-2"></i>
                                <p class="mb-0">Jl Abdul Gani GG III No 19</p>
                            </div>
                            <div class="mb-4"></div>
                        </div>
                        <div class="text-center py-2 bg-success rounded-bottom rounded-top-0">
                            <span>Telah Diambil</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
