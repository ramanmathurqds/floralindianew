<?php
    if(isset($specialProducts->results) && !isset($specialProducts->results->error)) {
?>
<div class="gift-category">
    <div class="categoryCarosuel owl-carousel owl-theme gift-option-wrapper">
        <?php
            if(isset($catData->results)) {
                foreach ($catData->results as $key => $value) {
        ?>
                <a class="btn-category" href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->Name))?>/<?=$value->ID?>" data-categoryid="<?=$value->ID?>" data-value="<?=$value->Name?>">
                    <div class="gift-options">
                        <img src="<?=DOMAIN?><?=$value->Logo?>" loading="lazy" />
                        <div class="caption"><?=$value->Name?></div>
                    </div>
                </a>
        <?php }} ?>   
    </div>
</div>
<div class="product-listing-wrapper mt-4">
    <div class="row" id="ProductListPartial">
        <?php
            foreach ($specialProducts->results as $key => $value) {
                $oneDayDelivery = $value->isOneDayDelivery === '1' ? ' oneDay' : '';
                $price = ' '.$value->Price;
                if(isset($value->ProductName)) {
        ?>
            <div class="col-6 col-sm-4 col-xl-3 custom-grid-device item mb-4 tagged-product" data-category="<?=$value->ProductCategoryID?>">

                <?php 
                    if($value->ActiveWishList == '1') {
                        $active = 'active';
                        $title = 'Remove from wishlist';
                        $heart = 'fas';
                    } else{
                        $active = 'inactive';
                        $title = 'Add to wishlist';
                        $heart = 'far';
                    }
                ?>

                <div class="product-block <?=$active?>" id="product-<?=$value->ProductID?>">
                    <a href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>">
                        <div class="prduct-image-wrapper">
                            <div class="img-overlay"></div>
                            <img src="<?=DOMAIN?><?=$value->ProductIamge?>" class="product-img shimmer" alt="<?=$value->ProductName?>" loading="lazy">
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
                                    <span data-avg-rating="<?=round($ratingStars)?>" class="d-none d-lg-inline-block avg-rating">
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </span>
                                </td>

                                <td data-toggle="popover" data-trigger="click" data-html="true" data-content="<div class='popover-content pop-social-platform'><p><span class='share-caption'>share</span><a title='facebook' href='https://www.facebook.com/sharer.php?u=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>'><i class='fab fa-facebook'></i></a> <a title='whatsapp' href='https://api.whatsapp.com/send?text=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>'><i class='fab fa-whatsapp'></i></a> <a title='pinterest' href='http://pinterest.com/pin/create/button/?url=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>'><i class='fab fa-pinterest'></i></a> <a title='email' class='email-share' href='mailto:<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>&subject=Floral India - <?=$value->ProductName?>&body=<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>'><i class='fa fa-envelope'></i></a></p></div>">
                                    <a>
                                        <i class="fas fa-share-alt"></i>
                                    </a>
                                </td>

                                <td class="d-none d-lg-table-cell">
                                    <a title="View this product" href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$value->ProductCategoryID?>/<?=$value->ProductID?>"><i class="fas fa-eye"></i></a>
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
        <?php }} ?>
    </div>
    </div>
<?php } ?>