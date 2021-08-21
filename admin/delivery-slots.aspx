<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="delivery-slots.aspx.vb" Inherits="admin_delivery_slots" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Delivery Slots</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="AddNew" NavigateUrl="/admin/delivery-slot-details.aspx?action=new" ClientIDMode="Static" runat="server" CssClass="c-btn c-btn-primary" Text="Add New"></asp:HyperLink>
            </div>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <asp:Panel ID="pnlSuccess" runat="server" Visible="false" CssClass="alert alert-success">
                    <asp:Label ID="lblSuccess" runat="server"></asp:Label>
                </asp:Panel>

                <div class="table-responsive">
                    <table  class="table table-hover product-table dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Charges</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvInclusionPrices" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%#Container.DataItemIndex + 1 %></td>
                                        <td><%# Eval("DeliveryName") %></td>
                                        <td><%# Eval("Charges") %></td>
                                        <td><asp:HyperLink ID="btnEdit" NavigateUrl='<%# "/admin/delivery-slot-details.aspx?action=edit&ID=" & Eval("DeliveryID") %>' runat="server" CssClass="btn btn-link text-primary" Text="Edit"></asp:HyperLink> <%--<asp:Button ID="btnDelete" runat="server" CssClass="btn btn-link text-danger" CommandName="DeletePrice" CommandArgument='<%# Eval("ID") %>' OnClientClick="return confirm('Are you sure you want to delete this?')" Text="Delete"></asp:Button>--%>
                                        </td>
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

