<?php
if (!isset($options)) {
    $options = get_option('liw_settings');
}
?>

<script>
    var map;
    var latJs = parseFloat('<?php if (isset($lat) && strlen(strval($lat)) > 1) {
        echo $lat;
    } ?>');
    var lngJs = parseFloat('<?php if (isset($lng) && strlen(strval($lng)) > 1) {
        echo $lng;
    }
        ?>');
    var detailPath = '<?php echo $options['detail_path'] ?>';

    function initMap() {
        let bounds = new google.maps.LatLngBounds();


        if (hitListOnly) {
            markerDataJS = markerDataHitlistJs;
             map = new google.maps.Map(document.getElementById('map'),
                {
                    zoom: 13,
                    center: {
                        lat: latJs ? latJs : markerDataJS[0].lat,
                        lng: lngJs ? lngJs : markerDataJS[0].long
                    }
                })
        } else {
            if (markerDataHitlistJs.length > 0 && markerDataAllJs.length === 0) {
                markerDataJS = markerDataHitlistJs;
            } else {
                markerDataJS = markerDataAllJs;
            }
             map = new google.maps.Map(document.getElementById('map'),
                {
                    zoom: 6,
                    center: {
                        lat: markerDataJS[0].lat,
                        lng: markerDataJS[0].long
                    }
                }
            )
        }

        if (latJs && lngJs || typeof searchLocationPosition !== 'undefined') {
            var pinSVGHole = "M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z";
            var labelOriginFilled = new google.maps.Point(12, 9);
            var pinColor = "#5384ED";
            var markerImage = {
                path: pinSVGHole,
                anchor: new google.maps.Point(12, 17),
                fillOpacity: 1,
                fillColor: pinColor,
                strokeWeight: 2,
                strokeColor: "white",
                scale: 2,
                labelOrigin: labelOriginFilled
            };
            var markerPosition = new google.maps.Marker({
                icon: markerImage,
                position: {
                    lat: latJs ? latJs : searchLocationPosition.lat,
                    lng: lngJs ? lngJs : searchLocationPosition.lng
                },
                map: map
            });
            bounds.extend({
                lat: latJs ? latJs : searchLocationPosition.lat,
                lng: lngJs ? lngJs : searchLocationPosition.lng
            });
            let positionText = '';
            if (latJs && lngJs) {
                positionText = '<h5>Deine Position</h5>';
            } else {
                positionText = '<h5>Suchort</h5>';
            }

            markerPosition.addListener('mouseover', function () {
                infowindow.setContent(positionText);
                infowindow.open(map, this, positionText);

            });
        }


        for (var i = 0; i < markerDataJS.length; i++) {
            var infowindow = new google.maps.InfoWindow;

            if (markerDataJS[i].lat && markerDataJS[i].long) {
                var marker = new google.maps.Marker({
                    position: {lat: markerDataJS[i].lat, lng: markerDataJS[i].long},
                    map: map
                });
            }
            let contentString = "<div>" +
                "<h4 class='infoWindowHeadline'>" + markerDataJS[i].name + "</h4>" +
                "<p>" + markerDataJS[i].street + "<br>" +
                markerDataJS[i].city + "</p>" +
                "<a href='/" + detailPath + "/" + markerDataJS[i].path + "'>Details</a>" +
                "</div>";
            // "<a href='/" + detailPath + "/?locid=" + markerDataJS[i].id + "'>Details</a>"
            marker.addListener('mouseover', function () {
                infowindow.setContent(contentString);
                infowindow.open(map, this, contentString);
                toggleBounce(this);
            });
            if (markerDataJS.length > 1) {
                bounds.extend(marker.getPosition());
            }

        }


        function toggleBounce(m) {
            m.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(function () {
                m.setAnimation(null);
            }, 2000);
        }


        if (markerDataJS.length > 1) {
            map.fitBounds(bounds);
        }


    }


</script>

<!--<button class="btn btn-primary" onclick="toggleMarkers()">toggle view</button> -->





