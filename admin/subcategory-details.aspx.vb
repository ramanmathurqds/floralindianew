Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class subcategory_details
    Inherits System.Web.UI.Page

    Private Sub subcategory_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString
            Me.populateCategories()

            If action = "edit" Then
                Me.getSubCategoryDetails()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
                btnDelete.Visible = False
            End If
        End If
    End Sub

    Private Sub getSubCategoryDetails()
        Try
            Dim ID As String = Request.QueryString("ID").ToString
            Dim query As String = "SELECT * FROM ProductSubCategory WHERE ProductSubCategoryID = @ID"
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
                                lblSubCatName.Text = dt.Rows(0)("ProductSubCategoryName").ToString().Trim
                                txtSubCatName.Text = dt.Rows(0)("ProductSubCategoryName").ToString().Trim
                                txtShortText.Text = dt.Rows(0)("ProductSubCategoryDescription").ToString().Trim()
                                lblIcon.Text = dt.Rows(0)("IconURL").ToString().Trim()
                                lblImage.Text = dt.Rows(0)("ImageURL").ToString().Trim()
                                imgCat.ImageUrl = lblIcon.Text
                                imgSubCat.ImageUrl = lblImage.Text
                                drpCategory.ClearSelection()
                                Dim selectedParentCategory = dt.Rows(0)("ParentCategory").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedParentCategory) Then
                                    drpCategory.Items.FindByValue(selectedParentCategory).Selected = True
                                End If

                                txtPosition.Text = dt.Rows(0)("SequenceNo").ToString
                                Dim isActive As String = dt.Rows(0)("IsActive").ToString
                                    If isActive = "True" Then
                                        chkActive.Checked = True
                                    End If
                                End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub populateCategories()
        Try
            drpCategory.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM mastercategory WHERE isActive = '1' AND SubMenuID = '1'"
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

    Protected Sub addCategory()
        Try
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            If String.IsNullOrEmpty(txtPosition.Text) Then
                txtPosition.Text = 10000
            End If

            Me.imgUploader()

            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO ProductSubCategory (ProductSubCategoryName, ProductSubCategoryDescription, ParentCategory, ImageURL, IconURL, SequenceNo, CountryCode, IsActive) Values(@name, @ProductSubCategoryDescription, @ParentCategory, @ImageURL, @IconURL, @position, @CountryCode, @status)"
            cmd.Parameters.AddWithValue("@name", txtSubCatName.Text)
            cmd.Parameters.AddWithValue("@ProductSubCategoryDescription", txtShortText.Text)
            cmd.Parameters.AddWithValue("@ParentCategory", drpCategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@ImageURL", lblImage.Text)
            cmd.Parameters.AddWithValue("@IconURL", lblIcon.Text)
            cmd.Parameters.AddWithValue("@position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/subcategory.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub updateCategory()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Me.imgUploader()

            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            If String.IsNullOrEmpty(txtPosition.Text) Then
                txtPosition.Text = 10000
            End If

            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE ProductSubCategory SET ProductSubCategoryName = @name, ProductSubCategoryDescription = @ProductSubCategoryDescription, ParentCategory = @ParentCategory, ImageURL = @ImageURL, IconURL = @IconURL, SequenceNo = @position, CountryCode = @CountryCode, IsActive = @status WHERE ProductSubCategoryID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@name", txtSubCatName.Text)
            cmd.Parameters.AddWithValue("@ProductSubCategoryDescription", txtShortText.Text)
            cmd.Parameters.AddWithValue("@ParentCategory", drpCategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@ImageURL", lblImage.Text) 'use for homepage subcategory block
            cmd.Parameters.AddWithValue("@IconURL", lblIcon.Text) 'use for category page subcategory icon
            cmd.Parameters.AddWithValue("@position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getSubCategoryDetails()
            pnlSuccess.Visible = True
            successMessage.Text = "Subcategory updated successfully."
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub deleteCategory()
        Dim ID As String = Request.QueryString("id").ToString()
        Dim con As New MySqlConnection
        Dim cmd As New MySqlCommand
        con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
        cmd.Connection = con
        con.Open()
        cmd.CommandText = "DELETE FROM ProductSubCategory WHERE ProductSubCategoryID = @ID"
        cmd.Parameters.AddWithValue("@ID", ID)
        cmd.ExecuteNonQuery()
        con.Close()
        Response.Redirect("/admin/subcategory.aspx")
    End Sub

    Private Sub imgUploader()
        Dim commonPath As String = "/Content/assets/images/common/"

        If fileSubCatIcon.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileSubCatIcon.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileSubCatIcon.SaveAs(Server.MapPath(commonPath + newname))
            lblIcon.Text = Convert.ToString(commonPath + newname)
        End If

        If fileSubCatImage.FileName <> "" Then
            Dim file2 As New System.IO.FileInfo(fileSubCatImage.PostedFile.FileName)
            Dim newname2 As String = file2.Name.Remove((file2.Name.Length - file2.Extension.Length))
            newname2 = (newname2 & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file2.Extension
            fileSubCatImage.SaveAs(Server.MapPath(commonPath + newname2))
            lblImage.Text = Convert.ToString(commonPath + newname2)
        End If
    End Sub
End Class
