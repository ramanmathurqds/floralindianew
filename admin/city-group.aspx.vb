Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class city_group
    Inherits System.Web.UI.Page

    Private Sub city_group_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindCityGroup()
            Me.populateCountry()
        End If
    End Sub

    Private Sub bindCityGroup()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT CityGroupID, CityGroupName, CountryCode, IsActive FROM CityGroup"
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCityGroup.DataSource = dt
                        lvCityGroup.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub populateCountry()
        Try
            drpCountry.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM Countries WHERE isActive = 'True'"
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
        Response.Redirect("/admin/city-group-details.aspx?action=new&country=" & drpCountry.SelectedValue.ToString().Trim)
    End Sub
End Class
