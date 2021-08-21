<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="promo-code-details.aspx.vb" Inherits="promo_code_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sc1" runat="server"></asp:ScriptManager>
    <div class="admin-page-container promo-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/promo-codes.aspx"><i class="fas fa-angle-left"></i>Promo codes</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblPromoName" runat="server" Text="New Promo code"></asp:Label>
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
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtPromoName">Promo Name</label>
                                                <asp:TextBox ID="txtPromoName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtPromoCode">Promo Code <sup class="super super-required">*</sup></label>
                                                <asp:TextBox ID="txtPromoCode" runat="server" CssClass="form-control" ClientIDMode="Static" placeholder="e.g. FLORAL10"></asp:TextBox>
                                                <asp:RequiredFieldValidator ID="rv1" runat="server" ControlToValidate="txtPromoCode" ErrorMessage="Please enter promo code" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtPercent">Redeem type <sup class="super super-required">*</sup></label>
                                                <asp:DropDownList ID="drpRedeem" runat="server" CssClass="form-control" ClientIDMode="Static">
                                                    <asp:ListItem></asp:ListItem>
                                                    <asp:ListItem Value="amt">Amount</asp:ListItem>
                                                    <asp:ListItem Value="percent">Percent(%)</asp:ListItem>
                                                </asp:DropDownList>
                                                <asp:RequiredFieldValidator ID="RequiredFieldValidator4" runat="server" ControlToValidate="txtPercent" ErrorMessage="Please enter discount percent" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <div id="pnlPercent">
                                                    <label for="txtPercent">Discount Percent <sup class="super super-required">*</sup></label>
                                                    <asp:TextBox ID="txtPercent" runat="server" CssClass="form-control" ClientIDMode="Static" placeholder="e.g. 10" TextMode="Number" MaxLength="2"></asp:TextBox>
                                                    <asp:RequiredFieldValidator ID="RequiredFieldValidator1" runat="server" ControlToValidate="txtPercent" ErrorMessage="Please enter discount percent" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                                </div>

                                                <div id="pnlAmount">
                                                    <label for="txtAmount">Amount Value <sup class="super super-required">*</sup></label>
                                                    <asp:TextBox ID="txtAmount" runat="server" CssClass="form-control" ClientIDMode="Static" placeholder="e.g. 500" TextMode="Number" MaxLength="10"></asp:TextBox>
                                                    <asp:RequiredFieldValidator ID="RequiredFieldValidator7" runat="server" ControlToValidate="txtAmount" ErrorMessage="Please enter discount amount value" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                                </div>
                                            </div>
                                        </div>

                                         <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtMaxAmountLimit">Maximum discount limit in amount <sup class="super super-required">*</sup></label>
                                                <asp:TextBox ID="txtMaxAmountLimit" runat="server" CssClass="form-control" ClientIDMode="Static" placeholder="e.g. 500" TextMode="Number"></asp:TextBox>
                                                <asp:RequiredFieldValidator ID="RequiredFieldValidator2" runat="server" ControlToValidate="txtMaxAmountLimit" ErrorMessage="Please enter maximum amount to redeem" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <asp:UpdatePanel ID="upCountry" runat="server">
                                                <ContentTemplate>
                                                    <div class="form-group">
                                                        <label for="drpCountry">Country <sup class="super super-required">*</sup></label>
                                                        <asp:DropDownList ID="drpCountry" AutoPostBack="true" runat="server" CssClass="form-control discountType">
                                                            <asp:ListItem></asp:ListItem>
                                                            <asp:ListItem Value="1">All</asp:ListItem>
                                                        </asp:DropDownList>
                                                        <asp:RequiredFieldValidator ID="RequiredFieldValidator5" runat="server" ControlToValidate="drpCountry" ErrorMessage="Please select country" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                                    </div>
                                                </ContentTemplate>
                                            </asp:UpdatePanel>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <asp:UpdatePanel ID="upType" runat="server">
                                                <ContentTemplate>
                                                    <div class="form-group">
                                                        <label for="drpDiscountType">Discount Type <sup class="super super-required">*</sup></label>
                                                        <asp:DropDownList ID="drpDiscountType" AutoPostBack="true" runat="server" CssClass="form-control discountType">
                                                            <asp:ListItem></asp:ListItem>
                                                            <asp:ListItem Value="Cart">Cart</asp:ListItem>
                                                            <asp:ListItem Value="Category">Category</asp:ListItem>
                                                        </asp:DropDownList>
                                                        <asp:RequiredFieldValidator ID="RequiredFieldValidator3" runat="server" ControlToValidate="drpDiscountType" ErrorMessage="Please select discount type" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                                    </div>
                                                </ContentTemplate>
                                            </asp:UpdatePanel>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <asp:UpdatePanel ID="upCategory" runat="server">
                                                <ContentTemplate>
                                                    <div class="form-group">
                                                        <label for="drpCategory">Select Category <sup class="super super-required">*</sup></label>
                                                        <asp:DropDownList ID="drpCategory" runat="server" CssClass="form-control discountCategory">
                                                            <asp:ListItem></asp:ListItem>
                                                        </asp:DropDownList>
                                                        <asp:RequiredFieldValidator ID="rvCategory" runat="server" ControlToValidate="drpCategory" ErrorMessage="Please select category" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                                    </div>
                                                </ContentTemplate>
                                            </asp:UpdatePanel>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtExpiryDate">Expiry Date <sup class="super super-required">*</sup></label>
                                                <asp:TextBox ID="txtExpiryDate" runat="server" CssClass="form-control datepicker" ClientIDMode="Static" MaxLength="10"></asp:TextBox>
                                                <asp:RequiredFieldValidator ID="RequiredFieldValidator6" runat="server" ControlToValidate="txtExpiryDate" ErrorMessage="Please enter promo expiry date" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label for="txtUsageLimit">Maximum usage limit</label>
                                                <asp:TextBox ID="txtUsageLimit" runat="server" CssClass="form-control" ClientIDMode="Static" TextMode="Number" placeholder="100"></asp:TextBox>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="checkbox-container">
                                                    <asp:CheckBox ID="chkActive" runat="server" Text="Live this city on website" />
                                                </div>
                                            </div>

                                            <div class="mt-5">
                                                <asp:Button ID="btnAdd" runat="server" CssClass="c-btn c-btn-primary" Text="Save" OnClick="addPromoCode"  />

                                                <asp:Button ID="btnUpdate" runat="server" CssClass="c-btn c-btn-primary" Text="Save" OnClick="updatePromocode"  />

                                                <asp:Button ID="btnDelete" runat="server" CssClass="c-btn c-btn-danger" Text="Delete" onClientClick=" return confirm('Are you sure you want to delete this?')" OnClick="deletePromcode" />
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
    </div>
</asp:Content>

