<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="city-group-details.aspx.vb" Inherits="city_group_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="lblLoginID" runat="server"></asp:Label>
        <asp:Label ID="lblLoginName" runat="server"></asp:Label>
    </asp:Panel>

    <asp:Label ID="lblGroupID" runat="server" CssClass="d-none" ClientIDMode="Static"></asp:Label>
    <div class="admin-page-container city-group-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/city-group.aspx"><i class="fas fa-angle-left"></i>City Group</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblCatName" runat="server" Text="Add City Group"></asp:Label>
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
                                        <label for="txtCityGroupName">City Group Name</label>
                                        <asp:TextBox ID="txtCityGroupName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                    </div>

                                    <div class="form-group">
                                        <label for="drpCountry">Country</label>
                                        <asp:DropDownList ID="drpCountry" AutoPostBack="true" runat="server" ClientIDMode="Static" CssClass="form-control">
                                            <asp:ListItem></asp:ListItem>
                                        </asp:DropDownList>
                                        <input type="hidden" id="selectedCountry" />
                                    </div>

                                    <div class="form-group">
                                        <label for="txtCities">Select City</label>
                                        <asp:TextBox ID="txtCities" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>

                                        <div class="city-alert alert alert-success" style="display:none">
                                            <strong>City added successfully</strong>
                                        </div>

                                        <div class="city-alert-danger alert alert-danger" style="display:none">
                                            <strong>This city already added.</strong>
                                        </div>

                                        <ul class="list-city"></ul>

                                        <!--selected city list-->
                                        <ul class="selectedCity selected-options mt-3">
                                            <asp:ListView ID="lvCity" runat="server" ClientIDMode="Static">
                                                <ItemTemplate>
                                                    <li>
                                                        <asp:Label ID="lblCityID" ClientIDMode="Static" runat="server" ToolTip='<%# Eval("CityID") %>' Text='<%# Eval("CityID") %>'></asp:Label>
                                                        <asp:LinkButton ID="btnRemoveCity" runat="server" CssClass="btn-remove city-remove" CommandArgument='<%# Eval("CityGroupListID") %>' CommandName="removeCity">&#10005;</asp:LinkButton>
                                                    </li>
                                                </ItemTemplate>
                                            </asp:ListView>
                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <div class="checkbox-container">
                                            <asp:CheckBox ID="chkActive" runat="server" Text="Activate this Group" />
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

