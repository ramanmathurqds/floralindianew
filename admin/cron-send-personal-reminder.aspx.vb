Imports System.Data
Imports System.Globalization
Imports System.IO
Imports System.Net.Mail
Imports K4os.Compression.LZ4.Internal
Imports MySql.Data.MySqlClient
Partial Class admin_cron_send_personal_reminder
    Inherits System.Web.UI.Page
    Dim DayLimit1 As Integer = ConfigurationManager.AppSettings("DayLimit1").ToString().Trim()
    Dim DayLimit2 As Integer = ConfigurationManager.AppSettings("DayLimit2").ToString().Trim()
    Dim DayLimit3 As Integer = ConfigurationManager.AppSettings("DayLimit3").ToString().Trim()
    Private Sub admin_cron_send_personal_reminder_Load(sender As Object, e As EventArgs) Handles Me.Load
        'set email settings in session
        Session("EmailSendingID") = ConfigurationManager.AppSettings("EmailSendingID").ToString().Trim()
        Session("EmailSendingPassword") = ConfigurationManager.AppSettings("EmailSendingCode").ToString().Trim()
        Me.triggerReminder1()
        Me.triggerReminder2()
        Me.triggerReminderAuto1()
        Me.triggerReminderAuto2()
    End Sub

    'reminder 1 triggeres on defined(in web.config) days before of reminder date
    Private Sub triggerReminder1()
        Try
            Dim query As String = "SELECT * FROM `flrl_personal_reminder` WHERE ReminderType = 'Manual' AND DATE(`ReminderDate`) <= DATE_ADD(CURDATE(), INTERVAL " & DayLimit1 & " DAY) AND IsNotified1 = 0 AND IsActive = 1 LIMIT 1"
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
                                Dim ReminderID As String = dt.Rows(0)("ID").ToString().Trim()
                                lblReminderUserID.Text = dt.Rows(0)("UserID").ToString().Trim()
                                lblReminderUserName.Text = dt.Rows(0)("User_name").ToString().Trim()
                                lblReminderEmail.Text = dt.Rows(0)("Email").ToString().Trim()
                                lblReminderContact.Text = dt.Rows(0)("ContactNumber").ToString().Trim()
                                lblReminderTitle.Text = dt.Rows(0)("ReminderName").ToString().Trim()
                                Try
                                    Dim reader As New StreamReader(Server.MapPath("/emailers/personal-reminder.html"))
                                    Dim readFile As String = reader.ReadToEnd()
                                    Dim ReminderBodyContent = readFile

                                    ReminderBodyContent = ReminderBodyContent.Replace("$$User$$", lblReminderUserName.Text)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$ReminderName$$", lblReminderTitle.Text)

                                    'send reminder email
                                    Dim mail As MailMessage = New MailMessage()
                                    mail.From = New MailAddress("info@floralindia.com")
                                    mail.To.Add(lblReminderEmail.Text)
                                    mail.Subject = "Reminder for " & lblReminderTitle.Text & " - Floral India"
                                    mail.Body = ReminderBodyContent
                                    mail.IsBodyHtml = True
                                    Dim smtp As SmtpClient = New SmtpClient("103.120.176.195")
                                    smtp.Credentials = New System.Net.NetworkCredential(Session("EmailSendingID").ToString().Trim(), Session("EmailSendingPassword").ToString().Trim())
                                    smtp.Send(mail)
                                    Me.updateReminderStatus(ReminderID, "Notify1")
                                Catch ex As Exception
                                    Response.Write(ex)
                                End Try
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    'reminder 2 triggeres on same day of reminder date
    Private Sub triggerReminder2()
        Try
            Dim query As String = "SELECT * FROM `flrl_personal_reminder` WHERE ReminderType = 'Manual' AND DATE(`ReminderDate`) = CURDATE() AND IsNotified = '0' AND IsActive = '1' LIMIT 1"
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
                                Dim ReminderID As String = dt.Rows(0)("ID").ToString().Trim()
                                lblReminderUserID.Text = dt.Rows(0)("UserID").ToString().Trim()
                                lblReminderUserName.Text = dt.Rows(0)("User_name").ToString().Trim()
                                lblReminderEmail.Text = dt.Rows(0)("Email").ToString().Trim()
                                lblReminderContact.Text = dt.Rows(0)("ContactNumber").ToString().Trim()
                                lblReminderTitle.Text = dt.Rows(0)("ReminderName").ToString().Trim()
                                Try
                                    Dim reader As New StreamReader(Server.MapPath("/emailers/personal-reminder.html"))
                                    Dim readFile As String = reader.ReadToEnd()
                                    Dim ReminderBodyContent = readFile

                                    ReminderBodyContent = ReminderBodyContent.Replace("$$User$$", lblReminderUserName.Text)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$ReminderName$$", lblReminderTitle.Text)

                                    'send reminder email
                                    Dim mail As MailMessage = New MailMessage()
                                    mail.From = New MailAddress("info@floralindia.com")
                                    mail.To.Add(lblReminderEmail.Text)
                                    mail.Subject = "Reminder for " & lblReminderTitle.Text & " - Floral India"
                                    mail.Body = ReminderBodyContent
                                    mail.IsBodyHtml = True
                                    Dim smtp As SmtpClient = New SmtpClient("103.120.176.195")
                                    smtp.Credentials = New System.Net.NetworkCredential(Session("EmailSendingID").ToString().Trim(), Session("EmailSendingPassword").ToString().Trim())
                                    smtp.Send(mail)
                                    Me.updateReminderStatus(ReminderID, "Notify2")
                                Catch ex As Exception
                                    Response.Write(ex)
                                End Try
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub


    'reminder1 triggers on defined(in web.config) days before of reminder date. These for auto set reminders
    Private Sub triggerReminderAuto1()
        Try
            Dim query As String = "SELECT * FROM `flrl_personal_reminder` WHERE ReminderType = 'Auto' AND DATE(`ReminderDate`) <= DATE_ADD(CURDATE(), INTERVAL " & DayLimit1 & " DAY) AND IsNotified1 = 0 AND IsActive = 1 LIMIT 1"
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
                                Dim ReminderID As String = dt.Rows(0)("ID").ToString().Trim()
                                lblReminderUserID.Text = dt.Rows(0)("UserID").ToString().Trim()
                                lblReminderUserName.Text = dt.Rows(0)("User_name").ToString().Trim()
                                lblReminderEmail.Text = dt.Rows(0)("Email").ToString().Trim()
                                lblReminderContact.Text = dt.Rows(0)("ContactNumber").ToString().Trim()
                                lblReminderTitle.Text = dt.Rows(0)("ReminderName").ToString().Trim()
                                Dim DiscountCode As String = dt.Rows(0)("DiscountCode").ToString().Trim()
                                Dim LastOrderDate As String = dt.Rows(0)("ReminderDate").ToString().Trim()
                                LastOrderDate = LastOrderDate.Substring(0, 10)
                                Dim ODSplit As String() = LastOrderDate.Split("-")
                                Dim daydt As String = ODSplit(2)
                                Dim monthIndex = ODSplit(1)
                                Dim MonthName As String = CultureInfo.CurrentCulture.DateTimeFormat.GetMonthName(monthIndex)
                                Try
                                    Dim reader As New StreamReader(Server.MapPath("/emailers/auto-reminder.html"))
                                    Dim readFile As String = reader.ReadToEnd()
                                    Dim ReminderBodyContent = readFile

                                    ReminderBodyContent = ReminderBodyContent.Replace("$$User$$", lblReminderUserName.Text)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$Receiver$$", lblReminderTitle.Text)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$ThisDate$$", daydt & " " & MonthName)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$DiscountCode$$", DiscountCode)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$DaysCount$$", ConfigurationManager.AppSettings("DayLimit2"))

                                    'send reminder email
                                    Dim mail As MailMessage = New MailMessage()
                                    mail.From = New MailAddress("info@floralindia.com")
                                    mail.To.Add(lblReminderEmail.Text)
                                    mail.Subject = "A gift reminder for " & lblReminderTitle.Text & " special occasion - Floral India"
                                    mail.Body = ReminderBodyContent
                                    mail.IsBodyHtml = True
                                    Dim smtp As SmtpClient = New SmtpClient("103.120.176.195")
                                    smtp.Credentials = New System.Net.NetworkCredential(Session("EmailSendingID").ToString().Trim(), Session("EmailSendingPassword").ToString().Trim())
                                    smtp.Send(mail)
                                    Me.updateReminderStatus(ReminderID, "Notify1")
                                Catch ex As Exception
                                    Response.Write(ex)
                                End Try
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    'reminder2 triggers on defined(in web.config) days before of reminder date. These for auto set reminders
    Private Sub triggerReminderAuto2()
        Try
            Dim query As String = "SELECT * FROM `flrl_personal_reminder` WHERE ReminderType = 'Auto' AND DATE(`ReminderDate`) <= DATE_ADD(CURDATE(), INTERVAL " & DayLimit1 & " DAY) AND IsNotified1 = 0 AND IsActive = 1 LIMIT 1"
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
                                Dim ReminderID As String = dt.Rows(0)("ID").ToString().Trim()
                                lblReminderUserID.Text = dt.Rows(0)("UserID").ToString().Trim()
                                lblReminderUserName.Text = dt.Rows(0)("User_name").ToString().Trim()
                                lblReminderEmail.Text = dt.Rows(0)("Email").ToString().Trim()
                                lblReminderContact.Text = dt.Rows(0)("ContactNumber").ToString().Trim()
                                lblReminderTitle.Text = dt.Rows(0)("ReminderName").ToString().Trim()
                                Dim DiscountCode As String = dt.Rows(0)("DiscountCode").ToString().Trim()
                                Dim LastOrderDate As String = dt.Rows(0)("ReminderDate").ToString().Trim()
                                LastOrderDate = LastOrderDate.Substring(0, 10)
                                Dim ODSplit As String() = LastOrderDate.Split("-")
                                Dim daydt As String = ODSplit(2)
                                Dim monthIndex = ODSplit(1)
                                Dim MonthName As String = CultureInfo.CurrentCulture.DateTimeFormat.GetMonthName(monthIndex)
                                Try
                                    Dim reader As New StreamReader(Server.MapPath("/emailers/auto-reminder.html"))
                                    Dim readFile As String = reader.ReadToEnd()
                                    Dim ReminderBodyContent = readFile

                                    ReminderBodyContent = ReminderBodyContent.Replace("$$User$$", lblReminderUserName.Text)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$Receiver$$", lblReminderTitle.Text)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$ThisDate$$", daydt & " " & MonthName)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$DiscountCode$$", DiscountCode)
                                    ReminderBodyContent = ReminderBodyContent.Replace("$$DaysCount$$", ConfigurationManager.AppSettings("DayLimit3"))

                                    'send reminder email
                                    Dim mail As MailMessage = New MailMessage()
                                    mail.From = New MailAddress("info@floralindia.com")
                                    mail.To.Add(lblReminderEmail.Text)
                                    mail.Subject = "A gift reminder for " & lblReminderTitle.Text & " special occasion - Floral India"
                                    mail.Body = ReminderBodyContent
                                    mail.IsBodyHtml = True
                                    Dim smtp As SmtpClient = New SmtpClient("103.120.176.195")
                                    smtp.Credentials = New System.Net.NetworkCredential(Session("EmailSendingID").ToString().Trim(), Session("EmailSendingPassword").ToString().Trim())
                                    smtp.Send(mail)
                                    Me.updateReminderStatus(ReminderID, "Notify2")
                                Catch ex As Exception
                                    Response.Write(ex)
                                End Try
                            End If
                        End Using
                    End Using
                End Using
            End Using
        Catch ex As Exception
            Response.Write(ex)
        End Try
    End Sub

    Private Sub updateReminderStatus(ByVal ReminderID As String, ByVal Notify As String)
        Dim con As New MySqlConnection
        Dim query As String = String.Empty
        If Notify = "Notify1" Then
            query = "UPDATE flrl_personal_reminder SET IsNotified1 = '1' WHERE ID = @ID"
        ElseIf Notify = "Notify2" Then
            query = "UPDATE flrl_personal_reminder SET IsNotified2 = '1' WHERE ID = @ID"
        End If
        Try
            Dim cmd As New MySqlCommand
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            cmd.Connection = con
            con.Open()
            cmd.CommandText = query
            cmd.Parameters.AddWithValue("@ID", ReminderID)
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try

        Try
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Dim cmd As New MySqlCommand()
            cmd.CommandText = "INSERT INTO flrl_personal_reminder_sent (ReminderID) Values(@ReminderID)"
            cmd.Parameters.AddWithValue("@ReminderID", ReminderID)
            cmd.Connection = con
            con.Open()
            cmd.ExecuteNonQuery()
            con.Close()
        Catch ex As Exception
            Response.Write(ex)
        Finally
            con.Dispose()
        End Try
    End Sub
End Class
