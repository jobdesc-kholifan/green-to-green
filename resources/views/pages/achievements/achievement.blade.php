@extends('skins.user')

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <div class="card card-outline card-olive shadow">
                        <div class="card-body">
                            <h5 class="card-title text-bold mb-2">Bronze achievement</h5>
                            <p class="card-text">Look throught all of your archievement</p>
                            <h5 class="card-title text-bold mb-2">Tugas</h5>
                            <div class="card-text mb-3">
                                <ol class="list-inline">
                                    <li class="text-success">
                                        <i class="fa fa-check-circle mr-2"></i>
                                        <span>Tugas 1</span>
                                    </li>
                                    <li class="text-secondary">
                                        <i class="fa fa-circle mr-2"></i>
                                        <span>Tugas 2</span>
                                    </li>
                                    <li class="text-secondary">
                                        <i class="fa fa-circle mr-2"></i>
                                        <span>Tugas 3</span>
                                    </li>
                                </ol>
                            </div>
                            <div class="progress progress-sm mb-2 active">
                                <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                    <span class="sr-only">20% Complete</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-block btn-success btn-sm">
                                <span>Buat Request Pick Up</span>
                            </button>
                            <button class="btn btn-block btn-primary btn-sm">
                                <i class="fa fa-share-alt mr-2"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
