Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class users
    Inherits System.Web.UI.Page

    Private Sub users_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.getUserDetail()
            Me.bindUsers()
        End If
    End Sub

    Private Sub getUserDetail()
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
                                    selfID.Text = dt.Rows(0)("ID").ToString()
                                    selfRole.Text = dt.Rows(0)("role").ToString()
                                    Dim userPermission As String = dt.Rows(0)("permission").ToString().Trim
                                    Dim assisgnedPermission As String() = userPermission.Split("|")
                                    'for view
                                    For Each ap As String In assisgnedPermission
                                        If ap = "5V" Then
                                            tblAuth.Visible = True
                                            invalidPage.Visible = False
                                            Exit For
                                        Else
                                            invalidPage.Visible = True
                                        End If
                                    Next

                                    'for create
                                    For Each ap As String In assisgnedPermission
                                        If ap = "5C" Then
                                            btnAddUser.Visible = True
                                            Exit For
                                        End If
                                    Next
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

    Private Sub bindUsers()
        Try
            Dim query As String = "SELECT * FROM admin"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Dim con As New MySqlConnection(conString)
            Dim cmd As New MySqlCommand(query)
            con.Open()
            Dim da As New MySqlDataAdapter()
            cmd.Connection = con
            da.SelectCommand = cmd
            Dim dt As New DataTable()
            da.Fill(dt)
            ViewState("products") = query
            lvUsers.DataSource = dt
            lvUsers.DataBind()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvUsers_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvUsers.ItemCommand
        If e.CommandName = "delete" Then
            Dim ID As Integer = e.CommandArgument
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "DELETE FROM admin WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Me.bindUsers()
        End If
    End Sub
End Class
