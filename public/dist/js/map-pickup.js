const MapsConfig = function(options) {
    this.config = options.config !== undefined ? options.config : {zoom: 18, mapTypeControl: false};
    this.selectorSearch = options.selectorSearch !== undefined ? options.selectorSearch : '#form-places';

    this.onConfirm = options.onConfirm !== undefined ? options.onConfirm : () => {};
};

const MapsPickup = function(selector, options = {}) {
    this.$ = $(selector);
    this.options = new MapsConfig(options);

    this.map = new google.maps.Map(this.$.get(0), this.options.config);
    this.geocoder = new google.maps.Geocoder();

    this.marker = null;
    this.infoWindow = null;

    this.searchForm();
};

MapsPickup.prototype.getCurrentLocation = function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                this.geocoding({latLng: pos});
            },
            () => {}
        );
    } else {
        console.log("Gagal mendapatakan lokasi");
    }
};

MapsPickup.prototype.geocoding = function(addressComponent) {
    if(this.infoWindow !== null)
        this.infoWindow.close();

    this.geocoder.geocode(addressComponent,
        (results, status) => {
            if (status === google.maps.GeocoderStatus.OK)
            {
                const location = results[0].geometry.location;
                const pos = {lat: location.lat(), lng: location.lng()};
                this.marker = new google.maps.Marker({
                    position: pos,
                    map: this.map,
                    draggable: true,
                });
                this.map.setCenter(pos);

                google.maps.event.addListener(this.marker, 'dragend', (marker) => {
                    if(this.infoWindow !== null)
                        this.infoWindow.close();

                    this.geocoding({latLng: marker.latLng});
                });

                const $info = $('<div>').append(
                    $('<p>', {class: 'text-center'})
                        .html(results[0].formatted_address)
                        .css({width: 300, fontSize: 16, lineHeight: 1.5}),
                    $('<button>', {type: 'button', class: 'btn bg-olive btn-xs btn-block'}).append(
                        $('<span>').html('Konfirmasi')
                    ).click(() => this.options.onConfirm(results[0]))
                );

                this.infoWindow = new google.maps.InfoWindow({
                    content: $info.get(0),
                });

                this.infoWindow.open({
                    anchor: this.marker,
                    map: this.map,
                    shouldFocus: false,
                });
            } else {
                console.error('Cannot determine address at this location.'+status);
            }
        }
    );
};

MapsPickup.prototype.searchForm = function() {

    const $input = $(this.options.selectorSearch);
    console.log($input);

    const searchBox = new google.maps.places.SearchBox($input.find('input').get(0));
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push($input.get(0));

    this.map.addListener("bounds_changed", () => {
        searchBox.setBounds(this.map.getBounds());
    });

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        this.marker.setMap(null);

        const bounds = new google.maps.LatLngBounds();

        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }

            this.marker = new google.maps.Marker({
                map: this.map,
                title: place.name,
                position: place.geometry.location,
                draggable: true,
            });

            this.geocoding({latLng: place.geometry.location});

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        this.map.fitBounds(bounds);
    });
};
