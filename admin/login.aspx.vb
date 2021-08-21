Imports System.Data
Imports MySql.Data.MySqlClient
Imports System.IO
Imports System.Security.Cryptography

Partial Class login
    Inherits System.Web.UI.Page
    Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
    Private Sub getQuotes()
        Using con As MySqlConnection = New MySqlConnection(conString)
            Try
                Dim query As String = "SELECT * from quotes WHERE status = 'active' order by RAND() LIMIT 1"
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                quoter.InnerHtml = dt.Rows(0)("quote")
                                author.InnerText = dt.Rows(0)("aurthor").ToString()
                            End If
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End Using
    End Sub

    Private Sub admin_login_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.getQuotes()
            Me.populateCountry()
            If Not String.IsNullOrEmpty(Session("login")) Then
                If Not String.IsNullOrEmpty(Session("redirect")) Then
                    Response.Redirect(Session("redirect"))
                Else
                    Response.Redirect("/admin/orders.aspx?type=all")
                End If
            End If
        End If
    End Sub

    Private Sub btnLogin_Click(sender As Object, e As EventArgs) Handles btnLogin.Click
        Dim user As String = String.Empty
        Dim email As String = String.Empty
        Dim pswd As String = String.Empty
        Dim page As String = String.Empty
        Dim redirectPage As String = String.Empty
        Try
            Dim redirect As String = String.Empty
            If Session("redirect") Is Nothing Then
                redirect = "/admin/products.aspx?type=all"
            Else
                redirect = Session("redirect").ToString
            End If
            If Not String.IsNullOrEmpty(txtUsername.Text) And Not String.IsNullOrEmpty(txtPassword.Text) Then
                Dim str As String = "SELECT * FROM admin WHERE username = @userName and password = @password and status = 'active'"
                Using con As MySqlConnection = New MySqlConnection(conString)
                    Try
                        Dim cmd As New MySqlCommand(str, con)
                        cmd.Parameters.AddWithValue("@username", txtUsername.Text)
                        cmd.Parameters.AddWithValue("@password", Encrypt(txtPassword.Text.Trim()))
                        Dim da As New MySqlDataAdapter(cmd)
                        Dim ds As New DataSet()
                        da.Fill(ds)
                        con.Close()
                        user = ds.Tables(0).Rows(0)("username")
                        pswd = ds.Tables(0).Rows(0)("password")
                        If ds.Tables(0).Rows.Count > 0 Then
                            If (user = txtUsername.Text.ToString AndAlso pswd = Encrypt(txtPassword.Text.Trim())) Then
                                Session("login") = txtUsername.Text
                                Session("LoginType") = "Floral"
                                Dim selectedCountry = drpCountry.SelectedValue.ToString().Trim()
                                If String.IsNullOrEmpty(selectedCountry) Then
                                    Session("country") = "IN"
                                    Session("countryName") = "India"
                                Else
                                    Session("countryName") = drpCountry.SelectedItem.ToString().Trim()
                                End If
                                Response.Redirect(redirect)
                            Else
                                Response.Write("<script language='javascript'>alert('User name or password is invalid');</script>")
                            End If
                        Else
                            Response.Write("<script language='javascript'>alert('User name or password is invalid');</script>")
                        End If
                    Catch ex As Exception
                        Response.Write(ex)
                    End Try
                End Using
            End If
        Catch ex As Exception
            Response.Write("<script language='javascript'>alert('User name or password is invalid');</script>")
        End Try
    End Sub

    Private Sub populateCountry()
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
End Class
