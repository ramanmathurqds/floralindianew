Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class master_occasion_details
    Inherits System.Web.UI.Page

    Private Sub master_occasion_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString
            Me.populateCountries()

            Dim typeID As String = Request.QueryString("typeID").ToString().Trim()
            If typeID = "2" Then
                linkBack.NavigateUrl = "/admin/occasion.aspx"
                lblBackText.Text = "Occasion"
            ElseIf typeID = "3" Then
                linkBack.NavigateUrl = "/admin/festivalaspx"
                lblBackText.Text = "Festival"
            ElseIf typeID = "4" Then
                pnDates.Visible = False
                linkBack.NavigateUrl = "/admin/relation.aspx"
                lblBackText.Text = "Relation"
            End If

            If action = "edit" Then
                Me.getEventDetails()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
                btnDelete.Visible = False
                drpCountry.Items.FindByValue(Session("country").ToString().Trim()).Selected = True
            End If
        End If
    End Sub

    Private Sub getEventDetails()
        Try
            Dim ID As String = Request.QueryString("ID").ToString
            Dim query As String = "SELECT * FROM mastercategory WHERE ID = @ID"
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
                                lblOccasionName.Text = dt.Rows(0)("Name").ToString
                                txtOccasionName.Text = lblOccasionName.Text
                                txtOccasionHashtags.Text = dt.Rows(0)("HashTagName").ToString().Trim()
                                lblOccasionTypeID.Text = dt.Rows(0)("SubMenuID").ToString
                                txtVideoLink.Text = dt.Rows(0)("ProductListPageVideoURL").ToString
                                lblOccasionHomeBanner.Text = dt.Rows(0)("OccasionImage").ToString().Trim()
                                lblOccasionOfferBanner.Text = dt.Rows(0)("OccasionBanner").ToString().Trim()
                                lblBannerImage.Text = dt.Rows(0)("ProductListPageImageURL").ToString
                                imgOccasionHomeBanner.ImageUrl = lblOccasionHomeBanner.Text
                                imgOccasionOfferBanner.ImageUrl = lblOccasionOfferBanner.Text
                                imgBanner.ImageUrl = lblBannerImage.Text
                                txtSurcharge.Text = dt.Rows(0)("Surcharge").ToString().Trim()

                                txtSeoTitle.Text = dt.Rows(0)("SeoTitle").ToString()
                                txtSeoKeywords.Text = dt.Rows(0)("SeoMetaKeywords").ToString()
                                txtSeoDescription.Text = dt.Rows(0)("SeoMetaDescription").ToString()

                                Dim ds As String = dt.Rows(0)("StartDate").ToString()
                                If Not String.IsNullOrEmpty(ds) Then
                                    Dim dtStart As DateTime = ds
                                    txtStartDate.Text = dtStart.ToString("yyyy-MM-dd")
                                End If

                                Dim da As String = dt.Rows(0)("EndDate").ToString()
                                If Not String.IsNullOrEmpty(da) Then
                                    Dim dtActual As DateTime = da
                                    txtActualDate.Text = dtActual.ToString("yyyy-MM-dd")
                                End If

                                drpCountry.ClearSelection()
                                Dim selectedCountry = dt.Rows(0)("CountryCode").ToString
                                If Not String.IsNullOrEmpty(selectedCountry) Then
                                    drpCountry.Items.FindByValue(selectedCountry).Selected = True
                                End If

                                txtPosition.Text = dt.Rows(0)("SequenceNo").ToString

                                Dim isActive As String = dt.Rows(0)("IsActive").ToString
                                If isActive = "1" OrElse isActive = "True" Then
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

    Protected Sub addEvent()
        Try
            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim startDate As String = txtStartDate.Text
            If startDate = "" Then
                startDate = "1900-01-01"
            End If

            Dim actualDate As String = txtActualDate.Text
            If actualDate = "" Then
                actualDate = "1900-01-01"
            End If

            Dim surcharge As Integer = 0
            If Not String.IsNullOrEmpty(txtSurcharge.Text) Then
                surcharge = txtSurcharge.Text
            End If

            Me.imgUploader()
            Dim typeID As String = Request.QueryString("typeID").ToString().Trim()

            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO mastercategory (Name, HashTagName, SubMenuID, OccasionImage, OccasionBanner, ProductListPageImageURL, ProductListPageVideoURL, StartDate, EndDate, Surcharge, CountryCode, SequenceNo, SeoTitle, SeoMetaKeywords, SeoMetaDescription, IsActive) Values(@name, @HashTagName, @SubMenuID, @OccasionImage, @OccasionBanner, @ProductListPageImageURL, @ProductListPageVideoURL, @StartDate, @EndDate, @Surcharge, @CountryCode, @position, @SeoTitle, @SeoMetaKeywords, @SeoMetaDescription, @status)"
            cmd.Parameters.AddWithValue("@name", txtOccasionName.Text)
            cmd.Parameters.AddWithValue("@HashTagName", txtOccasionHashtags.Text)
            cmd.Parameters.AddWithValue("@SubMenuID", typeID)
            cmd.Parameters.AddWithValue("@OccasionImage", lblOccasionHomeBanner.Text)
            cmd.Parameters.AddWithValue("@OccasionBanner", lblOccasionOfferBanner.Text)
            cmd.Parameters.AddWithValue("@ProductListPageImageURL", lblBannerImage.Text)
            cmd.Parameters.AddWithValue("@ProductListPageVideoURL", txtVideoLink.Text)
            cmd.Parameters.AddWithValue("@position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@SeoTitle", txtSeoTitle.Text)
            cmd.Parameters.AddWithValue("@SeoMetaKeywords", txtSeoKeywords.Text)
            cmd.Parameters.AddWithValue("@SeoMetaDescription", txtSeoDescription.Text)
            cmd.Parameters.AddWithValue("@StartDate", startDate & " 23:59:59")
            cmd.Parameters.AddWithValue("@EndDate", actualDate & " 23:59:59")
            cmd.Parameters.AddWithValue("@Surcharge", surcharge)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString())
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            If typeID = "2" Then
                linkBack.NavigateUrl = "/admin/occasion.aspx"
                lblBackText.Text = "Occasion"
            ElseIf typeID = "3" Then
                linkBack.NavigateUrl = "/admin/festival.aspx"
                lblBackText.Text = "Festival"
            ElseIf typeID = "4" Then
                linkBack.NavigateUrl = "/admin/relation.aspx"
                lblBackText.Text = "Relation"
            End If
            Response.Redirect(linkBack.NavigateUrl.ToString().Trim())
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub updateEvent()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Me.imgUploader()

            Dim isActive As String = "0"
            If chkActive.Checked = True Then
                isActive = "1"
            End If

            Dim startDate As String = txtStartDate.Text
            If startDate = "" Then
                startDate = "1900-01-01"
            End If

            Dim actualDate As String = txtActualDate.Text
            If actualDate = "" Then
                actualDate = "1900-01-01"
            End If

            Dim surcharge As Integer = 0
            If Not String.IsNullOrEmpty(txtSurcharge.Text) Then
                surcharge = txtSurcharge.Text
            End If

            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE mastercategory SET Name = @name, HashTagName = @HashTagName, OccasionImage = @OccasionImage, OccasionBanner = @OccasionBanner, ProductListPageImageURL = @ProductListPageImageURL, ProductListPageVideoURL = @ProductListPageVideoURL, StartDate = @StartDate, EndDate = @EndDate, Surcharge = @Surcharge, CountryCode = @CountryCode, SequenceNo = @position, SeoTitle = @SeoTitle, SeoMetaKeywords = @SeoMetaKeywords, SeoMetaDescription = @SeoMetaDescription, IsActive = @status WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@name", txtOccasionName.Text)
            cmd.Parameters.AddWithValue("@HashTagName", txtOccasionHashtags.Text)
            cmd.Parameters.AddWithValue("@OccasionImage", lblOccasionHomeBanner.Text)
            cmd.Parameters.AddWithValue("@OccasionBanner", lblOccasionOfferBanner.Text)
            cmd.Parameters.AddWithValue("@ProductListPageImageURL", lblBannerImage.Text)
            cmd.Parameters.AddWithValue("@ProductListPageVideoURL", txtVideoLink.Text)
            cmd.Parameters.AddWithValue("@position", txtPosition.Text)
            cmd.Parameters.AddWithValue("@SeoTitle", txtSeoTitle.Text)
            cmd.Parameters.AddWithValue("@SeoMetaKeywords", txtSeoKeywords.Text)
            cmd.Parameters.AddWithValue("@SeoMetaDescription", txtSeoDescription.Text)
            cmd.Parameters.AddWithValue("@StartDate", startDate & " 23:59:59")
            cmd.Parameters.AddWithValue("@EndDate", actualDate & " 23:59:59")
            cmd.Parameters.AddWithValue("@Surcharge", surcharge)
            cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString())
            cmd.Parameters.AddWithValue("@status", isActive)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getEventDetails()
            pnlSuccess.Visible = True
            successMessage.Text = "Updated successfully."
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub deleteEvent()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Dim typeID As String = Request.QueryString("typeID").ToString().Trim()
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM mastercategory WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Close()
            If typeID = "2" Then
                linkBack.NavigateUrl = "/admin/occasion.aspx"
            ElseIf typeID = "3" Then
                linkBack.NavigateUrl = "/admin/festival.aspx"
            ElseIf typeID = "4" Then
                linkBack.NavigateUrl = "/admin/relation.aspx"
            End If
            Response.Redirect(linkBack.NavigateUrl.ToString().Trim())
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub imgUploader()
        Dim bannerPath As String = "/Content/assets/images/banners/"
        If fileListingPage.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileListingPage.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileListingPage.SaveAs(Server.MapPath(bannerPath + newname))
            lblBannerImage.Text = Convert.ToString(bannerPath + newname)
        End If

        If fileOccasionHomeBanner.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileOccasionHomeBanner.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileOccasionHomeBanner.SaveAs(Server.MapPath(bannerPath + newname))
            lblOccasionHomeBanner.Text = Convert.ToString(bannerPath + newname)
        End If

        If fileOccasionOfferBanner.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileOccasionOfferBanner.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileOccasionOfferBanner.SaveAs(Server.MapPath(bannerPath + newname))
            lblOccasionOfferBanner.Text = Convert.ToString(bannerPath + newname)
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
