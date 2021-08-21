<div class="ab-start">
	<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img src="<?=DOMAIN?>Content/assets/images/banners/balloon-bg.jpg" class="d-block w-100">
			</div>

			<div class="carousel-item ">
				<img src="<?=DOMAIN?>Content/assets/images/banners/flower-banner.jpg" class="d-block w-100">
			</div>

			<div class="carousel-item">
				<img src="<?=DOMAIN?>Content/assets/images/banners/cake-banner.jpg" class="d-block w-100">
			</div>
		</div>
	</div>
</div>
<div class="contact-page overhead">
	<div class="overhead-content">
        <div class="abs">
			<h1 class="common-heading text-center text-uppercase">Contact</h1>

			<div class="container custom-container container-md mt-5">
				<div class="row">
					<div class="col-4 custom-grid2">
						<a href="#" class="c-btn c-btn-bordered contact-quick-links go-contact">Get in touch</a>
					</div>

					<div class="col-4 custom-grid2">
						<a target="_blank" href="https://api.whatsapp.com/send?phone=919910200043&text=Hello,%20I%20have%20a%20question%20about" class="c-btn c-btn-bordered contact-quick-links">Live Chat</a>
					</div>

					<div class="col-4 custom-grid2">
						<a href="#" class="c-btn c-btn-bordered contact-quick-links go-social">Social Media</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="overpage-content">
        <div class="container">
			<div class="row">
				<div class="col-12 col-lg-6 get-contact">
					<h3 class="color-grey-dark common-heading no-shadow underlined">Get in touch</h3>
					<div class="contact-form common-form">
						<form class="form formValidation" id="contactForm" name="contactForm" autocomplete="off">
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<label class="lbl-field" for="contactName">Your Name <span class="mandate">*</span></label>
											<input type="text" id="contactName" name="contactName" class="custom-txt no-box-shadow" />
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<label class="lbl-field" for="contactSurname">Surname <span class="mandate">*</span></label>
											<input type="text" id="contactSurname" name="contactSurname" class="custom-txt no-box-shadow" />
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<label class="lbl-field" for="contactEmail">Email <span class="mandate">*</span></label>
											<input type="email" id="contactEmail" name="contactEmail" class="custom-txt no-box-shadow" />
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<label class="lbl-field" for="contactPhone">Phone No <span class="mandate">*</span></label>
											<input type="number" inputmode="numeric" id="contactPhone" name="contactPhone" class="custom-txt no-box-shadow" />
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<label class="lbl-field" for="contactSubject">Subject <span class="mandate">*</span></label>
											<select id="contactSubject" name="contactSubject" class="custom-txt no-box-shadow">
												<option></option>
												<option>To place order</option>
												<option>General message</option>
												<option>Order tracking</option>
												<option>Vender / Tie up</option>
												<option>Corporate</option>
												<option>Feedback</option>
												<option>Other</option>
											</select>
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<label class="lbl-field" for="contactMessage">Message <span class="mandate">*</span></label>
											<textarea id="contactMessage" rows="3" name="contactMessage" class="custom-txt no-box-shadow"></textarea>
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<div class="c-form-control">
											<button type="button" class="c-btn c-btn-primary c-btn-smooth submitContact">Send message</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="col-12 col-lg-5 offset-lg-1">
					<div class="branches-section">
						<h3 class="color-grey-dark common-heading no-shadow underlined">Head Office</h3>
						<p class="mt-3 address">
							<span class="color-grey-dark">FLORAL INDIA</span><br />D-851 New Friends Colony,<br /> New Delhi -110025. India
						</p>

						<div class="mt-4">
							<iframe class="i-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d897580.0493244753!2d76.64883942401488!3d28.504470376847244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce39765135ae9%3A0x6311486a5793378e!2sFloral%20India!5e0!3m2!1sen!2sin!4v1609007505516!5m2!1sen!2sin" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
						</div>

						<div class="contact-email mt-4">
							<a href="tel:<?=$siteData->results->MobileNo?>"><i class="fab fa-whatsapp"></i> <?=$siteData->results->MobileNo?></a><br />
							<a href="mailto:<?=$gdData->results->Email?>"><i class="fas fa-envelope"></i> <?=$gdData->results->Email?></a>
						</div>

						<div class="social-contact">
							<div class="color-grey-dark">Social Media</div>
							<a class="fb" href="https://www.facebook.com/FloralIndiaOfficial" target="_blank"><i class="fab fa-facebook"></i></a>
							<a class="insta" href="https://instagram.com/floralindiaofficial?igshid=dicsp7ik9gzx" target="_blank"><i class="fab fa-instagram"></i></a>
							<a class="pinterest" href="https://pin.it/2IuVwoL" target="_blank"><i class="fab fa-pinterest"></i></a>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="branches-section other-branches">
						<h3 class="color-grey-dark common-heading no-shadow underlined">Our Branches</h3>
						<div class="row mt-4">
							<div class="col-12 col-lg-4">
								 <div class="address-details">
									 <div class="address-details-col">
									 	<i class="fa" aria-hidden="true">
										 	<img src="<?=DOMAIN?>Content/assets/images/common/india-flag.png">
										</i>
									 </div>

									 <div class="contact-info">
										<h4>India</h4>
										<p>D-851 New Friends Colony<br />New Delhi -110025. India
										<iframe class="i-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d897580.0493244753!2d76.64883942401488!3d28.504470376847244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce39765135ae9%3A0x6311486a5793378e!2sFloral%20India!5e0!3m2!1sen!2sin!4v1609007505516!5m2!1sen!2sin" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
										</p>
										<a class="primary-dark" href="tel:91<?=$siteData->results->MobileNo?>"><i class="fab fa-whatsapp"></i> +91<?=$siteData->results->MobileNo?></a>
									</div>
								 </div>
							</div>

							<div class="col-12 col-lg-4">
								 <div class="address-details">
									 <div class="address-details-col">
									 	<i class="fa" aria-hidden="true">
										 	<img src="<?=DOMAIN?>Content/assets/images/common/uk-flag.png">
										</i>
									 </div>

									 <div class="contact-info">
										<h4>United Kingdom(UK)</h4>
										<p>8 Clarendon Road, Holland Park, London W11 3AA
											<iframe class="i-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.3224091157163!2d-0.20907728422999902!3d51.50730077963505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760fe68c71224f%3A0xdf3d1455c4316ada!2s8%20Clarendon%20Rd%2C%20Notting%20Hill%2C%20London%20W11%203AA%2C%20UK!5e0!3m2!1sen!2sin!4v1609332902880!5m2!1sen!2sin" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
										</p>
										<a class="primary-dark" href="tel:447517742871"><i class="fab fa-whatsapp"></i> +44 7517742871</a>
									</div>
								 </div>
							</div>

							<div class="col-12 col-lg-4">
								 <div class="address-details">
									 <div class="address-details-col">
									 	<i class="fa" aria-hidden="true">
										 	<img src="<?=DOMAIN?>Content/assets/images/common/usa-flag.png">
										</i>
									 </div>

									 <div class="contact-info">
										<h4>USA</h4>
										<p>Address will be added soon
										<iframe class="i-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2483.3224091157163!2d-0.20907728422999902!3d51.50730077963505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48760fe68c71224f%3A0xdf3d1455c4316ada!2s8%20Clarendon%20Rd%2C%20Notting%20Hill%2C%20London%20W11%203AA%2C%20UK!5e0!3m2!1sen!2sin!4v1609332902880!5m2!1sen!2sin" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
										</p>
										<a class="primary-dark" href="tel:91<?=$siteData->results->MobileNo?>"><i class="fab fa-whatsapp"></i> +91<?=$siteData->results->MobileNo?></a>
									</div>
								 </div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="promoted-products">
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

                                    if($i >= 5) {
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>