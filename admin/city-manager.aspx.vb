Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class city_manager
    Inherits System.Web.UI.Page

    Private Sub city_manager_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindCities()
        End If
    End Sub

    Private Sub bindCities()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT CityID, CityName, IsActive FROM Cities WHERE CountryCode = @countryCode"
                    cmd.Connection = con
                    cmd.Parameters.AddWithValue("@countryCode", "IN")
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCities.DataSource = dt
                        lvCities.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvCities_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvCities.ItemDataBound
        Dim lblStatus As Label = TryCast(e.Item.FindControl("lblActive"), Label)
        Dim isActive As String = lblStatus.Text

        If isActive = "True" OrElse isActive = "1" Then
            lblStatus.Text = "active"
        Else
            lblStatus.Text = "inactive"
        End If
    End Sub
End Class
