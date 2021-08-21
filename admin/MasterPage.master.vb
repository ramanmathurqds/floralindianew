Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class MasterPage
    Inherits System.Web.UI.MasterPage
    Private Sub MasterPage_Load(sender As Object, e As EventArgs) Handles Me.Load
        Me.checkLoginStatus()
        Me.checkLoginType()
        Me.getActiveCountry()

        If Not Me.IsPostBack Then
            Me.getPlacedOrders()
            Me.getUserDetail()
            Me.bindCountries()

            'set email settings in session
            Session("EmailSendingID") = ConfigurationManager.AppSettings("EmailSendingID").ToString().Trim()
            Session("EmailSendingPassword") = ConfigurationManager.AppSettings("EmailSendingCode").ToString().Trim()
        End If
    End Sub

    'checks login by floral or vendor
    Private Sub checkLoginType()
        Try
            Dim loginType = Session("LoginType").ToString().Trim()
            If loginType = "Floral" Then
                pnlFloral.Visible = True
            ElseIf loginType = "Vendor" Then
                pnlVendor.Visible = True
            End If
        Catch ex As Exception

        End Try
    End Sub

    Protected Sub logout()
        Session.Remove("login")
        Response.Redirect("/admin/login.aspx")
    End Sub

    Private Sub getUserDetail()
        If Not String.IsNullOrEmpty(Session("login")) Then
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Try
                    Dim query As String = "SELECT * FROM admin WHERE username = @username"
                    Using cmd As New MySqlCommand(query)
                        cmd.Parameters.AddWithValue("@username", Session("login"))
                        Using sda As New MySqlDataAdapter()
                            cmd.Connection = con
                            con.Open()
                            sda.SelectCommand = cmd
                            Using dt As New DataTable()
                                sda.Fill(dt)
                                If dt.Rows.Count > 0 Then
                                    Dim firstName As String = dt.Rows(0)("firstName").ToString()
                                    firstName = firstName.Substring(0, 1).ToUpper()
                                    Dim lastName As String = dt.Rows(0)("lastName").ToString()
                                    lastName = lastName.Substring(0, 1).ToUpper()
                                    userChar.InnerText = firstName & lastName
                                    displayName.Text = "Hie! " & dt.Rows(0)("firstName").ToString()
                                    'Dim userrole As String = dt.Rows(0)("role").ToString()
                                    'If userrole = "admin" Then
                                    '    manager.Visible = True
                                    'End If
                                    Session("staffname") = dt.Rows(0)("ID").ToString()
                                End If
                            End Using
                        End Using
                    End Using
                Catch ex As Exception
                    Response.Write(ex)
                Finally
                    con.Close()
                End Try
            End Using
        End If
    End Sub

    Private Sub getActiveCountry()
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(conString)
            Try
                Dim query As String = "SELECT * FROM countries WHERE CountryCode = @CountryCode"
                Using cmd As New MySqlCommand(query)
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country"))
                    Using sda As New MySqlDataAdapter()
                        cmd.Connection = con
                        con.Open()
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                imgSelectedCountry.ImageUrl = ConfigurationManager.AppSettings("rootHost").ToString().Trim() & dt.Rows(0)("CountryFlag").ToString().Trim()
                                lblSelectedCountry.Text = dt.Rows(0)("CountryName").ToString().Trim()
                                Session("countryName") = dt.Rows(0)("CountryName").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Close()
            End Try
        End Using
    End Sub

    Private Sub getPlacedOrders()
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(conString)
            Try
                Dim query As String = "SELECT ID, LastTrackingStatus FROM flrl_orderdetails WHERE LastTrackingStatus = 'Order Placed'"
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Connection = con
                        con.Open()
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblPendingOrders.Text = dt.Rows.Count().ToString().Trim()
                                pnlPendingOrders.Visible = True
                            End If
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Close()
            End Try
        End Using
    End Sub

    Private Sub bindCountries()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
        Using con As New MySqlConnection(constr)
            Try
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM Countries WHERE isActive = '1'"
                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCountries.DataSource = dt
                        lvCountries.DataBind()
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Close()
            End Try
        End Using
    End Sub

    Private Sub checkLoginStatus()
        If String.IsNullOrEmpty(Session("login")) AndAlso String.IsNullOrEmpty(Session("country")) AndAlso String.IsNullOrEmpty(Session("LoginType")) Then
            Session("redirect") = Me.Request.Url.AbsoluteUri.ToString().Trim()
            Response.Redirect("/admin/login.aspx")
        End If
    End Sub

    Private Sub lvCountries_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvCountries.ItemCommand
        If e.CommandName = "ChangeCountry" Then
            Session("country") = e.CommandArgument.ToString().Trim()
            Response.Redirect(Request.Url.AbsoluteUri)
        End If
    End Sub
End Class

