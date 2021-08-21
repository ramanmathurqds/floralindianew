<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="inclusion-pricing.aspx.vb" Inherits="admin_inclusion_pricing" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sc1" runat="server"></asp:ScriptManager>
    <div class="admin-page-container">
        <div class="row">
            <div class="col-4 col-lg-8">
                <h1 class="common-title">Inclusion Item Pricing</h1>
            </div>

            <div class="col-8 col-lg-4 text-right">
                <asp:Button ID="btnAddNew" ClientIDMode="Static" runat="server" CssClass="c-btn c-btn-primary" Text="Add New"></asp:Button>
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
                                <th>Category</th>
                                <th>Cost(Per Pcs)</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvInclusionPrices" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><%#Container.DataItemIndex + 1 %></td>
                                        <td><%# Eval("SubcategoryName") %> <asp:Label ID="CategoryID" runat="server" Visible="false" Text='<%# Eval("CategoryID") %>'></asp:Label></td>
                                        <td><asp:Label ID="categoryName" runat="server"></asp:Label></td>
                                        <td><%# Eval("Cost") %></td>
                                        <td><asp:Button ID="btnEdit" runat="server" CssClass="btn btn-link text-primary" CommandName="UpdatePrice" CommandArgument='<%# Eval("ID") %>' Text="Edit"></asp:Button> | <asp:Button ID="btnDelete" runat="server" CssClass="btn btn-link text-danger" CommandName="DeletePrice" CommandArgument='<%# Eval("ID") %>' OnClientClick="return confirm('Are you sure you want to delete this?')" Text="Delete"></asp:Button></td>
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
                <asp:UpdatePanel ID="up1" runat="server">
                    <ContentTemplate>
                        <asp:Label ID="lblID" runat="server" Visible="false"></asp:Label>
                <div class="frm-group d-none">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>ID</td>
                            </tr>
                        </thead>

                        <tbody>
                            <asp:ListView ID="lvIncluionProducts" runat="server">
                                <ItemTemplate>
                                    <tr>
                                        <td><asp:Label ID="lblProductID" runat="server" Text='<%# Eval("ProductID") %>'></asp:Label></td>
                                    </tr>
                                </ItemTemplate>
                            </asp:ListView>
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <div class="mb-3">
                        <label class="bold">Select Category</label>
                        <asp:DropDownList ID="drpCategory" runat="server" CssClass="form-control" AutoPostBack="true">
                            <asp:ListItem></asp:ListItem>
                        </asp:DropDownList>
                        <asp:RequiredFieldValidator ID="rv0" runat="server" CssClass="error" ControlToValidate="drpCategory" ErrorMessage="Required" ValidationGroup="modalval"></asp:RequiredFieldValidator>
                    </div>
                </div>

                <div class="form-group">
                    <div class="mb-3">
                        <label class="bold">Select Subcategory</label>
                        <asp:DropDownList ID="drpSubcategory" runat="server" CssClass="form-control">
                            <asp:ListItem></asp:ListItem>
                        </asp:DropDownList>
                        <asp:RequiredFieldValidator ID="rv1" runat="server" CssClass="error" ControlToValidate="drpSubcategory" ErrorMessage="Required" ValidationGroup="modalval"></asp:RequiredFieldValidator>
                    </div>
                </div>

                <div class="form-group">
                    <label>Cost (Per pcs)</label>
                    <asp:TextBox ID="txtCost" runat="server" CssClass="form-control" TextMode="Number"></asp:TextBox>
                    <asp:RequiredFieldValidator ID="rv2" runat="server" CssClass="error" ControlToValidate="txtCost" ErrorMessage="Required" ValidationGroup="modalval"></asp:RequiredFieldValidator>
                </div>

                <asp:Button ID="btnAdd" runat="server" CssClass="btn btn-primary" Text="Add & continue" ValidationGroup="modalval" />

                <asp:Button ID="btnUpdate" runat="server" CssClass="btn btn-primary" Text="Save & continue" Visible="false" ValidationGroup="modalval" />
                    </ContentTemplate>

                    <Triggers>
                        <asp:PostBackTrigger ControlID="btnAdd" />
                        <asp:PostBackTrigger ControlID="btnUpdate" />
                        <asp:AsyncPostBackTrigger ControlID="btnAddNew" />
                    </Triggers>
                </asp:UpdatePanel>
          </div>
        </div>
      </div>
    </div>
</asp:Content>

