
Imports System.Net.Mail

Partial Class admin_email_test
    Inherits System.Web.UI.Page

    Private Sub admin_email_test_Load(sender As Object, e As EventArgs) Handles Me.Load
        Try
            Dim mail As MailMessage = New MailMessage()
            mail.From = New MailAddress("order@floralindia.com")
            mail.To.Add("suraj.creator@gmail.com")
            mail.Subject = "This is a test email from C# script"
            mail.Body = "This is a test email from C# script"
            Dim smtp As SmtpClient = New SmtpClient("103.120.176.195")
            smtp.Credentials = New System.Net.NetworkCredential("hosting@floralindia.com", "%jG1u7k7")
            smtp.Send(mail)
            Response.Write("Test mail sent")
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub
End Class
