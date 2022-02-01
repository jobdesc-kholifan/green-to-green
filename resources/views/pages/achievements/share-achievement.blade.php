<?php

/**
 * @var \App\Helpers\Collections\Achievements\AchievementCollection $achievement
 * @var \App\Helpers\Collections\Users\UserCollection $user
 * */

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Green to Green adalah website untuk pickup sampah">
    <title>Green to Green: {{ $user->getFullName() }} sudah mendapatkan achievement {{ $achievement->getTitle() }} - Green to Green</title>
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Green to Green: {{ $user->getFullName() }} sudah mendapatkan achievement {{ $achievement->getTitle() }} - Green to Green">
    <meta name="twitter:image" content="{{ $achievement->getUrlImage() }}">
    <meta name="twitter:description" content="Green to Green adalah website untuk pickup sampah">
    <meta name="twitter:label1" content="Est. reading time">
    <meta name="twitter:data1" content="1 minute">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/custom-style.css') }}">

    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=61f88c4097a9c5001998eae7&product=inline-share-buttons' async='async'></script>
</head>
<body class="py-5 px-3" style="background-color: #f1f1f1">

<div class="container">
    <h1 class="text-center mb-5">{{ $user->getFullName() }} sudah mendapatkan achievement {{ $achievement->getTitle() }}</h1>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
            <div class="card card-outline card-olive shadow {{$achievement->getUserAchievement()->getId() == null ? 'lock' : ''}}">
                @if($achievement->getUserAchievement()->getId() == null)
                    <div class="lock-icon text-olive" style="top: 40%">
                        <i class="fa fa-lock fa-3x"></i>
                    </div>
                @endif
                <div class="card-body">
                    <div class="img-contain img-circle my-5 mx-auto" style="width: 200px;height: 200px;background-image: url('{{ $achievement->getUrlImage() }}')"></div>
                    <h5 class="card-title text-bold mb-2">{{ $achievement->getTitle() }}</h5>
                    <p class="card-text">{{ substr($achievement->getDesc(), 0, 100) }}</p>
                    <h5 class="card-title text-bold mb-2">Tugas</h5>
                    <div class="card-text mb-5">
                        <ol class="list-inline">
                            @foreach($achievement->getTasks()->all() as $task)
                                <li class="{{ $task->payload()->points($task->user_payload()) >= 100 ? 'text-success' : '' }}">
                                    <div class="d-flex justify-content-start align-items-start">
                                        <i class="fa {{ $task->payload()->points($task->user_payload()) >= 100 ? 'fa-check-circle' : 'fa-circle' }} mr-2 mt-1"></i>
                                        <span>{{ $task->payload()->getDesc(true) }}</span>
                                    </div>
                                    {!! $task->payload()->messages($task->user_payload()) !!}
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="progress progress-sm mb-2 active">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: {{ $achievement->getUserAchievement()->getPercentage() }}%">
                            <span class="sr-only">{{ $achievement->getUserAchievement()->getPercentage() }}% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sharethis-inline-share-buttons"></div>
    <div class="text-center mt-5">
        Copyright © {{ date('Y') }} All rights reserved. <a href="{{ url('/') }}">Green to Green</a>
    </div>
</div>
</body>
</html>
