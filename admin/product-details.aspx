<%@ Page Title="" Language="VB" MasterPageFile="MasterPage.master" AutoEventWireup="false" CodeFile="product-details.aspx.vb" Inherits="product_details" EnableEventValidation="false" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <asp:ScriptManager ID="sm1" runat="server" EnableCdn="true"></asp:ScriptManager>
    <asp:Panel ID="pnlHidden" runat="server" CssClass="d-none">
        <asp:Label ID="lblProductImage" runat="server" ClientIDMode="Static"></asp:Label>
    </asp:Panel>

    <asp:HiddenField ID="selectedCountry" runat="server" ClientIDMode="Static" />
    <asp:HiddenField ID="productID" runat="server" ClientIDMode="Static" />

    <div class="admin-page-container product-detail-page">
        <div class="row">
            <div class="col-12">
                <nav role="navigation" class="back-nav">
                    <a href="/admin/products.aspx?type=all"><i class="fas fa-angle-left"></i>Products</a>
                </nav>

                <div class="mt-3">
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <h1 class="common-title">
                                <asp:Label ID="lblProductName" runat="server" Text="Product Name"></asp:Label>
                            </h1>
                        </div>

                        <div class="col-12 col-xl-4">
                            <div class="row">
                                <div class="col-6">
                                    <asp:Button ID="btnUpdate1" runat="server" CssClass="c-btn c-btn-block c-btn-primary mb-3" Text="Save" OnClick="updateInProductTable" ValidationGroup="SaveProduct" />
                                    <asp:ValidationSummary ShowModelStateErrors="true" ShowSummary="false" ShowMessageBox="true" ID="vs1" runat="server" ValidationGroup="SaveProduct" />
                                </div>

                                <div class="col-6">
                                    <asp:Button ID="Button1" runat="server" CssClass="c-btn c-btn-block c-btn-danger mb-3" Text="Delete" OnClick="deleteProduct" onClientClick=" return confirm('Are you sure you want to delete this?')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="row">
                        <div class="col-12">
                            <asp:Panel ID="pnlSuccess" runat="server" Visible="false" ClientIDMode="Static">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <asp:Label ID="pnlMessage" runat="server"></asp:Label>
                            </asp:Panel>
                        </div>

                         <div class="col-12 col-xl-8 mb-3">
                            <div class="card-container mb-2">
                                <div class="ui-card">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group mb-5">
                                                <label for="drpCategory">Category</label>
                                                <asp:DropDownList ID="drpCategory" AutoPostBack="true" runat="server" CssClass="form-control" ClientIDMode="Static">
                                                    <asp:ListItem></asp:ListItem>
                                                </asp:DropDownList>
                                                <asp:RequiredFieldValidator ID="rv8" runat="server" ControlToValidate="drpCategory" ErrorMessage="Select Catrgory" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group mb-3">
                                        <label for="drpSubCategory">Sub Category</label>
                                        <asp:UpdatePanel ID="upSubCategory" runat="server">
                                            <ContentTemplate>
                                                <asp:DropDownList ID="drpSubCategory" runat="server" CssClass="form-control" ClientIDMode="Static">
                                        </asp:DropDownList>
                                                <asp:RequiredFieldValidator ID="rv9" runat="server" ControlToValidate="drpSubCategory" ErrorMessage="Select Subcatrgory" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                            </ContentTemplate>

                                            <Triggers>
                                                <asp:AsyncPostBackTrigger ControlID="drpCategory"  />
                                            </Triggers>
                                        </asp:UpdatePanel>
                                    </div>

                                    <asp:Panel ID="pnlMapping" runat="server" Visible="false">
                                        <ul>
                                            <asp:ListView ID="lvcatSubcat" runat="server">
                                                <ItemTemplate>
                                                    <li class="d-none">
                                                        <asp:Label ID="mappingID" runat="server" Text='<%# Eval("ProductCategorySubCategoryMapping") %>'></asp:Label>
                                                    </li>
                                                </ItemTemplate>
                                            </asp:ListView>
                                        </ul>
                                    </asp:Panel>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtProductName">Product Name</label>
                                        <asp:TextBox ID="txtProductName" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                        <asp:RequiredFieldValidator ID="rv1" runat="server" ControlToValidate="txtProductName" ErrorMessage="Please enter product name" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtProductCode">Product Code</label>
                                        <asp:TextBox ID="txtProductCode" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                        <asp:RequiredFieldValidator ID="rv2" runat="server" ControlToValidate="txtProductCode" ErrorMessage="Please enter product code" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtDescription">Description</label>
                                        <asp:TextBox ID="txtDescription" TextMode="MultiLine" Rows="15" runat="server" ClientIDMode="Static" CssClass="form-control tiny-editor"></asp:TextBox>
                                        <%--<asp:RequiredFieldValidator ID="rv3" runat="server" ControlToValidate="txtDescription" ErrorMessage="Please enter description" CssClass="error" Display="Dynamic"></asp:RequiredFieldValidator>--%>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtShortDescription">Short Description</label>
                                        <asp:TextBox ID="txtShortDescription" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                        <asp:RequiredFieldValidator ID="rv4" runat="server" ControlToValidate="txtShortDescription" ErrorMessage="Required" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                    </div>
                                </div>
                            </div>

                            <div class="card-container">
                                <div class="ui-card">
                                    <h2 class="small-heading">Media (1200px X 1200px)<br />
                                        <strong style="color:red; font-size:16px">Note:- Images should be in .jpg, .png, .gif and maximum upto 100KB<br />Video should be .mp4 format and maximum upto 2MB</strong></h2>
                                    <hr />
                                    <div class="row">
                                        <div class="col-12 col-lg-6 mb-2">
                                            <div class="form-group">
                                                <div>
                                                    <label>Main Image / Video <small class="text-danger">(Click on image to change)</small></label>
                                                </div>
                                                <div class="hero-image">
                                                    <asp:FileUpload  ID="fileHero" runat="server" ClientIDMode="Static" CssClass="d-none file-size-validation" data-size="102400"/>
                                                    <label for="fileHero" class="lbl-main-img">
                                                        <div style="position: absolute;top: 48%;left: 0;width: 100%;text-align: center;color: #777;">Click here to upload image</div>
                                                        <asp:Image ID="imgHero" runat="server" ClientIDMode="Static" CssClass="img-product img_awesome" />
                                                        
                                                    </label>
                                                        <asp:HyperLink ID="previewLink" runat="server" ClientIDMode="Static" Target="_blank" Text="Preview"></asp:HyperLink>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 mb-2">
                                            <div class="form-group">
                                                <div>
                                                    <label>Other Images / Video</label>
                                                </div>
                                                <asp:Panel class="pnl-side-image" ID="sideImage" runat="server">
                                                    <asp:UpdatePanel ID="upOtherImage" runat="server">
                                                        <ContentTemplate>
                                                            <div class="row">
                                                                <asp:ListView ID="lvGallery" runat="server">
                                                                    <ItemTemplate>
                                                                        <div class="col-6 col-lg-4 mb-1">
                                                                            <div class="div-side-image">
                                                                                <asp:Label ID="lblIsVideo" runat="server" Text='<%# Eval("IsVideo") %>' Visible="false"></asp:Label>
                                                                                <asp:Image ID="lvSideImage" runat="server" ImageUrl='<%# Eval("ImageUrl") %>' CssClass="side-image img_awesome" />
                                                                                <div class="img-delete">
                                                                                    <asp:LinkButton ID="btnDelete" ClientIDMode="AutoID" CommandArgument='<%# Eval("OtherImageListId") %>' CommandName="delete-image" runat="server"><i class="fas fa-trash"></i></asp:LinkButton>

                                                                                    <asp:HyperLink ID="linkGallery" runat="server" ClientIDMode="Static" NavigateUrl='<%# Eval("ImageUrl") %>' Target="_blank"><i class="fas fa-eye"></i></asp:HyperLink>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </ItemTemplate>
                                                                </asp:ListView>

                                                                <div class="lister" style="display: none">
                                                                </div>

                                                                <div class="col-6 col-lg-4 mb-1">
                                                                    <div class="div-side-image upload-attachment" title="Add more images">
                                                                        <label for="fileGallery" class="side-image text-center"><i class="fas fa-plus"></i></label>
                                                                        <asp:FileUpload ID="fileGallery" runat="server" AllowMultiple="true" ClientIDMode="Static" CssClass="d-none file-size-validation" data-size="102400" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </ContentTemplate>
                                                    </asp:UpdatePanel>
                                                </asp:Panel>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <div class="form-group">
                                                <label>Select Watermark</label>
                                                <asp:DropDownList ID="drpWatermark" ClientIDMode="Static" runat="server" CssClass="form-control">
                                                    <asp:ListItem></asp:ListItem>
                                                </asp:DropDownList>
                                                <asp:RequiredFieldValidator ID="rv5" runat="server" ControlToValidate="drpWatermark" ErrorMessage="Please select watermark" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-container">
                                <div class="ui-card">
                                    <h2 class="small-heading">City Manager</h2>
                                    <hr />

                                    <div class="form-group">
                                        <label>Select City Group</label>
                                        <div data-simplebar data-simplebar-auto-hide="false" class="checkbox-container fixscroll mt-1">
                                            <asp:CheckBoxList ID="chkCityGroup" runat="server"></asp:CheckBoxList>
                                        </div>

                                        <div class="mt-3">
                                            <asp:ListView ID="lvCitiesFromGroup" runat="server">
                                                <ItemTemplate>
                                                    <asp:Label ID="lblGroupCityID" runat="server" Text='<%# Eval("CityID") %>'></asp:Label>
                                                </ItemTemplate>
                                            </asp:ListView>
                                        </div>
                                    </div>

                                    <hr />

                                    <div class="form-group">
                                        <label>Add city individually</label>
                                        <asp:TextBox ID="txtCities" ClientIDMode="Static" runat="server" CssClass="form-control chk-textbox" Placeholder="Search city..."></asp:TextBox>
                                    </div>

                                    <div class="city-alert alert alert-success" style="display:none">
                                        <strong>City added successfully</strong>
                                    </div>

                                    <div class="city-alert-danger alert alert-danger" style="display:none">
                                        <strong>This city already added.</strong>
                                    </div>

                                    <ul class="list-city"></ul>

                                    <ul class="selectedCity selected-options">
                                        <asp:ListView ID="lvCity" runat="server" ClientIDMode="Static">
                                            <ItemTemplate>
                                                <li>
                                                    <asp:Label ID="lblCityID" ClientIDMode="Static" runat="server" ToolTip='<%# Eval("CityID") %>' Text='<%# Eval("CityID") %>'></asp:Label>
                                                    <button type="button" class="btn-remove city-remove">&#10005;</button>
                                                </li>
                                            </ItemTemplate>
                                        </asp:ListView>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-container">
                                <div class="ui-card">
                                    <h2 class="small-heading">Pricing & Taxes</h2>
                                    <hr />
                                    <asp:UpdatePanel ID="upPricing" runat="server">
                                        <ContentTemplate>
                                            <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtMRP">MRP</label>
                                                <asp:TextBox ID="txtMrp" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                                <asp:RequiredFieldValidator ID="rv6" runat="server" ControlToValidate="txtMrp" ErrorMessage="Enter MRP" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="txtSellingPrice">Selling Price</label>
                                                <asp:TextBox ID="txtSellingPrice" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                                <asp:RequiredFieldValidator ID="rv7" runat="server" ControlToValidate="txtSellingPrice" ErrorMessage="Enter Selling price" CssClass="error" Display="Dynamic" ValidationGroup="SaveProduct"></asp:RequiredFieldValidator>

                                                <asp:CompareValidator ID="cv2" runat="server"
                                                    ControlToValidate="txtSellingPrice"
                                                    ControlToCompare="txtMrp"
                                                    Operator="LessThan"
                                                    ErrorMessage="Selling price can not be greater than MRP"
                                                    Type="Integer" CssClass="error" ValidationGroup="SaveProduct" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="checkbox-container">
                                                <asp:CheckBox ID="chkIsAutoPrice" runat="server" AutoPostBack="true" Text="Enable Automatic Pricing" />
                                            </div>
                                        </div>
                                    </div>
                                        </ContentTemplate>
                                    </asp:UpdatePanel>
                                </div>
                            </div>

                            <div class="card-container">
                                 <div class="ui-card">
                                     <h2 class="small-heading">Additional Information</h2>
                                     <hr />

                                     <asp:UpdatePanel ID="upInc" runat="server">
                                         <ContentTemplate>
                                             <div class="form-group">
                                         <label for="txtDescription">Inclusion</label>
                                         <div class="lv-panel">
                                             <div class="row header-pnl">
                                                 <div class="col-6 col-lg-3">
                                                     <label class="mb-0">Item</label>
                                                 </div>

                                                 <div class="col-6 col-lg-2">
                                                     <label class="mb-0">Qty</label>
                                                 </div>

                                                 <div class="col-6 col-lg-2">
                                                     <label class="mb-0">Color</label>
                                                 </div>

                                                 <div class="col-6 col-lg-2">
                                                     <label class="mb-0">Unit Cost</label>
                                                 </div>

                                                 <div class="col-6 col-lg-2">
                                                     <label class="mb-0">Total Cost</label>
                                                 </div>

                                                 <div class="col-6 col-lg-1"></div>
                                             </div>

                                             <asp:ListView ID="lvIncl" runat="server">
                                                 <ItemTemplate>
                                                     <div class="row mb-1">
                                                         <div class="col-6 col-lg-3">
                                                             <asp:Label ID="lblItem" runat="server" Text='<%# Eval("ItemName") %>'></asp:Label>
                                                         </div>

                                                        <div class="col-6 col-lg-2">
                                                             <asp:Label ID="lblQty" runat="server" Text='<%# Eval("Qty") %>'></asp:Label>
                                                        </div>

                                                        <div class="col-6 col-lg-2">
                                                             <asp:Label ID="lblColor" runat="server" Text='<%# Eval("Color") %>'></asp:Label>
                                                        </div>


                                                     <div class="col-6 col-lg-2">
                                                        <asp:Label ID="lblUnitCost" runat="server" Text='<%# Eval("UnitCost") %>'></asp:Label>
                                                     </div>

                                                    <div class="col-6 col-lg-2">
                                                        <asp:Label ID="lblSingleItemPrice" runat="server"></asp:Label>
                                                     </div>

                                                     <div class="col-6 col-lg-1">
                                                         <%--<asp:LinkButton ID="btnIncEdit" runat="server" CommandName="Edit" ClientIDMode="AutoID" CssClass="btn btn-link btn-sm text-primary"><i class="far fa-edit"></i></asp:LinkButton>--%>
                                                         <asp:LinkButton ID="btnIncRemove" ClientIDMode="AutoID" CommandArgument='<%# Eval("ID") %>' CommandName="removeIncl" runat="server" CssClass="btn btn-link btn-sm text-danger"><i class="fas fa-times"></i></asp:LinkButton>
                                                     </div>

                                                     <div class="col-12"></div>
                                                     </div>
                                                 </ItemTemplate>
                                             </asp:ListView>
                                             <hr />
                                             <div class="row">
                                                 <div class="col-6 col-lg-3">
                                                     <div class="form-group">
                                                         <label>Category</label>
                                                         <asp:DropDownList ID="drpIncCategory" runat="server" CssClass="form-control" AutoPostBack="true">
                                                             <asp:ListItem></asp:ListItem>
                                                         </asp:DropDownList>
                                                     </div>
                                                 </div>

                                                 <div class="col-6 col-lg-3">
                                                     <div class="form-group">
                                                         <label>Item</label>
                                                         <asp:TextBox ID="txtIncItem" Visible="false" runat="server" CssClass="form-control"></asp:TextBox>
                                                         <asp:DropDownList ID="drpIncItems" runat="server" CssClass="form-control" AutoPostBack="true">
                                                             <asp:ListItem></asp:ListItem>
                                                         </asp:DropDownList>
                                                     </div>
                                                 </div>

                                                 <div class="col-6 col-lg-2">
                                                     <div class="form-group">
                                                         <label>Qty</label>
                                                         <asp:TextBox ID="txtIncQty" runat="server" CssClass="form-control" TextMode="Number"></asp:TextBox>
                                                     </div>
                                                 </div>


                                                 <div class="col-6 col-lg-2">
                                                     <div class="form-group">
                                                         <label>Color</label>
                                                         <asp:DropDownList ID="drpIncColor" runat="server" CssClass="form-control">
                                                             <asp:ListItem></asp:ListItem>
                                                         </asp:DropDownList>
                                                     </div>
                                                 </div>

                                                 <div class="col-6 col-lg-2">
                                                     <div class="form-group">
                                                         <label>Unit Price</label>
                                                         <asp:TextBox ID="txtInclusionItemPrice" runat="server" CssClass="form-control"></asp:TextBox>
                                                     </div>
                                                 </div>

                                                 <div class="col-12">
                                                     <asp:LinkButton ID="btnAddInc" OnClick="AddInclusionItems" runat="server" CssClass="btn btn-block btn-success" Text="Add" ValidationGroup="SaveProduct"><i class="fas fa-plus"></i> Add Inclusion</asp:LinkButton>
                                                 </div>
                                             </div>
                                         </div>

                                         <asp:TextBox ID="txtInclusion" TextMode="MultiLine" Rows="10" runat="server" ClientIDMode="Static" CssClass="form-control" Visible="false"></asp:TextBox>
                                                 <hr />
                                     </div>
                                         </ContentTemplate>
                                     </asp:UpdatePanel>

                                     <div class="form-group">
                                         <label for="txtDescription">Substitution</label>
                                         <asp:TextBox ID="txtSubstitution" TextMode="MultiLine" Rows="10" runat="server" ClientIDMode="Static" CssClass="form-control tiny-editor"></asp:TextBox>
                                     </div>

                                     <div class="form-group">
                                         <label for="txtDeliveryNote">Delivery</label>
                                         <asp:TextBox ID="txtDeliveryNote" TextMode="MultiLine" Rows="10" runat="server" ClientIDMode="Static" CssClass="form-control tiny-editor"></asp:TextBox>
                                     </div>

                                     <div class="form-group d-none">
                                         <label for="txtPosition">Position</label>
                                         <asp:TextBox ID="txtPosition" runat="server" ClientIDMode="Static" CssClass="form-control"></asp:TextBox>
                                     </div>
                                 </div>
                             </div>

                             <div class="card-container">
                                <div class="ui-card">
                                    <h2 class="small-heading">SEO Tools</h2>
                                    <hr />
                                    <div class="form-group">
                                        <label for="txtMetaKeywords">Meta Keywords<small>(Maximum 200 characters)</small></label>
                                        <asp:TextBox ID="txtMetaKeywords" runat="server" ClientIDMode="Static" CssClass="form-control" TextMode="MultiLine" Rows="5" MaxLength="200"></asp:TextBox>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtMetaDescription">Meta Description<small>(Maximum 1000 characters)</small></label>
                                        <asp:TextBox ID="txtMetaDescription" runat="server" ClientIDMode="Static" CssClass="form-control" TextMode="MultiLine" Rows="5" MaxLength="1000"></asp:TextBox>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-4 mb-3">
                            <div class="card-container">
                                <div class="ui-card">
                                    <div class="checkbox-container">
                                        <asp:CheckBox ID="chkActive" runat="server" Text="Live this product on website" />
                                    </div>

                                     <hr />

                                    <div class="form-group mb-1 filters">
                                        <label>Select Filters</label>
                                        <div data-simplebar data-simplebar-auto-hide="false" class="checkbox-container fixscroll mt-1">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="chkAll" id="chkFiltersAll" />
                                                            <label for="chkFiltersAll">All</label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <asp:UpdatePanel ID="upFilters" runat="server">
                                                <ContentTemplate>
                                                    <asp:CheckBoxList ID="chkFilters" runat="server" ClientIDMode="Static" CssClass="chkChild chk-filters ">
                                                    </asp:CheckBoxList>
                                                </ContentTemplate>

                                                <Triggers>
                                                    <asp:AsyncPostBackTrigger ControlID="drpCategory" />
                                                </Triggers>
                                            </asp:UpdatePanel>
                                        </div>
                                    </div>

                                    <hr />

                                    <asp:UpdatePanel ID="upForDeliveryDays" runat="server">
                                        <ContentTemplate>
                                            <div class="form-group mb-3">
                                                <label>Tags</label>
                                                <div class="checkbox-container mt-1">
                                                   <asp:CheckBoxList ID="chkTagList" AutoPostBack="true" CssClass="chk-tags" runat="server">
                                                    </asp:CheckBoxList>
                                                </div>
                                            </div>
                                        </ContentTemplate>

                                        <Triggers>
                                            <asp:AsyncPostBackTrigger ControlID="drpMinDeliveryDay" />
                                        </Triggers>
                                    </asp:UpdatePanel>

                                    <asp:UpdatePanel ID="UpdatePanel1" runat="server">
                                        <ContentTemplate>
                                            <div class="form-group mb-3">
                                                <label>Minimum delivery day</label>
                                                <asp:DropDownList ID="drpMinDeliveryDay" AutoPostBack="true" runat="server" CssClass="form-control min-delivery-day">
                                                    <asp:ListItem></asp:ListItem>
                                                    <asp:ListItem Value="0">Same day</asp:ListItem>
                                                    <asp:ListItem Value="1">Next day</asp:ListItem>
                                                    <asp:ListItem Value="2">2 days</asp:ListItem>
                                                </asp:DropDownList>
                                            </div>
                                        </ContentTemplate>

                                        <Triggers>
                                            <asp:AsyncPostBackTrigger ControlID="chkTagList" />
                                        </Triggers>
                                    </asp:UpdatePanel>

                                    <hr />

                                    <!--bind occasion, festival, relation-->
                                    <asp:ListView ID="lvCommonCategory" runat="server" Visible="false">
                                        <ItemTemplate>
                                            <asp:Label ID="lblCatID" runat="server" Text='<%# Eval("ProductCategoryID") %>'></asp:Label>
                                        </ItemTemplate>
                                    </asp:ListView>
                                    <!--bind occasion, festival, relation-->

                                    <div class="form-group mb-3">
                                        <label>Occasion</label>
                                        <div data-simplebar data-simplebar-auto-hide="false" class="checkbox-container fixscroll mt-1">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="chkAll" id="chkOccasionAll" />
                                                            <label for="chkOccasionAll">All</label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <asp:CheckBoxList ID="chkOccasion" runat="server" CssClass="chkChild">
                                            </asp:CheckBoxList>
                                        </div>
                                    </div>

                                    <hr />

                                    <div class="form-group mb-3">
                                        <label>Festivals</label>
                                        <div data-simplebar data-simplebar-auto-hide="false" class="checkbox-container fixscroll mt-1">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="chkAll" id="chkFestivalAll" />
                                                            <label for="chkFestivalAll">All</label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <asp:CheckBoxList ID="chkFestivals" runat="server" CssClass="chkChild">
                                            </asp:CheckBoxList>
                                        </div>
                                    </div>

                                    <hr />

                                    <div class="form-group mb-3">
                                        <label>Relation</label>
                                        <div data-simplebar data-simplebar-auto-hide="false" class="checkbox-container fixscroll mt-1">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="chkAll" id="chkRelationAll" />
                                                            <label for="chkRelationAll">All</label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <asp:CheckBoxList ID="chkRelations" runat="server" CssClass="chkChild">
                                            </asp:CheckBoxList>
                                        </div>
                                    </div>

                                    <hr />

                                    <asp:Button ID="btnAdd" runat="server" CssClass="c-btn c-btn-block c-btn-primary mb-3" Text="Save" OnClick="updateInProductTable" ValidationGroup="SaveProduct" />

                                    <asp:Button ID="btnDelete" runat="server" CssClass="c-btn c-btn-block c-btn-danger mb-3" Text="Delete" OnClick="deleteProduct" onClientClick=" return confirm('Are you sure you want to delete this?')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/Content/admin/assets/js/lib/jquery.watermark.min.js"></script>
</asp:Content>

