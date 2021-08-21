<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="delivery-slot-details.aspx.vb" Inherits="admin_delivery_slots" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sc1" runat="server"></asp:ScriptManager>
     <div class="admin-page-container product-detail-page">
        <asp:UpdatePanel ID="up2" runat="server">
            <ContentTemplate>
                <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/delivery-slots.aspx"><i class="fas fa-angle-left"></i>Delivery slots</a>
                </nav>

                <div class="mt-3">
                    <h1 class="common-title">
                        <asp:Label ID="lblCatName" runat="server" Text="Add New"></asp:Label>
                    </h1>
                </div>

                <div class="mt-3">
                    <div class="row">
                        <asp:Panel ID="pnlSuccess" CssClass="col-12" runat="server" Visible="false">
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Success!</strong>
                                <asp:Label ID="successMessage" runat="server"></asp:Label>
                            </div>
                        </asp:Panel>

                        <div class="col-12 mb-3">
                            <div class="card-container mb-2">
                                <div class="ui-card">
                                    <div class="form-group">
                                        <label for="txtSlotName">Name</label>
                                        <asp:TextBox ID="txtSlotName" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                        <asp:RequiredFieldValidator ID="rv1" runat="server" ControlToValidate="txtSlotName" ErrorMessage="Please enter delivery slot name" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                    </div>

                                    <div class="form-group">
                                        <label>Categories</label>
                                        <div data-simplebar data-simplebar-auto-hide="false" class="checkbox-container fixscroll mt-1">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="chkAll" id="chkFiltersAll" />
                                                            <label for="chkFiltersAll">All</label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <asp:CheckBoxList ID="chKCategories" runat="server" ClientIDMode="Static" CssClass="chkChild chk-filters"></asp:CheckBoxList>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Timeslots</label>
                                        <div class="checkbox-container mt-1">
                                            <ul class="selectedCity selected-options">
                                                <asp:ListView ID="lvTimeslots" runat="server" ClientIDMode="Static">
                                                    <ItemTemplate>
                                                        <li>
                                                            <asp:LinkButton ID="btnTimeSlots" ClientIDMode="Static" runat="server" ToolTip="Click to edit" CommandName="EditSlot" CommandArgument='<%# Eval("ID") %>' Text='<%# Eval("TimeSlot") %>'></asp:LinkButton>
                                                            <asp:LinkButton ID="btnRemoveTImeSlots" runat="server" CssClass="btn-remove option-remove" CommandName="DeleteSlot" CommandArgument='<%# Eval("ID") %>' OnClientClick="return confirm('Are you sure you want to delete this?')" Text="Delete">&#10005;</asp:LinkButton>
                                                        </li>
                                                    </ItemTemplate>
                                                </asp:ListView>

                                                <li>
                                                    <asp:LinkButton ID="btnModalAddNewSlot" runat="server">Add new</asp:LinkButton>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    

                                    <div class="row">
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label>Delivery Charges</label>
                                                <asp:TextBox ID="txtDeliveryCharges" runat="server" ClientIDMode="Static" TextMode="Number" CssClass="form-control"></asp:TextBox>
                                                <asp:RequiredFieldValidator ID="rv3" runat="server" ControlToValidate="txtDeliveryCharges" ErrorMessage="Please enter delivery charges" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label for="txtPosition">Position</label>
                                                <asp:TextBox ID="txtPosition" TextMode="Number" runat="server" CssClass="form-control" ClientIDMode="Static"></asp:TextBox>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="checkbox-container">
                                            <asp:CheckBox ID="chkActive" runat="server" Text="Live this on website" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <asp:Button ID="btnAdd" runat="server" Text="Save & continue" CssClass="c-btn c-btn-primary" />

                                        <asp:Button ID="btnUpdate" runat="server" Text="Save & continue" CssClass="c-btn c-btn-primary" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </ContentTemplate>
        </asp:UpdatePanel>
     </div>

    <div class="modal" id="TimeSlotModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Time Slot</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <asp:UpdatePanel ID="up1" runat="server">
                        <ContentTemplate>
                            <asp:Label ID="lblID" runat="server" Visible="false"></asp:Label>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label class="bold">From</label>
                                            <asp:DropDownList ID="drpFromTime" runat="server" CssClass="form-control" AutoPostBack="true">
                                                <asp:ListItem></asp:ListItem>
                                            </asp:DropDownList>
                                            <asp:RequiredFieldValidator ID="rv0" runat="server" CssClass="error" ControlToValidate="drpFromTime" ErrorMessage="Required" ValidationGroup="modalval"></asp:RequiredFieldValidator>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="mb-3">
                                            <label class="bold">From</label>
                                            <asp:DropDownList ID="drpToTime" runat="server" CssClass="form-control" AutoPostBack="true">
                                                <asp:ListItem></asp:ListItem>
                                            </asp:DropDownList>
                                            <asp:RequiredFieldValidator ID="RequiredFieldValidator1" runat="server" CssClass="error" ControlToValidate="drpToTime" ErrorMessage="Required" ValidationGroup="modalval"></asp:RequiredFieldValidator>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <asp:Button ID="btnAddSlot" runat="server" CssClass="btn btn-primary" Text="Add & continue" ValidationGroup="btnUpdateSlot" OnClick="AddTimeSlot" />

                            <asp:Button ID="btnUpdateSlot" runat="server" CssClass="btn btn-primary" Text="Save & continue" Visible="false" ValidationGroup="modalval" OnClick="UpdateTimeSlot" />
                        </ContentTemplate>

                        <Triggers>
                            <asp:PostBackTrigger ControlID="btnAddSlot" />
                            <asp:PostBackTrigger ControlID="btnUpdateSlot" />
                            <asp:AsyncPostBackTrigger ControlID="btnModalAddNewSlot" />
                        </Triggers>
                    </asp:UpdatePanel>
                </div>
            </div>
        </div>
    </div>
</asp:Content>

