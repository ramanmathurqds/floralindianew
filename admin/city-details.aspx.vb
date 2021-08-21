Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class city_details
    Inherits System.Web.UI.Page

    Private Sub city_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.populateCountries()
            Me.populateStates()

            Dim action As String = Request.QueryString("action").ToString
            Me.populateCountries()
            btnDelete.Visible = False
            If action = "edit" Then
                Me.getCityDetails()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
            End If
        End If
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

    Private Sub getCityDetails()
        Try
            Dim ID As String = Request.QueryString("ID").ToString
            Dim query As String = "SELECT * FROM Cities WHERE CityID = @ID"
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
                                lblCatName.Text = dt.Rows(0)("CityName").ToString().Trim
                                txtCityName.Text = lblCatName.Text

                                drpStates.ClearSelection()
                                Dim selectedState As String = dt.Rows(0)("StateID").ToString().Trim
                                If Not String.IsNullOrEmpty(selectedState) Then
                                    drpStates.Items.FindByValue(selectedState).Selected = True
                                End If

                                drpCountry.ClearSelection()
                                Dim selectedCountry As String = dt.Rows(0)("CountryCode").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedCountry) Then
                                    drpCountry.Items.FindByValue(selectedCountry).Selected = True
                                End If

                                Dim isActive As String = dt.Rows(0)("IsActive").ToString()
                                If isActive = "1" OrElse isActive = True Then
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

    Private Sub populateStates()
        Try
            drpStates.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM States WHERE isActive = '1'"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("StateName").ToString()
                            item.Value = sdr("StateID").ToString()
                            drpStates.Items.Add(item)
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

            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO Cities (CityName, CountryCode, StateID, IsActive) Values(@CityName, @CountryCode, @StateID, @status)"
            cmd.Parameters.AddWithValue("@CityName", txtCityName.Text)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@StateID", drpStates.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/city-manager.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnUpdate_Click(sender As Object, e As EventArgs) Handles btnUpdate.Click
        Try
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim ID As String = Request.QueryString("id").ToString().Trim
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE Cities SET CityName = @CityName, StateID = @StateID, CountryCode = @CountryCode, IsActive = @status WHERE CityID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@CityName", txtCityName.Text)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@StateID", drpStates.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getCityDetails()
            pnlSuccess.Visible = True
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
