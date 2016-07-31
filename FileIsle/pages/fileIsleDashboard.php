<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php session_start();
include_once '../php/user/userSessionClass.php';

function callFunction($param)
{
  getParamater($param);
}

?>
<html>
    <head>
        <title>FileIsle-Dashboard</title>
        <!--Css Page-->
        <link rel="stylesheet" href="..//css/fileIsleDash.css"/>
       <!-- <script type ="text/javascript" src="../js/dragAnddrop/dragAndDropClass.js"></script> -->
        <script type="text/javascript" src="../js/popUpWindowClass.js"></script>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        <div id="h_div">
            <div id="top_bar">
                <img id="logo_img" src="..//logos/logo003.png" alt="fileIsleLogo" style="width:80px;height:80px;" title="FileIsle"/>
                
                <!--Hidden menu will be displayed upon clicking username-->                
                <div class="hidden_menu_options">
                    <ul>
                        <li><a class="logged_in_user" href=""><?php echo $_SESSION['username']; ?></a>                            
                            <ul class="user_options">
                                <li><a href="../php/user/logoutClass.php"><input type="button" class="hidden_options" id="logout_btn" name="logout" value="Logout"></a></li>
                                <li><a href="../php/helper/editUserDetailsCallerClass.php"><input type="button" class="hidden_options" id="edit_btn" value="Edit Details"></a></li>
                                <li><a href="fileIsleRecentInfoPage.php"><input type="button" class="hidden_options" id="recent_info_btn" value="Recent Info"></a></li>
                            </ul>
                    </ul>
                </div>
                
            </div>
                        
            <div id="menu_bar">    
                <div id="sub_menu_bar">
                    
                    <a href="../php/helper/parentHelperClass.php?parent='NULL'"><input type="image" src="..//reqIcons/home.svg" name="home_btn" class="icon_btn" id="home_btn" alt="home" title="Home"></a>
                    
                    <input type="image" src="..//reqIcons/pencil.svg" name="rename_btn" class="icon_btn" id="edit_name_btn" 
                           alt="edit" title="Rename" onclick="document.getElementById('rename_item_modal').style.cssText='display:block;'">
                    
                    
                    <input type="image" src="..//reqIcons/remove2.svg" name="del_btn" class="icon_btn" id="del_btn" alt="delete" title="Delete" onclick="document.getElementById('delete_modal').style.cssText='display:block;'">
                    <input type="image" src="..//reqIcons/folder.svg" name="new_folder_btn" class="icon_btn" id="new_folder_btn" alt="newFolder" title="New Folder" onclick="document.getElementById('new_folder_modal').style.cssText='display:block;'">
                    <input type="image" src="..//reqIcons/download.svg" name="download[]" class="icon_btn" id="dl_btn" alt="download" title="Download" form="main_form" onclick="<?php $_SESSION['action'] = 'download'; ?>">
                    
                    <form action="../php/helper/fileUploadHelperClass.php" method="post" class="button_tbl" name='uploadForm' enctype="multipart/form-data">
                        <!--Upload Button--> 
                        <label for="fileToUpload"><img src="..//reqIcons/upload.svg" class="icon_btn" alt='Upload File' title="Upload"/></label>
                        <input type="File" name="fileToUpload" id="fileToUpload" class="hidden_btn" onchange="uploadForm.submit();">
                    </form>
                    <!--Add to Favourites Button-->
                    <input type="image" src="..//reqIcons/addToFav.svg" name="add_to_fav[]" class="icon_btn" id="add_to_fav_btn" alt="add_to_fav" 
                           title="Add as Favourite" form="main_form" onclick="<?php $_SESSION['action'] = 'add_to_fav'; ?>">
                   
                    <!--Remove From Favourites Button-->
                    <input type="image" src="..//reqIcons/removeFromFav.svg" name="rem_from_fav[]" class="icon_btn" id="remove_from_fav_btn" 
                           alt="rem_from_fav" title="Remove From Favourite" form="main_form" onclick="<?php $_SESSION['action'] = 'rem_from_fav'; ?>">
                    
                    <input type="image" src="..//reqIcons/cog.svg" name="settings_btn" class="icon_btn" id="settings_btn" alt="settings" title="Settings">
                </div> 
                
            <div id="openModal" class="modal_div">
                
                <!--New Folder Modal-->
                <div class="modal_box" id="new_folder_modal">
                    <form id="new_folder_form" name="new_folder_form" method="post" action="../php/validation/validateFileFolderClass.php">    
                        <h2>Add New Folder</h2>
                        <input type="text" class="modal_input" id="folder_name_txt" name="folder_name_txt">
                    </form>
                    <input type="submit" class="modal_btn" form="new_folder_form" value="Add">
                    <button class="modal_btn" onclick="document.getElementById('new_folder_modal').style.cssText='display:none;'">Cancel</button>
                </div>
                
                <!--Rename Modal-->
                <div class="modal_box" id="rename_item_modal"> 
                    <form id="rename_folder_form" name="rename_item_form" method="post">    
                        <h2>Rename Selected Item</h2>
                        <input type="text" class="modal_input" id="rename_item_txt" name="rename_item_txt" form="main_form"></br>
                    </form>
                    
                    <button name="rename[]" class="modal_btn" form="main_form" onclick="<?php $_SESSION['action'] = 'rename';?>">Rename</button>
                    <button class="modal_btn" onclick="document.getElementById('rename_item_modal').style.cssText='display:none;'">Cancel</button>
                </div>
                
                <!--Deletion Modal-->
                <div class="modal_box" id="delete_modal">
                        <h2>Delete Selected Item?</h2>
                        <button name="delete[]" class="modal_btn" form="main_form" onclick="<?php $_SESSION['action'] = 'delete';?>">Yes</button>
                        <button class="modal_btn" onclick="document.getElementById('delete_modal').style.cssText='display:none;'">No</button>
                </div>
                
                
                    <!-- Error Div -->
                    <?php
                        if(isset($_SESSION['error_msg'])){
                            echo("<div id='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error_msg'] . "</div></br>");
                        }
                        else
                        {
                            echo("<div id='error_msg'> </div></br>");
                        }
                    ?> 
            </div>
                
            </div>
        </div>
        
        <?php
            
            if(isset($_SESSION['error']))
            {
                echo("<div id='error_msg' style='display: inline-block; text-align:center'>" . $_SESSION['error'] . "</div>");
            }
            else
            {
                echo("<div id='error_msg'>Test</div>");
            }
        ?>

        <div id="container_div">   
            
            <div id="side_bar_div">    
                <table id="favourites_table" style="width:80%">
                    <thead>
                        <th>Favourites List:</th>
                    </thead>
                    
                    <tbody>  
                        <?php 
                            include '../php/dataUtils/favouritesDataClass.php';

                            getTableFavourites();
                        ?>   
                    </tbody>
                </table>
    	
            </div>

            <div id="main_container_div">
                <form id="main_form" name="main_form" method="post" action="../php/menuBarActions/menuActionClass.php">
                   
                    <!--Files / Folders Table-->   
                    <table id="content_table" style="width:97%">
                        <thead>
                            <th style="width: 100px; text-align: center">Selected</th>
                            <th style="width: 250px">Filename</th>
                            <th style="width: 80px">Type</th>
                            <th style="width: 100px">Size</th>
                            <th style="width: 150px">Date Added</th> 
                            <th style="width: 150px">Date Modified</th>
                        </thead>
                        
                        <tbody>
                            <?php 
                                include '../php/dataUtils/mainTableDataClass.php';
                                include '../php/menuBarActions/checkedRowClass.php';
                                
                                if(isset($_SESSION['parent']))
                                {
                                    $parentId = $_SESSION['parent'];
                                    
                                    $count = getTableFiles($parentId);

                                    getTableFolders($parentId,$count);
                                }
                                else
                                {
                                    $count = getTableFiles('NULL');

                                    getTableFolders('NULL',$count);
                                }
                            ?>
                        </tbody>
                    </table>
                </form>                
                <!--Drag and Drop div will be activated upon clicking on the upload button-->
               <!-- <div class="drag_drop"></div>-->
            </div>
        </div>
    </body>
</html>
