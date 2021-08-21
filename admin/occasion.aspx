<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="occasion.aspx.vb" Inherits="occasion" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="lblCategoryID" runat="server"></asp:Label>
    </asp:Panel>

     <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Occasions</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="btnAdd" runat="server" NavigateUrl="/admin/master-occasion-details.aspx?action=new&typeID=2" CssClass="c-btn c-btn-primary" Text="Add Occasion" />
            </div>
        </div>

         <div class="card-container">
             <div class="ui-card">
                 <div class="table-responsive">
                     <table class="table table-hover product-table dataTable">
                         <thead>
                             <tr>
                                 <th>Name</th>
                                 <th>Start Date</th>
                                 <th>Occasion Date</th>
                                 <th>Country</th>
                                 <th>Status</th>
                             </tr>
                         </thead>

                         <tbody>
                             <asp:ListView  ID="lvOccasions" runat="server">
                                 <ItemTemplate>
                                     <tr>
                                         <td>
                                             <a style="text-decoration: none !important" href='<%# "/admin/master-occasion-details.aspx?action=edit&id=" & Eval("ID") & "&typeID=2" %>'>
                                                 <span class="product-name"><%# Eval("Name") %></span>
                                             </a>
                                         </td>
                                         <td><%# Eval("StartDate", "{0: MMM dd, yyyy}") %></td>
                                         <td><%# Eval("EndDate", "{0: MMM dd, yyyy}") %></td>
                                         <td><%# Eval("CountryCode") %></td>
                                         <td><%# Eval("IsActive") %></td>
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

