Imports MySql.Data.MySqlClient
Imports System.Web.Services

Partial Class service
    Inherits System.Web.UI.Page

    <WebMethod()>
    Public Shared Function getSubCategories(ByVal category As String) As List(Of _subCat)
        Dim constr As String = ConfigurationManager.ConnectionStrings("conio").ConnectionString
        Using con As New MySqlConnection(constr)
            Using cmd As New MySqlCommand("SELECT * FROM ProductSubCategory WHERE IsActive = 'True' AND ParentCategory = @parent")
                cmd.Connection = con
                cmd.Parameters.AddWithValue("@parent", category)
                Dim subCategories As New List(Of _subCat)()
                con.Open()
                Using sdr As MySqlDataReader = cmd.ExecuteReader()
                    While sdr.Read()
                        subCategories.Add(New _subCat() With {
                         .ProductSubCategoryID = sdr("ProductSubCategoryID").ToString(),
                         .ProductSubCategoryName = sdr("ProductSubCategoryName").ToString()
                        })
                    End While
                End Using
                con.Close()
                Return subCategories
            End Using
        End Using
    End Function

    <WebMethod>
    Public Shared Function insertCitiesForNewCityAddedInGroup(ByVal cityGroupProduct As List(Of CityGroupProduct)) As String
        Dim strCityGroupProduct As String = String.Empty

        For Each cgp As CityGroupProduct In cityGroupProduct
            strCityGroupProduct += String.Format("{0} - {1}  - {2}" & vbLf, cgp.ProductID, cgp.CityID, cgp.CityGroupID)
            Using con As New MySqlConnection()
                con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
                Using cmd As New MySqlCommand()
                    cmd.CommandText = "INSERT INTO productcitymapping (ProductID, CityID, CityGroupID, IsActive) VALUES(@productID, @cityID, @CityGroupID, @status)"
                    cmd.Parameters.AddWithValue("@productID", cgp.ProductID)
                    cmd.Parameters.AddWithValue("@cityID", cgp.CityID)
                    cmd.Parameters.AddWithValue("@CityGroupID", cgp.CityGroupID)
                    cmd.Parameters.AddWithValue("@status", 1)
                    cmd.Connection = con
                    con.Open()
                    cmd.ExecuteNonQuery()
                    con.Close()
                End Using
            End Using
        Next

        Return strCityGroupProduct
    End Function

    'this service is called while adding / updating products from admin panel
    <WebMethod()>
    Public Shared Sub addInCityMapping(cityData As cityData)
        Using con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Using cmd As New MySqlCommand()
                cmd.CommandText = "INSERT INTO productcitymapping (ProductID, CityID, CityGroupID, IsActive) VALUES(@productID, @cityID, @CityGroupID, @status)"
                cmd.Parameters.AddWithValue("@productID", cityData.productID)
                cmd.Parameters.AddWithValue("@cityID", cityData.cityID)
                cmd.Parameters.AddWithValue("@CityGroupID", "0")
                cmd.Parameters.AddWithValue("@status", 1)
                cmd.Connection = con
                con.Open()
                cmd.ExecuteNonQuery()
                con.Close()
            End Using
        End Using
    End Sub

    'used for city-group-details.aspx page
    <WebMethod()>
    Public Shared Sub addInCityGroupMapping(cityGroupData As cityGroupData)
        Using con As New MySqlConnection()
            con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
            Using cmd As New MySqlCommand()
                cmd.CommandText = "INSERT INTO CityGroupList (CityGroupID, CityID, IsActive) Values(@CityGroupID, @CityID, @status)"
                cmd.Parameters.AddWithValue("@CityGroupID", cityGroupData.CityGroupID)
                cmd.Parameters.AddWithValue("@cityID", cityGroupData.cityID)
                cmd.Parameters.AddWithValue("@status", 1)
                cmd.Connection = con
                con.Open()
                cmd.ExecuteNonQuery()
                con.Close()
            End Using
        End Using
    End Sub

    <WebMethod()>
    Public Shared Sub deleteCityProductMapping(cityData As cityData)
        Dim con As New MySqlConnection
        Dim cmd As New MySqlCommand
        con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
        cmd.Connection = con
        con.Open()
        cmd.CommandText = "DELETE FROM productcitymapping WHERE CityID = @cityID AND ProductID = @productID"
        cmd.Parameters.AddWithValue("@cityID", cityData.cityID)
        cmd.Parameters.AddWithValue("@productID", cityData.productID)
        cmd.ExecuteNonQuery()
        con.Close()
    End Sub

    '<WebMethod()>
    'Public Shared Sub deleteCityFromGroup(cityGroupData As cityGroupData)
    '    Dim con As New MySqlConnection
    '    Dim cmd As New MySqlCommand
    '    con.ConnectionString = ConfigurationManager.ConnectionStrings("conio").ConnectionString()
    '    cmd.Connection = con
    '    con.Open()
    '    cmd.CommandText = "DELETE FROM CityGroupList WHERE CityGroupListID = @CityGroupListID"
    '    cmd.Parameters.AddWithValue("@CityGroupListID", cityGroupData.CityGroupListID)
    '    cmd.ExecuteNonQuery()
    '    con.Close()
    'End Sub

    Public Class _subCat
        Public Property ProductSubCategoryID() As Integer
        Public Property ProductSubCategoryName() As String
    End Class

    Public Class cityData
        Public Property cityID() As String
            Get
                Return _cityID
            End Get
            Set(value As String)
                _cityID = value
            End Set
        End Property
        Private _cityID As String
        Public Property productID() As String
            Get
                Return _productID
            End Get
            Set(value As String)
                _productID = value
            End Set
        End Property
        Private _productID As String
    End Class

    Public Class cityGroupData
        Public Property cityID() As String
            Get
                Return _cityID
            End Get
            Set(value As String)
                _cityID = value
            End Set
        End Property
        Private _cityID As String
        Public Property CityGroupID() As String
            Get
                Return _CityGroupID
            End Get
            Set(value As String)
                _CityGroupID = value
            End Set
        End Property
        Private _CityGroupID As String
    End Class

    Public Class CityGroupProduct
        Public Property ProductID As String
        Public Property CityID As String
        Public Property CityGroupID As String
    End Class
End Class
