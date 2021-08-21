<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="customers.aspx.vb" Inherits="admin_customers" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Customers</h1>
            </div>

            <%--<div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="btnAdd" runat="server" NavigateUrl="/admin/category-details.aspx?action=new" CssClass="c-btn c-btn-primary" Text="Add Category" />
            </div>--%>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <div class="table-responsive">
                    <table class="table table-hover product-table dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Country Code</th>
                                <th>Position</th>
                                <th>Active</th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvCustomers" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%# Eval("ID") %></td>
                                        <td><%# Eval("UserType") %></td>
                                        <td>
                                            <a style="text-decoration:none !important" href='<%# "/admin/customer-details.aspx?action=edit&id=" & Eval("ID") %>'>
                                                <asp:Image ID="imgUser" runat="server" class="product-image" src='<%# Eval("ProfileImage") %>' />
                                                <span class="product-name"><%# Eval("FirstName") %></span> &nbsp; <span class="product-name"><%# Eval("LastName") %></span>
                                            </a>
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

