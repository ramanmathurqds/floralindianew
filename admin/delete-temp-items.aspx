<%@ Page Language="VB" AutoEventWireup="false" CodeFile="delete-temp-items.aspx.vb" Inherits="delete_temp_items" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
        <div>
            <asp:ListView ID="lvProducts" runat="server" Visible="false">
                <ItemTemplate>
                    <asp:Label ID="productID" runat="server" Text='<%# Eval("ProductID") %>'></asp:Label>
                </ItemTemplate>
            </asp:ListView>
        </div>
    </form>
</body>
</html>
