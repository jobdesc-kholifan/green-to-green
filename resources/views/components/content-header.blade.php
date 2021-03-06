<section class="content-header">
    <div class="{{ empty($container) ? 'container-fluid' : $container }}">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $title }}</h1>
                <small>{{ $subTitle }}</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-olive">
                            <span class="oi oi-home"></span>
                            Green to Green
                        </a>
                    </li>
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item{{$isBreadcrumbActive($breadcrumb)}}">
                            @if(!empty($breadcrumb['link']))
                                <a href="{{$breadcrumb['link']}}">{{ $breadcrumbLabel($breadcrumb) }}</a>
                            @elseif(!empty($breadcrumb['route']))
                                <a href="{{route($breadcrumb['route'])}}">{{ $breadcrumbLabel($breadcrumb) }}</a>
                            @else
                                {{ $breadcrumbLabel($breadcrumb) }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>
