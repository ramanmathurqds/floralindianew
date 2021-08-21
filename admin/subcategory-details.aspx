<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="subcategory-details.aspx.vb" Inherits="subcategory_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" CssClass="d-none">
        <asp:Label ID="lblIcon" runat="server" ClientIDMode="Static"></asp:Label>
        <asp:Label ID="lblImage" runat="server" ClientIDMode="Static"></asp:Label>
    </asp:Panel>

    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/subcategory.aspx"><i class="fas fa-angle-left"></i>Subcategories</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblSubCatName" runat="server" Text="Add Subcategory"></asp:Label>
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
                                        <label for="txtSubCatName">Category Name</label>
                                        <asp:TextBox ID="txtSubCatName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtSubCatName">Short Text</label>
                                        <asp:TextBox ID="txtShortText" runat="server" MaxLength="25" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Category Icon <small>(Displayed on Category Menu)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileSubCatIcon" CssClass="custom-file-input file-size-validation" data-size="51200" />
                                                    <label class="custom-file-label" for="fileSubCatIcon">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgCat" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Subcategory Image <small>(Displayed on Homepage)</small></label>
                                                <div class="custom-file uploader-wrapper">
                                                    <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileSubCatImage" CssClass="custom-file-input file-size-validation" data-size="102400" />
                                                    <label class="custom-file-label" for="fileSubCatImage">Choose file</label>
                                                </div>
                                                <div class="div-side-image image-wrapper">
                                                    <asp:Image ID="imgSubCat" runat="server" CssClass="side-image file-loader" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="drpCountry">Parent Category</label>
                                                <asp:DropDownList ID="drpCategory" runat="server" CssClass="form-control">
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

                                        <div class="col-12 mt-5">
                                            <div class="form-group">
                                                <div class="checkbox-container">
                                                    <asp:CheckBox ID="chkActive" runat="server" Text="Live this Category on website" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-5">
                                            <asp:Button ID="btnAdd" runat="server" OnClick="addCategory" CssClass="c-btn c-btn-primary" Text="Save"  />

                                            <asp:Button ID="btnUpdate" runat="server" OnClick="updateCategory" CssClass="c-btn c-btn-primary" Text="Save"  />

                                            <asp:Button ID="btnDelete" runat="server" CssClass="c-btn c-btn-danger" Text="Delete" onClientClick=" return confirm('Are you sure you want to delete this?')" OnClick="deleteCategory" />
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

