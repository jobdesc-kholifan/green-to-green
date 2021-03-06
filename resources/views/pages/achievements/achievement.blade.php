<?php

/**
 * @var \App\Helpers\Collections\Achievements\AchievementCollection[] $achievements
 * */

?>
@extends('skins.user')

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container">
            <div class="row">
                @foreach($achievements as $achievement)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                    <div class="card card-outline card-olive shadow {{$achievement->getUserAchievement()->getId() == null ? 'lock' : ''}}">
                        @if($achievement->getUserAchievement()->getId() == null)
                            <div class="lock-icon text-olive" style="top: 40%">
                                <i class="fa fa-lock fa-3x"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="img-contain img-circle mb-2 mx-auto" style="width: 150px;height: 150px;background-image: url('{{ $achievement->getUrlImage() }}')"></div>
                            <h5 class="card-title text-bold mb-2">{{ $achievement->getTitle() }}</h5>
                            <p class="card-text">{{ substr($achievement->getDesc(), 0, 100) }}</p>
                            <h5 class="card-title text-bold mb-2">Tugas</h5>
                            <div class="card-text mb-3">
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
                        <div class="card-footer">
                            <a href="{{ route(DBRoutes::pageAchievementShare, [auth()->id()]) }}?id={{ $achievement->getId() }}" class="btn btn-block bg-olive btn-sm">
                                <i class="fa fa-share-alt mr-2"></i>
                                <span>Share</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
