Imports System.Data
Imports MySql.Data.MySqlClient
Partial Class admin_inclusion_pricing
    Inherits System.Web.UI.Page

    Private Sub admin_inclusion_pricing_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.PopulateCategories()
            Me.bindInclusionPricing()
        End If
    End Sub

    Private Sub bindInclusionPricing()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_inclusion_price WHERE CountryCode = @CountryCode"
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

    Private Sub PopulateCategories()
        drpCategory.ClearSelection()
        Try
            drpSubcategory.Items.Clear()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ID, Name FROM mastercategory WHERE SubMenuID = '1' AND CountryCode = @CountryCode AND IsActive = 1"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
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
        Me.populateSubcategories()
    End Sub

    Private Sub populateSubcategories()
        Try
            drpSubcategory.Items.Clear()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM productsubcategory WHERE (ParentCategory = @ParentCategory OR IsCommon = '1') AND CountryCode = @CountryCode AND IsActive = 1"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Parameters.AddWithValue("@ParentCategory", drpCategory.SelectedValue.ToString().Trim())
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("ProductSubCategoryName").ToString()
                            item.Value = sdr("ProductSubCategoryID").ToString()
                            drpSubcategory.Items.Add(item)
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
        Try
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO flrl_inclusion_price (CategoryID, SubcategoryName, SubcategoryID, Cost, CountryCode) Values(@CategoryID, @SubcategoryName, @SubcategoryID, @Cost, @CountryCode)"
            cmd.Parameters.AddWithValue("@CategoryID", drpCategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@SubcategoryName", drpSubcategory.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@SubcategoryID", drpSubcategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@Cost", txtCost.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            pnlSuccess.Visible = True
            lblSuccess.Text = "Inclusion section added successfully"
            Me.bindInclusionPricing()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub btnUpdate_Click(sender As Object, e As EventArgs) Handles btnUpdate.Click
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_inclusion_price SET CategoryID = @CategoryID, SubcategoryName = @SubcategoryName, SubcategoryID = @SubcategoryID, Cost = @Cost, CountryCode = @CountryCode WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", lblID.Text)
            cmd.Parameters.AddWithValue("@CategoryID", drpCategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@SubcategoryName", drpSubcategory.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@SubcategoryID", drpSubcategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@Cost", txtCost.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.ExecuteNonQuery()
            con.Close()
            pnlSuccess.Visible = True
            lblSuccess.Text = "Inclusion section updated successfully"
            Me.bindInclusionPricing()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        'update flrl_product_inclusion
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE flrl_product_inclusion SET UnitCost = @UnitCost, Cost = (" & txtCost.Text & " * Qty) WHERE SubCategoryID = @SubCategoryID"
            cmd.Parameters.AddWithValue("@UnitCost", txtCost.Text)
            cmd.Parameters.AddWithValue("@SubcategoryID", drpSubcategory.SelectedValue.ToString().Trim())
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        For Each itm As ListViewDataItem In lvIncluionProducts.Items
            Dim sellingPrice As Integer = 0
            Dim ProductID As String = TryCast(itm.FindControl("lblProductID"), Label).Text
            Try
                Dim query As String = "SELECT SUM(Cost) as TotalCost FROM `flrl_product_inclusion` WHERE ProductID = @ProductID GROUP BY ProductID"
                Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
                Using con1 As New MySqlConnection(conString)
                    Using cmd1 As New MySqlCommand(query)
                        Using sda As New MySqlDataAdapter()
                            cmd1.Parameters.AddWithValue("@ProductID", ProductID)
                            cmd1.Connection = con1
                            sda.SelectCommand = cmd1
                            Using dt As New DataTable()
                                sda.Fill(dt)
                                If dt.Rows.Count > 0 Then
                                    sellingPrice = dt.Rows(0)("TotalCost").ToString().Trim()
                                End If
                            End Using
                        End Using
                    End Using
                End Using
            Catch ex As Exception

            End Try

            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE product SET Price = @SellingPrice WHERE ProductID = @ProductID"
            cmd.Parameters.AddWithValue("@SellingPrice", sellingPrice)
            cmd.Parameters.AddWithValue("@ProductID", ProductID)
            cmd.ExecuteNonQuery()
            con.Close()
        Next
        drpSubcategory.ClearSelection()
    End Sub

    Private Sub lvInclusionPrices_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvInclusionPrices.ItemCommand
        Dim ID As Integer = e.CommandArgument
        If e.CommandName = "UpdatePrice" Then
            ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('inclusionModal');", True)
            btnAdd.Visible = False
            btnUpdate.Visible = True
            lblID.Text = ID
            drpCategory.ClearSelection()
            drpSubcategory.ClearSelection()

            Try
                Dim query As String = "SELECT * FROM flrl_inclusion_price WHERE ID = @ID"
                Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
                Using con As New MySqlConnection(conString)
                    Using cmd As New MySqlCommand(query)
                        Using sda As New MySqlDataAdapter()
                            cmd.Parameters.AddWithValue("@ID", ID)
                            cmd.Connection = con
                            sda.SelectCommand = cmd
                            Using dt As New DataTable()
                                sda.Fill(dt)
                                If dt.Rows.Count > 0 Then
                                    Dim SelectedCategory As String = dt.Rows(0)("CategoryID").ToString().Trim()
                                    If Not String.IsNullOrEmpty(SelectedCategory) Then
                                        drpCategory.Items.FindByValue(SelectedCategory).Selected = True
                                    End If
                                    Me.populateSubcategories()
                                    Dim selectedItem As String = dt.Rows(0)("SubCategoryID").ToString().Trim()
                                    If Not String.IsNullOrEmpty(selectedItem) Then
                                        drpSubcategory.Items.FindByValue(selectedItem).Selected = True
                                    End If

                                    txtCost.Text = dt.Rows(0)("Cost").ToString().Trim()
                                End If
                            End Using
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            End Try

            Try
                Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
                Using con As New MySqlConnection(constr)
                    Using cmd As New MySqlCommand()
                        cmd.CommandText = "SELECT * FROM flrl_product_inclusion WHERE SubCategoryID = @SubcategoryID AND CountryCode = @CountryCode"
                        cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                        cmd.Parameters.AddWithValue("@SubcategoryID", drpSubcategory.SelectedValue.ToString().Trim())
                        cmd.Connection = con
                        Using sda As New MySqlDataAdapter(cmd)
                            Dim dt As New DataTable()
                            sda.Fill(dt)
                            lvIncluionProducts.DataSource = dt
                            lvIncluionProducts.DataBind()
                        End Using
                    End Using
                End Using
            Catch ex As Exception

            End Try
        End If

        If e.CommandName = "DeletePrice" Then
            Try
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM flrl_inclusion_price WHERE ID = @ID"
                cmd.Parameters.AddWithValue("@ID", ID)
                cmd.Parameters.AddWithValue("@SubcategoryName", drpSubcategory.SelectedItem.ToString().Trim())
                cmd.Parameters.AddWithValue("@SubcategoryID", drpSubcategory.SelectedValue.ToString().Trim())
                cmd.Parameters.AddWithValue("@Cost", txtCost.Text)
                cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                cmd.ExecuteNonQuery()
                con.Close()
                pnlSuccess.Visible = True
                lblSuccess.Text = "Inclusion section updated successfully"
                Me.bindInclusionPricing()
                drpSubcategory.ClearSelection()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If
    End Sub

    Private Sub btnAddNew_Click(sender As Object, e As EventArgs) Handles btnAddNew.Click
        ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenGenerictModal('inclusionModal');", True)
        btnAdd.Visible = True
        btnUpdate.Visible = False

        drpSubcategory.ClearSelection()
        txtCost.Text = ""
    End Sub

    Private Sub drpCategory_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpCategory.SelectedIndexChanged
        Me.populateSubcategories()
    End Sub

    Private Sub lvInclusionPrices_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvInclusionPrices.ItemDataBound
        Dim categoryName As Label = TryCast(e.Item.FindControl("categoryName"), Label)
        Dim CategoryID As String = TryCast(e.Item.FindControl("CategoryID"), Label).Text
        Dim query As String = "SELECT Name FROM mastercategory WHERE ID  = @CategoryID"
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con1 As New MySqlConnection(conString)
            Using cmd1 As New MySqlCommand(query)
                Using sda As New MySqlDataAdapter()
                    cmd1.Parameters.AddWithValue("@CategoryID", CategoryID)
                    cmd1.Connection = con1
                    sda.SelectCommand = cmd1
                    Using dt As New DataTable()
                        sda.Fill(dt)
                        If dt.Rows.Count > 0 Then
                            categoryName.Text = dt.Rows(0)("Name").ToString().Trim()
                        End If
                    End Using
                End Using
            End Using
        End Using
    End Sub
End Class
