<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="inclusion-pricing.aspx.vb" Inherits="admin_inclusion_pricing" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
     <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Inclusion Item Pricing</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <button type="button" class="c-btn c-btn-primary" data-toggle="modal" data-target="#inclusionModal">Add New</button>
            </div>
        </div>

         <div class="card-container">
            <div class="ui-card">
                <asp:Panel ID="pnlSuccess" runat="server" Visible="false" CssClass="alert alert-success">
                    <asp:Label ID="lblSuccess" runat="server"></asp:Label>
                </asp:Panel>
                <div class="table-responsive">
                    <table class="table table-hover product-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Cost(Per Pcs)</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvInclusionPrices" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%#Container.DataItemIndex + 1 %></td>
                                        <td><%# Eval("SubcategoryName") %></td>
                                        <td><%# Eval("Cost") %></td>
                                        <td><asp:Button ID="btnEdit" runat="server" CssClass="btn btn-link text-primary" CommandName="Edit" CommandArgument='<%# Eval("ID") %>' Text="Edit"></asp:Button> | <asp:Button ID="btnDelete" runat="server" CssClass="btn btn-link text-danger" CommandName="Delete" CommandArgument='<%# Eval("ID") %>' Text="Delete"></asp:Button></td>
                                    </tr>
                                </ItemTemplate>
                            </asp:ListView>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="inclusionModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Set Inclusion Item Pricing</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
                <asp:Label ID="lblID" runat="server" Visible="false"></asp:Label>
                <div class="form-group">
                    <div class="mb-3">
                        <label class="bold">Select Item</label>
                        <asp:DropDownList ID="drpSubcategory" runat="server" CssClass="form-control">
                            <asp:ListItem></asp:ListItem>
                        </asp:DropDownList>
                        <asp:RequiredFieldValidator ID="rv1" runat="server" CssClass="error" ControlToValidate="drpSubcategory" ErrorMessage="Required"></asp:RequiredFieldValidator>
                    </div>
                </div>

                <div class="form-group">
                    <label>Cost (Per pcs)</label>
                    <asp:TextBox ID="txtCost" runat="server" CssClass="form-control" TextMode="Number"></asp:TextBox>
                    <asp:RequiredFieldValidator ID="rv2" runat="server" CssClass="error" ControlToValidate="txtCost" ErrorMessage="Required"></asp:RequiredFieldValidator>
                </div>

                <asp:Button ID="btnAdd" runat="server" CssClass="btn btn-primary" Text="Save & countinue" />

                <asp:Button ID="btnUpdate" runat="server" CssClass="btn btn-warning" Text="Save & countinue" Visible="false" />
          </div>
        </div>
      </div>
    </div>
</asp:Content>

