<%@ Page Language="VB" AutoEventWireup="false" CodeFile="revive-mapping.aspx.vb" Inherits="admin_revive_mapping" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
        <div>
            <div>
                <asp:ListView ID="lvOccasion" runat="server">
                    <ItemTemplate>
                        <asp:Label ID="lblOccationID" runat="server" Text='<%# Eval("ID") %>'></asp:Label>
                    </ItemTemplate>
                </asp:ListView>
            </div>
            <hr />
            <table>
                <asp:ListView ID="lvProducts" runat="server">
                    <ItemTemplate>
                        <tr>
                            <td><asp:Label ID="lblProduct" runat="server" Text='<%# Eval("ProductID") %>'></asp:Label></td>
                        </tr>
                    </ItemTemplate>
                </asp:ListView>
            </table>

            <div>
                <asp:Button ID="btnSave" runat="server" Text="Save" />
            </div>
        </div>
    </form>
</body>
</html>
