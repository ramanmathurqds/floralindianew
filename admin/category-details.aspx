<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="category-details.aspx.vb" Inherits="category_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" CssClass="d-none">
        <asp:Label ID="lblIcon" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblDesktopImage" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblMobileImage" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblBannerImage" runat="server" ClientIDMode="Static"></asp:Label>
    </asp:Panel>

    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/category.aspx"><i class="fas fa-angle-left"></i>Categories</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblCatName" runat="server" Text="Add Category"></asp:Label>
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
                                        <label for="txtCatName">Category Name</label>
                                        <asp:TextBox ID="txtCatName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Category Dexsktop Image <small>(Display on homepage top category)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileCat" CssClass="custom-file-input common-file-uploader" />
                                                    <label class="custom-file-label" for="fileCat">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgCat" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Category Mobile Image <small>(Display on homepage top category)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileCatMob" CssClass="custom-file-input common-file-uploader file-size-validation" data-size="102400"  />
                                                    <label class="custom-file-label" for="fileCatMob">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgCatMob" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Category Icon <small>(100px X 100px - Displayed as Category icon)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileIcon" CssClass="custom-file-input common-file-uploader file-size-validation" data-size="51200" />
                                                    <label class="custom-file-label" for="fileIcon">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgCatIcon" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Category Banner Image <small>(664px X 550px -Display on Product Listing page)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileListingPage" CssClass="custom-file-input file-size-validation" data-size="102400" />
                                                    <label class="custom-file-label" for="fileListingPage">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgBanner" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="txtVideoLink">YouTube Video Link</label>
                                                <asp:TextBox ID="txtVideoLink" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtHSN">HSN Code</label>
                                                <asp:TextBox ID="txtHSN" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="drpGST">GST Percent</label>
                                                <asp:DropDownList ID="drpGST" runat="server" ClientIDMode="Static" CssClass="form-control">
                                                    <asp:ListItem></asp:ListItem>
                                                    <asp:ListItem Value="0">0%</asp:ListItem>
                                                    <asp:ListItem Value="5">5%</asp:ListItem>
                                                    <asp:ListItem Value="12">12%</asp:ListItem>
                                                    <asp:ListItem Value="18">18%</asp:ListItem>
                                                    <asp:ListItem Value="28">28%</asp:ListItem>
                                                </asp:DropDownList>
                                            </div>
                                        </div>


                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="drpCountry">Country</label>
                                                <asp:DropDownList ID="drpCountry" runat="server" CssClass="form-control">
                                                    <asp:ListItem></asp:ListItem>
                                                </asp:DropDownList>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
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
                                                    <asp:CheckBox ID="chkShowHome" runat="server" Text="Show this Category on homepage" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="checkbox-container">
                                                    <asp:CheckBox ID="chkActive" runat="server" Text="Live this Category on website" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-5">
                                            <asp:Button ID="btnAdd" runat="server" OnClick="addCategory" CssClass="c-btn c-btn-primary" Text="Add Category"  />

                                            <asp:Button ID="btnUpdate" runat="server" OnClick="updateCategory" CssClass="c-btn c-btn-primary" Text="Save Changes"  />
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

