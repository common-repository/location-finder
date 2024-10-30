<?php
if (!isset($_GET["locid"]) && !get_query_var('location', 1)) {
    exit("Keine korrekter Standort übergeben.");
} else {

    if (isset($_GET["locid"])) {
        $id = filter_var(trim(ucfirst($_GET['locid'])), FILTER_SANITIZE_STRING);
    }
    if (get_query_var('location', 1)) {
        $locationPath = get_query_var('location', 1);
    }

    if (isset($locationPath) && strlen($locationPath) > 1 || isset($id) && strlen($id) > 1) {

        global $options;
        $uri = $options['host'] . '/detail/' . ($locationPath ?: $id) . '?api_key=' . $options['api_key'];
        $request = wp_remote_get($uri);
        global $location;
        $location = json_decode(wp_remote_retrieve_body($request));
        global $post;
        global $rating;


        if(isset($options['show_review_widget']) && $options['show_review_widget'] && isset($location->{'id'})){
            $ratingRequest = wp_remote_get($options['host'] . '/rating/'.$location->{'id'}. '?api_key=' . $options['api_key']);
            $rating = json_decode(wp_remote_retrieve_body($ratingRequest));
        }


        add_filter('pre_get_document_title', function () use ($location) {
            $description = get_bloginfo('description');
            $name = get_bloginfo('name');

            return $location->{'name'} . ' in ' . $location->{"address"}->{'city'} . ' - ' . (isset($description) && $description !== '' ? $description : $name);
        },16);


//        function remove_meta_descriptions($html) {
//            $pattern = '/<meta name(.*)=(.*)"description"(.*)>/i';
//            $patternOgDesc = '/<meta property(.*)=(.*)"og:description"(.*)>/i';
//            $patternOgTitle = '/<meta property(.*)=(.*)"og:title"(.*)>/i';
//            $html = preg_replace($pattern, '', $html);
//            $html = preg_replace($patternOgDesc, '', $html);
//            $html = preg_replace($patternOgTitle, '', $html);
//            return $html;
//        }
//        function clean_meta_descriptions($html) {
//            ob_start('remove_meta_descriptions');
//            ob_end_flush();
//        }
//        add_action('get_header', 'clean_meta_descriptions', 1);



        add_action('wp_head', function () {

            global $location;
            $description = '';
            if (isset($location->{'description'})) {
                $description = $location->{'description'};
            } else {
                $description = 'Hier finden Sie Informationen über ' . $location->{'name'} . ' in ' . $location->{"address"}->{'city'};
            }
            if (isset($location->{"customImages"}) ) {
                ?>
                <meta property="og:image" content="<?php echo filter_var($location->{"customImages"}[0]->{"url"}, FILTER_SANITIZE_STRING); ?>"/>
                <?php
            }


            ?>
            <meta name="description" content="<?php echo  filter_var(substr($description,0,200), FILTER_SANITIZE_STRING);?>"/>
            <meta property="og:description" content="<?php echo filter_var(substr($description,0,200), FILTER_SANITIZE_STRING); ?>"/>
            <meta property="og:title" content="<?php echo $location->{'name'} . ' in ' . $location->{"address"}->{'city'} . ' - ' . get_bloginfo('name')?>"/>
            <?php
        }, 0);
    }



    add_action('wp_head', 'new_canonical', 1);
    function new_canonical()
    {
        global $wp;
        $current_url = home_url('/');
        $current_slug = add_query_arg(array(), $wp->request);
        $options = get_option('liw_settings');
        $detailPath = $options['detail_path'];
        if (strlen($detailPath) > 1 && strpos($current_slug, $detailPath) !== false) {
            remove_action('wp_head', 'rel_canonical');
            add_filter( 'wpseo_canonical', '__return_false' );
            echo '<link rel="canonical" href="' . $current_url . $current_slug . '" />';
        }
    }

}



