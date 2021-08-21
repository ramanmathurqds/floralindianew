<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="subcategory.aspx.vb" Inherits="subcategory" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="lblCategoryID" runat="server"></asp:Label>
    </asp:Panel>

     <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Subcategories</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="btnAdd" runat="server" NavigateUrl="/admin/subcategory-details.aspx?action=new" CssClass="c-btn c-btn-primary" Text="Add Subcategory" />
            </div>
        </div>

        <div class="card-container">
                <div class="ui-card">
                    <div class="table-responsive">
                        <table class="table table-hover product-table dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Parent Category</th>
                                    <th>Position</th>
                                    <th>Active</th>
                                </tr>
                            </thead>

                            <tbody>
                                <asp:ListView ID="lvCategories" runat="server">
                                    <ItemTemplate>
                                        <tr>
                                            <td><%# Eval("ProductSubCategoryID") %></td>
                                            <td>
                                                <a style="text-decoration:none !important" href='<%# "/admin/subcategory-details.aspx?action=edit&id=" & Eval("ProductSubCategoryID") %>'>
                                                    <span class="product-name"><%# Eval("ProductSubCategoryName") %></span>
                                                </a>
                                            </td>
                                            <td>
                                                <asp:Label ID="lblParentCategory" runat="server" ToolTip='<%# Eval("ParentCategory") %>'></asp:Label>
                                            </td>
                                            <td><%# Eval("SequenceNo") %></td>
                                            <td><asp:Label ID="lblActive" runat="server" Text='<%# Eval("isActive") %>'></asp:Label></td>
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

