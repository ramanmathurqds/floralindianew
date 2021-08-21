<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="city-group.aspx.vb" Inherits="city_group" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">City Groups</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <button type="button" class="c-btn c-btn-primary" data-toggle="modal" data-target="#modalCountry">Add New Group</button>
            </div>
        </div>

        <div class="card-container">
            <div class="ui-card">
                <div class="table-responsive">
                    <table class="table table-hover product-table dataTable">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Group Name</th>
                                <th>Active</th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvCityGroup" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%#Container.DataItemIndex + 1 %></td>
                                        <td><asp:Hyperlink ID="groupName" runat="server" Text='<%# Eval("CityGroupName").ToString().Trim() %>' NavigateUrl='<%# "/admin/city-group-details.aspx?action=edit&id=" & Eval("CityGroupID") & "&country=" & Eval("CountryCode") %>'></asp:Hyperlink></td>
                                        
                                        <td><asp:Label ID="lblStatus" runat="server" Text='<%# Eval("IsActive") %>'></asp:Label></td>
                                    </tr>
                                </ItemTemplate>
                            </asp:ListView>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCountry">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Country</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Country</label>
                        <asp:DropDownList ID="drpCountry" runat="server" CssClass="form-control"></asp:DropDownList>
                    </div>

                    <div class="form-group">
                        <asp:Button ID="btnAdd" runat="server" CssClass="c-btn c-btn-primary" Text="Continue" />
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
  </div>
</asp:Content>

