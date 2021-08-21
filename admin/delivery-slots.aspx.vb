Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class admin_delivery_slots
    Inherits System.Web.UI.Page

    Private Sub bindDeliverySlots()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_deliverytime WHERE CountryCode = @CountryCode"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvInclusionPrices.DataSource = dt
                        lvInclusionPrices.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub admin_delivery_slots_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindDeliverySlots()
        End If
    End Sub
End Class
