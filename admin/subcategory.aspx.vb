Imports System
Imports System.Configuration
Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class subcategory
    Inherits System.Web.UI.Page

    Private Sub subcategory_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Me.bindSubCategories()
        End If
    End Sub

    Private Sub bindSubCategories()
        Try
            Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ToString()
            Using con As New MySqlConnection(constr)
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "SELECT * FROM ProductSubCategory WHERE CountryCode = @CountryCode ORDER BY SequenceNo"
                    cmd.Parameters.AddWithValue("@CountryCode", Session("country").ToString().Trim())
                    cmd.Connection = con
                    Using sda As New MySqlDataAdapter(cmd)
                        Dim dt As New DataTable()
                        sda.Fill(dt)
                        lvCategories.DataSource = dt
                        lvCategories.DataBind()
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub lvCategories_ItemDataBound(sender As Object, e As ListViewItemEventArgs) Handles lvCategories.ItemDataBound
        Dim parentCatgoryID As String = TryCast(e.Item.FindControl("lblParentCategory"), Label).ToolTip.ToString().Trim()
        Dim lblParentCategory As Label = TryCast(e.Item.FindControl("lblParentCategory"), Label)
        Dim lblStatus As Label = TryCast(e.Item.FindControl("lblActive"), Label)
        Dim isActive As String = lblStatus.Text
        If isActive = "True" OrElse isActive = "1" Then
            lblStatus.Text = "active"
        Else
            lblStatus.Text = "inactive"
        End If

        Dim query As String = "SELECT Name FROM mastercategory WHERE ID = @ID AND SubMenuID = '1'"
        Dim conString As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(conString)
            Using cmd As New MySqlCommand(query)
                Using sda As New MySqlDataAdapter()
                    cmd.Parameters.AddWithValue("@ID", parentCatgoryID)
                    cmd.Connection = con
                    sda.SelectCommand = cmd
                    Using dt As New DataTable()
                        sda.Fill(dt)
                        If dt.Rows.Count > 0 Then
                            lblParentCategory.Text = dt.Rows(0)("Name").ToString().Trim()
                        End If
                    End Using
                End Using
            End Using
        End Using
    End Sub
End Class
