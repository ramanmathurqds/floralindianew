<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="promo-codes.aspx.vb" Inherits="promo_codes" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Promo Codes</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="btnAdd" runat="server" NavigateUrl="/admin/promo-code-details.aspx?action=new" CssClass="c-btn c-btn-primary" Text="Add Promo Code" />
            </div>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <div class="table-responsive">
                    <table class="table table-hover product-table dataTable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Discount Percent</th>
                                <th>DiscountType</th>
                                <th>Expiry</th>
                                <th>Country</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvPromoCode" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><asp:HyperLink ID="promoCodeLink" runat="server" NavigateUrl='<%# "/admin/promo-code-details.aspx?action=edit&id=" & Eval("ID") %>' Text='<%# Eval("PromoCode") %>'></asp:HyperLink></td>
                                        <td><%# Eval("DiscountPercent") %></td>
                                        <td><%# Eval("DiscountType") %></td>
                                        <td><%# Eval("PromoExpiry", "{0: MMM dd, yyyy}") %></td>
                                        <td><%# Eval("CountryName") %></td>
                                        <td><asp:Label ID="lblActive" runat="server" Text='<%# Eval("IsActive") %>'></asp:Label></td>
                                    </tr>
                                </ItemTemplate>
                            </asp:ListView>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</asp:Content>

