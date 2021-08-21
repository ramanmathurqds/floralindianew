<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="watermark-details.aspx.vb" Inherits="watermark_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" CssClass="d-none">
        <asp:Label ID="lblWatermarkImage" runat="server" ClientIDMode="Static"></asp:Label>
    </asp:Panel>

    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/watermark.aspx"><i class="fa fa-angle-left"></i>Watermarks</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblWatermarkName" runat="server" Text="Add Watermark"></asp:Label>
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
                                        <label for="txtWatermarkName">Name</label>
                                        <asp:TextBox ID="txtWatermarkName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="form-group">
                                        <label>Banner Image <small>(Display on Product Listing page)</small></label>
                                        <div class="custom-file uploader-wrapper">
                                            <asp:FileUpload runat="server" ClientIDMode="Static" ID="fileWatermark" CssClass="custom-file-input common-file-uploader" />
                                            <label class="custom-file-label" for="fileWatermark">Choose file</label>
                                        </div>
                                        <div class="div-side-image image-wrapper">
                                            <asp:Image ID="imgWatermark" runat="server" CssClass="side-image file-loader" />
                                        </div>
                                    </div>

                                    <div class="col-12 mt-5">
                                        <asp:Button ID="btnAdd" runat="server" OnClick="addEvent" CssClass="c-btn c-btn-primary" Text="Save" />

                                        <asp:Button ID="btnUpdate" runat="server" OnClick="updateEvent" CssClass="c-btn c-btn-primary" Text="Save" />

                                        <asp:Button ID="btnDelete" runat="server" CssClass="c-btn c-btn-danger" Text="Delete" OnClientClick=" return confirm('Are you sure you want to delete this?')" OnClick="deleteEvent" />
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

