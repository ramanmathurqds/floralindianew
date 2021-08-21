Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class promo_code_details
    Inherits System.Web.UI.Page
    Dim todaysDate As String = DateTime.Now.ToString("yyyy-MM-dd").ToString().Trim()
    Private Sub promo_code_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.populateCountries()

            Dim action As String = Request.QueryString("action").ToString
            Me.populateCountries()
            btnDelete.Visible = False
            If action = "edit" Then
                Me.getPromoDetails()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
            End If
        End If
    End Sub

    Private Sub getPromoDetails()
        Try
            Dim ID As String = Request.QueryString("ID").ToString
            Dim query As String = "SELECT * FROM flrl_promocode WHERE ID = @ID"
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
                                lblPromoName.Text = dt.Rows(0)("PromoName").ToString().Trim()
                                txtPromoName.Text = lblPromoName.Text
                                txtPromoCode.Text = dt.Rows(0)("PromoCode").ToString().Trim()
                                txtPercent.Text = dt.Rows(0)("DiscountPercent").ToString().Trim()
                                txtAmount.Text = dt.Rows(0)("DiscountAmount").ToString().Trim()
                                Dim redeemType As String = dt.Rows(0)("RedeemType").ToString().Trim()
                                drpRedeem.Items.FindByValue(redeemType).Selected = True
                                txtMaxAmountLimit.Text = dt.Rows(0)("DiscountMaxValue").ToString().Trim()
                                Dim selectedCountry As String = dt.Rows(0)("CountryCode").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedCountry) Then
                                    drpCountry.Items.FindByValue(selectedCountry).Selected = True
                                End If

                                Me.populateCategories()

                                Dim selectedDiscountType As String = dt.Rows(0)("DiscountType").ToString().Trim()
                                If Not String.IsNullOrEmpty(selectedDiscountType) Then
                                    drpDiscountType.Items.FindByText(selectedDiscountType).Selected = True
                                End If

                                Dim selectedCategory As String = dt.Rows(0)("CategoryID").ToString
                                If Not String.IsNullOrEmpty(selectedCategory) AndAlso Not selectedCategory = 0 Then
                                    drpCategory.Items.FindByValue(selectedCategory).Selected = True
                                End If

                                Dim expiryDate As DateTime = Convert.ToDateTime(dt.Rows(0)("PromoExpiry").ToString())
                                txtExpiryDate.Text = String.Format("{0:yyyy-MM-dd}", expiryDate)
                                txtUsageLimit.Text = dt.Rows(0)("UsageLimit").ToString().Trim()

                                Dim isActive = dt.Rows(0)("IsActive").ToString().Trim()
                                If isActive = True Then
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

    Protected Sub addPromoCode()
        If Me.Page.IsValid Then
            Try
                Dim isActive As String = 0
                If chkActive.Checked = True Then
                    isActive = 1
                End If



                Dim selectedCategory As Integer = 0
                If Not String.IsNullOrEmpty(drpCategory.SelectedValue.ToString()) Then
                    selectedCategory = drpCategory.SelectedValue.ToString().Trim()
                End If

                Dim con As New MySqlConnection()
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                Dim cmd As New MySqlCommand()
                cmd.CommandText = "INSERT INTO flrl_promocode (PromoName, PromoCode, RedeemType, DiscountPercent, DiscountAmount, DiscountMaxValue, CountryCode, CountryName, DiscountType, CategoryID, PromoExpiry, UsageLimit, IsActive, CreatedBy, CreatedDate) Values(@PromoName, @PromoCode, @RedeemType, @DiscountPercent, @DiscountAmount, @DiscountMaxValue, @CountryCode, @CountryName, @DiscountType, @CategoryID, @PromoExpiry, @UsageLimit, @IsActive, @CreatedBy, @CreatedDate)"
                cmd.Parameters.AddWithValue("@PromoName", txtPromoName.Text)
                cmd.Parameters.AddWithValue("@PromoCode", txtPromoCode.Text)
                cmd.Parameters.AddWithValue("@RedeemType", drpRedeem.SelectedValue.ToString().Trim())
                cmd.Parameters.AddWithValue("@DiscountPercent", txtPercent.Text)
                cmd.Parameters.AddWithValue("@DiscountAmount", txtAmount.Text)
                cmd.Parameters.AddWithValue("@DiscountMaxValue", txtMaxAmountLimit.Text)
                cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString())
                cmd.Parameters.AddWithValue("@CountryName", drpCountry.SelectedItem.ToString())
                cmd.Parameters.AddWithValue("@DiscountType", drpDiscountType.SelectedItem.ToString())
                cmd.Parameters.AddWithValue("@CategoryID", selectedCategory)
                cmd.Parameters.AddWithValue("@PromoExpiry", txtExpiryDate.Text)
                cmd.Parameters.AddWithValue("@UsageLimit", txtUsageLimit.Text)
                cmd.Parameters.AddWithValue("@IsActive", isActive)
                cmd.Parameters.AddWithValue("@CreatedBy", Session("staffname").ToString().Trim())
                cmd.Parameters.AddWithValue("@CreatedDate", todaysDate)
                cmd.Connection = con
                con.Open()
                cmd.ExecuteNonQuery()
                con.Close()
                Response.Redirect("/admin/promo-codes.aspx")
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If
    End Sub

    Protected Sub updatePromocode()
        If Me.Page.IsValid Then
            Try
                Dim isActive As String = 0
                If chkActive.Checked = True Then
                    isActive = 1
                End If

                Dim selectedCategory As Integer = 0
                If Not String.IsNullOrEmpty(drpCategory.SelectedValue.ToString()) Then
                    selectedCategory = drpCategory.SelectedValue.ToString().Trim()
                End If

                Dim ID As String = Request.QueryString("id").ToString().Trim
                Dim con As New MySqlConnection
                Dim cmd As New MySqlCommand
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                cmd.Connection = con
                con.Open()
                cmd.CommandText = "UPDATE flrl_promocode SET PromoName = @PromoName, PromoCode = @PromoCode, RedeemType = @RedeemType, DiscountPercent = @DiscountPercent, DiscountAmount = @DiscountAmount, DiscountMaxValue = @DiscountMaxValue, CountryCode = @CountryCode, CountryName = @CountryName, DiscountType = @DiscountType, CategoryID = @CategoryID, PromoExpiry = @PromoExpiry, UsageLimit = @UsageLimit, IsActive = @IsActive, UpdatedBy = @UpdatedBy, UpdatedDate = @UpdatedDate WHERE ID = @ID"
                cmd.Parameters.AddWithValue("@ID", ID)
                cmd.Parameters.AddWithValue("@PromoName", txtPromoName.Text)
                cmd.Parameters.AddWithValue("@PromoCode", txtPromoCode.Text)
                cmd.Parameters.AddWithValue("@RedeemType", drpRedeem.SelectedValue.ToString().Trim())
                cmd.Parameters.AddWithValue("@DiscountPercent", txtPercent.Text)
                cmd.Parameters.AddWithValue("@DiscountAmount", txtAmount.Text)
                cmd.Parameters.AddWithValue("@DiscountMaxValue", txtMaxAmountLimit.Text)
                cmd.Parameters.AddWithValue("@CountryCode", drpCountry.SelectedValue.ToString())
                cmd.Parameters.AddWithValue("@CountryName", drpCountry.SelectedItem.ToString())
                cmd.Parameters.AddWithValue("@DiscountType", drpDiscountType.SelectedItem.ToString())
                cmd.Parameters.AddWithValue("@CategoryID", selectedCategory)
                cmd.Parameters.AddWithValue("@PromoExpiry", txtExpiryDate.Text)
                cmd.Parameters.AddWithValue("@UsageLimit", txtUsageLimit.Text)
                cmd.Parameters.AddWithValue("@IsActive", isActive)
                cmd.Parameters.AddWithValue("@UpdatedBy", Session("staffname").ToString().Trim())
                cmd.Parameters.AddWithValue("@UpdatedDate", todaysDate)
                cmd.ExecuteNonQuery()
                con.Close()
                Me.getPromoDetails()
                pnlSuccess.Visible = True
                successMessage.Text = "Promo code updated succefully"
            Catch ex As Exception
                Response.Write(ex)
            End Try
        End If
    End Sub

    Protected Sub deletePromcode()
        Try
            Dim ID As String = Request.QueryString("id").ToString().Trim
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM flrl_promocode WHERE ID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/promo-codes.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub drpCountry_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpCountry.SelectedIndexChanged
        Me.populateCategories()
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

    Private Sub populateCategories()
        Try
            drpCategory.ClearSelection()
            Using conn As New MySqlConnection()
                conn.ConnectionString = ConfigurationManager _
                    .ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT Name, ID, SubMenuID FROM masterCategory WHERE isActive = '1' AND CountryCode = @countryCode"
                    cmd.Parameters.AddWithValue("@countryCode", drpCountry.SelectedValue.ToString)
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

    Private Sub drpDiscountType_SelectedIndexChanged(sender As Object, e As EventArgs) Handles drpDiscountType.SelectedIndexChanged
        Dim selectedDiscountType As String = drpDiscountType.SelectedItem.ToString
        If Not String.IsNullOrEmpty(selectedDiscountType) Then
            If selectedDiscountType = "Cart" Then
                drpCategory.ClearSelection()
                rvCategory.Enabled = False
                drpCategory.Enabled = False
            Else
                rvCategory.Enabled = True
                drpCategory.Enabled = True
            End If
        End If
    End Sub
End Class
