Imports System
Imports System.Configuration
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class products
    Inherits System.Web.UI.Page

    Private Sub products_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.PopulateCategory()
            Me.PopulateOccasion()
            Me.bindProducts()
            Me.generateID()
        End If
    End Sub

    Private Sub bindProducts()
        Try
            Dim query As String = String.Empty
            Dim baseQuery As String = "SELECT product.ProductID, product.ProductIamge, product.ProductName, product.ProductCode, product.Price, product.CountryCode, product.createdDate, product.UpdatedDate, product.CreatedBy, product.IsActive FROM product INNER JOIN productcategorysubcategorymapping ON product.ProductID = productcategorysubcategorymapping.ProductID"
            Dim whereClause As String = String.Empty
            Dim loadType As String = Request.QueryString("type").ToString().Trim()
            Dim extraQuery = " GROUP By productcategorysubcategorymapping.ProductID ORDER BY product.ProductID DESC"

            If loadType = "all" Then
                whereClause = "product.CountryCode = @CountryCode AND NOT product.isTemp = '1'"
            ElseIf loadType = "auto-price" Then
                whereClause = "product.IsAutoprice = '1' AND product.CountryCode = @CountryCode AND NOT product.isTemp = '1'"
            ElseIf loadType = "manual-price" Then
                whereClause = "product.IsAutoprice = '0' AND product.CountryCode = @CountryCode AND NOT product.isTemp = '1'"
            End If

            If String.IsNullOrEmpty(txtSearchQuery.Text) Then
                If Not drpCategory.SelectedValue = "" AndAlso Not drpOccasion.SelectedValue = "" Then
                    query = baseQuery & " WHERE productcategorysubcategorymapping.ProductCategoryID IN (@CategoryOccasionID) AND productcategorysubcategorymapping.MenuID = '1' AND " & whereClause & extraQuery
                ElseIf Not drpCategory.SelectedValue = "" Then
                    query = baseQuery & " WHERE productcategorysubcategorymapping.ProductCategoryID = @CategoryID  AND " & whereClause & extraQuery
                ElseIf Not drpOccasion.SelectedValue = "" Then
                    query = baseQuery & " WHERE productcategorysubcategorymapping.ProductCategoryID = @OccasionID AND " & whereClause & extraQuery
                Else
                    query = "SELECT ProductID, ProductIamge, ProductName, ProductCode, Price, CountryCode, createdDate, UpdatedDate, CreatedBy, IsActive FROM Product WHERE " & whereClause & " ORDER BY ProductID DESC"
                End If
            Else
                query = "SELECT ProductID, ProductIamge, ProductName, ProductCode, Price, CountryCode, createdDate, UpdatedDate, CreatedBy, IsActive FROM Product WHERE ProductName = @SearchQuery OR ProductCode = @SearchQuery AND " & whereClause & " ORDER BY ProductID DESC"
            End If
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = query
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Parameters.AddWithValue("@CategoryOccasionID", drpCategory.SelectedValue.ToString().Trim() & "," & drpOccasion.SelectedValue.ToString().Trim())
                    cmd.Parameters.AddWithValue("@CategoryID", drpCategory.SelectedValue.ToString().Trim())
                    cmd.Parameters.AddWithValue("@OccasionID", drpOccasion.SelectedValue.ToString().Trim())
                    cmd.Parameters.AddWithValue("@SearchQuery", txtSearchQuery.Text.Trim())
                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvProducts.DataSource = dt
                        lvProducts.DataBind()
                        lblItemCount.Text = dt.Rows.Count().ToString()
                    End Using
                    con.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvProducts_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvProducts.ItemDataBound
        Dim countryCode As String = TryCast(e.Item.FindControl("lblCountryCode"), Label).Text
        Dim lblStatus As Label = TryCast(e.Item.FindControl("lblActive"), Label)
        Dim isActive As String = lblStatus.Text

        If isActive = "True" Then
            lblStatus.Text = "active"
        Else
            lblStatus.Text = "inactive"
        End If

        Try
            Dim query As String = "SELECT CurrencyLogo FROM Countries WHERE CountryCode = @code AND IsActive = '1'"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@code", countryCode)
                        cmd.Connection = con
                        con.Open()
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                Dim currencyLogo As String = dt.Rows(0)("CurrencyLogo").ToString
                                TryCast(e.Item.FindControl("lblCurrency"), Label).Text = currencyLogo
                            End If
                        End Using
                        con.Close()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try

        Try
            Dim query As String = "SELECT firstName, lastName FROM admin WHERE ID = @ID"
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ID", TryCast(e.Item.FindControl("lblUser"), Label).ToolTip.ToString().Trim)
                        cmd.Connection = con
                        con.Open()
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                Dim userName As String = dt.Rows(0)("firstName").ToString().Trim() + " " + dt.Rows(0)("lastName").ToString().Trim()
                                TryCast(e.Item.FindControl("lblUser"), Label).Text = userName
                            End If
                        End Using
                        con.Close()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub OnPagePropertiesChanging(sender As Object, e As PagePropertiesChangingEventArgs)
        TryCast(lvProducts.FindControl("DataPager1"), DataPager).SetPageProperties(e.StartRowIndex, e.MaximumRows, False)
        Me.bindProducts()
        ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "scrollTop();", True)
    End Sub

    Private Sub btnAdd_Click(sender As Object, e As EventArgs) Handles btnAdd.Click
        Try
            Me.generateID()
            Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO Product (ProductID, IsActive, isTemp, CountryCode, CreatedBy, createdDate) VALUES(@ProductID, @status, @isTemp, @countryCode, @CreatedBy, @createdDate)"
            cmd.Parameters.AddWithValue("@ProductID", lblProductID.Text)
            cmd.Parameters.AddWithValue("@status", 0)
            cmd.Parameters.AddWithValue("@isTemp", 1)
            cmd.Parameters.AddWithValue("@CreatedBy", Session("staffname").ToString().Trim())
            cmd.Parameters.AddWithValue("@createdDate", todaysDate)
            cmd.Parameters.AddWithValue("@countryCode", Session("country").ToString().Trim())
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/product-details.aspx?action=edit&ID=" & lblProductID.Text & "&country=" & Session("country").ToString().Trim() & "&saved=false")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub generateID()
        Dim todaysDate As String = DateTime.Now.ToString("yyMMddhhmmss").ToString().Trim()
        lblProductID.Text = todaysDate
        'Response.Write(todaysDate)
    End Sub

    Private Sub txtSearchQuery_TextChanged(sender As Object, e As EventArgs) Handles txtSearchQuery.TextChanged
        Me.bindProducts()
    End Sub

    Private Sub PopulateCategory()
        Try
            Dim selectedCountry As String = Session("country").ToString().Trim()
            drpCategory.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT Name, ID, SubMenuID FROM masterCategory WHERE isActive = '1' AND CountryCode = @countryCode AND SubMenuID = '1'"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Name").ToString()
                            item.Value = sdr("ID").ToString()
                            drpCategory.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub PopulateOccasion()
        Try
            Dim selectedCountry As String = Session("country").ToString().Trim()
            drpCategory.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT Name, ID, SubMenuID FROM masterCategory WHERE isActive = '1' AND CountryCode = @countryCode AND (SubMenuID = '2' OR SubMenuID = '3')"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Name").ToString()
                            item.Value = sdr("ID").ToString()
                            drpOccasion.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub drpCategory_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpCategory.SelectedIndexChanged
        Me.bindProducts()
    End Sub

    Private Sub drpOccasion_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpOccasion.SelectedIndexChanged
        Me.bindProducts()
    End Sub
End Class
