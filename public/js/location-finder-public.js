(function ($) {
    'use strict';

    $(function () {
        showAllImages();
    });
})(jQuery);


var inputDisabled = false;
var coordsSet = false;
let positionData;
var galleryExpanded = false;

function getLocation() {
    if (!positionData) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (success) {
                    positionData = {
                        'lat': success.coords.latitude,
                        'lng': success.coords.longitude
                    };

                    document.getElementById('latInput').value = positionData.lat;
                    document.getElementById('lngInput').value = positionData.lng;

                    // remove query params
                    window.history.pushState({}, document.title, window.location.pathname);

                    document.getElementById('searchGeolocation').submit();
                });
        } else {
            inputLocation.attr('title', "Standortdaten werden von diesem Browser nicht unterstützt.");
        }
    } else {
        // showPosition();
    }
}

function showAllImages() {
    let hiddenImages = document.getElementsByClassName('hiddenImg');
    for (let i = 0; i < hiddenImages.length; i++) {
        // hiddenImages[i].classList.remove('hiddenImage');
        // more statements
        if (hiddenImages[i].style.display === '') {
            hiddenImages[i].style.display = 'none';
            if (i === 0) {
                galleryExpanded = false;
            }
        } else {
            hiddenImages[i].style.display = '';
            if (i === 0) {
                galleryExpanded = true;
            }
        }
    }

    if (document.getElementById('galleryToggleButton')) {
        document.getElementById('galleryToggleButton').innerHTML = galleryExpanded ? 'Zeige weniger Bilder' : 'Zeige alle Bilder';
    }


}

function showFallBack(imageElement) {
    imageElement.parentNode.classList.add('d-none');

    const  placeholder = document.createElement('span')
    placeholder.style = "padding:5px;white-space: nowrap";
    placeholder.innerHTML = imageElement.getAttribute('alt');

    imageElement.parentNode.parentNode.insertBefore(placeholder, imageElement.parentNode.nextSibling);

}
