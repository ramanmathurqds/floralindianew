Imports System.Data
Imports System.IO
Imports System.Net
Imports System.Net.Mail
Imports MySql.Data.MySqlClient

Partial Class admin_order_details
    Inherits System.Web.UI.Page
    Dim SmsUrl As String = ConfigurationManager.AppSettings("SmsUrl").ToString().Trim()
    Dim SmsUserKey As String = ConfigurationManager.AppSettings("SmsUserKey").ToString().Trim()
    Dim SmsApiKey As String = ConfigurationManager.AppSettings("SmsApiKey").ToString().Trim()
    Dim SmsSenderID As String = ConfigurationManager.AppSettings("SmsSenderID").ToString().Trim()
    Dim UTCTime As DateTime = Date.UtcNow
    Dim IndianTime As DateTime = UTCTime.AddHours(5.5)
    Dim RedefinedTime As DateTime = DateTime.Parse(IndianTime)
    Dim RedefinedDate As DateTime = DateTime.Parse(IndianTime)
    Dim isVendorForward As Boolean = False
    Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()

    Private Sub admin_order_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            lblOrderID.Text = Request.QueryString("orderID").ToString().Trim()
            Me.getOrderDetails()
            Me.getDataFromTrasaction()
            Me.bindOrderItems()
            Me.populatePickupAgent()
            Me.populateCarrier()
        End If
    End Sub

    Private Sub getOrderDetails()
        Try
            Dim query As String = "SELECT * FROM flrl_orderdetails WHERE OrderID = @OrderID"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblOrderDate.Text = dt.Rows(0)("CreatedDate").ToString().Trim()
                                TransactionID.Text = dt.Rows(0)("TransactionID").ToString().Trim()
                                customerID.Text = dt.Rows(0)("UserID").ToString().Trim()
                                lblBillingAddress.Text = dt.Rows(0)("BillingAddress").ToString().Trim()
                                lblPhone.Text = dt.Rows(0)("MobileNumber").ToString().Trim()
                                Me.getCustomerDetails()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub getDataFromTrasaction()
        Try
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand("SELECT TransactionCurrency, Discount FROM flrl_transactiondetail WHERE TransactionID = @TransactionID")
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@TransactionID", lblOrderID.Text)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblCurrency.Text = dt.Rows(0)("TransactionCurrency").ToString().Trim()
                                lblOrderDiscount.Text = dt.Rows(0)("Discount").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub bindOrderItems()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_orderdetails WHERE OrderID = @OrderID"
                    cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvItems.DataSource = dt
                        lvItems.DataBind()
                    End Using
                End Using
            End Using
            Me.SumUpOrder()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvItems_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvItems.ItemDataBound
        Dim pnlNotDelivered As Panel = TryCast(e.Item.FindControl("pnlNotDelivered"), Panel)
        Dim pnlDelivered As Panel = TryCast(e.Item.FindControl("pnlDelivered"), Panel)

        Dim P1 As Panel = TryCast(e.Item.FindControl("P1"), Panel)
        Dim P2 As Panel = TryCast(e.Item.FindControl("P2"), Panel)
        Dim P3 As Panel = TryCast(e.Item.FindControl("P3"), Panel)
        Dim P4 As Panel = TryCast(e.Item.FindControl("P4"), Panel)
        Dim P5 As Panel = TryCast(e.Item.FindControl("P5"), Panel)

        Dim P1Date As Label = TryCast(e.Item.FindControl("P1Date"), Label)
        Dim P2Date As Label = TryCast(e.Item.FindControl("P2Date"), Label)
        Dim P3Date As Label = TryCast(e.Item.FindControl("P3Date"), Label)
        Dim P4Date As Label = TryCast(e.Item.FindControl("P4Date"), Label)
        Dim P5Date As Label = TryCast(e.Item.FindControl("P5Date"), Label)

        Dim btnSetConfirm As LinkButton = TryCast(e.Item.FindControl("btnSetConfirm"), LinkButton)
        Dim btnSetPickup As LinkButton = TryCast(e.Item.FindControl("btnSetPickup"), LinkButton)
        Dim btnSetOnTheWay As LinkButton = TryCast(e.Item.FindControl("btnSetOnTheWay"), LinkButton)
        Dim btnSetDelivered As LinkButton = TryCast(e.Item.FindControl("btnSetDelivered"), LinkButton)

        Dim pnlSetPickup As Panel = TryCast(e.Item.FindControl("pnlSetPickup"), Panel)

        Dim lvTrackingHistory As ListView = TryCast(e.Item.FindControl("lvTrackingHistory"), ListView)

        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Using cmd As New MySqlCommand()
                cmd.CommandText = "SELECT * FROM flrl_tracking_history TrackingSubject WHERE OrderID = @OrderID AND ProductID = @ProductID"
                cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                cmd.Parameters.AddWithValue("@ProductID", TryCast(e.Item.FindControl("ProductID"), Label).Text)
                cmd.Connection = con
                Using sda As New MySqlDataAdapter(cmd)
                    Dim dt As New DataTable()
                    sda.Fill(dt)
                    lvTrackingHistory.DataSource = dt
                    lvTrackingHistory.DataBind()
                End Using
            End Using
        End Using

        For Each itm As ListViewDataItem In lvTrackingHistory.Items
            Dim TrackingSubject As String = TryCast(itm.FindControl("TrackingSubject"), Label).Text
            Dim TrackingDate As String = TryCast(itm.FindControl("TrackingDate"), Label).Text

            If TrackingSubject = "Order Placed" Then
                P1Date.Text = TrackingDate
                P1.Attributes.Add("class", "order-tracking completed")
                P2.Attributes.Add("class", "order-tracking")
                P3.Attributes.Add("class", "order-tracking")
                P4.Attributes.Add("class", "order-tracking")
                P5.Attributes.Add("class", "order-tracking")
            End If

            If TrackingSubject = "Order Confirmed" Then
                btnOrderAction.Text = "More actions"
                btnConfirmOrder.Visible = False
                btnMoveToP3.Visible = True
                btnSetConfirm.Enabled = False
                P2Date.Text = TrackingDate
                P1.Attributes.Add("class", "order-tracking completed")
                P2.Attributes.Add("class", "order-tracking completed")
                P3.Attributes.Add("class", "order-tracking")
                P4.Attributes.Add("class", "order-tracking")
                P5.Attributes.Add("class", "order-tracking")
            End If

            If TrackingSubject = "Enroute" Then
                btnConfirmOrder.Visible = False
                btnMoveToP4.Visible = True

                P3Date.Text = TrackingDate
                P1.Attributes.Add("class", "order-tracking completed")
                P2.Attributes.Add("class", "order-tracking completed")
                P3.Attributes.Add("class", "order-tracking completed")
                P4.Attributes.Add("class", "order-tracking")
                P5.Attributes.Add("class", "order-tracking")
            End If

            If TrackingSubject = "On the way" Then
                btnMoveToP5.Visible = True
                P4Date.Text = TrackingDate
                btnSetOnTheWay.Enabled = False
                P1.Attributes.Add("class", "order-tracking completed")
                P2.Attributes.Add("class", "order-tracking completed")
                P3.Attributes.Add("class", "order-tracking completed")
                P4.Attributes.Add("class", "order-tracking completed")
                P5.Attributes.Add("class", "order-tracking")
            End If

            If TrackingSubject = "Delivered" Then
                pnlDelivered.Visible = True
                pnlNotDelivered.Visible = False
                btnMoveToP6.Visible = False
                btnSetDelivered.Enabled = False
                btnSetPickup.Enabled = False
                P5Date.Text = TrackingDate
                P1.Attributes.Add("class", "order-tracking completed")
                P2.Attributes.Add("class", "order-tracking completed")
                P3.Attributes.Add("class", "order-tracking completed")
                P4.Attributes.Add("class", "order-tracking completed")
                P5.Attributes.Add("class", "order-tracking completed")
            End If
        Next
    End Sub

    Private Sub getCustomerDetails()
        Try
            Dim query As String = "SELECT * FROM flrl_usertable WHERE ID = @UserID"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@UserID", customerID.Text)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblCustomerEmail.Text = dt.Rows(0)("Email").ToString().Trim()
                                lblName.Text = dt.Rows(0)("FirstName").ToString().Trim() & " " & dt.Rows(0)("LastName").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub SumUpOrder()
        Dim intItemSubTotal As Integer = 0
        Dim intOrderSubtotal As Integer = 0

        Dim intOrderPackingTotal = 0
        Dim intOrderShippingTotal = 0

        Dim intTotalGST = 0

        For Each item As ListViewDataItem In lvItems.Items
            Dim lblItemSubtotal As Integer = TryCast(item.FindControl("lblItemSubtotal"), Label).Text
            Dim lblItemQty As Integer = TryCast(item.FindControl("lblItemQty"), Label).Text
            intItemSubTotal = lblItemSubtotal * lblItemQty
            intOrderSubtotal += intItemSubTotal

            Dim lblOrderPackingTotal As Integer = TryCast(item.FindControl("lblItemPackingTotal"), Label).Text
            intOrderPackingTotal += lblOrderPackingTotal

            Dim lblItemShippingTotal As Integer = TryCast(item.FindControl("lblItemShippingTotal"), Label).Text
            intOrderShippingTotal += lblItemShippingTotal

            Dim chargableGstItemTotal As Integer = intOrderSubtotal + intOrderPackingTotal + intOrderShippingTotal
            Dim lblItemGSTPercent As Decimal = TryCast(item.FindControl("lblItemGSTPercent"), Label).Text
            Dim lblItemGstCharge As Label = TryCast(item.FindControl("lblItemGstCharge"), Label)
            intTotalGST += (chargableGstItemTotal * lblItemGSTPercent) / 100
        Next

        lblOrderSubtotal.Text = intOrderSubtotal
        lblPackingTotal.Text = intOrderPackingTotal
        lblOrderShippingTotal.Text = intOrderShippingTotal
        lblTotalGST.Text = intTotalGST
        lblGrandTotal.Text = intOrderSubtotal + intOrderPackingTotal + intOrderShippingTotal
        lblOrderValue.Text = lblGrandTotal.Text
    End Sub

    'confirms entire order after customer placed order online
    Protected Sub ConfirmOrder()
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_orderdetails SET LastTrackingStatus = @status"
            cmd.Parameters.AddWithValue("@Status", "Order Confirmed")
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        'Try
        '    Dim con As New MySqlConnection
        '    Dim cmd As New MySqlCommand
        '    con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
        '    cmd.Connection = con
        '    con.Open()
        '    cmd.CommandText = "UPDATE flrl_transactiondetail SET LastTrackingStatus = @status"
        '    cmd.Parameters.AddWithValue("@Status", "Order Confirmed")
        '    cmd.ExecuteNonQuery()
        '    con.Close()
        'Catch ex As Exception
        '    Response.Write(ex)
        'End Try

        For Each itm As ListViewDataItem In lvItems.Items
            Dim ProductID As String = TryCast(itm.FindControl("ProductID"), Label).Text
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "INSERT INTO flrl_tracking_history (OrderID, ProductID, TrackingSubject, TrackingTime) VALUES(@OrderID, @ProductID, @TrackingSubject, @TrackingTime)"
                cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                cmd.Parameters.AddWithValue("@ProductID", ProductID)
                cmd.Parameters.AddWithValue("@TrackingSubject", "Order Confirmed")
                cmd.Parameters.AddWithValue("@TrackingTime", "NA")
                cmd.ExecuteNonQuery()
                con.Close()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        Next
        Me.bindOrderItems()
    End Sub

    'add singleitem in order to confirm state
    Protected Sub ConfirmSingleItemInOrder()
        If Me.Page.IsValid Then
            isVendorForward = True
            Dim ConfirmationTemplate = "/emailers/order-vendor-forward1.html"
            Dim ConfirmationSubject = "Confirmed: Your Order ID is " & lblOrderID.Text & ""
            'notify to Vendor
            Me.singleItemConfirmNotification(ConfirmationTemplate, ConfirmationSubject, isVendorForward)

            'notify to customer
            isVendorForward = False 'when notification goes to customer flag has to set false
            ConfirmationTemplate = "/emailers/order-confirmation.html"
            Me.singleItemConfirmNotification(ConfirmationTemplate, ConfirmationSubject, isVendorForward)


            Me.OrderSingleItemMessage("Confirmation", lblProductName.Text, "Confirmed")

            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "UPDATE flrl_orderdetails SET VendorEmail = @VendorEmail, LastTrackingStatus = @status WHERE OrderID = @OrderID AND ProductID = @ProductID"
                cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
                cmd.Parameters.AddWithValue("@VendorEmail", txtVendorEmail.Text)
                cmd.Parameters.AddWithValue("@Status", "Order Confirmed")
                cmd.ExecuteNonQuery()
                con.Close()
            Catch ex As Exception
                Response.Write(ex)
            End Try

            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "INSERT INTO flrl_tracking_history (OrderID, ProductID, TrackingSubject, TrackingTime) VALUES(@OrderID, @ProductID, @TrackingSubject, @TrackingTime)"
                cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
                cmd.Parameters.AddWithValue("@TrackingSubject", "Order Confirmed")
                cmd.Parameters.AddWithValue("@TrackingTime", "NA")
                cmd.ExecuteNonQuery()
                con.Close()
                Me.bindOrderItems()
            Catch ex As Exception
                Response.Write(ex)
            End Try

            'update product sold count
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "UPDATE product SET SoldCount = SoldCount + " & 1 & " WHERE ProductID = @ProductID"
                cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
                cmd.ExecuteNonQuery()
                con.Close()
            Catch ex As Exception
                Response.Write(ex)
            End Try

            'for auto set reminder feature
            Me.AddtoReminder()
        End If
    End Sub

    Private Sub OrderSingleItemMessage(ByVal MessageType As String, ByVal ItemName As String, ByVal State As String)
        Try
            Dim MessageBody As String = "Dear " & lblName.Text & vbCrLf & " Your order for " & ItemName.Trim() & " is " & State.Trim() & "." & vbCrLf & vbCrLf & "Thank you for shopping with Floral India."
            Dim client As New WebClient()
            Dim baseurl As String = SmsUrl & "" & SmsUserKey & "&apikey=" & SmsApiKey & "&mobile=" & lblPhone.Text & "&message=" & MessageBody & "&senderid=" & SmsSenderID & "&type=txt&tid=xyz"
            Dim Data As Stream = client.OpenRead(baseurl)
            Dim reader As New StreamReader(Data)
            Dim ResponseID As String = reader.ReadToEnd()
            Data.Close()
            reader.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub singleItemConfirmNotification(ByVal Template As String, ByVal ConfirmationSubject As String, ByVal VendorForward As Boolean)
        VendorForward = isVendorForward
        Try
            Dim ItemImageUrl As String = String.Empty
            Dim ItemName As String = String.Empty
            Dim ItemQty As String = String.Empty
            Dim ItemPrice As String = String.Empty
            Dim ItemDiscount As String = String.Empty
            Dim ItemShipping As String = String.Empty
            Dim ItemTotal As String = String.Empty
            Dim DeliveryName As String = String.Empty
            Dim DeliveryAddress As String = String.Empty
            Dim DeliveryContact As String = String.Empty
            Dim DeliveryDate As String = String.Empty
            Dim DeliveryTime As String = String.Empty
            Dim ItemMessage As String = String.Empty
            Dim OrderedItems As String = String.Empty
            Dim OrderSubTotal As String = String.Empty
            Dim OrderTotalShipping As String = String.Empty
            Dim OrderDiscount As String = String.Empty
            Dim OrderGrandTotal As String = String.Empty

            For Each item As ListViewDataItem In lvItems.Items
                Dim ProductID As String = TryCast(item.FindControl("ProductID"), Label).Text
                If ProductID = lblPickupProductID.Text Then
                    ItemImageUrl = ConfigurationManager.AppSettings("rootHost").ToString().Trim() & TryCast(item.FindControl("imgProduct"), Image).ImageUrl.ToString().Trim()
                    ItemName = TryCast(item.FindControl("productLink"), HyperLink).Text
                    lblProductName.Text = ItemName
                    ItemQty = TryCast(item.FindControl("lblItemQty"), Label).Text
                    ItemPrice = TryCast(item.FindControl("lblItemSubtotal"), Label).Text
                    ItemShipping = TryCast(item.FindControl("lblItemShippingTotal"), Label).Text
                    ItemTotal = Val(ItemPrice) + Val(ItemShipping)
                    DeliveryName = TryCast(item.FindControl("lblDeliveryName"), Label).Text
                    DeliveryContact = TryCast(item.FindControl("lblDeliveryContact"), Label).Text
                    DeliveryAddress = TryCast(item.FindControl("lblDeliveryAddress"), Label).Text
                    DeliveryDate = TryCast(item.FindControl("lblDeliveryDate"), Label).Text
                    DeliveryTime = TryCast(item.FindControl("lblDeliveryTimeSlot"), Label).Text
                    ItemMessage = TryCast(item.FindControl("lblItemMessage"), Label).Text
                    'OrderSubTotal += Val(ItemTotal) + Val(ItemShipping)
                    Exit For
                End If
            Next

            Dim reader As New StreamReader(Server.MapPath(Template))
            Dim readFile As String = reader.ReadToEnd()
            Dim OrderBodyContent As String = ""
            OrderBodyContent = readFile

            OrderBodyContent = OrderBodyContent.Replace("$$OrderNo$$", lblOrderID.Text)
            If VendorForward = True Then
                OrderBodyContent = OrderBodyContent.Replace("$$CustomerName$$", "Vendor")
            Else
                OrderBodyContent = OrderBodyContent.Replace("$$CustomerName$$", lblName.Text)
            End If
            OrderBodyContent = OrderBodyContent.Replace("$$DeliveryName$$", DeliveryName)
            OrderBodyContent = OrderBodyContent.Replace("$$DeliveryAddress$$", DeliveryAddress)
            OrderBodyContent = OrderBodyContent.Replace("$$DeliveryContact$$", DeliveryContact)

            OrderedItems += "<div class='row'>"
            OrderedItems += "<div class='col-3'>"
            OrderedItems += "<div style='border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;'>"
            OrderedItems += "<div align='center' class='img-container center autowidth' style='padding-right: 0px;padding-left: 0px;'>"
            OrderedItems += "<img align='center' alt='Alternate text' border='0' class='center autowidth' src='" & ItemImageUrl & "' style='text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: 0; width: 100%; max-width: 160px; display: block;' title='Alternate text' width='160' />"
            OrderedItems += "</div>"
            OrderedItems += "</div>"
            OrderedItems += "</div>"


            OrderedItems += "<div class='col-9'>"
            OrderedItems += "<div style='padding: 0 10px 0 10px; font-size:13px;'>"
            OrderedItems += "<table class='order-items' style='width:100%'>"

            OrderedItems += "<tr>"
            OrderedItems += "<td>" & ItemName & "</td>"
            OrderedItems += "<td>" & ItemQty & "</td>"
            OrderedItems += "</tr>"

            OrderedItems += "<tr>"
            If VendorForward = False Then
                OrderedItems += "<td>Price</td>"
                OrderedItems += "<td>" & ItemPrice & "</td>"
            Else
                OrderedItems += "<td>Qty</td>"
                OrderedItems += "<td>" & ItemQty & "</td>"
            End If
            OrderedItems += "</tr>"

            OrderedItems += "<tr>"
            If VendorForward = False Then
                OrderedItems += "<td>Shipping</td>"
                OrderedItems += "<td>" & ItemShipping & "</td>"
            Else
                OrderedItems += "<td>&nbsp;</td>"
                OrderedItems += "<td>&nbsp;</td>"
            End If
            OrderedItems += "</tr>"

            OrderedItems += "<tr>"
            If VendorForward = False Then
                OrderedItems += "<td>Total</td>"
                OrderedItems += "<td>" & ItemTotal & "</td>"
            Else
                OrderedItems += "<td>&nbsp;</td>"
                OrderedItems += "<td>&nbsp;</td>"
            End If
            OrderedItems += "</tr>"

            OrderedItems += "<tr>"
            OrderedItems += "<td colspan='2' style='font-style:italic;color:#ee398a'><b>" & DeliveryDate & " at " & DeliveryTime & "</b></td>"
            OrderedItems += "</tr>"

            OrderedItems += "<tr>"
            OrderedItems += "<td colspan='2' style='font-style:italic; color:#738434'> <br /><b>MESSAGE :- " & ItemMessage & "</b></td>"

            OrderedItems += "</tr>"
            OrderedItems += "</table>"
            OrderedItems += "</div>"
            OrderedItems += "</div>"
            OrderedItems += "<div class='clearfix'></div><div class='hr-line'></div>"
            OrderedItems += "</div>"

            OrderBodyContent = OrderBodyContent.Replace("$$OrderedItems$$", OrderedItems)

            'send confrimation email
            Dim mail As MailMessage = New MailMessage()
            mail.From = New MailAddress("order@floralindia.com")
            If VendorForward = True Then
                mail.To.Add(txtVendorEmail.Text)
                mail.Subject = "New order(" & lblOrderID.Text & ") forwarded from Floral India."
            Else
                mail.To.Add(lblCustomerEmail.Text)
                mail.Subject = ConfirmationSubject
            End If
            mail.Body = OrderBodyContent
            mail.IsBodyHtml = True
            Dim smtp As SmtpClient = New SmtpClient("103.120.176.195")
            smtp.Credentials = New System.Net.NetworkCredential(Session("EmailSendingID").ToString().Trim(), Session("EmailSendingPassword").ToString().Trim())
            smtp.Send(mail)
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    'add enitre order to pickup state
    Protected Sub AssignOrderPickup()
        Try
            For Each itm As ListViewDataItem In lvItems.Items
                Dim ProductID As String = TryCast(itm.FindControl("ProductID"), Label).Text
                Try
                    Dim con As New MySqlConnection
                    Dim cmd As New MySqlCommand
                    con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                    cmd.Connection = con
                    con.Open()
                    cmd.CommandText = "INSERT INTO flrl_tracking_history (OrderID, ProductID, TrackingSubject, TrackingTime) VALUES(@OrderID, @ProductID, @TrackingSubject, @TrackingTime)"
                    cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                    cmd.Parameters.AddWithValue("@ProductID", ProductID)
                    cmd.Parameters.AddWithValue("@TrackingSubject", "Enroute")
                    cmd.Parameters.AddWithValue("@TrackingTime", "NA")
                    cmd.ExecuteNonQuery()
                    con.Close()
                Catch ex As Exception
                    Response.Write(ex)
                End Try
            Next
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    'add single item in order to pickup state
    Protected Sub AssignSingleItemPickup()
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "INSERT INTO flrl_tracking_history (OrderID, ProductID, TrackingSubject, TrackingTime, awbNo, CouriorName, CouriorTrackingLink, DeliveryPerson, DeliveryContact) VALUES(@OrderID, @ProductID, @TrackingSubject, @TrackingTime, @awbNo, @CouriorName, @CouriorTrackingLink, @DeliveryPerson, @DeliveryContact)"
            cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd.Parameters.AddWithValue("@TrackingSubject", "Enroute")
            cmd.Parameters.AddWithValue("@TrackingTime", "NA")
            cmd.Parameters.AddWithValue("@awbNo", txtAwbNo.Text)
            cmd.Parameters.AddWithValue("@CouriorName", drpCarrier.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@CouriorTrackingLink", drpCarrier.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@DeliveryPerson", drpPickupMen.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@DeliveryContact", lblDeliveryMenContact.Text)
            cmd.ExecuteNonQuery()
            con.Close()

            Dim con2 As New MySqlConnection
            Dim cmd2 As New MySqlCommand
            con2.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd2.Connection = con2
            con2.Open()
            cmd2.CommandText = "UPDATE flrl_orderdetails SET LastTrackingStatus = 'Enroute' WHERE OrderID = @OrderID AND ProductID = @ProductID"
            cmd2.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd2.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd2.ExecuteNonQuery()
            con2.Close()

            Me.bindOrderItems()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    'update assinged pickup details
    Protected Sub UpdateSingleItemPickup()
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_tracking_history SET TrackingTime = @TrackingTime, awbNo = @awbNo, CouriorName = @CouriorName, CouriorTrackingLink = @CouriorTrackingLink, DeliveryPerson = @DeliveryPerson, DeliveryContact = @DeliveryContact WHERE OrderID = @OrderID AND ProductID = @ProductID AND  TrackingSubject = @TrackingSubject"
            cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd.Parameters.AddWithValue("@TrackingSubject", "Enroute")
            cmd.Parameters.AddWithValue("@TrackingTime", "NA")
            cmd.Parameters.AddWithValue("@awbNo", txtAwbNo.Text)
            cmd.Parameters.AddWithValue("@CouriorName", drpCarrier.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@CouriorTrackingLink", drpCarrier.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@DeliveryPerson", drpPickupMen.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@DeliveryContact", lblDeliveryMenContact.Text)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.bindOrderItems()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvItems_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvItems.ItemCommand
        'check if pickup details already exist or not
        lblPickupProductID.Text = e.CommandArgument
        If e.CommandName = "btnConfirm" Then
            'Me.ConfirmSingleItemInOrder()
            ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('modalConfirmOrder');", True)
        End If

        If e.CommandName = "btnPickup" OrElse e.CommandName = "btnOntheway" Then
            If e.CommandName = "btnPickup" Then
                ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('modalSetPickup');", True)
            End If
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand("SELECT * FROM flrl_tracking_history WHERE OrderID = @OrderID AND ProductID = @ProductID AND TrackingSubject = 'Enroute'")
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                        cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                If e.CommandName = "btnPickup" Then
                                    btnInsertPickup.Visible = False
                                    btnUpdatePickup.Visible = True
                                Else
                                    btnInsertPickup.Visible = True
                                    btnUpdatePickup.Visible = False
                                End If

                                If e.CommandName = "btnOntheway" Then
                                    Me.AddOnTheWay()
                                End If
                            End If
                        End Using
                    End Using
                End Using
            End Using
        End If

        'check if it has marked till on the way status
        If e.CommandName = "btnDelivered" Then
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand("SELECT * FROM flrl_tracking_history WHERE OrderID = @OrderID AND ProductID = @ProductID AND TrackingSubject = 'On the way'")
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
                        cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                Me.AddToDelivered()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        End If

        'cancel single item from order
        If e.CommandName = "SingleItemCancel" Then
            ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('modalCancelOrder');", True)
            cancelProductID.Text = e.CommandArgument
        End If
    End Sub

    Protected Sub AddOnTheWay()
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "INSERT INTO flrl_tracking_history (OrderID, ProductID, TrackingSubject, TrackingTime) VALUES(@OrderID, @ProductID, @TrackingSubject, @TrackingTime)"
            cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd.Parameters.AddWithValue("@TrackingSubject", "On the way")
            cmd.Parameters.AddWithValue("@TrackingTime", "NA")
            cmd.ExecuteNonQuery()
            con.Close()


            Dim con2 As New MySqlConnection
            Dim cmd2 As New MySqlCommand
            con2.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd2.Connection = con2
            con2.Open()
            cmd2.CommandText = "UPDATE flrl_orderdetails SET LastTrackingStatus = 'On the way' WHERE OrderID = @OrderID AND ProductID = @ProductID"
            cmd2.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd2.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd2.ExecuteNonQuery()
            con2.Close()

            Me.bindOrderItems()
            Dim DispatchedSubject = "Dispatched: Your Order ID is " & lblOrderID.Text & ""
            singleItemConfirmNotification("/emailers/order-dispatched.html", DispatchedSubject, isVendorForward)
            OrderSingleItemMessage("On the way", lblProductName.Text, "On the way")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub AddToDelivered()
        Dim myDate As New Label
        myDate.Text = RedefinedDate.AddDays(90).ToString("yyyy/MM/dd")
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "INSERT INTO flrl_tracking_history (OrderID, ProductID, TrackingSubject, TrackingTime) VALUES(@OrderID, @ProductID, @TrackingSubject, @TrackingTime)"
            cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd.Parameters.AddWithValue("@TrackingSubject", "Delivered")
            cmd.Parameters.AddWithValue("@TrackingTime", "NA")
            cmd.ExecuteNonQuery()
            con.Close()

            Dim con2 As New MySqlConnection
            Dim cmd2 As New MySqlCommand
            con2.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd2.Connection = con2
            con2.Open()
            cmd2.CommandText = "UPDATE flrl_orderdetails SET LastTrackingStatus = 'Delivered' WHERE OrderID = @OrderID AND ProductID = @ProductID"
            cmd2.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd2.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
            cmd2.ExecuteNonQuery()
            con2.Close()

            Me.bindOrderItems()
            Dim DeliveredSubject = "Delivered: Your Order ID is " & lblOrderID.Text & ""
            singleItemConfirmNotification("/emailers/order-delivered.html", DeliveredSubject, isVendorForward)
            OrderSingleItemMessage("Delivered", lblProductName.Text, "Delivered")
        Catch ex As Exception
            Response.Write(ex)
        End Try

        'activate wallet
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_wallet SET IsActve = '1', WalletExpiry = @WalletExpiry WHERE TransactionID = @TransactionID AND IsActve = '0'"
            cmd.Parameters.AddWithValue("@TransactionID", TransactionID.Text)
            cmd.Parameters.AddWithValue("@WalletExpiry", myDate.Text)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        'Try
        '    Dim con As New MySqlConnection
        '    Dim cmd As New MySqlCommand
        '    con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
        '    cmd.Connection = con
        '    con.Open()
        '    cmd.CommandText = "UPDATE flrl_orderdetails SET LastTrackingStatus = 'Delivered' WHERE OrderID = @OrderID AND ProductID = @ProductID"
        '    cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
        '    cmd.Parameters.AddWithValue("@ProductID", lblPickupProductID.Text)
        '    cmd.Parameters.AddWithValue("@TrackingTime", "NA")
        '    cmd.ExecuteNonQuery()
        '    con.Close()
        'Catch ex As Exception
        '    Response.Write(ex)
        'End Try
    End Sub

    Private Sub populatePickupAgent()
        Try
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_delivey_agent WHERE status = 'active'"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("DeliveryName").ToString()
                            item.Value = sdr("ID").ToString()
                            drpPickupMen.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub populateCarrier()
        Try
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_carrier_list WHERE status = 'active'"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("CarrierName").ToString()
                            item.Value = sdr("CarrierTrackingLink").ToString()
                            drpCarrier.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
    Private Sub drpPickupMen_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpPickupMen.SelectedIndexChanged
        'fetch delivery person details
        Try
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand("SELECT * FROM flrl_delivey_agent WHERE ID = @ID")
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ID", drpPickupMen.SelectedValue().ToString().Trim())
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblDeliveryMenContact.Text = dt.Rows(0)("DeliveryContact").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnCancelOrder_Click(sender As Object, e As EventArgs) Handles btnCancelOrder.Click
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_orderdetails SET LastTrackingStatus = @status WHERE ProductID = @ProductID AND OrderID = @OrderID"
            cmd.Parameters.AddWithValue("@Status", "Cancelled")
            cmd.Parameters.AddWithValue("@ProductID", cancelProductID.Text)
            cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "INSERT INTO flrl_cancelled_orders (OrderID, ProductID, CancelledBy, RefundAmount, CancelReason) VALUES(@OrderID, @ProductID, @CancelledBy, @RefundAmount, @CancelReason)"
            cmd.Parameters.AddWithValue("@OrderID", lblOrderID.Text)
            cmd.Parameters.AddWithValue("@ProductID", cancelProductID.Text)
            cmd.Parameters.AddWithValue("@CancelledBy", Session("staffname").ToString().Trim())
            cmd.Parameters.AddWithValue("@RefundAmount", lblGrandTotal.Text)
            cmd.Parameters.AddWithValue("@CancelReason", txtCancelReason.Text)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub smsModal()
        pnlSmsSuccess.Visible = False
        txtMobileNumber.Text = lblPhone.Text
        ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('modalSMSCustomer');", True)
    End Sub

    Private Sub btnSendMessage_Click(sender As Object, e As EventArgs) Handles btnSendMessage.Click
        Try
            Dim client As New WebClient()
            Dim baseurl As String = SmsUrl & "" & SmsUserKey & "&apikey=" & SmsApiKey & "&mobile=" & txtMobileNumber.Text & "&message=" & txtSmsContent.Text & "&senderid=" & SmsSenderID & "&type=txt&tid=xyz"
            Dim Data As Stream = client.OpenRead(baseurl)
            Dim reader As New StreamReader(Data)
            Dim ResponseID As String = reader.ReadToEnd()
            Data.Close()
            reader.Close()
            pnlSmsSuccess.Visible = True
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub chkFloralEmail_CheckedChanged(sender As Object, e As EventArgs) Handles chkFloralEmail.CheckedChanged
        If chkFloralEmail.Checked = True Then
            txtVendorEmail.Text = "info@floralindia.com"
        Else
            txtVendorEmail.Text = ""
        End If
    End Sub


    Private Sub AddtoReminder()
        Dim OD As String = lblOrderDate.Text.Substring(0, 10)
        Dim ODSplit As String() = OD.Split("-")
        Dim RemindeYear As Integer = ODSplit(2) + 1
        Dim RemindMonth As Integer = ODSplit(1)
        Dim RemindDate As Integer = ODSplit(0)
        Dim ReminderDate As String = RemindeYear.ToString() + "-" + RemindMonth.ToString() + "-" + RemindDate.ToString()
        Me.GenerateCode()
        For Each itm As ListViewDataItem In lvItems.Items
            Me.AssignDiscountCode(ReminderDate)
            Dim ReminderName As String = TryCast(itm.FindControl("lblDeliveryName"), Label).Text
            If String.IsNullOrEmpty(ReminderName) Or ReminderName = "" Then
                ReminderName = "Myself"
            End If
            Dim LocationName As String = TryCast(itm.FindControl("lblDeliveryAddress"), Label).Text
            Dim ItemMessage As String = TryCast(itm.FindControl("lblItemMessage"), Label).Text
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "INSERT INTO flrl_personal_reminder (UserID, ReminderType, User_name, ReminderName, LocationName, STD, ContactNumber, Email, DiscountCode, EventCode, Event, ReminderDate, Preference, Notes, IsActive) VALUES (@UserID, 'Auto', @User_name, @ReminderName, @LocationName, @STD, @ContactNumber, @Email, @DiscountCode, @EventCode, @Event, @ReminderDate, @Preference, @Notes, @IsActive)"
                cmd.Parameters.AddWithValue("@UserID", customerID.Text)
                cmd.Parameters.AddWithValue("@ReminderType", "Auto")
                cmd.Parameters.AddWithValue("@User_name", lblName.Text)
                cmd.Parameters.AddWithValue("@ReminderName", ReminderName)
                cmd.Parameters.AddWithValue("@LocationName", LocationName)
                cmd.Parameters.AddWithValue("@STD", "91")
                cmd.Parameters.AddWithValue("@ContactNumber", lblPhone.Text)
                cmd.Parameters.AddWithValue("@Email", lblCustomerEmail.Text)
                cmd.Parameters.AddWithValue("@DiscountCode", lblCode.Text)
                cmd.Parameters.AddWithValue("@EventCode", "0")
                cmd.Parameters.AddWithValue("@Event", "Others")
                cmd.Parameters.AddWithValue("@ReminderDate", ReminderDate)
                cmd.Parameters.AddWithValue("@Preference", "Both")
                cmd.Parameters.AddWithValue("@Notes", ItemMessage)
                cmd.Parameters.AddWithValue("@IsActive", 1)
                cmd.ExecuteNonQuery()
                con.Close()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        Next
    End Sub

    Private Sub AssignDiscountCode(ByVal OccasionDate As String)
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "INSERT INTO flrl_promocode (PromoCode, PromoName, CountryCode, CountryName, DiscountMaxValue, DiscountPercent, RedeemType, DiscountAmount, DiscountType, PromoExpiry, CategoryID, UsageLimit, IsActive, CreatedBy, CreatedDate) VALUES (@PromoCode, @PromoName, @CountryCode, @CountryName, @DiscountMaxValue, @DiscountPercent, @RedeemType, @DiscountAmount, @DiscountType, @PromoExpiry, @CategoryID, @UsageLimit, @IsActive, @CreatedBy, @CreatedDate)"
            cmd.Parameters.AddWithValue("@PromoName", lblCode.Text)
            cmd.Parameters.AddWithValue("@PromoCode", lblCode.Text)
            cmd.Parameters.AddWithValue("@RedeemType", "percent")
            cmd.Parameters.AddWithValue("@DiscountPercent", "10")
            cmd.Parameters.AddWithValue("@DiscountAmount", "0")
            cmd.Parameters.AddWithValue("@DiscountMaxValue", "10000")
            cmd.Parameters.AddWithValue("@CountryCode", Session("country"))
            cmd.Parameters.AddWithValue("@CountryName", Session("countryName"))
            cmd.Parameters.AddWithValue("@DiscountType", "Cart")
            cmd.Parameters.AddWithValue("@CategoryID", "0")
            cmd.Parameters.AddWithValue("@PromoExpiry", OccasionDate)
            cmd.Parameters.AddWithValue("@UsageLimit", "0")
            cmd.Parameters.AddWithValue("@IsActive", "1")
            cmd.Parameters.AddWithValue("@CreatedBy", Session("staffname").ToString().Trim())
            cmd.Parameters.AddWithValue("@CreatedDate", todaysDate)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub GenerateCode()
        Dim alphabets As String = "abcdefghijklmnopqrstuvwxyz"
        Dim numbers As String = "1234567890"
        Dim length As New Label
        Dim alphanumeric As New Label

        length.Text = "10"
        alphanumeric.Text = "4"
        Dim characters As String = numbers
        If alphanumeric.Text = "4" Then
            characters += Convert.ToString(alphabets) & numbers
        End If

        Dim lengthTake As Integer = Integer.Parse(length.Text)
        Dim otp As String = String.Empty
        For i As Integer = 0 To lengthTake - 1
            Dim character As String = String.Empty
            Do
                Dim index As Integer = New Random().Next(0, characters.Length)
                character = characters.ToCharArray()(index).ToString()
            Loop While otp.IndexOf(character) <> -1
            otp += character
        Next
        lblCode.Text = otp.ToUpper.ToString()
    End Sub
End Class
