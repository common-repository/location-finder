<?php
global $options;
global $skip;
global $locations;
global $searchLocation;
global $lat;
global $lng;


if (isset($_POST['searchInput'])) {
    $searchLocation = sanitize_text_field(ucfirst($_POST['searchInput']));
} elseif (isset($_GET['location'])) {
    $searchLocation = sanitize_text_field(ucfirst($_GET['location']));
}
if (isset($_POST['lat']) && isset($_POST['lng'])) {
    $lat = filter_var(trim(ucfirst($_POST['lat'])), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $lng = filter_var(trim(ucfirst($_POST['lng'])), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $searchLocation = null;
} elseif (isset($_GET['lat']) && isset($_GET['lng'])) {
    $lat = filter_var(trim(ucfirst($_GET['lat'])), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $lng = filter_var(trim(ucfirst($_GET['lng'])), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

if (isset($_GET["skip"])) {
    $skip = filter_var(trim(ucfirst($_GET['skip'])), FILTER_SANITIZE_STRING);
} else {
    $skip = 0;
}

//get uri
if (isset($lat) && isset($lng)) {
    $uri = $options['host'] . '/coords?latitude=' . $lat . '&longitude=' . $lng . '&radius=100000&api_key=' . $options['api_key'] . '&skip=' . $skip . '&take=' . $options['take'];;

} elseif (isset($searchLocation)) {
    $uri = $options['host'] . '?locations=' . urlencode($searchLocation) . '&radius=100000&take=' . $options['take'] . '&skip=' . $skip . '&api_key=' . $options['api_key'];
    $uriAll = $options['host'] . '/address' . '?locations=' . urlencode($searchLocation) . '&radius=100000&api_key=' . $options['api_key'];
} else {
    $uri = $options['host'] . '?api_key=' . $options['api_key'] . '&skip=' . $skip . '&take=' . $options['take'];
    $uriAll = $options['host'] . '/address?api_key=' . $options['api_key'];
}
//get data from uri
$request = wp_remote_get($uri);

$locations = json_decode(wp_remote_retrieve_body($request));

add_filter('pre_get_document_title', function () use ($searchLocation, $lat, $lng) {
    $description = get_bloginfo('description');
    $name = get_bloginfo('name');
    if (isset($searchLocation) && strlen($searchLocation) > 0) {
        $title = 'Filialen in ' . $searchLocation . ' - ' . get_bloginfo('name');
    } else if (isset($lat) && isset($lng)) {
        $title = 'Filialen in der Nähe - ' . (isset($description) && $description !== '' ? $description : $name);
    } else {
        $title = get_the_title() . ' - ' . (isset($description) && $description !== '' ? $description : $name);
    }

    return $title;
}, 16);


add_action('wp_head', 'new_canonical', 1);
function new_canonical()
{
    global $wp;
    global $skip;
    global $searchLocation;


    $current_url = home_url('/');
    $current_slug = add_query_arg(array(), $wp->request);

    if (isset($searchLocation) && $searchLocation != '') {
        $locationText = '?location=' . $searchLocation;
    } else {
        $locationText = '';
    }
    if ($skip != 0) {
        $skipText = (isset($searchLocation) ? '&' : '?') . 'skip=' . $skip;
    } else {
        $skipText = '';
    }

    add_filter( 'wpseo_canonical', '__return_false' );
    remove_action('wp_head', 'rel_canonical');
    echo '<link rel="canonical" href="' . $current_url . $current_slug . $locationText . $skipText . '" />';
}
