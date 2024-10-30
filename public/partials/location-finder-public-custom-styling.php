<?php
$options = get_option('liw_settings');

function color_luminance( $hex, $percent ) {

    // validate hex string

    $hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
    $new_hex = '#';

    if ( strlen( $hex ) < 6 ) {
        $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
    }

    // convert to decimal and change luminosity
    for ($i = 0; $i < 3; $i++) {
        $dec = hexdec( substr( $hex, $i*2, 2 ) );
        $dec = min( max( 0, $dec + $dec * $percent ), 255 );
        $new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
    }

    return $new_hex;
}


if (isset($options['primary_color'])): ?>
    <style>

        .liw-bootstrap .btn-danger, .liw-bootstrap .btn-danger:visited {
            background-color: <?php echo $options['primary_color']?> !important;
            border-color: <?php echo $options['primary_color']?> !important;
        }
         .liw-bootstrap .btn-danger:hover, .liw-bootstrap .btn-danger:active {
             background-color: <?php echo color_luminance($options['primary_color'], -0.2)?> !important;
             border-color: <?php echo color_luminance($options['primary_color'], -0.2)?> !important;
        }

        .liw-bootstrap .btn-danger.focus, .liw-bootstrap .btn-danger:focus {
            box-shadow: none !important;
        }
    </style>
<?php endif ?>
