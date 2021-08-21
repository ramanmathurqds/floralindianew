<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="users.aspx.vb" Inherits="users" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="selfID" runat="server"></asp:Label>
        <asp:Label ID="userID" runat="server"></asp:Label>
        <asp:Label ID="selfRole" runat="server"></asp:Label>
        <asp:Label ID="selfPassword" runat="server"></asp:Label>
    </asp:Panel>

    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Users Lists</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="btnAddUser" runat="server" NavigateUrl="/admin/user-details.aspx?action=new" CssClass="c-btn c-btn-primary d-inline-block text-center" Text="Add User" Visible="false" />
            </div>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <div id="tblAuth" runat="server" visible="false" class="table-responsive">
                    <table id="ListViewTable" class="table table-bordered listing-table">
                        <thead>
                            <tr>
                                <th style="width:30px">Sr.No</th>
                                <th>Name</th>
                                <th style="width:150px">Role</th>
                                <th style="width:100px">Status</th>
                                <th style="width:100px"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvUsers" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%# Container.DataItemIndex + 1 %></td>
                                        <td>
                                            <asp:HyperLink ID="linkPage" runat="server" Text='<%# Eval("firstName") & " " & Eval("lastName") %>' NavigateUrl='<%# "user-details.aspx?action=edit&id=" & Eval("ID") %>'></asp:HyperLink></td>
                                        <td><%# Eval("role") %></td>
                                        <td><%# Eval("status") %></td>
                                        <td>
                                            <asp:LinkButton ID="btnDelete" runat="server" CommandArgument='<%# Eval("ID") %>' CommandName="delete" OnClientClick="return confirm('Are you sure you want to remove this user?')"><span style="color:red"><i class="fas fa-trash"></i> Delete</span></asp:LinkButton>
                                        </td>
                                    </tr>
                                </ItemTemplate>
                            </asp:ListView>
                        </tbody>
                    </table>
                </div>

                <div id="invalidPage" runat="server" class="alert alert-danger" visible="false">
                    <strong>Sorry!</strong> You are not authorised to access this page.
                </div>
            </div>
        </div>
    </div>
</asp:Content>

