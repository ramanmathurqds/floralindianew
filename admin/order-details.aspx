<%@ Page Title="" Language="VB" MasterPageFile="~/admin/MasterPage.master" AutoEventWireup="false" CodeFile="order-details.aspx.vb" Inherits="admin_order_details" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sc1" runat="server" EnableCdn="true"></asp:ScriptManager>

    <asp:Panel ID="pnlHidden" runat="server" Visible="false">
        <asp:Label ID="customerID" runat="server"></asp:Label>
        <asp:Label ID="TransactionID" runat="server"></asp:Label>
        <asp:Label ID="lblCode" runat="server"></asp:Label>
    </asp:Panel>

    <asp:Label ID="lblCurrency" runat="server" CssClass="d-none payment-currency"></asp:Label>
    <div class="admin-page-container order-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/orders.aspx?type=all"><i class="fas fa-angle-left"></i>Orders</a>
                </nav>

                <div class="row">
                    <div class="col-12 col-lg-8 mt-2">
                        <h1 class="common-title">
                            <asp:Label ID="lblOrderID" runat="server" Text="Order ID"></asp:Label>
                        </h1>
                        <div><asp:Label ID="lblOrderDate" runat="server"></asp:Label></div>
                    </div>

                    <div class="col-12 col-lg-4 mt-2 text-right">
                        <div class="dropdown">
                          <asp:LinkButton ID="btnOrderAction" runat="server" OnClientClick="return false" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            Confirm this order
                          </asp:LinkButton>
                          <div class="dropdown-menu">
                            <asp:LinkButton ID="btnConfirmOrder" OnClick="ConfirmOrder" CssClass="dropdown-item" runat="server" Text="Confirm Order"></asp:LinkButton>

                              <!--<asp:LinkButton ID="btnMoveToP3" OnClientClick="return false" CssClass="dropdown-item" runat="server" Text="Set Pickup" Visible="false"></asp:LinkButton>

                              <asp:LinkButton ID="btnMoveToP4" OnClientClick="return false" CssClass="dropdown-item" runat="server" Text="Set to On the way" Visible="false"></asp:LinkButton>

                              <asp:LinkButton ID="btnMoveToP5" OnClientClick="return false" CssClass="dropdown-item" runat="server" Text="Delivered & Close" Visible="false"></asp:LinkButton>

                              <asp:LinkButton ID="btnMoveToP6" OnClientClick="return false" runat="server" CSSClass="dropdown-item pointer" data-toggle="modal" data-target="#modalCancelOrder">Cancel Order</asp:LinkButton>-->
                          </div>
                        </div>
                    </div>

                    <asp:Panel ID="pnlSuccess" CssClass="col-12" runat="server" Visible="false">
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Success!</strong>
                            <asp:Label ID="successMessage" runat="server"></asp:Label>
                        </div>
                    </asp:Panel>

                    <div class="col-12 col-lg-8 mb-3">
                        <div class="card-container mb-2">
                            <div class="ui-card">
                                <div class="card-title bold">Order Summary</div>
                                <hr />
                                <asp:ListView ID="lvItems" runat="server">
                                    <ItemTemplate>
                                        <div class="row order-items">
                                            <div class="col-12">
                                                <asp:Panel ID="pnlNotDelivered" runat="server">
                                                    <h4 class="small-heading text-danger mb-3">Delivery by <asp:Label ID="lblDeliveryDate" runat="server" Text='<%# Eval("DeliveryDate", "{0: dd MMM, yyyy}") %>'></asp:Label>  - <asp:Label ID="lblDeliveryTimeSlot" runat="server" Text='<%#Eval("DeliveryTimeSlot") %>'></asp:Label>
                                                    <span class="d-none delivery-time-slot"><%# Eval("DeliveryTimeSlot") %></span>
                                                    <span class="d-none delivery-date"><%# Eval("DeliveryDate", "{0: MM dd, yyyy}")%></span>&nbsp;(<span class="countdown-timer"></span>)
                                                    </h4>
                                                </asp:Panel>

                                                <asp:Panel ID="pnlDelivered" runat="server" Visible="false">
                                                    <h4 class="small-heading text-success mb-3">
                                                        Delivered
                                                    </h4>
                                                </asp:Panel>
                                                <div class="alert alert-info shipping-address">
                                                    <strong>Shipping Address : </strong>
                                                    <asp:Label ID="lblDeliveryName" runat="server" Text='<%# Eval("DeliveryName") %>'></asp:Label>
                                                    <asp:Label ID="lblDeliveryAddress" runat="server" Text='<%# Eval("ShippingAddress") %>'></asp:Label>
                                                    <asp:Label ID="lblDeliveryContact" runat="server" Text='<%# Eval("DeliveryContact") %>'></asp:Label>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-8">
                                                <div class="order-product-image">
                                                    <asp:Image ID="imgProduct" runat="server" ImageUrl='<%# Eval("ProductImage") %>' />
                                                    <asp:Label ID="ProductID" runat="server" Visible="false" Text='<%# Eval("ProductID") %>'></asp:Label>
                                                </div>

                                                <div class="product-name-details">
                                                    <asp:HyperLink ID="productLink" runat="server" NavigateUrl='<%# "/admin/product-details.aspx?action=edit&country=IN&saved=none&id=" & Eval("ProductID") %>' Text='<%# Eval("ProductName") %>' target="_blank"></asp:HyperLink>
                                                    <p><%# "Size : " & Eval("size") & " | " & Eval("Feature") & " . " & Eval("Type") %></p>
                                                </div>
                                            </div>

                                            <div class="col-6 col-lg-2">
                                                <span class="order-item-pricing">
                                                    <%# ((Val(Eval("ProductPrice")) + Val(Eval("ProductSizePrice"))) / Eval("ProductQty")) & " x " & Eval("ProductQty")  %> 
                                                </span>
                                            </div>

                                            <div class="col-6 col-lg-2">
                                                <span class="order-item-pricing">
                                                    <asp:Label ID="lblItemSubtotal" runat="server" Text='<%# Val(Eval("ProductPrice") + Val(Eval("ProductSizePrice"))) %>' Visible="false"></asp:Label>
                                                    <asp:Label ID="lblItemQty" runat="server" Text='<%# Eval("ProductQty") %>' Visible="false"></asp:Label>
                                                    <asp:Label ID="lblItemPackingTotal" runat="server" Text='<%# Eval("PackingChrg") %>' Visible="false"></asp:Label>
                                                    <asp:Label ID="lblItemShippingTotal" runat="server" Text='<%# Eval("ShippingChrg") %>' Visible="false"></asp:Label>
                                                    <asp:Label ID="lblItemGSTPercent" runat="server" Text='<%# Eval("GSTPercent") %>' Visible="false"></asp:Label>
                                                    <asp:Label ID="lblItemGstCharge" runat="server"></asp:Label>

                                                    <span class="webCurrency"></span> <%# ((Val(Eval("ProductPrice")) + Val(Eval("ProductSizePrice"))) * Eval("ProductQty")) %>
                                                </span>
                                            </div>

                                            <div class="col-12">
                                                <p>Sender : <%# Eval("SenderName") %></p>
                                                <p>Message : <asp:Label ID="lblItemMessage" runat="server" Text='<%# Eval("	SenderMessage") %>'></asp:Label></p>
                                            </div>

                                            <div class="col-12">
                                                <div class="row">
						                            <div class="col-12 hh-grayBox pb20">
							                            <div class="row justify-content-between">
								                            <asp:Panel ID="P1" runat="server" >
									                            <span class="is-complete"></span>
									                            <p><asp:Label ID="P1Title" runat="server" Text="Order Placed"></asp:Label><br><asp:label ID="P1Date" runat="server"></asp:label></p>
								                            </asp:Panel>

                                                            <asp:Panel ID="P2" runat="server" >
									                            <asp:LinkButton ID="btnSetConfirm" runat="server" CommandName="btnConfirm" CommandArgument='<%# Eval("ProductID") %>' CssClass="is-complete pointer"></asp:LinkButton>
									                            <p><asp:Label ID="P2Title" runat="server" Text="Order Confirmed"></asp:Label><br><asp:label ID="P2Date" runat="server"></asp:label></p>
								                            </asp:Panel>

                                                            <asp:Panel ID="P3" runat="server">
									                            <asp:LinkButton ID="btnSetPickup" runat="server" CommandName="btnPickup" CommandArgument='<%# Eval("ProductID") %>' CssClass="is-complete pointer"></asp:LinkButton>
									                            <p><asp:Label ID="P3Title" runat="server" Text="Enroute"></asp:Label><br><asp:label ID="P3Date" runat="server"></asp:label></p>
								                            </asp:Panel>

								                            <asp:Panel ID="P4" runat="server">
									                            <asp:LinkButton ID="btnSetOnTheWay" runat="server" CommandName="btnOntheway" CommandArgument='<%# Eval("ProductID") %>' CssClass="is-complete pointer"></asp:LinkButton>
									                            <p><asp:Label ID="P4Title" runat="server" Text="On the way"></asp:Label><br><asp:label ID="P4Date" runat="server"></asp:label></p>
								                            </asp:Panel>

								                            <asp:Panel ID="P5" runat="server">
									                            <asp:LinkButton ID="btnSetDelivered" runat="server" CommandName="btnDelivered" CommandArgument='<%# Eval("ProductID") %>' CssClass="is-complete pointer"></asp:LinkButton>
									                           <p><asp:Label ID="P5Title" runat="server" Text="Delivered"></asp:Label><br><asp:label ID="P5Date" runat="server"></asp:label></p>
								                            </asp:Panel>
							                            </div>
						                            </div>

                                                    <div class="col-12 text-center">
                                                        <p class="mt-4"><asp:LinkButton ID="btnCancelSingleOrder" runat="server" CommandArgument='<%# Eval("ProductID") %>' CommandName="SingleItemCancel" CssClass="btn btn-danger">Cancel Order Item</asp:LinkButton></p>
                                                    </div>
					                            </div>

                                                <table class="d-none">
                                                    <asp:ListView ID="lvTrackingHistory" runat="server" Visible="false">
                                                        <ItemTemplate>
                                                            <tr data-step='<%# "P" & Container.DataItemIndex + 1 %>'>
                                                                <td><asp:Label ID="TrackingSubject" runat="server" Text='<%# Eval("TrackingSubject") %>'></asp:Label></td>
                                                                <td><asp:Label ID="TrackingDate" runat="server" Text='<%# Eval("TrackingDate", "{0:dd MMM, yyyy}") %>'></asp:Label></td>
                                                            </tr>
                                                        </ItemTemplate>
                                                    </asp:ListView>
                                                </table>
                                            </div>
                                        </div>
                                        <hr />
                                    </ItemTemplate>
                                </asp:ListView>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card-container mb-2">
                            <div class="ui-card">
                                <div class="card-title bold">Customer Info</div>
                                <hr />
                                <div class="text-capitalize customer-info">
                                    <strong>Email :- </strong> <asp:Label ID="lblCustomerEmail" runat="server"></asp:Label><br />
                                <strong>Name :- </strong> <asp:Label ID="lblName" runat="server"></asp:Label><br />
                                <strong>Phone :- </strong> <asp:LinkButton ID="lblPhone" OnClick="smsModal" runat="server"></asp:LinkButton>
                                </div>
                            </div>
                        </div>

                        <div class="card-container mb-2">
                            <div class="ui-card">
                                <div class="card-title bold">Billing Address</div>
                                <hr />
                                <div class="text-capitalize customer-info">
                                    <asp:Label ID="lblBillingAddress" runat="server"></asp:Label>
                                </div>
                            </div>
                        </div>

                        <div class="card-container mb-2">
                            <div class="ui-card">
                                <div class="card-title bold">Payment Summary</div>
                                <hr />

                                <table class="payment-summary-table">
                                    <tr>
                                        <td class="w-50">Subtotal</td>
                                        <td class="text-right w-50"><span class="webCurrency"></span> <asp:Label ID="lblOrderSubtotal" runat="server"></asp:Label></td>
                                    </tr>

                                    <tr>
                                        <td class="w-50">Packing Charges</td>
                                        <td class="text-right w-50"><span class="webCurrency"></span> (+)<asp:Label ID="lblPackingTotal" runat="server"></asp:Label></td>
                                    </tr>

                                    <tr>
                                        <td class="w-50">Packing Charges</td>
                                        <td class="text-right w-50"><span class="webCurrency"></span> (-)<asp:Label ID="lblOrderDiscount" runat="server"></asp:Label></td>
                                    </tr>

                                    <tr>
                                        <td class="w-50">Shipping</td>
                                        <td class="text-right w-50"><span class="webCurrency"></span> (+)<asp:Label ID="lblOrderShippingTotal" runat="server"></asp:Label></td>
                                    </tr>

                                    <tr>
                                        <td class="w-50">GST</td>
                                        <td class="text-right w-50"><span class="webCurrency"></span> (-)<asp:Label ID="lblTotalGST" runat="server"></asp:Label></td>
                                    </tr>

                                    <tr>
                                        <td class="w-50">Grand Total</td>
                                        <td class="text-right w-50"><span class="webCurrency"></span> <asp:Label ID="lblGrandTotal" runat="server"></asp:Label></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalCancelOrder">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cancel Order</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
              <asp:Label ID="cancelProductID" runat="server" Visible="false"></asp:Label>
              <div class="alert alert-danger"><strong>You would need to refund <span class="webCurrency"></span> <asp:Label ID="lblOrderValue" runat="server"></asp:Label></strong></div>

               <div class="form-group">
                   <label class="bold">Reason to cancel</label>
                   <asp:TextBox ID="txtCancelReason" runat="server" CssClass="form-control" TextMode="MultiLine" Rows="5"></asp:TextBox>
               </div>

              <asp:Button ID="btnCancelOrder" runat="server" CssClass="btn btn-danger" Text="Proceed to cancel" />
          </div>
        </div>
      </div>
    </div>

    <!--SMS to customer-->
    <div class="modal" id="modalSMSCustomer">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Notify Customer</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
             <asp:UpdatePanel ID="upCustomerSMS" runat="server">
                 <ContentTemplate>
                     <asp:Panel ID="pnlSmsSuccess" runat="server" Visible="false" CssClass="alert alert-success">
                         <strong>SMS Notification sent to customer</strong>
                     </asp:Panel>
                      <div class="form-group">
                          <label class="bold">Send to</label>
                          <asp:TextBox ID="txtMobileNumber" runat="server" CssClass="form-control"></asp:TextBox>
                      </div>     

                       <div class="form-group">
                           <label class="bold">Reason to cancel</label>
                           <asp:TextBox ID="txtSmsContent" runat="server" CssClass="form-control" TextMode="MultiLine" Rows="5"></asp:TextBox>
                       </div>

                      <asp:Button ID="btnSendMessage" runat="server" CssClass="btn btn-danger" Text="Send Message" />
                 </ContentTemplate>
             </asp:UpdatePanel>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalConfirmOrder">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Please enter vendor email address</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                 </div>

                <div class="modal-body">
                    <asp:UpdatePanel ID="upConfirm" runat="server">
                        <ContentTemplate>
                            <div class="form-group">
                                <div class="mb-3">
                                   <label class="bold"></label>
                                   <asp:TextBox ID="txtVendorEmail" runat="server" CssClass="form-control"></asp:TextBox>
                                   <asp:RequiredFieldValidator ID="rv2" runat="server" ErrorMessage="Please enter vendor email" CssClass="text-danger inline-block" ControlToValidate="txtVendorEmail" ValidationGroup="vendorValidation" Display="Dynamic"></asp:RequiredFieldValidator>

                                    <asp:RegularExpressionValidator ID="regexEmailValid" runat="server" ValidationExpression="\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*" ControlToValidate="txtVendorEmail" ErrorMessage="Invalid Email Address" CssClass="text-danger inline-block" ValidationGroup="vendorValidation" Display="Dynamic"></asp:RegularExpressionValidator>

                                    <div class="checkbox-container">
                                        <asp:CheckBox ID="chkFloralEmail" AutoPostBack="true" ClientIDMode="Static" runat="server" Text="Use default vendor(info@floralindia.com)" />
                                    </div>
                               </div>

                                <asp:Button ID="btnSendToVendor" runat="server" CssClass="btn btn-primary" OnClick="ConfirmSingleItemInOrder" Text="Forward to vendor" ValidationGroup="vendorValidation" />
                            </div>
                        </ContentTemplate>

                        <Triggers>
                            <asp:PostBackTrigger ControlID="btnSendToVendor" />
                        </Triggers>
                    </asp:UpdatePanel>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalSetPickup">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Set Pick up</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
              <asp:UpdatePanel ID="upModalPickup" runat="server">
                  <ContentTemplate>
                      <asp:Label ID="lblPickupProductID" runat="server" Visible="false"></asp:Label>
                      <asp:Label ID="lblProductName" runat="server" Visible="false"></asp:Label>
                      <div class="form-group">
                           <div class="mb-3">
                               <label class="bold">Select Delivery Person</label>
                               <asp:DropDownList ID="drpPickupMen" AutoPostBack="true" runat="server" CssClass="form-control">
                                   <asp:ListItem></asp:ListItem>
                               </asp:DropDownList>
                               <asp:Label ID="lblDeliveryMenContact" runat="server" Visible="false"></asp:Label>
                           </div>

                           <p>Or</p>

                           <div>
                               <label class="bold">Select Carrier</label>
                               <asp:DropDownList ID="drpCarrier" runat="server" CssClass="form-control">
                                   <asp:ListItem></asp:ListItem>
                               </asp:DropDownList>
                           </div>
                       </div>

                      <div class="form-group">
                          <label>AWB No</label>
                          <asp:TextBox ID="txtAwbNo" runat="server" CssClass="form-control"></asp:TextBox>
                      </div>

                      <asp:Button ID="btnInsertPickup" runat="server" CssClass="btn btn-primary" OnClick="AssignSingleItemPickup" Text="Proceed to pickup" />

                      <asp:Button ID="btnUpdatePickup" runat="server" CssClass="btn btn-warning" OnClick="UpdateSingleItemPickup" Text="Update Pickup details" Visible="false" />
                  </ContentTemplate>

                  <Triggers>
                      <asp:PostBackTrigger ControlID="btnInsertPickup" />
                      <asp:PostBackTrigger ControlID="btnUpdatePickup" />
                  </Triggers>
              </asp:UpdatePanel>
          </div>
        </div>
      </div>
    </div>
</asp:Content>

