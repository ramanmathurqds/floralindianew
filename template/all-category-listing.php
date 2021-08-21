<div class="product-listing-page no-order">

<?php if(!isset($data->results->error)) { ?>
    <div class="btn-filter">
        <img src="<?=DOMAIN?>Content/assets/images/common/filter.png" />
    </div>

    <!--filter overlay-->
    <div class="overlay-bar"></div>
    <!--filter overlay-->

    <div data-simplebar data-simplebar-auto-hide="false" class="filter-box">
        <input type="hidden" value="<?=$countryCode?>" id="countryCode" />
        <input type="hidden" value="" id="filter_delivery" />
        <input type="hidden" value="<?=$listParam?>" id="listType" />

        <div class="filter-header">
            <div class="patch"></div>
            <button class="btn-close"><img src="<?=DOMAIN?>Content/assets/images/common/close-light.png" alt="close-light" /></button>
            <div class="filter-title">FILTER</div>
            <div class="filter-subtitle">Your Selection</div>
            <div class="filter-clear">
                <button class="btn-clear-filter" id="clear-filters">clear all selections</button>
            </div>
        </div>

        <div class="filter-body">
            <div class="filter-section">
                <div class="filter-title">Price</div>

                <div class="filter-item custom-range-slider">
                    <input type="hidden" id="hidden_minimum_price" value="300">
                    <input type="hidden" id="hidden_maximum_price" value="30000">
                    <div class="cs-widget">
                        <div class="cs-widget-content">
                            <div id="slider-container"></div>
                            <span data-min="300" class="min-value"></span>
                            <span data-max="30000" class="max-value"></span>
                            <p class="clearfix">
                                <input type="text" readonly id="filter-weight" class="price-range d-none filter_items" data-filter-group="weight"/>
                            </p>
                            <div id="slider-range"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
            // if($data1->results) {
            //     $a = 0;
            //     foreach($data1->results as $key => $val) {
            //         if(isset($val->FilterName)) {
            ?>
                <!-- <div class="filter-section subcat-filters" data-catId="<?=$val->CategoryID?>">
                    <div class="filter-collapse">+</div>
                    <div class="filter-title"><?=$val->FilterName?></div>

                    <div class="filter-item">
                        <ul class="filter-check">
                        <?php // foreach($val->Filters as $key => $value) { ?>
                            <li>
                                <input id="filter-<?=$a?><?=$val->CategoryID?>" value="<?=str_replace(' ', '', $value->FilterValue);?>-<?=$value->CategoryFilterID?>" type="checkbox" class="filter_items common_selector" data-filter="<?=$value->FilterValue?>" data-name="<?=$value->FilterValue?>" />
                                <label for="filter-<?=$a?><?=$val->CategoryID?>"><?=$value->FilterValue?></label>
                            </li>
                        <?php // $a++; } ?>
                        </ul>
                    </div>
                </div> -->
            <?php // }}} ?>


            <div class="filter-section subcat-filters">
                <div class="filter-collapse">+</div>
                <div class="filter-title">One day delivery</div>

                <div class="filter-item">
                    <ul class="filter-check">
                        <li>
                            <input id="filter-delivery" value="oneDay" type="checkbox" class="filter_items common_selector" data-name="oneDay" />
                            <label for="filter-delivery">One Day Delivery</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

    <div class="container child-page custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a href="javascript:;"><?=ucfirst($listParam)?></a></li>
            </ul>
        </div>
 
        <?php
        if(!isset($data->results->error)) {
            if(isset($expressSubCatOfCat->results)) {
        ?>
            <div class="gift-category d-none">
                <div class="giftCategoryCarosuel owl-carousel owl-theme gift-option-wrapper">
                <?php
                    foreach ($expressSubCatOfCat->results as $key => $value) {
                        if(isset($value->ProductSubCategoryName)) {
                ?>
                    <a class="btn-category active slider-item" href="<?=DOMAIN?><?=$countryCode?>/listing/product/<?=strtolower(str_replace(" ", "-", $CatName))?>/<?=strtolower(str_replace(" ", "-", $value->ProductSubCategoryName))?>/<?=$value->CategoryID?>" data-value="<?=$value->ProductSubCategoryName?>">
                        <div class="gift-options" data-categoryid="<?=$value->ProductSubCategoryID?>">
                            <img src="<?=DOMAIN?><?=$value->IconURL?>" alt="icons" />
                            <div class="caption"><?=$value->ProductSubCategoryName?></div>
                        </div>
                    </a>
                <?php }} ?>
                </div>
            </div>
        <?php } ?>

        <?php
            if(isset($data->results) && !isset($data->results->error)) {
        ?>
            <div class="product-list-block">
                <div class="selected-filters">
                    <div class="filter-text">Filter By :</div>
                    <div class="filter-box-wrapper">
                        <ul class="filter-tags">
                        </ul>
                    </div>
                </div>

                <div class="product-head">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                        <?php if(isset($data->results)) { ?>
                            <h1 class="title-box"><?=ucfirst($listParam)?></h1>

                            <span class="count-box">(Showing <span class="start"><?=$data->results->Page?></span> - <span class="totalLinks"><?=$data->results->TotalLinks?></span> of <span class="totalProducts"><?=$data->results->TotalProducts?></span> results)</span>
                        <?php } ?>
                        </div>
                        <div class="col-6 d-lg-block d-none text-right">
                            <div class="universal-filters dropdown float-right">
                                <select class="form-control dropdown-select sorting jq-drop" id="selectBox">
                                    <option value="">SORT BY</option>
                                    <option value="priceASC">PRICE : LOW TO HIGH</option>
                                    <option value="priceDESC">PRICE : HIGH TO LOW</option>
                                    <!--<option value="nameASC">Name ASC</option>
                                    <option value="nameDESC">Name Desc</option>-->
                                    <option value="bestSeller">SORT BY : BEST SELLERS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    if(isset($data->results)) {
                ?>
                <div class="product-listing-wrapper">
                    <div class="row" id="ProductListPartial">
                    <?php
                    $cust_filter = '';
                    foreach ($data->results->Products as $key => $value) {
                        $oneDayDelivery = $value->isOneDayDelivery === '1' ? ' oneDay' : '';
                        $price = ' '.$value->Price;
                        
                        if (isset($value->Filters)) {
                            $cust_filter = ' '.implode(' ', array_column($value->Filters, 'FilterValue'));
                        }
                        if(isset($value->ProductName)) {
                    ?>
                    <div class="col-6 col-sm-4 col-xl-3 custom-grid-device item<?=$cust_filter?><?=$oneDayDelivery?><?=$price?>" data-weight="<?=$value->Price?>">

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
                                        <?php if(!empty($value->ProductShortDescription)) { ?>
                                            <span class="short-description">(<?=$value->ProductShortDescription?>)</span>
                                        <?php } ?>
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

            <?php 
                $output = '
                    <br />
                    <div align="center" class="c-main-pagination">
                    <ul class="c-pagination">
                    ';

                    $total_links = $data->results->TotalLinks;
                    $page = $data->results->Page;
                    $previous_link = '';
                    $next_link = '';
                    $page_link = '';

                    // echo $total_links;

                    if($total_links > 7)
                    {
                        if($page < 8)
                        {
                            for($count = 1; $count <= 8; $count++)
                            {
                                $page_array[] = $count;
                            }
                            $page_array[] = '...';
                            $page_array[] = $total_links;
                        }
                        else
                        {
                            $end_limit = $total_links - 8;
                            if($page > $end_limit)
                            {
                                $page_array[] = 1;
                                $page_array[] = '...';
                                for($count = $end_limit; $count <= $total_links; $count++)
                                {
                                    $page_array[] = $count;
                                }
                            }
                            else
                            {
                                $page_array[] = 1;
                                $page_array[] = '...';
                                for($count = $page - 1; $count <= $page + 1; $count++)
                                {
                                    $page_array[] = $count;
                                }
                                $page_array[] = '...';
                                $page_array[] = $total_links;
                            }
                        }
                    }
                    else
                    {
                        for($count = 1; $count <= $total_links; $count++)
                        {
                            $page_array[] = $count;
                        }
                    }

                    for($count = 0; $count < count($page_array); $count++)
                    {
                        if($page == $page_array[$count])
                        {
                            $page_link .= '
                                <li class="c-page-item active">
                                <a class="c-page-link" href="javascript:void(0)">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                                </li>';

                            $previous_id = $page_array[$count] - 1;
                            if($previous_id > 0)
                            {
                                $previous_link = '<li class="c-page-item"><a class="c-page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'"><i class="fa fa-angle-left"></i></a></li>';
                            }
                            else
                            {
                                $previous_link = '
                                <li class="c-page-item disabled">
                                    <a class="c-page-link" href="javascript:void(0)"><i class="fa fa-angle-left"></i></a>
                                </li>';
                            }
                            $next_id = $page_array[$count] + 1;
                            if($next_id >= $total_links)
                            {
                                $next_link = '
                                <li class="c-page-item disabled">
                                    <a class="c-page-link" href="javascript:void(0)"><i class="fa fa-angle-right"></i></a>
                                </li>';
                            }
                            else
                            {
                                $next_link = '<li class="c-page-item"><a class="c-page-link" href="javascript:void(0)" data-page_number="'.$next_id.'"><i class="fa fa-angle-right"></i></a></li>';
                            }
                        }
                        else
                        {
                            if($page_array[$count] == '...')
                            {
                                $page_link .= '
                                <li class="c-page-item disabled">
                                    <a class="c-page-link" href="javascript:void(0)">...</a>
                                </li>';
                            }
                            else
                            {
                                $page_link .= '<li class="c-page-item"><a class="c-page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>';
                            }
                        }
                    }

                    $output .= $previous_link . $page_link . $next_link;
                    $output .= '
                    </ul>
                    </div>';

                    echo $output;

                ?>

                <?php } ?>
            </div>
        <?php } else { ?>
            <br/><br/>
            <h3 style="text-align:center;">No Products found..!!</h3>
        <?php } ?>

    <?php } ?>
    </div>
</div>

<!--mobile sort and filter panel-->
<div class="sort-filter-box d-none">
    <div class="col-1-2">
        <button type="button" class="panel-button get-sort">Sort</button>
    </div>
    <div class="section-divider"></div>
    <div class="col-1-2">
        <button type="button" class="panel-button get-filter">Filter</button>
    </div>
</div>

<!--sorting drawer-->
<div class="drawer active">
    <div class="drawer-inner">
        <ul>
            <li class="heading-li">Sort By</li>
            <li data-value="priceASC" class="btn-li-sort">PRICE : LOW TO HIGH</li>
            <li data-value="priceDESC" class="btn-li-sort">PRICE : HIGH TO LOW</li>
            <li data-value="bestSeller" class="btn-li-sort">BEST SELLERS</li>
        </ul>

        <button type="button" class="c-btn c-btn-block c-btn-semi-smooth c-btn-light close-drawer">
            Close
        </button>
    </div>
</div>