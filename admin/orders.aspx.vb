Imports System
Imports System.Configuration
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class admin_orders
    Inherits System.Web.UI.Page

    Private Sub admin_orders_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindOrders()
        End If
    End Sub

    Private Sub bindOrders()
        Try
            Dim query As String = String.Empty
            Dim SubQuery As String = " AND Country = " & Session("countryName") & ""
            If Not String.IsNullOrEmpty(txtOrderID.Text) Then
                query = "SELECT * FROM flrl_orderdetails Group BY OrderID WHERE TransactionID = @OrderID " & SubQuery & ""
            Else
                Dim OrderType = Request.QueryString("type").ToString().Trim()
                If OrderType = "all" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE Country = '" & Session("countryName") & "' Group BY OrderID ORDER BY ID DESC"
                ElseIf OrderType = "cancelled" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE LastTrackingStatus = 'Cancelled' " & SubQuery & ""
                ElseIf OrderType = "confirmed" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE LastTrackingStatus = 'Confirmed' " & SubQuery & ""
                ElseIf OrderType = "Enroute" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE LastTrackingStatus = 'Enroute' " & SubQuery & ""
                ElseIf OrderType = "on-the-way" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE LastTrackingStatus = 'On the way' " & SubQuery & ""
                ElseIf OrderType = "delivered" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE LastTrackingStatus = 'Delivered' " & SubQuery & ""
                ElseIf OrderType = "closest-delivery" Then
                    query = "SELECT * FROM flrl_orderdetails WHERE Country = '" & Session("countryName") & "' ORDER BY DeliveryDate ASC"
                End If
            End If
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.Parameters.AddWithValue("@OrderID", txtOrderID.Text)
                    cmd.CommandText = query
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvOrders.DataSource = dt
                        lvOrders.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvOrders_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvOrders.ItemDataBound
        'get customer name by its ID
        Try
            Dim customerID As Label = TryCast(e.Item.FindControl("customerID"), Label)
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand("SELECT FirstName, LastName FROM flrl_usertable WHERE ID = @ID")
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ID", customerID.Text)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                customerID.Text = dt.Rows(0)("FirstName").ToString() + " " + dt.Rows(0)("LastName").ToString()
                            End If
                        End Using
                    End Using
                End Using
            End Using

            Dim orderID As String = TryCast(e.Item.FindControl("lblOrderID"), Label).Text
            Dim lblCustomerPaid As Label = TryCast(e.Item.FindControl("lblCustomerPaid"), Label)

            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand("SELECT TransactionTotal  FROM flrl_transactiondetail WHERE TransactionID = @TransactionID")
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@TransactionID", orderID)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblCustomerPaid.Text = dt.Rows(0)("TransactionTotal").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub OnPagePropertiesChanging(sender As Object, e As PagePropertiesChangingEventArgs)
        TryCast(lvOrders.FindControl("DataPager1"), DataPager).SetPageProperties(e.StartRowIndex, e.MaximumRows, False)
        Me.bindOrders()
    End Sub

    Private Sub txtOrderID_TextChanged(sender As Object, e As EventArgs) Handles txtOrderID.TextChanged
        Me.bindOrders()
    End Sub
End Class
