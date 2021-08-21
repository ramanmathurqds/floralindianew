<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="city-details.aspx.vb" Inherits="city_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/city.aspx"><i class="fas fa-angle-left"></i>City</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblCatName" runat="server" Text="Add City"></asp:Label>
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
                                        <label for="txtSubCatName">City Name</label>
                                        <asp:TextBox ID="txtCityName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="form-group filters">
                                        <label for="drpStates">Select State</label>
                                        <asp:DropDownList ID="drpStates" runat="server" CssClass="form-control">
                                            <asp:ListItem></asp:ListItem>
                                        </asp:DropDownList>
                                    </div>

                                    <div class="form-group">
                                            <label for="drpCountry">Country</label>
                                            <asp:DropDownList ID="drpCountry" runat="server" CssClass="form-control">
                                                <asp:ListItem></asp:ListItem>
                                            </asp:DropDownList>
                                        </div>

                                        <div class="form-group">
                                            <div class="checkbox-container">
                                                <asp:CheckBox ID="chkActive" runat="server" Text="Live this city on website" />
                                            </div>
                                        </div>

                                        

                                        <div class="mt-5">
                                            <asp:Button ID="btnAdd" runat="server" CssClass="c-btn c-btn-primary" Text="Save"  />

                                            <asp:Button ID="btnUpdate" runat="server" CssClass="c-btn c-btn-primary" Text="Save"  />

                                            <asp:Button ID="btnDelete" runat="server" CssClass="c-btn c-btn-danger" Text="Delete" onClientClick=" return confirm('Are you sure you want to delete this?')" />
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

