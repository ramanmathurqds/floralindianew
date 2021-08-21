<%@ Page Language="VB" AutoEventWireup="false" CodeFile="login.aspx.vb" Inherits="login" %>

<!DOCTYPE html>

<html class="login-body" xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>LOGIN - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css?family=Lexend+Deca&display=swap" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="/Content/admin/assets/css/lib/bootstrap.min.css" rel="stylesheet" />
    <link href="/Content/admin/assets/css/lib/" rel="stylesheet" />
    <link href="/Content/admin/assets/css/admin-style.css" rel="stylesheet" />
    
</head>
<body style="background-color:transparent">
    <form id="form1" runat="server">
        <div class="container-fluid">
            <div class="login-page">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="login-left">
                            <h1 id="quoter" runat="server" style="font-size:40px"></h1>
                            <p class="text-light">- <i id="author" runat="server"></i></p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="login-box" style="height:100vh">
                            <img src="/Content/assets/images/common/floral-logo.png" class="img-logo" />
                            
                            <div class="login-form">
                                <h1 class="">Admin Panel Login</h1>
                                <hr />
                                <div class="form-group text-left">
                                    <label>Username</label>
                                    <asp:TextBox ID="txtUsername" runat="server" CssClass="form-control"></asp:TextBox>
                                </div>

                                <div class="form-group text-left">
                                    <label>Password</label>
                                    <asp:TextBox ID="txtPassword" runat="server" CssClass="form-control" TextMode="Password"></asp:TextBox>
                                </div>

                                <div class="form-group text-left">
                                    <label>Country</label>
                                    <asp:DropDownList ID="drpCountry" runat="server" CssClass="form-control">
                                        <asp:ListItem></asp:ListItem>
                                    </asp:DropDownList>
                                </div>

                                <div class="form-group">
                                    <asp:Button ID="btnLogin" runat="server" Text="Login" CssClass="btn btn-block btn-primary text-center"  />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="/Content/admin/assets/js/lib/jquery-3.3.1.js"></script>
    <script src="/Content/admin/assets/js/lib/bootstrap.js"></script>
    <script src="/Content/admin/assets/js/admin-custom.js"></script>
</body>
</html>
