<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="updateAutoPriceBulk.aspx.vb" Inherits="admin_updateAutoPriceBulk" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="card-container">
            <div class="ui-card">
                <asp:Button  ID="btnBulkUpdate" runat="server" CssClass="btn btn-primary" Text="Bulk Update" /><br />
                <div class="table-responsive">
                    <table  class="table">
                        <tbody>
                            <tr>
                                <td>ProductID</td>
                                <td>Item Name</td>
                                <td>Final Cost</td>
                            </tr>
                            <asp:ListView ID="lvInclusion" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><asp:Label ID="lblProductID" runat="server" Text='<%# Eval("ProductID") %>'></asp:Label></td>
                                        <td><%# Eval("ItemName") %></td>
                                        <td><asp:label ID="lblFinalCost" runat="server"></asp:label></td>
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

