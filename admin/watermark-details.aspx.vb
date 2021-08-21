Imports System.Data
Imports MySql.Data.MySqlClient

Partial Class WaterMark_details
    Inherits System.Web.UI.Page

    Private Sub WaterMark_details_Load(sender As Object, e As EventArgs) Handles Me.Load
        If Not Me.IsPostBack Then
            Dim action As String = Request.QueryString("action").ToString

            If action = "edit" Then
                Me.getWaterMarkDetails()
                btnAdd.Visible = False
            ElseIf action = "new" Then
                btnUpdate.Visible = False
                btnDelete.Visible = False
            End If
        End If
    End Sub

    Private Sub getWaterMarkDetails()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Dim query As String = "SELECT * FROM WaterMark WHERE WaterMarkID = @ID"
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
                                lblWatermarkName.Text = dt.Rows(0)("WaterMarkName").ToString().Trim()
                                txtWatermarkName.Text = lblWatermarkName.Text
                                lblWatermarkImage.Text = dt.Rows(0)("WaterMarkImage").ToString().Trim()
                                imgWatermark.ImageUrl = lblWatermarkImage.Text
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
            Me.imgUploader()

            Dim con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO WaterMark (WaterMarkName, WaterMarkImage) Values(@name, @WaterMarkImage)"
            cmd.Parameters.AddWithValue("@name", txtWatermarkName.Text)
            cmd.Parameters.AddWithValue("@WaterMarkImage", lblWatermarkImage.Text)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
            Response.Redirect("/admin/WaterMark.aspx")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub updateEvent()
        Try
            Me.imgUploader()
            Dim ID As String = Request.QueryString("id").ToString
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "UPDATE WaterMark SET WaterMarkName = @name, WaterMarkImage = @WaterMarkImage WHERE WaterMarkID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.Parameters.AddWithValue("@name", txtWatermarkName.Text)
            cmd.Parameters.AddWithValue("@WaterMarkImage", lblWatermarkImage.Text)
            cmd.ExecuteNonQuery()
            con.Close()
            Me.getWaterMarkDetails()
            pnlSuccess.Visible = True
            successMessage.Text = "WaterMark updated successfully."
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Protected Sub deleteEvent()
        Try
            Dim ID As String = Request.QueryString("id").ToString
            Dim con As New MySqlConnection
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = "DELETE FROM WaterMark WHERE WaterMarkID = @ID"
            cmd.Parameters.AddWithValue("@ID", ID)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub imgUploader()
        Dim commonPath As String = "/Content/assets/images/common/"

        If fileWatermark.FileName <> "" Then
            Dim file As New System.IO.FileInfo(fileWatermark.PostedFile.FileName)
            Dim newname As String = file.Name.Remove((file.Name.Length - file.Extension.Length))
            newname = (newname & System.DateTime.Now.ToString("_ddMMyyhhmmss")) + file.Extension
            fileWatermark.SaveAs(Server.MapPath(commonPath + newname))
            lblWatermarkImage.Text = Convert.ToString(commonPath + newname)
        End If
    End Sub
End Class
