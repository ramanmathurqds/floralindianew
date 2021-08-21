Imports System.Data
Imports MySql.Data.MySqlClient
Partial Class delete_temp_items
    Inherits System.Web.UI.Page
    Private Sub deleteTempItem()
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM Product WHERE isTemp = '1'"
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub delete_temp_items_Load(sender As Object, e As EventArgs) Handles Me.Load
        Me.bindTempItems()
        Me.deleteTempItem()
        'Me.deleteDuplicateSubCategory()
    End Sub

    Private Sub bindTempItems()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ProductID FROM Product WHERE isTemp = '1'"
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvProducts.DataSource = dt
                        lvProducts.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvProducts_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvProducts.ItemDataBound
        Dim productID As String = TryCast(e.Item.FindControl("ProductID"), Label).Text
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM ProductCityMapping WHERE ProductID = @ProductID"
            cmd.Parameters.AddWithValue("@ProductID", productID)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub deleteDuplicateSubCategory()
        Dim con As New MySqlConnection
        Dim cmd As New MySqlCommand
        con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
        cmd.Connection = con
        con.Open()
        cmd.CommandText = "DELETE FROM productcategorysubcategorymapping WHERE `ProductSubCategoryID` IS NULL"
        cmd.ExecuteNonQuery()
        con.Close()
    End Sub
End Class
