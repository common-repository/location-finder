<div class="liw-bootstrap" itemscope itemtype="http://schema.org/LocalBusiness">
    <div class="container container-storelocator mb-5">
        <div class="row">
            <div class="col d-flex mt-5 mb-5">
                <?php if (isset($location->{"businessLogo"})): ?>
                    <img  itemprop="logo" alt="Logo <?php echo $location->{"name"} ?>" src="<?php echo $location->{"businessLogo"}->{'url'} ?>" class="align-self-center mr-2"
                         style="max-width: 150px;height:auto;"/>
                <?php endif ?>

                <div class="mb-0  align-self-center">
                    <h1 class="
                <?php if ($location->{'rating'} != 0): ?>
                    mb-0
                    <?php else: ?>
                    mb-5
                <?php endif ?>
                    mt-5" itemprop="name">
                        <?php echo $location->{"name"} ?>
                    </h1>

                    <?php if ($location->{'rating'} != 0): ?>
                        <p class="text-warning mb-5">
                            <span class="text-warning"><?php echo $location->{'rating'} ?></span>
                            <?php for ($s = 1; $s <= $location->{'rating'}; $s++) {
                                echo "<i class='fas fa-star text-warning'></i>";
                            } ?>
                            <?php for ($s = 1; $s <= 5 - $location->{'rating'}; $s++) {
                                echo "<i class='fas fa-star text-secondary'></i>";
                            } ?>
                        </p>
                    <?php endif ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-5 align-self-md-stretch">
                <div id="map" style="width:100%;height:100%;min-height:300px"></div>
            </div>
            <div class="col-lg-7">

                <div class="row">
                    <div class="col-lg-6">
                        <h6>Kontakt</h6>

                        <?php
                        global $rating;
                        if (isset($rating) && isset($rating->{'averageRating'}) && isset($rating->{'reviewCount'})) {
                            echo '<span itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">' .
                                '<meta itemprop="ratingValue" content="' . number_format($rating->{'averageRating'},1) . '">' .
                                '<meta itemprop="reviewCount" content="' . $rating->{'reviewCount'} . '"></span>';
                        }

                        ?>

                        <ul class="fa-ul" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <li><span class="fa-li"><i class="fas fa-map-marker-alt"></i></span>
                                <span itemprop="streetAddress"><?php echo $location->{"address"}->{'street'} . ' ' . $location->{"address"}->{'house'} ?></span>
                                <br>
                                <span itemprop="postalCode"><?php echo $location->{"address"}->{'postCode'} . '</span> <span itemprop="addressLocality">' . $location->{"address"}->{'city'} ?></span>
                            </li>
                            <?php if (isset($location->{'phone'})): ?>
                                <li><span class="fa-li"><i
                                                class="fas fa-phone"></i></span><span
                                            itemprop="telephone"><?php echo returnFormattedPhone($location->{'phone'}) ?></span>
                                </li>
                            <?php endif ?>
                            <?php if (isset($location->{'eMail'})): ?>
                                <li><span class="fa-li"><i class="fas fa-envelope"></i></span><a
                                            href="mailto:<?php echo $location->{"eMail"} ?>"><span
                                                itemprop="email"><?php echo $location->{"eMail"} ?></span> </a>
                                </li>
                            <?php endif ?>

                            <?php if (isset($location->{'website'})): ?>
                                <li><span class="fa-li"><i class="fas fa-globe"></i></span>
                                    <a target="_blank" href="<?php echo $location->{"website"}->{'url'} ?>">
                                        <?php if (isset($location->{"website"}->{'displayUrl'})): ?>
                                            <?php echo $location->{"website"}->{'displayUrl'} ?>
                                        <?php else: ?>
                                            <?php echo $location->{"website"}->{'url'} ?>
                                        <?php endif ?>
                                    </a>
                                </li>
                            <?php endif ?>
                        </ul>
                        <?php if (isset($location->{'website'}) && isset($location->{"website"}->{'url'})): ?>
                            <meta itemprop="url" content="<?php echo $location->{"website"}->{'url'} ?>">
                        <?php endif ?>


                        <a class="btn btn-danger btn-sm mr-1 mb-1" target="_blank"
                           href="<?php echo 'https://www.google.com/maps/dir//' . urlencode($location->{'address'}->{'street'} . ' ' . $location->{'address'}->{'house'} . ' ' . $location->{'address'}->{'postCode'} . ' ' . $location->{'address'}->{'city'}) ?>"
                        >Wegbeschreibung</a>
                        <?php if (isset($location->{'website'})): ?>
                            <a class="btn btn-danger btn-sm mb-1" target="_blank"
                               href="<?php echo $location->{"website"}->{'url'} ?>">Website</a>
                        <?php endif ?>


                        <!--                        review widget-->
                        <?php
                        $options = get_option('liw_settings');
                        ?>
                        <?php if ($options['show_review_widget']): ?>


                            <div class="row">
                                <div class="col">
                                    <div id="review-widget-detail" style="margin: 10px 0"></div>

                                </div>
                            </div>

                            <script>
                                window.addEventListener("load", function () {
                                    window['reviewWidget'] = new ReviewWidget();
                                    reviewWidget.locationId = '<?php echo $location->{"id"}?>';
                                    reviewWidget.widgetDiv = 'review-widget-detail';
                                    reviewWidget.key = '<?php echo $options['api_key']?>';
                                    reviewWidget.ratingType = '<?php echo $options['review_widget_rating_type'] ? $options['review_widget_rating_type'] : 'average-rating' ?>';
                                    reviewWidget.initWidget();
                                });
                            </script>
                        <?php endif ?>

                    </div>
                    <?php if (isset($location->{"openingsHours"})): ?>
                        <div class="col-lg-6">
                            <h6>Öffnungszeiten</h6>
                            <ul class="fa-ul">
                                <li>
                                    <span class="fa-li "><i class="far fa-clock"></i></span>
                                    <?php if (isset($location->{'currentOpening'})): ?>
                                        <?php if ($location->{'currentOpening'}->{'openedNow'}): ?>
                                            <span class="text-success">Jetzt geöffnet:</span><br>
                                        <?php else: ?>
                                            <span class="text-danger">Momentan geschlossen.</span><br>
                                            <span>Öffnungszeiten:</span><br>
                                        <?php endif ?>
                                        <?php echo strtr($location->{'currentOpening'}->{'openingToday'}, [', ' => '<br>', 'closed' => 'Heute geschlossen']) . (($location->{'currentOpening'}->{'openingToday'} != 'closed') ? ' Uhr' : "") ?>
                                    <?php endif ?>
                                </li>

                                <?php foreach ($location->{"openingsHours"} as $opening):
                                    $metaSchema = '';
                                    if ($opening->{'value'} != 'closed') {
                                        $metaSchema = '<meta itemprop="openingHours" content="' . $opening->{'key'} . ' ' . $opening->{'value'} . '">';
                                    }
                                    ?>

                                    <?php
                                    global $openingDays; echo '<li>' . $metaSchema . '<span class="fa-li font-weight-bold">' . strtr($opening->{'key'}, $openingDays) . '</span>' . strtr($opening->{'value'}, [', ' => '<br>', 'closed' => 'geschlossen']) . (($opening->{'value'} != 'closed') ? ' Uhr' : "") . '</li>' ?>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                </div>

                <?php if (isset($location->{'currentOfferText'}) || isset($location->{'currentOfferUrl'})): ?>

                    <div class="row">
                        <div class="col">
                            <?php if (isset($location->{'currentOfferText'})): ?>
                                <p><?php echo $location->{'currentOfferText'} ?></p>
                            <?php endif ?>

                            <?php if (isset($location->{'currentOfferUrl'})): ?>
                                <a href="<?php echo $location->{'currentOfferUrl'}->{'url'} ?>" target="_blank">
                                    <?php if (isset($location->{'currentOfferUrl'}->{'displayUrl'})): ?>
                                        <?php echo $location->{'currentOfferUrl'}->{'displayUrl'} ?>
                                    <?php else: ?>
                                        <?php echo $location->{'currentOfferUrl'}->{'url'} ?>
                                    <?php endif ?>
                                </a>
                            <?php endif ?>
                        </div>
                    </div>

                <?php endif ?>
            </div>


        </div>
    </div>

    <?php if (isset($location->{'description'}) || isset($location->{'trademarks'})): ?>
        <div class="container-fluid mb-5">
            <?php if (isset($location->{'description'})): ?>
                <div class="row mt-5 mb-5 p-5" style="background: #eeeeee">
                    <div class="col text-center">
                        <h2><?php echo $location->{"name"} ?></h2>
                        <p><?php echo $location->{"description"} ?></p>
                    </div>
                </div>
            <?php endif ?>
            <?php if (isset($location->{'trademarks'})): ?>
                <div class="row mt-5 mb-5 p-5">
                    <div class="col text-center">
                        <?php foreach ($location->{"trademarks"} as $trademark): ?>
                                <div style="height:70px; width:100px; margin:10px; display: inline-block; line-height: 70px"  id="img-<?php echo $location->{"id"} ?>">
                                    <img src="https://api.lead-hub.de/imgs/misc/<?php echo rawurlencode(preg_replace('/\//', '', $trademark)) ?>.jpg"
                                         alt="<?php echo $trademark ?>" onerror="showFallBack(this)" title="<?php echo $trademark ?>"
                                    style="max-width:100%;max-height:100%">
                                </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>


    <?php if (isset($location->{'customImages'})): ?>
        <div class="container-fluid mb-5">
            <div
                    data-featherlight-gallery
                    data-featherlight-filter="a"
            >
                <div class="row justify-content-center mb-5 p-5">
                    <div class="col-12 text-center">
                        <h2>Galerie</h2>
                    </div>

                    <?php foreach ($location->{"customImages"} as $i => $img): ?>

                        <div class="col-sm-4 col-lg-3 col-xl-2
                    <?php if (count($location->{"customImages"}) > 11 && $i > 11): ?><?php echo 'hiddenImg' ?><?php endif ?>">

                            <a itemprop="image" href="<?php echo $img->{"url"} ?>">
                                <div class="imageGalleryWrapper"
                                     style="background-image: url('<?php echo $img->{"url"} ?>')"></div>
                            </a>

                        </div>

                    <?php endforeach ?>

                    <?php if ((count($location->{"customImages"}) > 11)): ?>

                        <div class="col-12 text-center">
                            <button class="btn btn-danger" id="galleryToggleButton" onclick="showAllImages()">Zeige
                                alle Bilder
                            </button>
                        </div>
                    <?php endif ?>

                </div>
            </div>
        </div>
    <?php endif ?>

</div>
