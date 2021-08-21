<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="products.aspx.vb" Inherits="products" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sc1" runat="server" EnableCdn="true"></asp:ScriptManager>
    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="lblProductID" runat="server"></asp:Label>
    </asp:Panel>

    <asp:UpdateProgress ID="upp1" runat="server">
        <ProgressTemplate>
            <div class="full-loader-image"></div>
        </ProgressTemplate>
    </asp:UpdateProgress>

    <div class="admin-page-container product-listing-page">
            <div class="row">
                <div class="col-4 col-lg-8">
                    <h1 class="common-title">Products</h1>
                </div>

                <div class="col-8 col-lg-4 text-right">
                    <asp:Button ID="btnAdd" runat="server" CssClass="c-btn c-btn-primary" Text="Add New Product" />
                </div>
            </div>

            <div class="card-container">
                <div class="ui-card">
                    <div class="row">
                       <div class="col-12 col-sm-4">
                           <div class="form-group">
                                <label>Search</label>
                                <asp:TextBox ID="txtSearchQuery" placeholder="Product name or Product code" AutoPostBack="true" runat="server" CssClass="form-control"></asp:TextBox>
                           </div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link product-type" data-name="all" href="/admin/products.aspx?type=all">All Products</a></li>
                        <li class="nav-item"><a class="nav-link product-type" data-name="auto-price" href="/admin/products.aspx?type=auto-price">Auto Priced</a></li>
                        <li class="nav-item"><a class="nav-link product-type" data-name="manual-price" href="/admin/products.aspx?type=manual-price">Manual Priced</a></li>
                    </ul>
                    <br>

                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <label>Category</label>
                                <asp:DropDownList ID="drpCategory" runat="server" CssClass="form-control" AutoPostBack="true">
                                    <asp:ListItem></asp:ListItem>
                                </asp:DropDownList>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <label>Occasion / Festival</label>
                                <asp:DropDownList ID="drpOccasion" runat="server" CssClass="form-control" AutoPostBack="true">
                                    <asp:ListItem></asp:ListItem>
                                </asp:DropDownList>
                            </div>
                        </div>
                    </div>

                    <asp:UpdatePanel ID="up1" runat="server">
                        <ContentTemplate>
                            <div class="form-group">
                                <strong><asp:Label ID="lblItemCount" runat="server"></asp:Label></strong> Products found
                            </div>
                            <div class="table-responsive">
                                <asp:ListView ID="lvProducts" runat="server" GroupPlaceholderID="groupPlaceHolder1" ItemPlaceholderID="itemPlaceHolder1" OnPagePropertiesChanging="OnPagePropertiesChanging">
                                    <LayoutTemplate>
                                        <table class="table table-hover product-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Product</th>
                                                    <th>Product Code</th>
                                                    <th>Price</th>
                                                    <th>Created Date</th>
                                                    <th>Created By</th>
                                                    <th>Last Modified</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <asp:PlaceHolder runat="server" ID="groupPlaceHolder1"></asp:PlaceHolder>
                                        </table>

                                        <div class="datapager">
                                            <asp:DataPager ID="DataPager1" ClientIDMode="Static" runat="server" PageSize="36" PagedControlID="lvProducts" ViewStateMode="Enabled">
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
                                            <tr>
                                                <asp:PlaceHolder runat="server" ID="itemPlaceHolder1"></asp:PlaceHolder>
                                            </tr>
                                        </tbody>
                                    </GroupTemplate>

                                    <ItemTemplate>
                                        <tr>
                                            <td style="width:50px;"><%#Container.DataItemIndex + 1 %>
                                                <asp:Label ID="lblCountryCode" Visible="false" runat="server" Text='<%# Eval("CountryCode") %>'></asp:Label>
                                            </td>
                                            <td style="width:150px; text-overflow:ellipsis; white-space:nowrap">
                                                <a style="text-decoration:none !important" href='<%# "/admin/product-details.aspx?action=edit&id=" & Eval("ProductID") & "&country=" & Eval("CountryCode") & "&saved=false" %>'>
                                                    <img class="product-image" src='<%# Eval("ProductIamge") %>' />
                                                    <span class="product-name"><%# Eval("ProductName") %></span>
                                                </a>
                                            </td>
                                            <td style="width:100px;"><%# Eval("ProductCode") %></td>
                                            <td style="width:100px;"><asp:Label ID="lblCurrency" runat="server"></asp:Label> <%# Eval("Price") %></td>
                                            <td><%# Eval("createdDate", "{0: MMM dd, yyyy}") %></td>
                                            <td><asp:Label ID="lblUser" runat="server" ToolTip='<%# Eval("CreatedBy") %>'></asp:Label></td>
                                            <td><%# Eval("UpdatedDate", "{0: MMM dd, yyyy}") %></td>
                                            <td style="width:100px;"><asp:Label ID="lblActive" runat="server" Text='<%# Eval("IsActive") %>'></asp:Label></td>
                                        </tr>
                                    </ItemTemplate>

                                    <EmptyDataTemplate>
                                        <strong>No Items Found....</strong>
                                    </EmptyDataTemplate>
                                </asp:ListView>
                            </div>
                        </ContentTemplate>

                        <Triggers>
                            <asp:AsyncPostBackTrigger ControlID="drpCategory" />
                            <asp:AsyncPostBackTrigger ControlID="drpOccasion" />
                        </Triggers>
                    </asp:UpdatePanel>
                </div>
            </div>
        </div>
</asp:Content>

