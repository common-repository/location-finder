<script>
    var markerDataJS = [];
    var markerDataAllJs = [];
    var markerDataHitlistJs = [];
</script>
<?php

include_once dirname(__FILE__) . '/location-finder-public-hitlist-request.php';
global $options;
global $skip;
global $locations;
global $searchLocation;

//marker for map
$markerDataAll = [];

if ($skip == 0 && isset($uriAll)) {
    $requestAll = wp_remote_get($uriAll);
    $locationsAll = json_decode(wp_remote_retrieve_body($requestAll));

    foreach ($locationsAll->{"locationAddressList"} as $location) {
        ?>
        <script>
            markerDataAllJs.push({
                lat: parseFloat(<?php echo($location->{'address'}->{'coordinate'}->{'lat'}) ?>),
                long: parseFloat(<?php echo($location->{'address'}->{'coordinate'}->{'long'}) ?>),
                name: "<?php echo($location->{'name'}) ?>",
                street: "<?php echo($location->{'address'}->{'street'} . ' ' . $location->{'address'}->{'house'}) ?>",
                city: "<?php echo($location->{'address'}->{'postCode'} . ' ' . $location->{'address'}->{'city'}) ?>",
                id: "<?php echo($location->{'id'}) ?>",
                path: "<?php echo($location->{'path'}) ?>",
            });
        </script>
        <?php
    }
}

//pagination
$totalResults = $locations->{'total'};
$pages = ceil($totalResults / $options['take']);
$currentPage = $pages - ceil((($totalResults - $skip) / $options['take'])) + 1;
$searchLocationPosition = isset($locations->{"searchedCoordinate"}) ? $locations->{"searchedCoordinate"} : null;

function returnFormattedPhone($phone)
{
    return '+' . str_ireplace('-', ' ', strval($phone));
}

?>

<?php if (isset($options['show_review_widget'])): ?>
    <script type="text/javascript" src="https://listing.lead-hub.de/static/review/js/review-widget.min.js"></script>
<?php endif ?>


<!--template-->
<div class="liw-bootstrap">
    <?php include_once("location-finder-public-custom-styling.php"); ?>

    <div class="container container-storelocator mb-5">
        <div class="row">
            <div class="col mb-3">
                <form id="searchform" method="get">
                    <div class="position-relative">
                        <div class="input-group mb-3 ">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" type="button" id="button-getlocation"
                                        onclick="getLocation()"><i class="fas fa-crosshairs"></i></button>
                            </div>
                            <input type="text" class="form-control" style="height:38px;margin-right:0"
                                   autocomplete="off"
                                   value="<?php if (isset($searchLocation) && !isset($lat) && !isset($lng)) echo $searchLocation; ?>"
                                   placeholder="PLZ oder Stadt" name="location" id="searchInput"
                                   aria-label="PLZ oder Stadt" aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-danger" type="submit" id="button-search"><i
                                            class="fas fa-search"></i> Suchen
                                </button>
                            </div>
                        </div>
                        <ul class="list-group" id="resultsList"></ul>

                    </div>
                </form>
            </div>
        </div>

        <!--    geo loc form-->
        <div class="d-none">
            <form id="searchGeolocation" method="post">
                <input id="latInput" name="lat">
                <input id="lngInput" name="lng">
            </form>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <p>Die Suche
                    <?php if (isset($lng) && isset($lat)): ?>
                    im <b>Umkreis</b>
                    <?php if (isset($locations->{'total'})): ?>
                        ergab <?php echo $locations->{'total'} ?>
                    <?php else: ?>
                        ergab keine
                    <?php endif ?> Treffer.</p>

                <?php elseif (isset($searchLocation)): ?>
                    in <?php echo $searchLocation ?>
                    <?php if (isset($locations->{'total'})): ?>
                        ergab <?php echo $locations->{'total'} ?>
                    <?php else: ?>
                        ergab keine
                    <?php endif ?> Treffer.</p>
                <?php else: ?>
                    ergab
                    <?php if (isset($locations->{'total'})): ?>
                        <?php echo $locations->{'total'} ?>
                    <?php else: ?>
                        keine
                    <?php endif ?>
                    Treffer.</p>
                <?php endif ?>

                <form id="toggleMarkerForm" hidden>
                    <label class="btn btn-sm btn-secondary mb-4" for="togglemarkerCheckbox" style="cursor: pointer">
                        <input type="checkbox" class="mr-2" id="togglemarkerCheckbox"
                               onchange="toggleMarkers()">Zeige nur Marker der Trefferliste</label>
                </form>

                <?php if (isset($searchLocationPosition)): ?>
                    <script>
                        var searchLocationPosition = {
                            lat: null,
                            lng: null
                        };
                        searchLocationPosition.lat = <?php echo $searchLocationPosition->{'lat'} ?>;
                        searchLocationPosition.lng =  <?php echo $searchLocationPosition->{'long'} ?>;
                    </script>
                <?php endif ?>

                <?php if (is_array($locations->{"locations"}) || is_object($locations->{"locations"})): ?>
                    <?php foreach ($locations->{"locations"} as $location): ?>
                        <script>
                            markerDataHitlistJs.push({
                                lat: parseFloat(<?php echo($location->{'address'}->{'coordinate'}->{'lat'}) ?>),
                                long: parseFloat(<?php echo($location->{'address'}->{'coordinate'}->{'long'}) ?>),
                                name: "<?php echo($location->{'name'}) ?>",
                                street: "<?php echo($location->{'address'}->{'street'} . ' ' . $location->{'address'}->{'house'}) ?>",
                                city: "<?php echo($location->{'address'}->{'postCode'} . ' ' . $location->{'address'}->{'city'}) ?>",
                                id: "<?php echo($location->{'id'}) ?>",
                                path: "<?php echo($location->{'path'}) ?>",
                            });
                        </script>

                        <div class="hitlist-result-box" itemscope itemtype="http://schema.org/LocalBusiness">

                            <?php if ($location->{'path'}): ?>
                                <a
                                   href="/<?php echo $options['detail_path'] ?>/<?php echo $location->{'path'} ?>">
                                    <h6 class="mb-0" itemprop="name"><?php echo $location->{'name'} ?></h6>
                                </a>
                            <?php else: ?>
                                <a
                                   href="/<?php echo $options['detail_path'] ?>/?location=<?php echo $location->{'id'} ?>">
                                    <h6 class="mb-0" itemprop="name"><?php echo $location->{'name'} ?></h6>
                                </a>
                            <?php endif ?>



                            <?php if (isset($location->{'distance'})): ?>
                                <?php if ($location->{'distance'} > 1000): ?>
                                    <small class="text-secondary"><i
                                                class="fas fa-map-marker-alt"></i> <?php echo number_format_i18n($location->{'distance'} / 1000, 1) ?>
                                        km</small>
                                <?php else: ?>
                                    <small class="text-secondary"><i
                                                class="fas fa-map-marker-alt"></i> <?php echo number_format_i18n($location->{'distance'}, 0) ?>
                                        m</small>
                                <?php endif ?>
                            <?php endif ?>

                            <div class="row">
                                <div class="col-lg-6">

                                    <p class="mb-1">
                                        <?php if ($location->{'rating'} != 0): ?>
                                            <span class="text-warning"><?php echo $location->{'rating'} ?></span>
                                            <?php for ($s = 1; $s <= $location->{'rating'}; $s++) {
                                                echo "<i class='fas fa-star text-warning'></i>";
                                            } ?>
                                            <?php for ($s = 1; $s <= 5 - $location->{'rating'}; $s++) {
                                                echo "<i class='fas fa-star text-secondary'></i>";
                                            } ?>
                                        <?php endif ?>
                                    </p>
                                    <p itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                        <span itemprop="streetAddress"><?php echo $location->{'address'}->{'street'} . ' ' . $location->{'address'}->{'house'} . '</span><br>
                                        <span itemprop="postalCode">' . $location->{'address'}->{'postCode'} . '</span>  <span itemprop="addressLocality">' . $location->{'address'}->{'city'} . '</span>' . (($location->{'phone'}) ? '<br>Tel.: <span itemprop="telephone">' . returnFormattedPhone($location->{'phone'}) . '</span></p>' : "") ?>
                                    <p>
                                        <?php if (isset($location->{'currentOpening'})): ?>
                                            <?php if ($location->{'currentOpening'}->{'openedNow'}): ?>
                                                <span class="text-success">Jetzt geöffnet:</span><br>
                                            <?php else: ?>
                                                <span class="text-danger">Momentan geschlossen.</span><br>
                                                <span>Öffnungszeiten:</span><br>
                                            <?php endif ?>
                                            <?php echo strtr($location->{'currentOpening'}->{'openingToday'}, [', ' => '<br>', 'closed' => 'Heute geschlossen']) . ((isset($opening->{'value'}) && $opening->{'value'} != 'closed') ? ' Uhr' : "") ?>

                                            <?php foreach ($location->{"openingsHours"} as $opening):
                                                $metaSchema = '';
                                                if ($opening->{'value'} != 'closed') {
                                                    $metaSchema = '<meta itemprop="openingHours" content="' . $opening->{'key'} . ' ' . $opening->{'value'} . '">';
                                                }

                                                echo $metaSchema;
                                            endforeach ?>
                                        <?php endif ?>
                                    </p>



                                    <?php if ($location->{'path'}): ?>
                                        <a class="btn btn-danger" rel="nofollow"
                                           href="/<?php echo $options['detail_path'] ?>/<?php echo $location->{'path'} ?>">Details</a>
                                    <?php else: ?>
                                        <a class="btn btn-danger" rel="nofollow"
                                           href="/<?php echo $options['detail_path'] ?>/?location=<?php echo $location->{'id'} ?>">Details</a>
                                    <?php endif ?>

                                    <a class="btn btn-danger" target="_blank"
                                       href="<?php echo 'https://www.google.com/maps/dir//' . urlencode($location->{'address'}->{'street'} . ' ' . $location->{'address'}->{'house'} . ' ' . $location->{'address'}->{'postCode'} . ' ' . $location->{'address'}->{'city'}) ?>"
                                    >Wegbeschreibung</a>
                                </div>
                                <div class="col-lg-6">
                                    <!--                        review widget-->
                                    <?php
                                    $options = get_option('liw_settings');
                                    ?>
                                    <?php if (isset($options['show_review_widget'])): ?>

                                        <div class="row">
                                            <div class="col">
                                                <div id="review-widget-container-<?php echo $location->{"id"} ?>"
                                                     style="margin: 10px 0"></div>

                                            </div>
                                        </div>

                                        <script>
                                            window.addEventListener("load", function () {
                                                let locationId =  <?php  echo $location->{"id"}?>;
                                                this['widget-' + locationId] = new ReviewWidget();
                                                this['widget-' + locationId].locationId = '<?php echo $location->{"id"}?>';
                                                this['widget-' + locationId].key = '<?php echo $options['api_key']?>';
                                                this['widget-' + locationId].widgetDiv = 'review-widget-container-<?php echo $location->{"id"}?>';
                                                this['widget-' + locationId].ratingType = '<?php echo $options['review_widget_rating_type'] ? $options['review_widget_rating_type'] : 'average-rating' ?>';
                                                this['widget-' + locationId].initWidget();
                                            });
                                        </script>
                                    <?php endif ?>
                                </div>
                            </div>
                            <hr>
                        </div>

                    <?php endforeach ?>
                <?php endif ?>

                <?php if ($pages > 1): ?>
                    <div class="hitlist-pagination mb-3">
                        <?php if ($currentPage != 1 && $pages > 0) {
                            echo "<a class='btn btn-sm btn-link-secondary' rel='prev' href='?skip=" . ((($currentPage - 1) * $options['take']) - $options['take']) . (isset($searchLocation) ? ('&location=' . $searchLocation) : '') . "'><i class='fas fa-chevron-left'></i></a>";
                        } else {
                            echo '<button class="btn btn-sm btn-link" disabled><i class="fas fa-chevron-left"></i></button>';
                        }
                        ?>

                        <?php
                        if ($currentPage >= 5) {
                            echo "<a class='btn btn-sm btn-link-secondary' href='?skip=0" . (isset($searchLocation) ? ('&location=' . $searchLocation) : '') . "'><i class='fas fa-angle-double-left'></i></a>";
                        }
                        ?>

                        <?php for ($p = 1; $p <= $pages; $p++) {
                            if ($p >= $currentPage - 3 && $p <= $currentPage + 3 || $currentPage < 4 && $p <= 7) {
                                if ($p == $currentPage) {
                                    echo "<button class='btn btn-sm btn-secondary' disabled>$p</button>";
                                } else {
                                    if($currentPage-1 == $p) {
                                        $prevNext = 'rel="prev"';
                                    } else if ($currentPage+1 == $p) {
                                        $prevNext = 'rel="next"';
                                    } else {
                                        $prevNext = '';
                                    }


                                    if (isset($lat) && isset($lng)) {
                                        echo "<a class='btn btn-sm btn-link-secondary' " . $prevNext . " href='?lat=" . $lat . "&lng=" . $lng . "&skip=" . (($p * $options['take']) - $options['take']) . "'>$p</a>";
                                    } else {
                                        echo "<a class='btn btn-sm btn-link-secondary' " . $prevNext . " href='?skip=" . (($p * $options['take']) - $options['take']) . (isset($searchLocation) ? ('&location=' . $searchLocation) : '') . "'>$p</a>";
                                    }
                                }
                            }
                        }
                        ?>

                        <?php
                        if ($currentPage != $pages && $pages > 7) {
                            echo "<a class='btn btn-sm btn-link-secondary' href='?skip=" . ($pages * $options['take'] - $options['take']) . "" . (isset($searchLocation) ? ('&location=' . $searchLocation) : '') . "'><i class='fas fa-angle-double-right'></i></a>";
                        }
                        ?>

                        <?php if ($currentPage != $pages && $pages > 0) {
                            echo "<a class='btn btn-sm btn-link-secondary' rel='next' href='?skip=" . ((($currentPage + 1) * $options['take']) - $options['take']) . (isset($searchLocation) ? ('&location=' . $searchLocation) : '') . "'><i class='fas fa-chevron-right'></i></a>";
                        } else {
                            echo '<button class="btn btn-sm btn-link" disabled><i class="fas fa-chevron-right"></i></button>';
                        }
                        ?>
                    </div>
                <?php endif ?>

            </div>

            <div class="col-lg-7 align-self-md-stretch">
                <div id="map" style="width: 100%; height:100%;min-height:300px; max-height:3000px"></div>
            </div>
        </div>
    </div>
</div>


<script>
    var hitListOnly;
    <?php if($skip == 0 && !isset($lat) && !isset($lng)): ?>
    hitListOnly = document.getElementById('togglemarkerCheckbox').checked;
    document.getElementById('toggleMarkerForm').hidden = false;
    <?php else: ?>
    hitListOnly = true;
    document.getElementById('togglemarkerCheckbox').checked = true;
    <?php endif ?>
    function toggleMarkers() {
        hitListOnly = !hitListOnly;
        initMap();
    }
</script>

<?php include("location-finder-public-gmaps.php"); ?>

<?php if ($options['use_autocomplete_search']) {
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $options['google_api_key'] ?>&callback=initMap&libraries=places"
            async defer></script>
    <?php
    include("location-finder-public-autocomplete.php");
} else {
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $options['google_api_key'] ?>&callback=initMap"
            async defer></script>
    <?php
}
