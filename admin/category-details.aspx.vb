Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class category_details
    Inherits System.Web.UI.Page
    Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()

    Private Sub category_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString
            Me.populateCountries()

            If action = "edit" Then
                Me.getCategoryDetails()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
                drpCountry.Items.FindByValue(Session("country").ToString().Trim()).Selected = True
            End If
        End If
    End Sub

    Private Sub getCategoryDetails()
        Try
            Dim ID As String = Request.QueryString("ID").ToString
            Dim query As String = "SELECT * FROM mastercategory WHERE ID = @ID AND SubMenuID = '1'"
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
                                lblCatName.Text = dt.Rows(0)("Name").ToString
                                txtCatName.Text = dt.Rows(0)("Name").ToString
                                txtVideoLink.Text = dt.Rows(0)("ProductListPageVideoURL").ToString

                                lblDesktopImage.Text = dt.Rows(0)("DesktopImageURL").ToString
                                lblMobileImage.Text = dt.Rows(0)("MobileImageURL").ToString
                                lblIcon.Text = dt.Rows(0)("Logo").ToString
                                lblBannerImage.Text = dt.Rows(0)("ProductListPageImageURL").ToString

                                imgCat.ImageUrl = lblDesktopImage.Text
                                imgCatMob.ImageUrl = lblMobileImage.Text
                                imgCatIcon.ImageUrl = lblIcon.Text
                                imgBanner.ImageUrl = lblBannerImage.Text

                                txtHSN.Text = dt.Rows(0)("HSN").ToString().Trim

                                Dim gstPercent As String = dt.Rows(0)("GstPercent").ToString().Trim
                                If Not String.IsNullOrEmpty(gstPercent) Then
                                    drpGST.Items.FindByValue(gstPercent).Selected = True
                                End If

                                drpCountry.ClearSelection()
                                Dim selectedCountry As String = dt.Rows(0)("CountryCode").ToString
                                If Not String.IsNullOrEmpty(selectedCountry) Then
                                    drpCountry.Items.FindByValue(selectedCountry).Selected = True
                                End If

                                txtPosition.Text = dt.Rows(0)("SequenceNo").ToString
                                txtSeoTitle.Text = dt.Rows(0)("SeoTitle").ToString()
                                txtSeoKeywords.Text = dt.Rows(0)("SeoMetaKeywords").ToString()
                                txtSeoDescription.Text = dt.Rows(0)("SeoMetaDescription").ToString()

                                Dim isHomeActive As String = dt.Rows(0)("DisaplyOnHomePage").ToString
                                If isHomeActive = "True" OrElse isHomeActive = "1" Then
                                    chkShowHome.Checked = True
                                End If

                                Dim isActive As String = dt.Rows(0)("IsActive").ToString
                                If isActive = "True" OrElse isActive = "1" Then
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

    Protected Sub addCategory()
        Try
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim isHomeActive As String = "0"
            If chkShowHome.Checked = True Then
                isHomeActive = "1"
            End If

            Me.imgUploader()

            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO mastercategory (Name, DesktopImageURL, MobileImageURL, ProductListPageImageURL, Logo, ProductListPageVideoURL, HSN, GstPercent,CountryCode, SequenceNo, SeoTitle, SeoMetaKeywords, SeoMetaDescription, DisaplyOnHomePage, SubMenuID, CreatedDate, CreatedBy, IsActive) Values(@name, @desktopImgUrl, @mobileImgUrl, @productListImgUrl, @logo, @videoURL, @hsn, @gstPercent,@CountryCode, @position, @SeoTitle, @SeoMetaKeywords, @SeoMetaDescription, @DisaplyOnHomePage, @SubMenuID, @CreatedDate, @CreatedBy, @status)"
            cmd.Parameters.AddWithValue("@name", txtCatName.Text)
            cmd.Parameters.AddWithValue("@desktopImgUrl", lblDesktopImage.Text)
            cmd.Parameters.AddWithValue("@mobileImgUrl", lblMobileImage.Text)
            cmd.Parameters.AddWithValue("@productListImgUrl", lblBannerImage.Text)
            cmd.Parameters.AddWithValue("@logo", lblIcon.Text)
            cmd.Parameters.AddWithValue("@position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@SeoTitle", txtSeoTitle.Text)
            cmd.Parameters.AddWithValue("@SeoMetaKeywords", txtSeoKeywords.Text)
            cmd.Parameters.AddWithValue("@SeoMetaDescription", txtSeoDescription.Text)
            cmd.Parameters.AddWithValue("@videoURL", txtVideoLink.Text)
            cmd.Parameters.AddWithValue("@hsn", txtHSN.Text)
            cmd.Parameters.AddWithValue("@gstPercent", drpGST.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString())
            cmd.Parameters.AddWithValue("@DisaplyOnHomePage", isHomeActive)
            cmd.Parameters.AddWithValue("@SubMenuID", "1")
            cmd.Parameters.AddWithValue("@CreatedDate", todaysDate)
            cmd.Parameters.AddWithValue("@CreatedBy", Session("staffname").ToString().Trim())
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/category.aspx")
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

            Dim isHomeActive As String = "0"
            If chkShowHome.Checked = True Then
                isHomeActive = "1"
            End If

            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE mastercategory SET Name = @name, DesktopImageURL = @desktopImgUrl, MobileImageURL = @mobileImgUrl, ProductListPageImageURL = @productListImgUrl, Logo = @logo, ProductListPageVideoURL = @videoURL, HSN = @hsn, GstPercent = @gstPercent, CountryCode = @CountryCode, SequenceNo = @position, SeoTitle = @SeoTitle, SeoMetaKeywords = @SeoMetaKeywords, SeoMetaDescription = @SeoMetaDescription, DisaplyOnHomePage = @DisaplyOnHomePage, SubMenuID = @SubMenuID, UpdatedBy = @UpdatedBy, UpdatedDate = @UpdatedDate , IsActive = @status WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@name", txtCatName.Text)
            cmd.Parameters.AddWithValue("@desktopImgUrl", lblDesktopImage.Text)
            cmd.Parameters.AddWithValue("@mobileImgUrl", lblMobileImage.Text)
            cmd.Parameters.AddWithValue("@productListImgUrl", lblBannerImage.Text)
            cmd.Parameters.AddWithValue("@logo", lblIcon.Text)
            cmd.Parameters.AddWithValue("@position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@SeoTitle", txtSeoTitle.Text)
            cmd.Parameters.AddWithValue("@SeoMetaKeywords", txtSeoKeywords.Text)
            cmd.Parameters.AddWithValue("@SeoMetaDescription", txtSeoDescription.Text)
            cmd.Parameters.AddWithValue("@videoURL", txtVideoLink.Text)
            cmd.Parameters.AddWithValue("@hsn", txtHSN.Text)
            cmd.Parameters.AddWithValue("@gstPercent", drpGST.SelectedValue.ToString().Trim)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString())
            cmd.Parameters.AddWithValue("@DisaplyOnHomePage", isHomeActive)
            cmd.Parameters.AddWithValue("@SubMenuID", "1")
            cmd.Parameters.AddWithValue("@UpdatedDate", todaysDate)
            cmd.Parameters.AddWithValue("@UpdatedBy", Session("staffname").ToString().Trim())
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getCategoryDetails()
            pnlSuccess.Visible = True
            successMessage.Text = "Category updated successfully."
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub imgUploader()
        Dim bannerPath As String = "/Content/assets/images/banners/"
        Dim commonPath As String = "/Content/assets/images/common/"

        If fileIcon.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileIcon.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileIcon.SaveAs(Server.MapPath(commonPath + newname))
            lblIcon.Text = Convert.ToString(commonPath + newname)
        End If

        If fileCatMob.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileCatMob.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileCatMob.SaveAs(Server.MapPath(commonPath + newname))
            lblMobileImage.Text = Convert.ToString(commonPath + newname)
        End If

        If fileCat.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileCat.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileCat.SaveAs(Server.MapPath(bannerPath + newname))
            lblDesktopImage.Text = Convert.ToString(bannerPath + newname)
        End If

        If fileListingPage.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileListingPage.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileListingPage.SaveAs(Server.MapPath(bannerPath + newname))
            lblBannerImage.Text = Convert.ToString(bannerPath + newname)
        End If
    End Sub

    Private Sub populateCountries()
        Try
            drpCountry.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM Countries WHERE isActive = '1'"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("CountryName").ToString()
                            item.Value = sdr("CountryCode").ToString()
                            drpCountry.Items.Add(item)
                        End While
                    End Using
                    conn.Close()
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
