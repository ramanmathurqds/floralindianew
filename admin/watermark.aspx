<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="watermark.aspx.vb" Inherits="watermark" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Watermark</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:HyperLink ID="btnAdd" runat="server" NavigateUrl="/admin/watermark-details.aspx?action=new" CssClass="c-btn c-btn-primary" Text="Add Watermark" />
            </div>
        </div>

        <div class="card-container">
             <div class="ui-card">
                 <div class="table-responsive">
                     <table class="table table-hover product-table dataTable">
                         <thead>
                             <tr>
                                 <th>Name</th>
                             </tr>
                         </thead>

                         <tbody>
                             <asp:ListView  ID="lvWatermarks" runat="server">
                                 <ItemTemplate>
                                     <tr>
                                         <td style="width: 300px; text-overflow: ellipsis; white-space: nowrap">
                                             <a style="text-decoration: none !important" href='<%# "/admin/watermark-details.aspx?action=edit&id=" & Eval("WaterMarkID")%>'>
                                                 <img class="product-image" src='<%# Eval("WaterMarkImage") %>' style="width:150px" />
                                                 <span class="product-name"><%# Eval("WaterMarkName") %></span>
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

