Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class promo_codes
    Inherits System.Web.UI.Page

    Private Sub bindPromoCodes()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT ID, PromoCode, PromoName, DiscountPercent, DiscountType, PromoExpiry, CountryName, IsActive FROM flrl_promocode WHERE IsActive = '1'"
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvPromoCode.DataSource = dt
                        lvPromoCode.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub promo_codes_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindPromoCodes()
        End If
    End Sub

    Private Sub lvPromoCode_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvPromoCode.ItemDataBound
        Dim lblStatus As Label = TryCast(e.Item.FindControl("lblActive"), Label)
        Dim isActive As String = lblStatus.Text

        If isActive = "True" Then
            lblStatus.Text = "active"
        Else
            lblStatus.Text = "inactive"
        End If
    End Sub
End Class
