<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="master-occasion-details.aspx.vb" Inherits="master_occasion_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" CssClass="d-none">
        <asp:Label ID="lblIcon" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblDesktopImage" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblMobileImage" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblBannerImage" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblOccasionHomeBanner" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblOccasionOfferBanner" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblOccasionTypeID" runat="server" ClientIDMode="Static"></asp:Label>
    </asp:Panel>

    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <asp:HyperLink ID="linkBack" runat="server"><i class="fa fa-angle-left"></i><asp:Label ID="lblBackText" runat="server"></asp:Label></asp:HyperLink>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblOccasionName" runat="server" Text="Add Occasion"></asp:Label>
                    </h1>
                </div>

                 <div class="mt-3">
                    <div class="row">
                        <asp:Panel ID="pnlSuccess" CssClass="col-12" runat="server" Visible="false">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Success!</strong>
                                <asp:Label ID="successMessage" runat="server"></asp:Label>
                            </div>
                        </asp:Panel>

                        <div class="col-12 mb-3">
                            <div class="card-container mb-2">
                                <div class="ui-card">
                                    <div class="form-group">
                                        <label for="txtOccasionName">Name</label>
                                        <asp:TextBox ID="txtOccasionName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtOccasionHashtags">Hash Tags <small>(max length - 30 chars. Display on Home page)</small></label>
                                        <asp:TextBox ID="txtOccasionHashtags" runat="server" CssClass="form-control" ClientIDMode="Static" maxlength="30"></asp:TextBox>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label>Banner Image <small>(Product Listing page - 521px X 432px)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileListingPage" CssClass="custom-file-input file-size-validation" data-size="102400" />
                                                    <label class="custom-file-label" for="fileListingPage">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgBanner" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                         <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label>Occasion Image <small>(Display on Home page - 503px X 543px)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileOccasionHomeBanner" CssClass="custom-file-input file-size-validation" data-size="102400" />
                                                    <label class="custom-file-label" for="fileOccasionHomeBanner">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgOccasionHomeBanner" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label>Occasion Offer Image <small>(Display on Home page - 630px X 245px)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileOccasionOfferBanner" CssClass="custom-file-input file-size-validation" data-size="102400" />
                                                    <label class="custom-file-label" for="fileOccasionOfferBanner">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgOccasionOfferBanner" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtVideoLink">YouTube Video Link</label>
                                        <asp:TextBox ID="txtVideoLink" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtVideoLink">Surcharge on Delivery <small>(applicable when you set actual date of occasion)</small></label>
                                        <asp:TextBox ID="txtSurcharge" runat="server" CssClass="form-control" ClientIDMode="Static" TextMode="Number"></asp:TextBox>
                                    </div>

                                    <asp:Panel ID="pnDates" runat="server" CssClass="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group date" data-provide="datepicker">
                                                <label for="txtStartDate">Start Date <small>(Start date to be displayed on Website menu)</small></label>
                                                <div>
                                                    <asp:TextBox ID="txtStartDate" MaxLength="10" data-date-format="yyyy-mm-dd" runat="server" CssClass="form-control datepicker" ClientIDMode="Static"></asp:TextBox>
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group date" data-provide="datepicker">
                                                <label for="txtActualDate">Actual Date <small>(Actual day of occasion)</small></label>
                                                <div>
                                                    <asp:TextBox ID="txtActualDate" MaxLength="10" data-date-format="yyyy-mm-dd" runat="server" CssClass="form-control datepicker" ClientIDMode="Static"></asp:TextBox>
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </asp:Panel>

                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="drpCountry">Country</label>
                                                <asp:DropDownList ID="drpCountry" runat="server" CssClass="form-control">
                                                    <asp:ListItem></asp:ListItem>
                                                </asp:DropDownList>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtPosition">Position</label>
                                                <asp:TextBox ID="txtPosition" TextMode="Number" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="txtSeo">SEO Title</label>
                                                <asp:TextBox ID="txtSeoTitle" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="txtSeoKeywords">SEO Keywords <small>(Best practices add maximum 10 keywords only)</small></label>
                                                <asp:TextBox ID="txtSeoKeywords" TextMod="multiline" runat="server" CssClass="form-control" ClientIDMode="Static" Rows="3"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="txtSeoDescription">SEO Description</label>
                                                <asp:TextBox ID="txtSeoDescription" TextMod="multiline" runat="server" CssClass="form-control" ClientIDMode="Static" Rows="5"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-5">
                                            <div class="form-group">
                                                <div class="checkbox-container">
                                                    <asp:CheckBox ID="chkActive" runat="server" Text="Live this on website" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-5">
                                            <asp:Button ID="btnAdd" runat="server" OnClick="addEvent" CssClass="c-btn c-btn-primary" Text="Save"  />

                                            <asp:Button ID="btnUpdate" runat="server" OnClick="updateEvent" CssClass="c-btn c-btn-primary" Text="Save"  />

                                            <asp:Button ID="btnDelete" runat="server" CssClass="c-btn c-btn-danger" Text="Delete" onClientClick=" return confirm('Are you sure you want to delete this?')" OnClick="deleteEvent" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</asp:Content>

