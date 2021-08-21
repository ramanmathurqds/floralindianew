
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class admin_delivery_slots
    Inherits System.Web.UI.Page

    Private Sub admin_delivery_slots_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString
            Me.populateCategory()
            Me.PopulateTimeDropdown()
            If action = "edit" Then
                Me.getDeliveryDetails()
                Me.BindSelectedTimeSlots()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
            End If
        End If
    End Sub

    Private Sub getDeliveryDetails()
        Try
            Dim deliveryID As String = Request.QueryString("ID").ToString()
            Dim query As String = "SELECT * FROM flrl_deliverytime WHERE DeliveryID = @ID"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ID", deliveryID)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblCatName.Text = dt.Rows(0)("DeliveryName").ToString().Trim()
                                txtSlotName.Text = lblCatName.Text
                                txtDeliveryCharges.Text = dt.Rows(0)("Charges").ToString().Trim()
                                Dim selectedCategory As String = dt.Rows(0)("CategoryID").ToString().Trim()
                                Dim splitCategory As String() = selectedCategory.Split("|"c)
                                For Each sc As String In splitCategory
                                    For Each chk As ListItem In chKCategories.Items
                                        If sc = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                txtPosition.Text = dt.Rows(0)("SequenceNo").ToString().Trim()
                                Dim isActive As String = dt.Rows(0)("IsActive").ToString
                                If isActive = "True" OrElse isActive = "1" Then
                                    chkActive.Checked = True
                                End If
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub populateCategory()
        Try
            Dim selectedCountry As String = Session("country").ToString().Trim()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT Name, ID, SubMenuID FROM masterCategory WHERE isActive = '1' AND CountryCode = @countryCode AND SubMenuID = '1'"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Name").ToString()
                            item.Value = sdr("ID").ToString()
                            chKCategories.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnAdd_Click(sender As Object, e As EventArgs) Handles btnAdd.Click
        Try
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim selectedCategories As String = String.Empty
            For Each chk As ListItem In chKCategories.Items
                If chk.Selected = True Then
                    selectedCategories += chk.Value & "|"
                End If
            Next
            selectedCategories = selectedCategories.TrimEnd("|")

            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO flrl_deliverytime (DeliveryName, CategoryID, Charges, CountryCode, SequenceNo, IsActive) Values(@DeliveryName, @CategoryID, @Charges, @CountryCode, @Position, @status)"
            cmd.Parameters.AddWithValue("@DeliveryName", txtSlotName.Text)
            cmd.Parameters.AddWithValue("@CategoryID", selectedCategories)
            cmd.Parameters.AddWithValue("@Charges", txtDeliveryCharges.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.Parameters.AddWithValue("@Position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/delivery-slots.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnUpdate_Click(sender As Object, e As EventArgs) Handles btnUpdate.Click
        Try
            Dim deliveryID As String = Request.QueryString("ID").ToString()
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim selectedCategories As String = String.Empty
            For Each chk As ListItem In chKCategories.Items
                If chk.Selected = True Then
                    selectedCategories += chk.Value & "|"
                End If
            Next
            selectedCategories = selectedCategories.TrimEnd("|")

            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_deliverytime SET DeliveryName = @DeliveryName, CategoryID = @CategoryID, Charges = @Charges, CountryCode = @CountryCode, SequenceNo = @Position, IsActive = @status WHERE DeliveryID = @ID"
            cmd.Parameters.AddWithValue("@ID", deliveryID)
            cmd.Parameters.AddWithValue("@DeliveryName", txtSlotName.Text)
            cmd.Parameters.AddWithValue("@CategoryID", selectedCategories)
            cmd.Parameters.AddWithValue("@Charges", txtDeliveryCharges.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.Parameters.AddWithValue("@Position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getDeliveryDetails()
            pnlSuccess.Visible = True
            successMessage.Text = "Delivery Slot updated successfully."
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub BindSelectedTimeSlots()
        Try
            Dim deliveryID As String = Request.QueryString("ID").ToString()
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_DeliveryTimeSlot WHERE DeliveryID = @DeliveryID"
                    cmd.Parameters.AddWithValue("@DeliveryID", deliveryID)
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvTimeslots.DataSource = dt
                        lvTimeslots.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvTimeslots_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvTimeslots.ItemCommand
        Dim ID As Integer = e.CommandArgument
        If e.CommandName = "DeleteSlot" Then
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM flrl_deliverytimeslot WHERE ID = @ID"
                cmd.Parameters.AddWithValue("@ID", ID)
                cmd.ExecuteNonQuery()
                con.Close()
                Me.BindSelectedTimeSlots()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If

        If e.CommandName = "EditSlot" Then
            Try
                ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('TimeSlotModal');", True)
                lblID.Text = ID
                btnAddSlot.Visible = False
                btnUpdateSlot.Visible = True

                Dim query As String = "SELECT * FROM flrl_deliverytimeslot WHERE ID = @ID"
                Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
                Using con As New MySqlConnection(conString)
                    Using cmd As New MySqlCommand(query)
                        Using sda As New MySqlDataAdapter()
                            cmd.Parameters.AddWithValue("@ID", ID)
                            cmd.Connection = con
                            sda.SelectCommand = cmd
                            Using dt As New DataTable()
                                sda.Fill(dt)
                                If dt.Rows.Count > 0 Then
                                    Dim FromTime As String = dt.Rows(0)("FromTime").ToString().Trim()
                                    Dim ToTime As String = dt.Rows(0)("ToTime").ToString().Trim()
                                    drpToTime.ClearSelection()
                                    drpFromTime.ClearSelection()
                                    drpFromTime.Items.FindByText(FromTime).Selected = True
                                    drpToTime.Items.FindByText(ToTime).Selected = True
                                End If
                            End Using
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If
    End Sub

    Protected Sub UpdateTimeSlot()
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_deliverytimeslot SET FromTime = @FromTime, ToTime = @ToTime, TimeSlot = @TimeSlot, InitiateTime = @InitiateTime, MaxTime = @MaxTime WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", lblID.Text)
            cmd.Parameters.AddWithValue("@FromTime", drpFromTime.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@ToTime", drpToTime.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@TimeSlot", drpFromTime.SelectedItem.ToString().Trim() & " - " & drpToTime.SelectedItem.ToString().Trim() & "hrs")
            cmd.Parameters.AddWithValue("@InitiateTime", (drpToTime.SelectedItem.ToString().Trim().Substring(0, 2)))
            cmd.Parameters.AddWithValue("@MaxTime", drpToTime.SelectedItem.ToString().Trim() & "hrs")
            cmd.ExecuteNonQuery()
            con.Close()
            Me.BindSelectedTimeSlots()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub AddTimeSlot()
        Try
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO flrl_deliverytimeslot (DeliveryID, FromTime , ToTime, TimeSlot, InitiateTime, MaxTime, CountryCode) Values(@DeliveryID, @FromTime , @ToTime, @TimeSlot, @InitiateTime, @MaxTime, @CountryCode)"
            cmd.Parameters.AddWithValue("DeliveryID", Request.QueryString("ID").ToString().Trim())
            cmd.Parameters.AddWithValue("@FromTime", drpFromTime.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@ToTime", drpToTime.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@TimeSlot", drpFromTime.SelectedItem.ToString().Trim() & " - " & drpToTime.SelectedItem.ToString().Trim() & "hrs")
            cmd.Parameters.AddWithValue("@InitiateTime", (drpToTime.SelectedItem.ToString().Trim().Substring(0, 2)))
            cmd.Parameters.AddWithValue("@MaxTime", drpToTime.SelectedItem.ToString().Trim() & "hrs")
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Me.BindSelectedTimeSlots()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnModalAddNewSlot_Click(sender As Object, e As EventArgs) Handles btnModalAddNewSlot.Click
        btnAddSlot.Visible = True
        btnUpdateSlot.Visible = False
        ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('TimeSlotModal');", True)
    End Sub

    Private Sub PopulateTimeDropdown()
        Try
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_timebox"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Time").ToString()
                            item.Value = sdr("ID").ToString()
                            drpFromTime.Items.Add(item)
                            drpToTime.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
