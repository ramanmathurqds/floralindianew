<input type="hidden" value="<?=$countryCode?>" id="countryCode" />
<div class="home-page child-page no-order">
    <div class="container custom-container">
        <div class="hot-categories">
            <div class="row">
                <div class="col-xl-9 col-12">
                    <div class="row">
                        <?php if($homePageCatData->results) {
                        $i = 1;
                        foreach ($homePageCatData->results as $key => $value)
                        {
                            if ($i >= 6) {
                                break;
                            }
                        ?>
                        <div class="col-6 custom-grid2 cat-grid hotCat_<?=$i++?>">
                            <a class="hotCatLink" href="<?=DOMAIN?><?=$countryCode?>/listing/gifts/product/<?=strtolower(str_replace(" ","-",$value->Name))?>/<?=$value->ID?>">
                                <div class="hvrbox i-size1">
                                    <img src="<?=DOMAIN?><?=$value->DesktopImageURL?>" loading="lazy" alt="<?=$value->Name?>" class="common-1 desk-img d-lg-block d-none hvrbox-layer_bottom rect shimmer">
                                    <img src="<?=DOMAIN?><?=$value->MobileImageURL?>" loading="lazy" alt="<?=$value->Name?>" class="mob-img d-block d-lg-none hvrbox-layer_bottom shimmer">
                                    <div class="hvrbox-layer_top dark">
                                        <div class="hvrbox-text">
                                            <div class="hvrbox-text-inner"><?=$value->Name?></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } $i++; } ?>
                        <?php if(!isMobile()) { ?>
                    </div>
                </div>

                <div class="col-xl-3 col-12 custom-grid2 cat-grid">
                    <?php } ?>
                    <?php if($homePageCatData->results) {
                    $i = 1;
                    $a = 2;
                    foreach ($homePageCatData->results as $key => $value)
                    {
                        if ($i >= 6) {
                    ?>
                    <div class="hotCat_<?=$i++?> col-6-layout">
                        <a class="hotCatLink" href="<?=DOMAIN?><?=$countryCode?>/listing/gifts/product/<?=strtolower(str_replace(" ","-",$value->Name))?>/<?=$value->ID?>">
                            <div class="hvrbox i-size<?=$a++?>">
                                <img src="<?=DOMAIN?><?=$value->DesktopImageURL?>" loading="lazy" alt="<?=$value->Name?>" class="common-1 desk-img d-none d-lg-block hvrbox-layer_bottom shimmer">
                                <img src="<?=DOMAIN?><?=$value->MobileImageURL?>" loading="lazy" alt="<?=$value->Name?>" class="mob-img d-block d-lg-none hvrbox-layer_bottom img-fluid shimmer">
                                <div class="hvrbox-layer_top dark">
                                    <div class="hvrbox-text">
                                        <div class="hvrbox-text-inner"><?=$value->Name?></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php $a++; } $i++; }} ?>
                </div>  
            </div>
        </div>
            <?php // } ?>
    </div>

    <!--Offer1 section
        <section class="full-width mt-3">
            <div class="offer-section offer-section1">
                <a href="#">
                    <div class="hvrbox">
                        <img src="@Model.OfferList[0].OfferImage" class="offer-banner" />
                        <div class="hvrbox-layer_top dark">
                            <div class="hvrbox-text">
                                <div class="hvrbox-text-inner offer-title">
                                    <input type="hidden" value="June 10, 2020 19:00:00" id="offer1Expiry" />
                                    OFFER OF THE DAY &nbsp;&nbsp;&nbsp; <span id="offer1Count" class="offer-timer"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>-->

    <section class="full-width mt-5 mt-5-custom">
        <?php if(isset($HashTagName)) { ?>
            <div class="hashtag">
                <h1 class="common-heading text-uppercase text-center no-shadow">
                    <li><a href="javascript:;"><?=$HashTagName?></a></li>
                </h1>
            </div>
        <?php } ?>

        <div class="container container-mob-no-pad custom-container mt-5 mt-5-custom">
            <div class="promotional-section">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block custom-grid">
                        <img src="<?=DOMAIN?><?=$OccasionImage?>" loading="lazy" alt="<?=$OccasionImage?>" class="img-100" />
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="row">
                        <?php if(isset($countryBasedProduct->results)) {
                            $i = 1;
                            foreach ($countryBasedProduct->results as $key => $value)
                            {
                                if(isset($value->ProductName)) {
                                    if($value->ActiveWishList == '1') {
                                        $active = 'active';
                                        $title = 'Remove from wishlist';
                                        $heart = 'fas';
                                    } else{
                                        $active = 'inactive';
                                        $title = 'Add to wishlist';
                                        $heart = 'far';
                                    }

                                    if($i >= 3) {
                                        break;
                                    }
                        ?>
                            <div class="col-6 custom-grid">
                                <div class="product-block <?=$active?>" id="product-<?=$value->ProductID?>">
                                    <a href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>">
                                        <div class="prduct-image-wrapper"> 
                                            <div class="img-overlay"></div>   
                                            <img src="<?=DOMAIN?><?=$value->ProductIamge?>" class="product-img" loading="lazy" alt="<?=$value->ProductName?>" />
                                        </div>

                                        <div class="product-info text-center">
                                            <div class="product-title">
                                                <p><?=$value->ProductName?></p>
                                                <span class="short-description">(<?=$value->ProductShortDescription?>)</span>
                                            </div>

                                            <div class="product-price">
                                                <p>
                                                    <span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="setCurrBasedPrice"><?=$value->Price?></span> &nbsp;&nbsp;
                                                    <span class="cancelled-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="setCurrBasedPrice"><?=$value->Mrp?></span></span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="product-action">
                                        <table class="extras">
                                            <tr>
                                                <td>
                                                    <?php
                                                        $ratingCount = ($value->ProductRatingCount > 0) ? $value->ProductRatingCount : 0;
                                                        $productRating = isset($value->ProductRating) ? $value->ProductRating : 0;
                                                        $ratingStars = ($ratingCount > 0) ? $productRating : 0;
                                                    ?>
                                                    <span class="nowrap">(<?=$ratingCount?> reviews)</span>
                                                    <span data-avg-rating="<?=round($ratingCount)?>" class="d-none d-lg-inline-block avg-rating">
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </span>
                                                </td>

                                                <td data-toggle="popover" data-trigger="click" data-html="true" data-content="<div class='popover-content pop-social-platform'><p><span class='share-caption'>share</span><a title='facebook' href='https://www.facebook.com/sharer.php?u=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>'><i class='fab fa-facebook'></i></a> <a title='whatsapp' href='https://api.whatsapp.com/send?text=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>'><i class='fab fa-whatsapp'></i></a> <a title='pinterest' href='http://pinterest.com/pin/create/button/?url=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>'><i class='fab fa-pinterest'></i></a> <a title='email' class='email-share' href='mailto:<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>&subject=Floral India - <?=$value->ProductName?>&body=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>'><i class='fa fa-envelope'></i></a></p></div>">
                                                    <a>
                                                        <i class="fas fa-share-alt"></i>
                                                    </a>
                                                </td>

                                                <td class="d-none d-lg-table-cell">
                                                    <a title="View this product" href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>"><i class="fas fa-eye"></i></a>
                                                </td>

                                                <td>
                                                    <a class="btn-fav whishlist" title="<?=$title?>">
                                                        <i class="<?=$heart?> fa-heart"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; }}} ?>
                        </div>

                        <div class="row">
                            <div class="col-12 custom-grid">
                                <div class="promo-banner">
                                    <img src="<?=DOMAIN?><?=$OccasionBanner?>" loading="lazy" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="full-width mt-5">
        <div class="container container-mob-no-pad custom-container">
            <div class="tabbed-category">
                <ul class="row">
                    <!-- <?php
                        if(isset($tagData->results)) {
                            foreach ($tagData->results as $key => $value) {
                    ?>
                        <li class="col" data-categorytagid="<?=$value->CategoryTagID?>">
                            <a href="javascript:;" role="button"><?=$value->CategoryTagName?></a>
                        </li>
                    <?php }} ?> -->

                    <li class="col" data-categorytagid="lb">
                        <a href="javascript:;" role="button" disabled="disabled">Budget Price</a>
                    </li>

                    <li class="col" data-categorytagid="tr">
                        <a href="javascript:;" role="button" disabled>Trending</a>
                    </li>

                    <li class="col" data-categorytagid="bs">
                        <a href="javascript:;" role="button" class="active">Best Sellers</a>
                    </li>
                </ul>
            </div>

            <?php require_once(TEMPLATE.'special-products.php'); ?>
        </div>
    </section>

    <!--offer2 section
        <section class="container custom-container mt-5 d-lg-block d-none">
            <div class="offer-section offer-section1">
                <a href="#">
                    <div class="hvrbox">
                        <img src="@Model.OfferList[1].OfferImage" class="offer-banner" />
                        <div class="hvrbox-layer_top dark">
                            <div class="hvrbox-text">
                                <div class="hvrbox-text-inner offer-title">
                                    OFFER OF THE DAY &nbsp;&nbsp;&nbsp; <span class="offer-timer">03h . 04m . 20s</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    -->
    
    <section class="full-width mt-5">
        <h1 class="common-heading text-uppercase text-center no-shadow color-grey-dark"><span class="primary-dark">#Floral</span> Insta Story</h1>

        <div class="mt-4">
        <script src="https://apps.elfsight.com/p/platform.js" defer></script>
        <div class="elfsight-app-f9367a1a-37aa-4e4d-b4ff-eae5e3522c58"></div>
        </div>
    </section>



    <img src="https://www.google-analytics.com/collect?v=1&t=pageview&tid=UA-37862768-1&cid=35963a79-4589-49d7-b876-2b224d0f825b&dp=Home&z=<?=uniqid()?>&uid=78978944" />

</div>
