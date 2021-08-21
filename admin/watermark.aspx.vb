Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class watermark
    Inherits System.Web.UI.Page

    Private Sub watermark_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindWatermarks()
        End If
    End Sub

    Private Sub bindWatermarks()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM WaterMark"
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvWatermarks.DataSource = dt
                        lvWatermarks.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
