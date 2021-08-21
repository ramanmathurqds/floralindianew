<%@ Page Language="VB" AutoEventWireup="false" CodeFile="cron-send-personal-reminder.aspx.vb" Inherits="admin_cron_send_personal_reminder" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
        <asp:Panel ID="pnlHidden" runat="server" Visible="false">
            <asp:Label ID="lblReminderEmail" runat="server"></asp:Label>
            <asp:Label ID="lblReminderContact" runat="server"></asp:Label>
            <asp:Label ID="lblReminderUserID" runat="server"></asp:Label>
            <asp:Label ID="lblReminderUserName" runat="server"></asp:Label>
            <asp:Label ID="lblReminderTitle" runat="server"></asp:Label>
        </asp:Panel>
    </form>
</body>
</html>
