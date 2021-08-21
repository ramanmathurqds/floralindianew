Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class city_group_details
    Inherits System.Web.UI.Page

    Private Sub generateID()
        Dim todaysDate As String = DateTime.Now.ToString("yyMMddhhmmss").ToString().Trim()
        lblGroupID.Text = todaysDate
    End Sub

    Private Sub populateSelectedCity()
        Try
            Dim cityGroupID As String = Request.QueryString("ID").ToString().Trim()
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT CityGroupListID, CityGroupID, CityID FROM CityGroupList WHERE CityGroupID = @CityGroupID"
                    cmd.Parameters.AddWithValue("@CityGroupID", cityGroupID)
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCity.DataSource = dt
                        lvCity.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvCity_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvCity.ItemDataBound
        Dim cityName As Label = TryCast(e.Item.FindControl("lblCityID"), Label)
        Try
            Dim query As String = "SELECT CityName FROM Cities Where CityID = @cityID"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@cityID", cityName.ToolTip().ToString())
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                cityName.Text = dt.Rows(0)("CityName").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub city_group_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.authenticateUser()
            Dim action As String = Request.QueryString("action").ToString().Trim()
            Dim countryCode As String = Request.QueryString("country").ToString().Trim()
            Me.populateCountries()
            If action = "edit" Then
                Me.getCityGroupDetails()
                btnAdd.Visible = False
                lblGroupID.Text = Request.QueryString("id").ToString()
            ElseIf action = "new" Then
                Me.generateID()
                btnUpdate.Visible = False
            End If
            drpCountry.ClearSelection()
            drpCountry.Items.FindByValue(countryCode).Selected = True
        End If
    End Sub

    Private Sub getCityGroupDetails()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Dim query As String = "SELECT * FROM CityGroup WHERE CityGroupID = @ID"
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
                                lblCatName.Text = dt.Rows(0)("CityGroupName").ToString().Trim
                                txtCityGroupName.Text = lblCatName.Text
                                Me.populateSelectedCity()
                                Me.bindLinkedProducts()
                                Dim isActive As String = dt.Rows(0)("IsActive").ToString()
                                If isActive = "True" Then
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

    Private Sub populateCountries()
        Try
            drpCountry.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM Countries WHERE isActive = '1'"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("CountryName").ToString()
                            item.Value = sdr("CountryCode").ToString()
                            drpCountry.Items.Add(item)
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
            Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO CityGroup (CityGroupID, CityGroupName, CountryCode, CreatedDate, CreatedBy, IsActive) Values(@CityGroupID, @CityGroupName, @CountryCode, @CreatedDate, @CreatedBy, @IsActive)"
            cmd.Parameters.AddWithValue("@CityGroupID", lblGroupID.Text)
            cmd.Parameters.AddWithValue("@CityGroupName", txtCityGroupName.Text)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@CreatedBy", lblLoginID.Text)
            cmd.Parameters.AddWithValue("@CreatedDate", todaysDate)
            cmd.Parameters.AddWithValue("@IsActive", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/city-group.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnUpdate_Click(sender As Object, e As EventArgs) Handles btnUpdate.Click
        Try
            Dim ID As String = Request.QueryString("id").ToString().Trim
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE CityGroup SET CityGroupName = @CityGroupName, CountryCode = @CountryCode, UpdatedDate = @UpdatedDate, UpdatedBy = @UpdatedBy, IsActive = @IsActive WHERE CityGroupID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@CityGroupName", txtCityGroupName.Text)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@UpdatedBy", lblLoginID.Text)
            cmd.Parameters.AddWithValue("@UpdatedDate", todaysDate)
            cmd.Parameters.AddWithValue("@IsActive", "1")
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getCityGroupDetails()
            'Me.deleteExitingMappingWhileUpdate()
            pnlSuccess.Visible = True
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub deleteExitingMappingWhileUpdate()
        Try
            Dim ID As String = Request.QueryString("id").ToString().Trim
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM CityGroupList WHERE CityGroupID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub authenticateUser()
        If Not String.IsNullOrEmpty(Session("login")) Then
            Try
                Dim query As String = "SELECT * FROM admin WHERE username = @username"
                Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
                Using con As New MySqlConnection(conString)
                    Using cmd As New MySqlCommand(query)
                        cmd.Parameters.AddWithValue("@username", Session("login"))
                        Using sda As New MySqlDataAdapter()
                            cmd.Connection = con
                            sda.SelectCommand = cmd
                            Using dt As New DataTable()
                                sda.Fill(dt)
                                If dt.Rows.Count > 0 Then
                                    lblLoginID.Text = dt.Rows(0)("ID").ToString().Trim
                                    lblLoginName.Text = dt.Rows(0)("firstName").ToString().Trim & " " & dt.Rows(0)("lastName").ToString().Trim
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

    Private Sub lvCity_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvCity.ItemCommand
        Dim ID As String = e.CommandArgument.Split("|")(0).ToString()
        Dim CityID As String = e.CommandArgument.Split("|")(1).ToString()
        If e.CommandName = "removeCity" Then
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM CityGroupList WHERE CityGroupListID = @CityGroupListID"
                cmd.Parameters.AddWithValue("@CityGroupListID", ID)
                cmd.ExecuteNonQuery()
                con.Close()
                Me.populateSelectedCity()
            Catch ex As Exception
                Response.Write(ex)
            End Try

            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM productcitymapping WHERE CityGroupID = @CityGroupID AND CityID = @CityID"
                cmd.Parameters.AddWithValue("@CityGroupID", lblGroupID.Text)
                cmd.Parameters.AddWithValue("@CityID", CityID)
                cmd.ExecuteNonQuery()
                con.Close()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If
    End Sub

    Private Sub bindLinkedProducts()
        Try
            Dim cityGroupID As String = Request.QueryString("ID").ToString().Trim()
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ProductID FROM product WHERE CityGroupID REGEXP @CityGroupID"
                    cmd.Parameters.AddWithValue("@CityGroupID", cityGroupID)
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvProducts.DataSource = dt
                        lvProducts.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
