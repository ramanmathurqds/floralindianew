
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class admin_inclusion_pricing
    Inherits System.Web.UI.Page

    Private Sub admin_inclusion_pricing_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.populateSubcategories()
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

    Private Sub lvInclusionPrices_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvInclusionPrices.ItemCommand
        Dim ID As Integer = e.CommandArgument
        If e.CommandName = "Edit" Then
            ScriptManager.RegisterStartupScript(Me, Me.[GetType](), "Pop", "OpenInclPriceModal();", True)
            btnAdd.Visible = False
            btnUpdate.Visible = True
            lblID.Text = ID
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
        End If

        If e.CommandName = "Delete" Then
            Try

            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If
    End Sub

    Private Sub populateSubcategories()
        Try
            drpSubcategory.Items.Clear()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM productsubcategory WHERE CountryCode = @CountryCode AND IsActive = 1 AND ParentCategory = 91"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
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
            cmd.CommandText = "INSERT INTO flrl_inclusion_price (SubcategoryName, SubcategoryID, Cost, CountryCode) Values(@SubcategoryName, @SubcategoryID, @Cost, @CountryCode)"
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
            cmd.CommandText = "UPDATE flrl_inclusion_price SET SubcategoryName = @SubcategoryName, SubcategoryID = @SubcategoryID, Cost = @Cost, CountryCode = @CountryCode WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@SubcategoryName", drpSubcategory.SelectedItem.ToString().Trim())
            cmd.Parameters.AddWithValue("@SubcategoryID", drpSubcategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@Cost", txtCost.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.ExecuteNonQuery()
            con.Close()
            pnlSuccess.Visible = True
            lblSuccess.Text = "Inclusion section updated successfully"
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
