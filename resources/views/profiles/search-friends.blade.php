@extends('skins.user')

@section('content')
    <x-content-header container="container" :title='$title' :breadcrumbs="$breadcrumbs"/>

    <div class="content">
        <div class="container mb-4">
            <div class="row mb-4">
                <div class="offset-md-2 col-12 col-md-8">
                    <div class="">
                        <label for="input-search" class="d-none"></label>
                        <div class="input-group border rounded-pill bg-light border-olive">
                            <div class="input-group-prepend pl-2">
                                <span class="input-group-text bg-transparent border-0 text-olive">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            <input
                                type="text"
                                id="input-search"
                                name="address"
                                class="form-control border-0 form-control-lg pl-2 bg-transparent cursor-text"
                                placeholder="Cari teman ..."
                                autocomplete="off"
                                required
                            />
                            <input type="hidden" id="input-lat-lng" name="lat_lng">
                        </div>
                    </div>
                </div>
            </div>
            <div id="list-results"></div>
        </div>
    </div>
@endsection

@push('script-footer')
    <script src="{{ asset('dist/js/list-friends.js') }}"></script>
    <script type="text/javascript">

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

        const $inputSearch = $('#input-search');
        $inputSearch.donetyping(() => list.search($inputSearch.val()));
    </script>
@endpush
