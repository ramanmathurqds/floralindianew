Imports System.Data
Imports MySql.Data.MySqlClient
Partial Class admin_revive_mapping
    Inherits System.Web.UI.Page

    Private Sub admin_revive_mapping_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindProducts()
            Me.bindOccasion()
        End If
    End Sub

    Private Sub bindProducts()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ProductID FROM Product WHERE NOT (isTemp='1') AND IsActive = '1' ORDER BY ProductID DESC"
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

    Private Sub bindOccasion()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ID FROM masterCategory WHERE isActive = '1' AND CountryCode = 'IN' AND (NOT SubMenuID = '1')"
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvOccasion.DataSource = dt
                        lvOccasion.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnSave_Click(sender As Object, e As EventArgs) Handles btnSave.Click
        For Each item As ListViewDataItem In lvOccasion.Items
            Dim occasionID As String = TryCast(item.FindControl("lblOccationID"), Label).Text
            For Each product As ListViewDataItem In lvProducts.Items
                Dim productID As String = TryCast(product.FindControl("lblProduct"), Label).Text
                Try
                    Dim con As New MySqlConnection()
                    con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                    Dim cmd As New MySqlCommand()
                    cmd.CommandText = "INSERT INTO ProductCategorySubCategoryMapping (ProductCategoryID, ProductSubCategoryID, ProductID, IsActive) VALUES(@ProductCategoryID, @ProductSubCategoryID, @ProductID, @status)"
                    cmd.CommandTimeout = 100000
                    cmd.Parameters.AddWithValue("@ProductCategoryID", occasionID)
                    cmd.Parameters.AddWithValue("@ProductSubCategoryID", "0")
                    cmd.Parameters.AddWithValue("@ProductID", productID)
                    cmd.Parameters.AddWithValue("@status", 1)
                    cmd.Connection = con
                    con.Open()
                    cmd.ExecuteNonQuery()
                    con.Close()
                Catch ex As Exception
                    Response.Write(ex)
                End Try
            Next
        Next
    End Sub
End Class
