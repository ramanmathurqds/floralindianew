<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="user-details.aspx.vb" Inherits="user_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="lblLoginID" runat="server"></asp:Label>
        <asp:Label ID="lblLoginName" runat="server"></asp:Label>
    </asp:Panel>

    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/users.aspx"><i class="fas fa-angle-left"></i>Users</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblCatName" runat="server" Text="Add User"></asp:Label>
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
                                    <div id="isAuth" runat="server" visible="false">
                                        <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtFirstName">First Name</label>
                                                <asp:TextBox ID="txtFirstName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtLastName">Last Name</label>
                                                <asp:TextBox ID="txtLastName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtRolde">Role</label>
                                                <asp:DropDownList ID="drpRole" runat="server" ClientIDMode="Static" CssClass="form-control">
                                                    <asp:ListItem Value="lvl0">Admin</asp:ListItem>
                                                    <asp:ListItem Value="lvl1">Staff Level 1</asp:ListItem>
                                                    <asp:ListItem Value="lvl2">Staff Level 2</asp:ListItem>
                                                </asp:DropDownList>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtUseraname">Username / Email</label>
                                                <asp:TextBox ID="txtUsename" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtPassword">Password</label>
                                                <asp:TextBox ID="txtPassword" TextMode="Password" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                                <asp:Label ID="lblPassword" runat="server" Visible="false"></asp:Label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="checkbox-container">
                                                    <asp:CheckBox ID="chkActive" runat="server" Text="Active this user" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-5 col-12 d-none">
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label>Site Management</label>
                                                    </div>
                                                </div>

                                                <asp:CheckBoxList ID="chkSiteManager" runat="server" CssClass="col-12 col-md-8 chk-permission" RepeatDirection="Horizontal">
                                                    <asp:ListItem Value="1V">View</asp:ListItem>
                                                    <asp:ListItem Value="1C">Create</asp:ListItem>
                                                    <asp:ListItem Value="1U">Update</asp:ListItem>
                                                    <asp:ListItem Value="1D">Delete</asp:ListItem>
                                                    <asp:ListItem Value="1A">All</asp:ListItem>
                                                </asp:CheckBoxList>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label>Product Management</label>
                                                    </div>
                                                </div>

                                                <asp:CheckBoxList ID="chkProductManager" runat="server" CssClass="col-12 col-md-8 chk-permission" RepeatDirection="Horizontal">
                                                    <asp:ListItem Value="2V">View</asp:ListItem>
                                                    <asp:ListItem Value="2C">Create</asp:ListItem>
                                                    <asp:ListItem Value="2U">Update</asp:ListItem>
                                                    <asp:ListItem Value="2D">Delete</asp:ListItem>
                                                    <asp:ListItem Value="2A">All</asp:ListItem>
                                                </asp:CheckBoxList>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label>Category & Other Tags</label>
                                                    </div>
                                                </div>

                                                <asp:CheckBoxList ID="chkTagsManager" runat="server" CssClass="col-12 col-md-8 chk-permission" RepeatDirection="Horizontal">
                                                    <asp:ListItem Value="3V">View</asp:ListItem>
                                                    <asp:ListItem Value="3C">Create</asp:ListItem>
                                                    <asp:ListItem Value="3U">Update</asp:ListItem>
                                                    <asp:ListItem Value="3D">Delete</asp:ListItem>
                                                    <asp:ListItem Value="3A">All</asp:ListItem>
                                                </asp:CheckBoxList>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label>Order Manager</label>
                                                    </div>
                                                </div>

                                                <asp:CheckBoxList ID="chkOrderManager" runat="server" CssClass="col-12 col-md-8 chk-permission" RepeatDirection="Horizontal">
                                                    <asp:ListItem Value="4V">View</asp:ListItem>
                                                    <asp:ListItem Value="4C" Enabled="false">Create</asp:ListItem>
                                                    <asp:ListItem Value="4U">Update</asp:ListItem>
                                                    <asp:ListItem Value="4D">Delete</asp:ListItem>
                                                    <asp:ListItem Value="4A">All</asp:ListItem>
                                                </asp:CheckBoxList>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label>User Manager</label>
                                                    </div>
                                                </div>

                                                <asp:CheckBoxList ID="chkUserManager" runat="server" CssClass="col-12 col-md-8 chk-permission" RepeatDirection="Horizontal">
                                                    <asp:ListItem Value="5V">View</asp:ListItem>
                                                    <asp:ListItem Value="5C">Create</asp:ListItem>
                                                    <asp:ListItem Value="5U">Update</asp:ListItem>
                                                    <asp:ListItem Value="5D">Delete</asp:ListItem>
                                                    <asp:ListItem Value="5A">All</asp:ListItem>
                                                </asp:CheckBoxList>
                                            </div>
                                        </div>
                                    </div>

                                        <div class="mt-5">
                                            <asp:Button ID="btnAdd" runat="server" Visible="false" CssClass="c-btn c-btn-primary" Text="Save" />

                                            <asp:Button ID="btnUpdate" runat="server" Visible="false" CssClass="c-btn c-btn-primary" Text="Save" />

                                            <asp:Button ID="btnDelete" runat="server" Visible="false" CssClass="c-btn c-btn-danger" Text="Delete" OnClientClick=" return confirm('Are you sure you want to delete this?')" />
                                        </div>
                                    </div>

                                    <div id="invalidPage" runat="server" class="alert alert-danger" visible="false">
                                        <strong>Sorry!</strong> You are not authorised to access this page.
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

