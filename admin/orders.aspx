<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="orders.aspx.vb" Inherits="admin_orders" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sc1" runat="server"></asp:ScriptManager>
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Orders</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <%--<button type="button" class="c-btn c-btn-primary" data-toggle="modal" data-target="#modalCountry">Add New Product</button>--%>
            </div>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <asp:UpdatePanel ID="up1" runat="server">
                    <ContentTemplate>
                        <div class="row">
                            <!-- Custom Filter -->
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label>Search by Order ID</label>
                                    <asp:TextBox ID="txtOrderID" AutoPostBack="true" CssClass="form-control filter-by-country" runat="server" ClientIDMode="Static"></asp:TextBox>
                                </div>
                            </div>

                            <div class="col-12 col-sm-2">
                                <div class="form-group d-none">
                                    <label>Filter by country</label>
                                    <asp:DropDownList ID="searchByCountry" CssClass="form-control filter-by-country" runat="server" ClientIDMode="Static">
                                        <asp:ListItem></asp:ListItem>
                                    </asp:DropDownList>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                                <tbody>
                                    <asp:ListView ID="lvOrders" runat="server" GroupPlaceholderID="groupPlaceHolder1" ItemPlaceholderID="itemPlaceHolder1" OnPagePropertiesChanging="OnPagePropertiesChanging">
                                        <LayoutTemplate>
                                            <table class="table table-hover order-table table-sm compact-table">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Date</th>
                                                        <th>Delivery by</th>
                                                        <th>Customer</th>
                                                        <th>Total</th>
                                                        <th>Fulfillment</th>
                                                    </tr>
                                                </thead>
                                                <asp:PlaceHolder runat="server" ID="groupPlaceHolder1"></asp:PlaceHolder>
                                            </table>

                                            <div class="datapager">
                                                <asp:DataPager ID="DataPager1" ClientIDMode="Static" runat="server" PageSize="36" PagedControlID="lvOrders" ViewStateMode="Enabled">
                                                    <Fields>
                                                        <asp:NextPreviousPagerField ButtonType="Link" ShowFirstPageButton="false" ShowPreviousPageButton="True" ShowNextPageButton="false" ButtonCssClass="nextPre" RenderNonBreakingSpacesBetweenControls="false" />
                                                        <asp:NumericPagerField ButtonType="Link" ButtonCount="10" RenderNonBreakingSpacesBetweenControls="false" />

                                                        <asp:NextPreviousPagerField ButtonType="Link" ShowNextPageButton="true" ShowLastPageButton="false" ShowPreviousPageButton="false" ButtonCssClass="nextPre" RenderNonBreakingSpacesBetweenControls="false" />
                                                    </Fields>
                                                </asp:DataPager>
                                                <div class="clear"></div>
                                            </div>
                                        </LayoutTemplate>

                                        <GroupTemplate>
                                            <tbody>
                                                <tr class="order-link pointer">
                                                    <asp:PlaceHolder runat="server" ID="itemPlaceHolder1"></asp:PlaceHolder>
                                                </tr>
                                            </tbody>
                                        </GroupTemplate>

                                        <ItemTemplate>
                                            <td class="order-id"><asp:Label ID="lblOrderID" runat="server" Text='<%# Eval("OrderID") %>'></asp:Label></td>
                                            <td><%# Eval("CreatedDate", "{0: MMM dd, yyyy}") %></td>
                                            <td><%# Eval("DeliveryDate", "{0: MMM dd, yyyy}") %></td>
                                            <td class="text-capitalize"><asp:Label ID="customerID" runat="server" Text='<%# Eval("UserID") %>'></asp:Label></td>
                                            <%--<td><%# Val(Eval("ProductPrice")) + Val(Eval("ProductSizePrice")) + Val(Eval("ShippingChrg")) + Val(Eval("PackingChrg")) %></td>--%>
                                            <td>
                                                <asp:Label ID="lblCustomerPaid" runat="server"></asp:Label>
                                            </td>
                                            <td><%# Eval("LastTrackingStatus") %></td>
                                        </ItemTemplate>

                                        <EmptyDataTemplate>
                                            <strong>No Items Found....</strong>
                                        </EmptyDataTemplate>
                                    </asp:ListView>
                                </tbody>
                            </table>
                        </div>
                    </ContentTemplate>
                </asp:UpdatePanel>
            </div>
        </div>
    </div>
</asp:Content>

