Imports System
Imports System.Configuration
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class festival
    Inherits System.Web.UI.Page

    Private Sub occasion_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindOccasions()
        End If
    End Sub

    Private Sub bindOccasions()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM mastercategory WHERE SubMenuID = '3' AND CountryCode = @CountryCode ORDER BY SequenceNo"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvOccasions.DataSource = dt
                        lvOccasions.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvOccasions_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvOccasions.ItemDataBound
        Dim lblStatus As Label = TryCast(e.Item.FindControl("lblActive"), Label)
        Dim isActive As String = lblStatus.Text
        If isActive = "True" OrElse isActive = "1" Then
            lblStatus.Text = "active"
        Else
            lblStatus.Text = "inactive"
        End If
    End Sub
End Class
