
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class admin_updateAutoPriceBulk
    Inherits System.Web.UI.Page

    Private Sub admin_updateAutoPriceBulk_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindInclusionPricing()
        End If
    End Sub

    Private Sub bindInclusionPricing()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_product_inclusion WHERE CountryCode = @CountryCode Group By ProductID"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvInclusion.DataSource = dt
                        lvInclusion.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvInclusion_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvInclusion.ItemDataBound
        Dim ProductID As String = TryCast(e.Item.FindControl("lblProductID"), Label).Text
        Dim lblFinalCost As Label = TryCast(e.Item.FindControl("lblFinalCost"), Label)
        Try
            Dim query As String = "SELECT ProductID, Qty, UnitCost, Cost, SUM(Cost) as finalCost FROM `flrl_product_inclusion` WHERE ProductID = @ProductID"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ProductID", ProductID)
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblFinalCost.Text = dt.Rows(0)("finalCost").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnBulkUpdate_Click(sender As Object, e As EventArgs) Handles btnBulkUpdate.Click
        For Each itm As ListViewDataItem In lvInclusion.Items
            Dim ProductID As String = TryCast(itm.FindControl("lblProductID"), Label).Text
            Dim lblFinalCost As Label = TryCast(itm.FindControl("lblFinalCost"), Label)
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                cmd.CommandTimeout = 500000
                con.Open()
                cmd.CommandText = "UPDATE product SET IsAutoprice = 1, Price = @Price WHERE ProductID = @ProductID"
                cmd.Parameters.AddWithValue("@Price", lblFinalCost.Text)
                cmd.Parameters.AddWithValue("@ProductID", ProductID)
                cmd.ExecuteNonQuery()
                con.Close()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        Next
    End Sub
End Class
