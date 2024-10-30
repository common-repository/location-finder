<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       Location-finder
 * @since      1.0.0
 *
 * @package    Location_Finder
 * @subpackage Location_Finder/public
 */
?>
<?php
global $location;
global $options;


function returnFormattedPhone($phone)
{
    return '+' . str_ireplace('-', ' ', strval($phone));
}

//mapping
global $openingDays;
$openingDays = [
    'Mo' => 'Mo',
    'Tu' => 'Di',
    'We' => 'Mi',
    'Thu' => 'Do',
    'Fr' => 'Fr',
    'Sa' => 'Sa',
    'Su' => 'So',
];
?>


<?php if (isset($location)): ?>
    <script>
        var markerDataHitlistJs = [];
        markerDataHitlistJs.push({
            lat: parseFloat(<?php echo($location->{'address'}->{'coordinate'}->{'lat'}) ?>),
            long: parseFloat(<?php echo($location->{'address'}->{'coordinate'}->{'long'}) ?>),
            name: "<?php echo($location->{'name'}) ?>",
            street: "<?php echo($location->{'address'}->{'street'} . ' ' . $location->{'address'}->{'house'}) ?>",
            city: "<?php echo($location->{'address'}->{'postCode'} . ' ' . $location->{'address'}->{'city'}) ?>",
            id: "<?php echo($location->{'id'}) ?>",
            path: "<?php echo($location->{'path'}) ?>",
        });

        var hitListOnly = true;
    </script>

    <?php if (isset($options['show_review_widget'])): ?>
        <script type="text/javascript" src="https://listing.lead-hub.de/static/review/js/review-widget.min.js"></script>
    <?php endif ?>
    <?php include_once("location-finder-public-custom-styling.php"); ?>
    <?php include("location-finder-public-detail-de.php"); ?>


    <?php include("location-finder-public-gmaps.php"); ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $options['google_api_key'] ?>&callback=initMap"
            async defer></script>
<?php else: ?>
    <?php echo('<h6>Kein korrekter Pfad eines Standortes übergeben.</h6>') ?>
<?php endif ?>
