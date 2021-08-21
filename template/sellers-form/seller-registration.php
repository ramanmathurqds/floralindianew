<div class="seller-body">
    <div class="container container-md">
        <div class="row form-container">
            <div class="col-12 col-lg-3">
                <div class="seller-form-sections">
                    <div class="active-chevron"></div>
                    <div data-form="1" class="seller-form-section-blocks active">
                        <div class="seller-form-section-empty"></div>
                        <div class="seller-form-section-count completed">1</div>
                        <div class="seller-form-section-content">
                            <div class="seller-form-section-title">
                                Busines type
                                <p>
                                    Independent seller or Enterprise.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div data-form="2" class="seller-form-section-blocks">
                        <div class="seller-form-section-empty"></div>
                        <div class="seller-form-section-count completed">2</div>
                        <div class="seller-form-section-content">
                            <div class="seller-form-section-title">
                                Business Info
                                <p>
                                    Outlet name,  owner details, address and contact details.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div data-form="3" class="seller-form-section-blocks">
                        <div class="seller-form-section-empty"></div>
                        <div class="seller-form-section-count">3</div>
                        <div class="seller-form-section-content">
                            <div class="seller-form-section-title">
                                Additional Info
                                <p>
                                    Product types, delivery cities and delivery timings.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="seller-form-body">
                    <form id="basicDetailForm" name="basicDetailForm">
                        <div data-form="1" class="form-block form1 active">
                            <h1 class="head-title">Business Type</h1>

                            <div class="seller-form">
                                <div class="row">
                                    <div class="col-11">
                                        <h2 class="seller-form-title">What type of solution are you interested in?</h2>
                                        <p class="seller-form-subtitle">Select most relevant category</p>
                                    </div>

                                    <div class="col-1">
                                        <div class="section-toggle-chevron">
                                            <div class="c-chevron chevron top"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 collapsable">
                                    <div class="seller-type mb-3">
                                        <input type="radio" id="chkIndependent" runat="server" class="custom-radio required" name="sellerType" value="Independent Seller" />
                                        <label for="chkIndependent" class="lbl-radio-chk">
                                            <span class="chk-title">Independent seller</span>
                                            <span class="chk-subtitle">(Small businesses with 1 shop)</span>
                                        </label>
                                    </div>

                                    <div class="seller-type">
                                        <input type="radio" id="chkEnterprice" runat="server" class="custom-radio required" name="sellerType" value="Enterprise" />
                                        <label for="chkEnterprice" class="lbl-radio-chk">
                                            <span class="chk-title">Enterprise</span>
                                            <span class="chk-subtitle">(Businesses with 2 shops or more who may need a bespoke solution)</span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" class="input-field required" id="businessType" name="BusinessType" data-error-required-msg="Please select your business type" />
                                        <div class="error-message err-type"></div>
                                    </div>

                                    <div class="form-next-button">
                                        <div class="container container-md">
                                            <div class="row text-right">
                                                <div class="col-12">
                                                    <button class="btn btn-primary-custom btn-next" type="button">Next step <span class="chevron right"></span> </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div data-form="2" class="form-block form2">
                            <h1 class="head-title">Business Information</h1>

                            <div class="seller-form">
                                <div class="row">
                                    <div class="col-11">
                                        <h2 class="seller-form-title">We would require some information about your business</h2>
                                        <p class="seller-form-subtitle">Outlet name, owner details, email, phone number</p>
                                    </div>

                                    <div class="col-1">
                                        <div class="section-toggle-chevron">
                                            <div class="c-chevron chevron top"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 collapsable">
                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <section class="custom-form-group">
                                                <section class="form-group-inner">
                                                    <input type="text" id="txtFullName" name="SellerName" class="input-field custom-form-control required pattern" data-val-validate-regex="<?= patternLettersOnly ?>" data-error-required-msg="Please enter your full name" data-error-invalid-msg="Please re-enter your first name using only letters" />
                                                    <label class="form-label">Your full name</label>
                                                    <div class="error-message"></div>
                                                </section>
                                            </section>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <section class="custom-form-group">
                                                <section class="form-group-inner">
                                                    <input type="text" id="txtBusinessName" name="BusinessName" class="input-field custom-form-control required pattern" data-error-required-msg="Please enter your business name" data-val-validate-regex="<?= patternAlphaNumeric ?>" data-error-required-msg="Please enter your full name" data-error-invalid-msg="Please re-enter business name using only letters or numbers" />
                                                    <label class="form-label">Business name</label>
                                                    <div class="error-message"></div>
                                                </section>
                                            </section>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <section class="custom-form-group">
                                                <section class="form-group-inner">
                                                    <input type="text" id="txtEmail" name="Email" class="input-field no-input-validate custom-form-control required pattern" data-error-required-msg="Please enter your email" data-val-validate-regex="<?= patternEmail ?>"  data-error-invalid-msg="Please enter valid email address" />
                                                    <label class="form-label">Your email</label>
                                                    <div class="error-message"></div>
                                                </section>
                                            </section>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <section class="custom-form-group">
                                                <section class="form-group-inner">
                                                    <input type="text" id="txtPhoneNumber" name="PhoneNumber" class="input-field custom-form-control required pattern no-input-validate" data-error-required-msg="Please enter your mobile number" data-val-validate-regex="<?= patternMobileNo ?>"  data-error-invalid-msg="Please enter valid mobile number" />
                                                    <label class="form-label">Your mobile number</label>
                                                    <div class="error-message"></div>
                                                </section>
                                            </section>
                                        </div>

                                        <div class="col-12">
                                            <section class="custom-form-group">
                                                <section class="form-group-inner">
                                                    <input type="text" id="txtBusinessUrl" name="BusinessUrl" class="input-field custom-form-control" />
                                                    <label class="form-label">Business website or Instagram page url</label>
                                                </section>
                                            </section>
                                        </div>

                                        <div class="col-12">
                                            <section class="custom-form-group">
                                                Please place the pin accurately at your outlet’s location on the map
                                                <section class="form-group-inner">
                                                    <input type="text" id="txtLocation" name="BusinessLocation" class="input-field custom-form-control required" data-error-required-msg="Please select your business location" />
                                                    <div class="error-message"></div>
                                                    <button class="detect-location-btn">
                                                        <i class="icn icn-location-detect">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#9C9C9C" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="icon-svg-title- icon-svg-desc-" role="img" class="rbbb40-0 kaLXAT"><linearGradient id="ckmzxs7xg004x3b5wo94pskte" x1="0" x2="100%" y1="0" y2="0"><stop offset="0" stop-color="#9C9C9C"></stop><stop offset="100%" stop-color="#9C9C9C"></stop></linearGradient><title id="icon-svg-title-"></title><desc id="icon-svg-desc-">It is an icon with title </desc><title>current-location</title><path d="M13.58 10c0 1.977-1.603 3.58-3.58 3.58s-3.58-1.603-3.58-3.58c0-1.977 1.603-3.58 3.58-3.58v0c1.977 0 3.58 1.603 3.58 3.58v0zM20 9.52v0.96c0 0.265-0.215 0.48-0.48 0.48v0h-1.72c-0.447 3.584-3.256 6.393-6.802 6.836l-0.038 0.004v1.72c0 0.265-0.215 0.48-0.48 0.48v0h-0.96c-0.265 0-0.48-0.215-0.48-0.48v0-1.72c-3.575-0.455-6.375-3.262-6.816-6.802l-0.004-0.038h-1.74c-0.265 0-0.48-0.215-0.48-0.48v0-0.96c0-0.265 0.215-0.48 0.48-0.48v0h1.74c0.445-3.578 3.245-6.385 6.781-6.836l0.039-0.004v-1.72c0-0.265 0.215-0.48 0.48-0.48v0h0.96c0.265 0 0.48 0.215 0.48 0.48v0 1.72c3.584 0.447 6.393 3.256 6.836 6.802l0.004 0.038h1.72c0.265 0 0.48 0.215 0.48 0.48v0zM15.96 10c0-3.292-2.668-5.96-5.96-5.96s-5.96 2.668-5.96 5.96c0 3.292 2.668 5.96 5.96 5.96v0c3.292 0 5.96-2.668 5.96-5.96v0z" fill="url(#ckmzxs7xg004x3b5wo94pskte)"></path></svg>
                                                        </i>
                                                        <span class="btn-text">Detect</span>
                                                    </button>
                                                </section>
                                                <div class="d-none">
                                                    <div class="pac-card" id="pac-card">
                                                        <div>
                                                            <div id="title">Autocomplete search</div>
                                                            <div id="type-selector" class="pac-controls">
                                                            <input  type="radio" name="type" id="changetype-all" checked="checked"
                                                            />
                                                            <label for="changetype-all">All</label>

                                                            <input type="radio" name="type" id="changetype-establishment" />
                                                            <label for="changetype-establishment">Establishments</label>

                                                            <input type="radio" name="type" id="changetype-address" />
                                                            <label for="changetype-address">Addresses</label>

                                                            <input type="radio" name="type" id="changetype-geocode" />
                                                            <label for="changetype-geocode">Geocodes</label>
                                                            </div>
                                                            <br />
                                                            <div id="strict-bounds-selector" class="pac-controls">
                                                            <input type="checkbox" id="use-location-bias" value="" checked />
                                                            <label for="use-location-bias">Bias to map viewport</label>

                                                            <input type="checkbox" id="use-strict-bounds" value="" />
                                                            <label for="use-strict-bounds">Strict bounds</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="map"></div>
                                            </section>
                                        </div>
                                    </div>

                                    <div class="form-next-button">
                                        <div class="container container-md">
                                            <div class="text-right">
                                                <button class="btn btn-link btn-back" type="button"><span class="chevron left"></span> Go back</button>

                                                <button class="btn btn-primary-custom btn-next" type="submit">Next step <span class="chevron right"></span> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div data-form="3" class="form-block form3">
                        <h1 class="head-title">Addional Info</h1>

                        <div class="seller-form">
                            <div class="row">
                                <div class="col-11">
                                    <h2 class="seller-form-title">We would require contact information about your business</h2>
                                    <p class="seller-form-subtitle">Product types, delivery cities and delivery timings.</p>
                                </div>

                                <div class="col-1">
                                    <div class="section-toggle-chevron">
                                        <div class="c-chevron chevron top"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 collapsable">
                                <div class="custom-form-group">
                                    Select types of product you sell

                                    <ul class="chk-group-lists">
                                        <li>
                                            <input type="checkbox" id="chkCat1" runat="server" class="custom-chk" name="chkCat1" />
                                            <label for="chkCat1" class="lbl-chk">
                                                <span class="chk-title">Flowers</span>
                                            </label>
                                        </li>

                                        <li>
                                            <input type="checkbox" id="chkCat2" runat="server" class="custom-chk" name="chkCat2" />
                                            <label for="chkCat2" class="lbl-chk">
                                                <span class="chk-title">Cakes</span>
                                            </label>
                                        </li>

                                        <li>
                                            <input type="checkbox" id="chkCat3" runat="server" class="custom-chk" name="chkCat3" />
                                            <label for="chkCat3" class="lbl-chk">
                                                <span class="chk-title">Balloons</span>
                                            </label>
                                        </li>

                                        <li>
                                            <input type="checkbox" id="chkCat4" runat="server" class="custom-chk" name="chkCat4" />
                                            <label for="chkCat4" class="lbl-chk">
                                                <span class="chk-title">Chocolates</span>
                                            </label>
                                        </li>

                                        <li>
                                            <input type="checkbox" id="chkCat5" runat="server" class="custom-chk" name="chkCat5" />
                                            <label for="chkCat5" class="lbl-chk">
                                                <span class="chk-title">Essentials</span>
                                            </label>
                                        </li>

                                        <li>
                                            <input type="checkbox" id="chkCat5" runat="server" class="custom-chk" name="chkCat5" />
                                            <label for="chkCat5" class="lbl-chk">
                                                <span class="chk-title">Essentials</span>
                                            </label>
                                        </li>

                                        <li>
                                            <input type="checkbox" id="chkCat5" runat="server" class="custom-chk" name="chkCat5" />
                                            <label for="chkCat5" class="lbl-chk">
                                                <span class="chk-title">Essentials</span>
                                            </label>
                                        </li>

                                        <div class="clearfix"></div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>