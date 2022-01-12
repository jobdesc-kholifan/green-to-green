@extends('skins.user')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Welcome to Green to Green <small></small></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item "><a href="#" class="text-success">Green to Green</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-success card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Archievement</h5>

                            <p class="card-text">Look throught all of your archievement</p>
                            <a href="#" class="card-link text-success">Show Archievement</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-success card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Set Pickup</h5>

                            <p class="card-text">Set your pickup location right here</p>
                            <a href="#" class="card-link text-success">Set Pickup Location</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-success card-outline">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Set Up Your Profile</h5>

                            <p class="card-text">Customize your own profile right here!</p>
                            <a href="#" class="card-link text-success">Cuztomize Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
