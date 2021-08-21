Imports System.Data
Imports MySql.Data.MySqlClient
Imports System.IO
Imports System.Security.Cryptography

Partial Class user_details
    Inherits System.Web.UI.Page
    Dim assisgnedPermission As String()
    Private Sub user_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        Me.checkLoginStatus()
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString().Trim
            If action = "edit" Then
                Me.getUserDetails()
                btnAdd.Visible = False
                btnUpdate.Visible = True
                btnDelete.Visible = True
            ElseIf action = "new" Then
                btnAdd.Visible = True
                btnUpdate.Visible = False
                btnDelete.Visible = False
            End If
        End If

        Me.authenticateUser()
    End Sub

    Private Sub checkLoginStatus()
        If String.IsNullOrEmpty(Session("login")) Then
            Response.Redirect("/admin/login.aspx")
        End If
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

                                    Dim userPermission As String = dt.Rows(0)("permission").ToString().Trim
                                    assisgnedPermission = userPermission.Split("|")
                                    'for view
                                    For Each ap As String In assisgnedPermission
                                        If ap = "5V" Then
                                            isAuth.Visible = True
                                            invalidPage.Visible = False
                                            Exit For
                                        Else
                                            invalidPage.Visible = True
                                        End If
                                    Next

                                    ''for create
                                    'For Each ap As String In assisgnedPermission
                                    '    If ap = "5C" Then
                                    '        btnAdd.Visible = True
                                    '        Exit For
                                    '    End If
                                    'Next

                                    ''for update
                                    'For Each ap As String In assisgnedPermission
                                    '    If ap = "5U" Then
                                    '        btnUpdate.Visible = True
                                    '        Exit For
                                    '    End If
                                    'Next

                                    ''for delete
                                    'For Each ap As String In assisgnedPermission
                                    '    If ap = "5D" Then
                                    '        btnDelete.Visible = True
                                    '        Exit For
                                    '    End If
                                    'Next
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

    Private Sub getUserDetails()
        Dim selectedUserID As String = Request.QueryString("id").ToString().Trim
        Try
            Dim query As String = "SELECT * FROM admin WHERE ID = @id"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    cmd.Parameters.AddWithValue("@id", selectedUserID)
                    Using sda As New MySqlDataAdapter()
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                txtFirstName.Text = dt.Rows(0)("firstName").ToString().Trim
                                txtLastName.Text = dt.Rows(0)("lastName").ToString().Trim
                                lblPassword.Text = dt.Rows(0)("password").ToString().Trim
                                lblCatName.Text = txtFirstName.Text & " " & txtLastName.Text
                                txtUsename.Text = dt.Rows(0)("username").ToString().Trim
                                Dim role As String = dt.Rows(0)("role").ToString().Trim
                                If Not String.IsNullOrEmpty(role) Then
                                    drpRole.ClearSelection()
                                    drpRole.Items.FindByText(role).Selected = True
                                End If

                                Dim selectedPermissions As String = dt.Rows(0)("permission").ToString().Trim()
                                Dim selectedSitePermisson As String() = selectedPermissions.Split("|"c)
                                For Each itm As String In selectedSitePermisson
                                    For Each chk As ListItem In chkSiteManager.Items
                                        If itm = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim selectedProductPermission As String() = selectedPermissions.Split("|"c)
                                For Each itm As String In selectedProductPermission
                                    For Each chk As ListItem In chkProductManager.Items
                                        If itm = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim selectedTagPermission As String() = selectedPermissions.Split("|"c)
                                For Each itm As String In selectedTagPermission
                                    For Each chk As ListItem In chkTagsManager.Items
                                        If itm = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim selectedOrderPermission As String() = selectedPermissions.Split("|"c)
                                For Each itm As String In selectedOrderPermission
                                    For Each chk As ListItem In chkOrderManager.Items
                                        If itm = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim selectedUserPermission As String() = selectedPermissions.Split("|"c)
                                For Each itm As String In selectedUserPermission
                                    For Each chk As ListItem In chkUserManager.Items
                                        If itm = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim chkStatus As String = dt.Rows(0)("status").ToString().Trim
                                If chkStatus = "active" Then
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

    Private Sub btnAdd_Click(sender As Object, e As EventArgs) Handles btnAdd.Click
        Try
            Dim isActive As String = "inactive"
            If chkActive.Checked = True Then
                isActive = "active"
            End If

            Dim strPermission As String = Me.Permission()
            Dim createdPassword As String = Me.generatedPassword()

            Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO admin (firstName, lastName, username, password, role, permission, createdDate, createdBy, status) Values(@firstName, @lastName, @username, @password, @role, @permission, @createdDate, @createdBy, @status)"
            cmd.Parameters.AddWithValue("@firstName", txtFirstName.Text)
            cmd.Parameters.AddWithValue("@lastName", txtLastName.Text)
            cmd.Parameters.AddWithValue("@username", txtUsename.Text)
            cmd.Parameters.AddWithValue("@password", Encrypt(createdPassword).Trim())
            cmd.Parameters.AddWithValue("@role", drpRole.SelectedItem.ToString().Trim)
            cmd.Parameters.AddWithValue("@permission", strPermission)
            cmd.Parameters.AddWithValue("@createdDate", todaysDate)
            cmd.Parameters.AddWithValue("@createdBy", lblLoginName.Text)
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/users.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Function Encrypt(clearText As String) As String
        Dim EncryptionKey As String = "MAKV2SPBNI99212"
        Dim clearBytes As Byte() = Encoding.Unicode.GetBytes(clearText)
        Using encryptor As Aes = Aes.Create()
            Dim pdb As New Rfc2898DeriveBytes(EncryptionKey, New Byte() {&H49, &H76, &H61, &H6E, &H20, &H4D,
         &H65, &H64, &H76, &H65, &H64, &H65,
         &H76})
            encryptor.Key = pdb.GetBytes(32)
            encryptor.IV = pdb.GetBytes(16)
            Using ms As New MemoryStream()
                Using cs As New CryptoStream(ms, encryptor.CreateEncryptor(), CryptoStreamMode.Write)
                    cs.Write(clearBytes, 0, clearBytes.Length)
                    cs.Close()
                End Using
                clearText = Convert.ToBase64String(ms.ToArray())
            End Using
        End Using
        Return clearText
    End Function

    Public Function generatedPassword() As String
        Dim createdPassword As String = String.Empty
        If Not String.IsNullOrEmpty(txtPassword.Text) Then
            createdPassword = Encrypt(txtUsename.Text.Trim())
        Else
            createdPassword = lblPassword.Text
        End If
        Return createdPassword
    End Function

    Public Function Permission() As String
        Dim strPermission As String = String.Empty
        For Each chk As ListItem In chkSiteManager.Items
            If chk.Selected = True Then
                strPermission += chk.Value & "|"
            End If
        Next

        For Each chk As ListItem In chkProductManager.Items
            If chk.Selected = True Then
                strPermission += chk.Value & "|"
            End If
        Next

        For Each chk As ListItem In chkTagsManager.Items
            If chk.Selected = True Then
                strPermission += chk.Value & "|"
            End If
        Next

        For Each chk As ListItem In chkOrderManager.Items
            If chk.Selected = True Then
                strPermission += chk.Value & "|"
            End If
        Next

        For Each chk As ListItem In chkUserManager.Items
            If chk.Selected = True Then
                strPermission += chk.Value & "|"
            End If
        Next

        strPermission = strPermission.TrimEnd("|")
        Return strPermission
    End Function

    Private Sub btnUpdate_Click(sender As Object, e As EventArgs) Handles btnUpdate.Click
        Try
            Dim isActive As String = "inactive"
            If chkActive.Checked = True Then
                isActive = "active"
            End If

            Dim strPermission As String = Me.Permission()
            Dim createdPassword As String = Me.generatedPassword()

            Dim ID As String = Request.QueryString("id").ToString().Trim
            Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE admin SET firstName = @firstName, lastName = @lastName, username = @username, role = @role, password = @password, permission = @permission, updatedDate = @updatedDate, updatedBy = @updatedBy, status = @status WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@firstName", txtFirstName.Text)
            cmd.Parameters.AddWithValue("@lastName", txtLastName.Text)
            cmd.Parameters.AddWithValue("@username", txtUsename.Text)
            cmd.Parameters.AddWithValue("@password", createdPassword)
            cmd.Parameters.AddWithValue("@role", drpRole.SelectedItem.ToString().Trim)
            cmd.Parameters.AddWithValue("@permission", strPermission)
            cmd.Parameters.AddWithValue("@updatedDate", todaysDate)
            cmd.Parameters.AddWithValue("@updatedBy", lblLoginName.Text)
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getUserDetails()
            pnlSuccess.Visible = True
            successMessage.Text = "User updated successfully."
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnDelete_Click(sender As Object, e As EventArgs) Handles btnDelete.Click
        Try
            Dim ID As String = Request.QueryString("id").ToString().Trim
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM admin WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/users.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
