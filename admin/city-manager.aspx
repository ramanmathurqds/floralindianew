<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="city-manager.aspx.vb" Inherits="city_manager" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-6">
                <h1 class="common-title">City Lists</h1>
            </div>

            <div class="col-8 col-lg-6 text-right">
                <asp:HyperLink ID="btnAdd" runat="server" NavigateUrl="/admin/city-details.aspx?action=new" CssClass="c-btn c-btn-primary d-inline-block text-center" Text="Add City" />

                <asp:HyperLink ID="btnCityGroup" runat="server" NavigateUrl="/admin/city-group.aspx" CssClass="c-btn c-btn-danger d-inline-block text-center">City Group</asp:HyperLink>
            </div>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <div class="table-responsive">
                    <table class="table table-hover product-table dataTable">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>City</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvCities" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%#Container.DataItemIndex + 1 %></td>
                                        <td><asp:Hyperlink ID="lblCityName" runat="server" Text='<%# Eval("CityName").ToString().Trim() %>' NavigateUrl='<%# "/admin/city-details.aspx?action=edit&id=" & Eval("CityID") %>'></asp:Hyperlink></td>
                                        
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

