Imports System.Data
Imports MySql.Data.MySqlClient
Imports System.Web.Services

Partial Class product_details
    Inherits System.Web.UI.Page
    Dim myHost As String = ConfigurationManager.AppSettings("rootHost").ToString()
    Dim inclString As String = ""
    Dim GalleryExt As Boolean = True

    Private Sub product_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString
            selectedCountry.Value = Request.QueryString("country").ToString()
            productID.Value = Request.QueryString("id").ToString()
            Me.populateCityGroups()
            Me.populateCategory()
            Me.populateMenuTags()
            Me.populateOccasions()
            Me.populateFestivals()
            Me.populateRelations()
            Me.populateWatermarks()
            Me.populateColors()

            If action = "edit" Then
                Me.getProductDetails()
                Me.bindCategorySubCategoryMapping()
                Me.bindInclusiveItems()
            End If

            Dim saved As String = Request.QueryString("saved").ToString.Trim()
            If saved = "true" Then
                pnlSuccess.Visible = True
                pnlSuccess.CssClass = "alert alert-success alert-dismissible"
                pnlMessage.Text = "Product updated Successfully."
            ElseIf saved = "false" Then
                pnlSuccess.Visible = True
                pnlSuccess.CssClass = "alert alert-danger alert-dismissible"
                pnlMessage.Text = "Something went wrong. Please check media uploaded has valid format and size."
            End If
        End If
    End Sub

    Private Sub generateProductCode()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Dim countryId As String = Request.QueryString("country").ToString
            Dim todaysDate As String = DateTime.Now.ToString("ddMMyyhhmm").ToString().Trim()
            txtProductCode.Text = "FL" & countryId & todaysDate
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub getProductDetails()
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(conString)
            Try
                Dim ID As String = Request.QueryString("id").ToString
                Dim query As String = "SELECT * FROM Product WHERE ProductID = @ID AND CountryCode = @CountryCode"

                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ID", ID)
                        cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                        cmd.Connection = con
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                lblProductName.Text = dt.Rows(0)("ProductName").ToString().Trim()
                                txtProductName.Text = lblProductName.Text
                                txtProductCode.Text = dt.Rows(0)("ProductCode").ToString()
                                If String.IsNullOrEmpty(txtProductCode.Text) Then
                                    Me.generateProductCode()
                                End If
                                txtPosition.Text = dt.Rows(0)("SortOrder").ToString()
                                txtShortDescription.Text = dt.Rows(0)("ProductShortDescription").ToString().Trim()
                                txtDescription.Text = dt.Rows(0)("ProductDescription").ToString().Trim()
                                txtSellingPrice.Text = dt.Rows(0)("Price").ToString().Trim()
                                Session("sellingPrice") = txtSellingPrice.Text
                                txtMrp.Text = dt.Rows(0)("Mrp").ToString().Trim()
                                txtInclusion.Text = dt.Rows(0)("Inclusion").ToString().Trim()
                                txtSubstitution.Text = dt.Rows(0)("Substitution").ToString().Trim()
                                txtDeliveryNote.Text = dt.Rows(0)("DeliveryDescription").ToString().Trim()
                                lblProductImage.Text = dt.Rows(0)("ProductIamge").ToString().Trim()
                                imgHero.ImageUrl = myHost + lblProductImage.Text
                                previewLink.NavigateUrl = imgHero.ImageUrl.ToString().Trim()
                                bindGalleryImages()

                                Dim selectedWatermark As String = dt.Rows(0)("WaterMarkID").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedWatermark) Then
                                    drpWatermark.Items.FindByValue(selectedWatermark).Selected = True
                                End If

                                txtMetaKeywords.Text = dt.Rows(0)("SeoMetaKeywords").ToString().Trim()
                                txtMetaDescription.Text = dt.Rows(0)("SeoMetaDescription").ToString().Trim()

                                Me.populateSelectedCity()
                                Me.bindSelectedOccasion()
                                Me.setSelectedCategoryAndSubCategory()
                                populateFilter()



                                Dim selectedMenus As String = dt.Rows(0)("MenuIDs").ToString().Trim()
                                Dim splitMenus As String() = selectedMenus.Split("|"c)
                                For Each sm As String In splitMenus
                                    For Each chk As ListItem In chkTagList.Items
                                        If sm = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                'check selected city group ids
                                Dim selectedGroupID As String = dt.Rows(0)("CityGroupID").ToString().Trim()
                                Dim splitCityGroupIds As String() = selectedGroupID.Split("|"c)
                                For Each scg As String In splitCityGroupIds
                                    For Each chk As ListItem In chkCityGroup.Items
                                        If scg = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim selectedFilterIDs As String = dt.Rows(0)("FilterIDs").ToString().Trim()
                                Dim splitFilters As String() = selectedFilterIDs.Split("|"c)
                                For Each sf As String In splitFilters
                                    For Each chk As ListItem In chkFilters.Items
                                        If sf = chk.Value Then
                                            chk.Selected = True
                                        End If
                                    Next
                                Next

                                Dim minDeliveryDay As Integer = dt.Rows(0)("MinDeliveryDay")
                                If Not String.IsNullOrEmpty(minDeliveryDay) Then
                                    drpMinDeliveryDay.ClearSelection()
                                    drpMinDeliveryDay.Items.FindByValue(minDeliveryDay).Selected = True
                                End If

                                Dim isAutoprice As String = dt.Rows(0)("IsAutoprice").ToString().Trim
                                If isAutoprice = "True" OrElse isAutoprice = "1" Then
                                    chkIsAutoPrice.Checked = True
                                End If

                                Dim isActive As String = dt.Rows(0)("IsActive").ToString().Trim()
                                If isActive = "True" Then
                                    chkActive.Checked = True
                                End If
                            End If
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Private Sub setSelectedCategoryAndSubCategory()
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(conString)
            Try
                Dim ID As String = Request.QueryString("id").ToString
                Dim query As String = "SELECT ProductCategoryID, ProductSubCategoryID FROM ProductCategorySubCategoryMapping WHERE ProductID = @ProdductID"

                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@ProdductID", ID)
                        cmd.Connection = con
                        con.Open()
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                Dim selectedCategoryID As String = dt.Rows(0)("ProductCategoryID").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedCategoryID) Then
                                    drpCategory.ClearSelection()
                                    drpCategory.Items.FindByValue(selectedCategoryID).Selected = True
                                End If

                                Me.populateSubcategories()
                                Dim selectedSubCategoryID As String = dt.Rows(0)("ProductSubCategoryID").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedSubCategoryID) AndAlso Not selectedSubCategoryID = "0" Then
                                    drpSubCategory.ClearSelection()
                                    drpSubCategory.Items.FindByValue(selectedSubCategoryID).Selected = True
                                End If

                                For Each itm As ListViewDataItem In lvCommonCategory.Items
                                    If lvCommonCategory.Items.Count > 0 Then
                                        Dim selectedCatID As String = TryCast(itm.FindControl("lblCatID"), Label).Text
                                        For Each chk As ListItem In chkOccasion.Items
                                            If selectedCatID = chk.Value Then
                                                chk.Selected = True
                                            End If
                                        Next

                                        For Each chk As ListItem In chkFestivals.Items
                                            If selectedCatID = chk.Value Then
                                                chk.Selected = True
                                            End If
                                        Next

                                        For Each chk As ListItem In chkRelations.Items
                                            If selectedCatID = chk.Value Then
                                                chk.Selected = True
                                            End If
                                        Next
                                    End If
                                Next

                                Me.populateInclusionItems()
                            End If
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    'occasion, festivals, relations
    Private Sub bindSelectedOccasion()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Try
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ProductID, ProductCategoryID FROM productcategorysubcategorymapping WHERE ProductID = @ProductID"
                    cmd.Parameters.AddWithValue("@ProductID", productID.Value.ToString().Trim())

                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCommonCategory.DataSource = dt
                        lvCommonCategory.DataBind()
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateCityGroups()
        Using conn As New MySqlConnection()
            Try
                Dim selectedCountry As String = Request.QueryString("country").ToString()
                drpCategory.ClearSelection()

                conn.ConnectionString = ConfigurationManager _
                        .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT CityGroupID, CityGroupName FROM citygroup WHERE CountryCode = @countryCode"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("CityGroupName").ToString()
                            item.Value = sdr("CityGroupID").ToString()
                            chkCityGroup.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateSelectedCity()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Try
                Dim productID As String = Request.QueryString("ID").ToString().Trim()

                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ProductID, CityID FROM ProductCityMapping WHERE ProductID = @productID"
                    cmd.Parameters.AddWithValue("@productID", productID)
                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCity.DataSource = dt
                        lvCity.DataBind()
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Private Sub lvCity_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvCity.ItemDataBound
        Dim cityName As Label = TryCast(e.Item.FindControl("lblCityID"), Label)
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(conString)
            Try
                Dim query As String = "SELECT CityName FROM Cities Where CityID = @cityID"
                Using cmd As New MySqlCommand(query)
                    Using sda As New MySqlDataAdapter()
                        cmd.Parameters.AddWithValue("@cityID", cityName.ToolTip().ToString())
                        cmd.Connection = con
                        con.Open()
                        sda.SelectCommand = cmd
                        Using dt As New DataTable()
                            sda.Fill(dt)
                            If dt.Rows.Count > 0 Then
                                cityName.Text = dt.Rows(0)("CityName").ToString().Trim()
                            End If
                        End Using
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Protected Sub updateInProductTable()
        Dim p = txtSellingPrice.Text
        Dim con As New MySqlConnection
        If Me.Page.IsValid Then
            Try
                deleteExistinfcatSubcatMapping()
                Dim isActive As String = 0
                If chkActive.Checked = True Then
                    isActive = 1
                End If

                Dim isAutoprice As String = 0
                If chkIsAutoPrice.Checked = True Then
                    isAutoprice = 1
                End If

                Dim productID As String = Request.QueryString("id").ToString().Trim()

                Dim selectedMenuIDs = String.Empty
                Dim isOneDayDelivery As String = 0
                For Each chk As ListItem In chkTagList.Items
                    If chk.Selected = True Then
                        selectedMenuIDs += chk.Value & "|"
                    End If
                Next
                selectedMenuIDs = selectedMenuIDs.TrimEnd("|")

                For Each chk As ListItem In chkTagList.Items
                    If chk.Selected = True AndAlso chk.Value = 2 Then
                        isOneDayDelivery = 1
                        Exit For
                    End If
                Next

                Dim selectedFilterIDs As String = String.Empty
                For Each chk As ListItem In chkFilters.Items
                    If chk.Selected = True Then
                        selectedFilterIDs += chk.Value & "|"
                    End If
                Next
                selectedFilterIDs = selectedFilterIDs.TrimEnd("|")

                Dim selectedCityGroupIDs As String = String.Empty
                For Each chk As ListItem In chkCityGroup.Items
                    If chk.Selected = True Then
                        selectedCityGroupIDs += chk.Value & "|"
                    End If
                Next
                selectedCityGroupIDs = selectedCityGroupIDs.TrimEnd("|")

                Dim Imgurl As String = String.Empty
                Dim IsVideo As Integer = 0
                Dim fileSize As Decimal = 0
                Dim Ext As Boolean = True
                If fileHero.FileName <> "" Then
                    Dim file As New System.IO.FileInfo(fileHero.PostedFile.FileName)
                    fileSize = Math.Round((CDec(fileHero.PostedFile.ContentLength) / CDec(1024)), 2)
                    Ext = Me.CheckMediaExtention(file.Extension, fileSize)
                    If Ext = True Then
                        Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
                        newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
                        fileHero.SaveAs(Server.MapPath("/Content/assets/images/products/" + newname))
                        lblProductImage.Text = Convert.ToString("/Content/assets/images/products/" + newname)
                        If file.Extension.ToString() = ".mp4" Then
                            IsVideo = 1
                        End If
                    End If
                End If



                Dim productName As String = txtProductName.Text
                If String.IsNullOrEmpty(productName) Then
                    productName = "Untitled Item"
                End If

                Me.makeInclusiveItemsString()

                Dim listingPosition = txtPosition.Text
                If listingPosition = "" Then
                    listingPosition = "100000"
                Else
                    listingPosition = txtPosition.Text
                End If

                Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "UPDATE Product SET SubcategoryID = @SubcategoryID, ProductName = @name, ProductCode = @productCode, ProductDescription = @description, ProductShortDescription = @shortDescription, MinDeliveryDay = @MinDeliveryDay, ProductType = @productType, ProductIamge = @mainImage, IsVideo = @IsVideo, WaterMarkID = @watermarkID, CityGroupID = @cityGroupID, Mrp = @mrp, Price = @price, IsAutoprice = @IsAutoprice, Inclusion = @Inclusion, Substitution = @Substitution, DeliveryDescription = @deliveryDescription, SortOrder = @position, SeoMetaKeywords = @metaKeywords, SeoMetaDescription = @metaDescription, FilterIDs = @filterID, MenuIDs = @menuId, isOneDayDelivery = @isOneDayDelivery, isActive = @status, UpdatedDate = @UpdatedDate, UpdatedBy = @UpdatedBy, isTemp = @isTemp WHERE ProductID = @ID"
                cmd.Parameters.AddWithValue("@ID", productID)
                cmd.Parameters.AddWithValue("@SubcategoryID", drpSubCategory.SelectedValue().ToString().Trim())
                cmd.Parameters.AddWithValue("@name", productName)
                cmd.Parameters.AddWithValue("@productCode", txtProductCode.Text)
                cmd.Parameters.AddWithValue("@description", txtDescription.Text)
                cmd.Parameters.AddWithValue("@shortDescription", txtShortDescription.Text)
                cmd.Parameters.AddWithValue("@MinDeliveryDay", drpMinDeliveryDay.SelectedValue().ToString())
                cmd.Parameters.AddWithValue("@productType", drpSubCategory.SelectedItem.ToString().Trim())
                cmd.Parameters.AddWithValue("@mainImage", lblProductImage.Text)
                cmd.Parameters.AddWithValue("@IsVideo", IsVideo)
                cmd.Parameters.AddWithValue("@watermarkID", drpWatermark.SelectedValue.ToString().Trim())
                cmd.Parameters.AddWithValue("@cityGroupID", selectedCityGroupIDs)
                cmd.Parameters.AddWithValue("@mrp", txtMrp.Text)
                cmd.Parameters.AddWithValue("@price", txtSellingPrice.Text)
                cmd.Parameters.AddWithValue("@IsAutoprice", isAutoprice)
                cmd.Parameters.AddWithValue("@position", listingPosition)
                cmd.Parameters.AddWithValue("@deliveryDescription", txtDeliveryNote.Text)
                cmd.Parameters.AddWithValue("@Inclusion", txtInclusion.Text)
                cmd.Parameters.AddWithValue("@Substitution", txtSubstitution.Text)
                cmd.Parameters.AddWithValue("@filterID", selectedFilterIDs)
                cmd.Parameters.AddWithValue("@menuID", selectedMenuIDs)
                cmd.Parameters.AddWithValue("@metaKeywords", txtMetaKeywords.Text)
                cmd.Parameters.AddWithValue("@metaDescription", txtMetaDescription.Text)
                cmd.Parameters.AddWithValue("@isOneDayDelivery", isOneDayDelivery)
                cmd.Parameters.AddWithValue("@UpdatedBy", Session("staffname").ToString().Trim())
                cmd.Parameters.AddWithValue("@UpdatedDate", todaysDate)
                cmd.Parameters.AddWithValue("@status", isActive)
                cmd.Parameters.AddWithValue("@isTemp", 0)
                cmd.ExecuteNonQuery()
                Me.uploadGallery()
                Me.addcatSubCatMapping()
                Me.addSelectedGroupCities()
                Session("sellingPrice") = txtSellingPrice.Text
                If Ext = False OrElse GalleryExt = False Then
                    Response.Redirect("/admin/product-details.aspx?action=edit&id=" & productID & "&country=" & Request.QueryString("country").ToString() & "&saved=false")
                Else
                    Response.Redirect("/admin/product-details.aspx?action=edit&id=" & productID & "&country=" & Request.QueryString("country").ToString() & "&saved=true")
                End If
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End If
    End Sub

    Private Sub updateSellingPriceForAuto()
        Dim con As New MySqlConnection
        Try
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE Product SET Price = @price WHERE ProductID = @ID"
            cmd.Parameters.AddWithValue("@ID", productID)
            cmd.Parameters.AddWithValue("@price", txtSellingPrice.Text)
            cmd.ExecuteNonQuery()
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try
    End Sub
    Private Function CheckMediaExtention(ByVal Ext As String, ByVal fileSize As Integer) As Boolean
        Dim ValidMedia As Boolean = False
        If Ext = ".mp4" OrElse Ext = ".jpg" OrElse Ext = ".jpeg" OrElse Ext = ".png" OrElse Ext = ".gif" Then
            If Ext = ".mp4" AndAlso fileSize <= 2048 Then
                ValidMedia = True
            ElseIf (Ext = ".jpg" OrElse Ext = ".jpeg" OrElse Ext = ".png" OrElse Ext = ".gif") AndAlso fileSize <= 100 Then
                ValidMedia = True
            Else
                ValidMedia = False
            End If
        End If
        Return ValidMedia
    End Function

    Private Function uploadGallery() As Boolean
        Dim con As New MySqlConnection()
        Try
            Dim _ProductID As String = Request.QueryString("id").ToString
            Dim Ext As Boolean = True
            If fileGallery.HasFiles Then
                For Each postedfile As HttpPostedFile In fileGallery.PostedFiles
                    Dim IsVideo = 0
                    Dim file As New System.IO.FileInfo(postedfile.FileName)
                    Dim fileSize = Math.Round((CDec(fileGallery.PostedFile.ContentLength) / CDec(1024)), 2)
                    Ext = Me.CheckMediaExtention(file.Extension, fileSize)
                    If Ext = True Then
                        Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
                        newname = (newname.Replace(" ", "-") & System.DateTime.Now.ToString("-ddMMyyhhmmss")) + file.Extension
                        postedfile.SaveAs(Server.MapPath("/Content/assets/images/products/") & newname)
                        Dim filepath As String = "/Content/assets/images/products/" & newname
                        If file.Extension.ToString() = ".mp4" Then
                            IsVideo = 1
                        End If
                        con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                        Dim query2 As String = "INSERT into OtherImageList (ImageUrl, IsVideo, ProductId, IsActive) values (@image, @IsVideo, @productID, @status)"
                        Dim cmd As New MySqlCommand(query2, con)
                        con.Open()
                        cmd.Parameters.AddWithValue("@image", filepath)
                        cmd.Parameters.AddWithValue("@IsVideo", IsVideo)
                        cmd.Parameters.AddWithValue("@productID", _ProductID)
                        cmd.Parameters.AddWithValue("@status", 1)
                        cmd.ExecuteNonQuery()
                    Else
                        Exit For
                    End If
                Next
            End If
            Me.bindGalleryImages()
            GalleryExt = Ext
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try
    End Function

    Private Sub bindGalleryImages()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Try
                Dim _productID As String = Request.QueryString("id").ToString
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT OtherImageListId, ImageUrl, IsVideo FROM OtherImageList WHERE IsActive = '1' AND ProductId = @productID"
                    cmd.Parameters.AddWithValue("@productID", _productID)
                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvGallery.DataSource = dt
                        lvGallery.DataBind()
                    End Using
                End Using

                For Each itm As ListViewDataItem In lvGallery.Items
                    Dim isVideo As String = TryCast(itm.FindControl("lblIsVideo"), Label).Text
                    Dim lvSideImage As Image = TryCast(itm.FindControl("lvSideImage"), Image)

                    If isVideo = "1" OrElse isVideo = "True" Then
                        lvSideImage.ImageUrl = "/Content/assets/images/common/play-button.png"
                    End If
                Next
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Private Sub bindCategorySubCategoryMapping()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Try
                Dim _productID As String = Request.QueryString("id").ToString
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ProductCategorySubCategoryMapping FROM ProductCategorySubCategoryMapping WHERE ProductID = @productID"
                    cmd.Parameters.AddWithValue("@productID", _productID)
                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvcatSubcat.DataSource = dt
                        lvcatSubcat.DataBind()
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Private Sub deleteExistinfcatSubcatMapping()
        Dim con As New MySqlConnection
        Try
            For Each itm As ListViewDataItem In lvcatSubcat.Items
                Dim ID As String = TryCast(itm.FindControl("mappingID"), Label).Text
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM ProductCategorySubCategoryMapping WHERE ProductCategorySubCategoryMapping = @ID"
                cmd.Parameters.AddWithValue("@ID", ID)
                cmd.ExecuteNonQuery()
                con.Dispose()
            Next
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try
    End Sub

    Private Sub addcatSubCatMapping()
        Dim _productID As String = Request.QueryString("id").ToString
        'delete existing mapping before saving
        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM ProductCategorySubCategoryMapping WHERE ProductID = @ID"
            cmd.Parameters.AddWithValue("@ID", _productID)
            cmd.ExecuteNonQuery()
            con.Dispose()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        Try
            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO ProductCategorySubCategoryMapping (ProductCategoryID, ProductSubCategoryID, MenuID, ProductID, IsActive) VALUES(@ProductCategoryID, @ProductSubCategoryID, @MenuID, @ProductID, @status)"
            cmd.Parameters.AddWithValue("@ProductCategoryID", drpCategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@ProductSubCategoryID", drpSubCategory.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@MenuID", "1")
            cmd.Parameters.AddWithValue("@ProductID", _productID)
            cmd.Parameters.AddWithValue("@status", 1)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Dispose()
        Catch ex As Exception
            Response.Write(ex)
        End Try

        'add occasion
        Dim selectedOccasionIDs As List(Of Integer) = New List(Of Integer)()
        For Each chk As ListItem In chkFestivals.Items
            If chk.Selected = True Then
                selectedOccasionIDs.Add(chk.Value)
            End If
        Next

        For Each chk As ListItem In chkOccasion.Items
            If chk.Selected = True Then
                selectedOccasionIDs.Add(chk.Value)
            End If
        Next

        For Each chk As ListItem In chkRelations.Items
            If chk.Selected = True Then
                selectedOccasionIDs.Add(chk.Value)
            End If
        Next

        For Each so As String In selectedOccasionIDs
            Try
                Dim con As New MySqlConnection()
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                Dim cmd As New MySqlCommand()
                cmd.CommandText = "INSERT INTO ProductCategorySubCategoryMapping (ProductCategoryID, ProductSubCategoryID, MenuID = '2', ProductID, IsActive) VALUES(@ProductCategoryID, @ProductSubCategoryID, @ProductID, @status)"
                cmd.Parameters.AddWithValue("@ProductCategoryID", so.ToString())
                cmd.Parameters.AddWithValue("@ProductSubCategoryID", drpSubCategory.SelectedValue.ToString().Trim())
                cmd.Parameters.AddWithValue("@ProductID", _productID)
                cmd.Parameters.AddWithValue("@status", 1)
                cmd.Connection = con
                con.Open()
                cmd.ExecuteNonQuery()
                con.Dispose()
            Catch ex As Exception
                Response.Write(ex)
            End Try
        Next
    End Sub

    Private Sub populateCategory()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                Dim selectedCountry As String = Session("country").ToString().Trim()
                drpCategory.ClearSelection()

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
                            drpIncCategory.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateSubcategories()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                drpSubCategory.Items.Clear()
                Dim dummy As New ListItem()
                dummy.Text = ""
                dummy.Value = ""
                drpSubCategory.Items.Add(dummy)
                Dim query As String = String.Empty
                If drpCategory.SelectedItem.ToString = "" Then
                    query = "SELECT * FROM ProductSubCategory WHERE isActive = '1'"
                Else
                    query = "SELECT * FROM ProductSubCategory WHERE ParentCategory = @parentCategory AND isActive = '1'"
                End If
                Using cmd As New MySqlCommand()
                    cmd.CommandText = query
                    cmd.Parameters.AddWithValue("@parentCategory", drpCategory.SelectedValue.ToString().Trim())
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("ProductSubCategoryName").ToString()
                            item.Value = sdr("ProductSubCategoryID").ToString()
                            drpSubCategory.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateFilter()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                'chkFilters.ClearSelection()
                chkFilters.Items.Clear()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT CategoryFilterID, CategoryID, FilterValue FROM CategoryFilter WHERE CategoryID = @categoryID AND isActive = '1' ORDER BY CategoryFilterID ASC"
                    cmd.Connection = conn
                    cmd.Parameters.AddWithValue("@categoryID", drpCategory.SelectedValue.ToString().Trim())
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("FilterValue").ToString()
                            item.Value = sdr("CategoryFilterID").ToString()
                            item.Attributes.Add("data-categoryID", sdr("CategoryID").ToString())
                            chkFilters.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateColors()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                drpIncColor.ClearSelection()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT CategoryFilterID, CategoryID, FilterValue FROM CategoryFilter WHERE isActive = '1' AND FilterName = 'Colour' ORDER BY `FilterName`"
                    cmd.Connection = conn
                    cmd.Parameters.AddWithValue("@categoryID", drpCategory.SelectedValue.ToString().Trim())
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("FilterValue").ToString()
                            item.Value = sdr("FilterValue").ToString()
                            drpIncColor.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateMenuTags()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                Dim selectedCountry As String = Session("country").ToString().Trim()
                chkTagList.ClearSelection()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM Menu WHERE isActive = '1' AND NOT MenuID = '4' AND NOT MenuID = '5' AND CountryCode = @countryCode"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("MenuName").ToString()
                            item.Value = sdr("MenuID").ToString()
                            chkTagList.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateOccasions()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                Dim selectedCountry As String = Session("country").ToString().Trim()
                chkOccasion.ClearSelection()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ID, Name FROM mastercategory WHERE SubMenuID = '2' AND isActive = '1' AND CountryCode = @countryCode"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Name").ToString()
                            item.Value = sdr("ID").ToString()
                            chkOccasion.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateFestivals()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                Dim selectedCountry As String = Session("country").ToString().Trim()
                chkFestivals.ClearSelection()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ID, Name FROM mastercategory WHERE SubMenuID = '3' AND isActive = '1' AND CountryCode = @countryCode"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Name").ToString()
                            item.Value = sdr("ID").ToString()
                            chkFestivals.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateRelations()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                Dim selectedCountry As String = Session("country").ToString().Trim()
                chkRelations.ClearSelection()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ID, Name FROM mastercategory WHERE SubMenuID = '4' AND isActive = '1' AND CountryCode = @countryCode"
                    cmd.Parameters.AddWithValue("@countryCode", selectedCountry)
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("Name").ToString()
                            item.Value = sdr("ID").ToString()
                            chkRelations.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub populateWatermarks()
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                drpWatermark.ClearSelection()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM WaterMark"
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("WaterMarkName").ToString()
                            item.Value = sdr("WaterMarkID").ToString()
                            item.Attributes.Add("data-img", myHost + sdr("WaterMarkImage").ToString())
                            drpWatermark.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub drpCategory_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpCategory.SelectedIndexChanged
        Me.populateSubcategories()
        Me.populateFilter()
        Me.populateInclusionItems()
    End Sub

    Private Sub lvGallery_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvGallery.ItemCommand
        If e.CommandName = "delete-image" Then
            Dim ID As Integer = e.CommandArgument
            Dim con As New MySqlConnection
            Try
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM otherimagelist WHERE otherImageListId = @ID"
                cmd.Parameters.AddWithValue("@ID", ID)
                cmd.ExecuteNonQuery()
                Me.bindGalleryImages()
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End If
    End Sub

    Protected Sub deleteProduct()
        Dim ID As String = Request.QueryString("id").ToString().Trim()

        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM OtherImageList WHERE ProductId = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Dispose()
        Catch ex As Exception
            Response.Write(ex)
        End Try


        Try
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM Product WHERE ProductID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Dispose()
            Response.Redirect("/admin/products.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub addSelectedGroupCities()
        Dim con As New MySqlConnection
        Try
            'first delete existing mapping
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM productcitymapping WHERE ProductID = @ProductID"
            cmd.Parameters.AddWithValue("@ProductID", productID.Value.ToString().Trim())
            cmd.ExecuteNonQuery()
            lvCity.Items.Clear()
            'getting cities in listview from selected group
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try

        Me.rebindCityGroupProduct()
    End Sub

    Private Sub rebindCityGroupProduct()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Try
                For Each chk As ListItem In chkCityGroup.Items
                    If chk.Selected = True Then
                        Dim selectedCityGroupID As String = chk.Value.ToString()
                        Using cmd As New MySqlCommand()
                            cmd.CommandText = "SELECT CityGroupID, CityID FROM CityGroupList WHERE CityGroupID = @CityGroupID"
                            cmd.Parameters.AddWithValue("@CityGroupID", selectedCityGroupID)
                            cmd.Connection = con
                            'con.Open()
                            Using sda As New MySqlDataAdapter(cmd)
                                Dim dt As New DataTable()
                                sda.Fill(dt)
                                lvCitiesFromGroup.DataSource = dt
                                lvCitiesFromGroup.DataBind()

                                For Each CityItem As ListViewDataItem In lvCitiesFromGroup.Items
                                    addCheckedCityMapping(chk.Value, TryCast(CityItem.FindControl("lblGroupCityID"), Label).Text)
                                Next
                            End Using
                        End Using
                    End If
                Next
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using
    End Sub

    Private Sub addCheckedCityMapping(ByVal selectedCity As String, ByVal cityID As String)
        'add selected city for mapping with product
        Dim con As New MySqlConnection()
        Try
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
            Dim query As String = "INSERT into productcitymapping (ProductID, CityID, CityGroupID, CreatedDate, CreatedBy, IsActive) values (@ProductID, @CityID, @CityGroupID, @CreatedDate, @CreatedBy, @IsActive)"
            Dim cmd As New MySqlCommand(query, con)
            con.Open()
            cmd.Parameters.AddWithValue("@ProductID", productID.Value.ToString().Trim())
            cmd.Parameters.AddWithValue("@CityID", cityID)
            cmd.Parameters.AddWithValue("@CityGroupID", selectedCity)
            cmd.Parameters.AddWithValue("@CreatedDate", todaysDate)
            cmd.Parameters.AddWithValue("@CreatedBy", Session("staffname").ToString().Trim())
            cmd.Parameters.AddWithValue("@IsActive", 1)
            cmd.ExecuteNonQuery()
            lvCity.Items.Clear()
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try
    End Sub

    Protected Sub AddInclusionItems()
        Dim con As New MySqlConnection()
        Try
            Dim ItemCost As Integer = 0
            ItemCost = Val(txtIncQty.Text) * Val(txtInclusionItemPrice.Text)
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim query2 As String = "INSERT into flrl_product_inclusion (ProductID, Qty, Color, CategoryID, CategoryName, ItemName, SubcategoryID, UnitCost, Cost, CountryCode) values (@ProductID, @Qty, @Color, @CategoryID, @CategoryName, @ItemName, @SubcategoryID, @UnitCost, @Cost, @CountryCode)"
            Dim cmd As New MySqlCommand(query2, con)
            con.Open()
            cmd.Parameters.AddWithValue("@ProductID", productID.Value.ToString().Trim())
            cmd.Parameters.AddWithValue("@Qty", txtIncQty.Text)
            cmd.Parameters.AddWithValue("@Color", drpIncColor.SelectedItem.Text)
            cmd.Parameters.AddWithValue("@CategoryID", drpIncCategory.SelectedValue().ToString())
            cmd.Parameters.AddWithValue("@CategoryName", drpIncCategory.SelectedItem().ToString())
            cmd.Parameters.AddWithValue("@ItemName", txtIncItem.Text)
            cmd.Parameters.AddWithValue("@SubcategoryID", drpIncItems.SelectedValue.ToString().Trim())
            cmd.Parameters.AddWithValue("@UnitCost", txtInclusionItemPrice.Text)
            cmd.Parameters.AddWithValue("@Cost", ItemCost)
            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
            cmd.ExecuteNonQuery()
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try
        Me.bindInclusiveItems()
        txtIncQty.Text = ""
        drpIncColor.ClearSelection()
        drpIncItems.ClearSelection()
        'drpIncCategory.ClearSelection()
        txtIncItem.Text = ""
    End Sub

    Private Sub bindInclusiveItems()
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Try
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM flrl_product_inclusion WHERE ProductId = @productID"
                    cmd.Parameters.AddWithValue("@productID", productID.Value.ToString().Trim())
                    cmd.Connection = con
                    con.Open()
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        ViewState("inclItems") = dt
                        sda.Fill(dt)
                        lvIncl.DataSource = dt
                        lvIncl.DataBind()
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
        End Using

        If chkIsAutoPrice.Checked Then
            Dim totalAddonPrice As Integer = 0
            For Each itm As ListViewDataItem In lvIncl.Items
                Dim lblSingleItemPrice As Label = TryCast(itm.FindControl("lblSingleItemPrice"), Label)
                Dim lblUnitCost As Integer = 0
                If Not String.IsNullOrEmpty(TryCast(itm.FindControl("lblUnitCost"), Label).Text) Then
                    lblUnitCost = TryCast(itm.FindControl("lblUnitCost"), Label).Text
                End If

                Dim lblQty As Integer = 0
                If Not String.IsNullOrEmpty(TryCast(itm.FindControl("lblQty"), Label).Text) Then
                    lblQty = TryCast(itm.FindControl("lblQty"), Label).Text
                End If
                If Not String.IsNullOrEmpty(lblQty) AndAlso Not String.IsNullOrEmpty(lblUnitCost) Then
                    lblSingleItemPrice.Text = lblUnitCost * lblQty
                    totalAddonPrice += Val(lblSingleItemPrice.Text)
                End If
            Next

            txtSellingPrice.Text = totalAddonPrice
        Else
            txtSellingPrice.Text = Session("sellingPrice")
        End If
    End Sub

    Private Sub lvIncl_ItemCommand(sender As Object, e As ListViewCommandEventArgs) Handles lvIncl.ItemCommand
        Dim ID As Integer = e.CommandArgument
        If e.CommandName = "removeIncl" Then
            Dim con As New MySqlConnection
            Try
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "DELETE FROM flrl_product_inclusion WHERE ID = @ID"
                cmd.Parameters.AddWithValue("@ID", ID)
                cmd.ExecuteNonQuery()
            Catch ex As Exception
                Response.Write(ex)
            Finally
                con.Dispose()
            End Try
            Me.bindInclusiveItems()
        End If
    End Sub

    Private Sub populateInclusionItems()
        drpIncItems.Items.Clear()
        drpIncItems.Items.Add("")
        If Not drpCategory.SelectedValue = 93 Then
            drpIncCategory.Enabled = False
        Else
            drpIncCategory.Enabled = True
        End If
        Using conn As New MySqlConnection()
            conn.ConnectionString = ConfigurationManager _
                .ConnectionStrings("conio").ConnectionString()
            Try
                Dim query As String = String.Empty
                query = "SELECT ProductSubCategoryID, ProductSubCategoryName FROM productsubcategory WHERE ParentCategory = @category OR IsCommon = '1'"
                Using cmd As New MySqlCommand()
                    cmd.CommandText = query
                    cmd.Parameters.AddWithValue("@category", drpIncCategory.SelectedValue.ToString())
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Connection = conn
                    conn.Open()
                    Using sdr As MySqlDataReader = cmd.ExecuteReader()
                        While sdr.Read()
                            Dim item As New ListItem()
                            item.Text = sdr("ProductSubCategoryName").ToString()
                            item.Value = sdr("ProductSubCategoryID").ToString()
                            drpIncItems.Items.Add(item)
                        End While
                    End Using
                End Using
            Catch ex As Exception
                Response.Write(ex)
            Finally
                conn.Dispose()
            End Try
        End Using
    End Sub

    Private Sub drpIncItems_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpIncItems.SelectedIndexChanged
        Dim selectedItem As String = drpIncItems.SelectedItem.ToString()

        If selectedItem = "Other Items" Then
            txtIncItem.Visible = True
            txtIncItem.Text = ""
        Else
            txtIncItem.Visible = False
            txtIncItem.Text = selectedItem
        End If

        'get inclusion price per unit for auto pricing feature
        If chkIsAutoPrice.Checked = True Then
            Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
            Using con As New MySqlConnection(conString)
                Try
                    Dim query As String = "SELECT Cost FROM flrl_inclusion_price WHERE SubCategoryID = @SubCategoryID AND CountryCode = @CountryCode"

                    Using cmd As New MySqlCommand(query)
                        Using sda As New MySqlDataAdapter()
                            cmd.Parameters.AddWithValue("@SubCategoryID", drpIncItems.SelectedValue.ToString().Trim())
                            cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                            cmd.Connection = con
                            con.Open()
                            sda.SelectCommand = cmd
                            Using dt As New DataTable()
                                sda.Fill(dt)
                                If dt.Rows.Count > 0 Then
                                    txtInclusionItemPrice.Text = dt.Rows(0)("Cost").ToString().Trim()
                                Else
                                    txtInclusionItemPrice.Text = "0"
                                End If
                            End Using
                        End Using
                    End Using
                Catch ex As Exception
                    Response.Write(ex)
                Finally
                    con.Dispose()
                End Try
            End Using
        Else
            txtInclusionItemPrice.Text = "0"
        End If
    End Sub

    Private Sub makeInclusiveItemsString()
        If lvIncl.Items.Count > 0 Then
            For Each itm As ListViewDataItem In lvIncl.Items
                If Not TryCast(itm.FindControl("lblItem"), Label).Text = "Addon" Then
                    inclString += "<li>" + TryCast(itm.FindControl("lblQty"), Label).Text + " " + TryCast(itm.FindControl("lblColor"), Label).Text + " " + TryCast(itm.FindControl("lblItem"), Label).Text + "</li>"
                End If
            Next

            txtInclusion.Text = "<ul>" + inclString + "</ul>"
        End If
    End Sub

    Private Sub chkTagList_SelectedIndexChanged(sender As Object, e As EventArgs) Handles chkTagList.SelectedIndexChanged
        Dim needToday = "NEED TODAY"
        For Each chk As ListItem In chkTagList.Items
            If chkTagList.Items.FindByText(needToday).Selected = True Then
                drpMinDeliveryDay.ClearSelection()
                drpMinDeliveryDay.Items.FindByValue("0").Selected = True
                Exit For
            Else
                drpMinDeliveryDay.ClearSelection()
                drpMinDeliveryDay.Items.FindByValue("1").Selected = True
            End If
        Next
    End Sub

    Private Sub drpMinDeliveryDay_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpMinDeliveryDay.SelectedIndexChanged
        Dim needToday = "NEED TODAY"
        If drpMinDeliveryDay.Items.FindByValue("2").Selected = True OrElse drpMinDeliveryDay.Items.FindByValue("1").Selected = True Then
            chkTagList.Items.FindByText(needToday).Selected = False
        Else
            chkTagList.Items.FindByText(needToday).Selected = True
        End If
    End Sub

    Private Sub drpIncCategory_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpIncCategory.SelectedIndexChanged
        Me.populateInclusionItems()
    End Sub

    Private Sub chkIsAutoPrice_CheckedChanged(sender As Object, e As EventArgs) Handles chkIsAutoPrice.CheckedChanged
        Me.bindInclusiveItems()
    End Sub
End Class
