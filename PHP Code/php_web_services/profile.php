<?php 

$page_title="Admin Profile";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");

if(isset($_SESSION['id']))
{

  $qry="SELECT * FROM tbl_admin WHERE id='".$_SESSION['id']."'";

  $result=mysqli_query($mysqli,$qry);
  $row=mysqli_fetch_assoc($result);

}
if(isset($_POST['submit']))
{

  $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
  {
    $_SESSION['class']="error";
    $_SESSION['msg']="invalid_email_format";
  }
  else{
    if($_FILES['image']['name']!="")
    { 
      if($row['image']!="")
      {
        unlink('images/'.$row['image']);
      }

      $image="profile.png";
      $pic1=$_FILES['image']['tmp_name'];
      $tpath1='images/'.$image;

      copy($pic1,$tpath1);

      $data = array( 
        'username'  =>  $username,
        'email'  =>  $email,
        'image'  =>  $image
      );
    }
    else
    {
      $data = array( 
        'username'  =>  $username,
        'email'  =>  $email, 
      );
    }

    if(isset($_POST['password']) && $_POST['password']!="")
    {
      $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
      
      $data = array_merge($data, array("password" => trim($password)));
    }

    $update=Update('tbl_admin', $data, "WHERE id = '".$_SESSION['id']."'"); 

    $_SESSION['msg']="11";
    $_SESSION['class']="success";
  }

  header( "Location:profile.php");
  exit;
}

?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"><?=$page_title?></div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="card-body mrg_bottom">
        <form action="" name="editprofile" method="post" class="form form-horizontal" enctype="multipart/form-data">

          <div class="section">
            <div class="section-body">
              <div class="form-group">
                <label class="col-md-3 control-label">Profile Image :-</label>
                <div class="col-md-6">
                  <div class="fileupload_block">
                    <input type="file" name="image" id="fileupload" accept=".png, .jpg, .PNG, .JPG" onchange="fileValidation()">
                    <?php if($row['image']!='') {?>
                      <div class="fileupload_img" id="uploadPreview"><img type="image" src="images/<?php echo $row['image'];?>" alt="category image" style="width: 90px;height: 90px;"/></div>
                    <?php }else{?>
                      <div class="fileupload_img" id="uploadPreview"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Username :-</label>
                <div class="col-md-6">
                  <input type="text" name="username" id="username" value="<?php echo $row['username'];?>" class="form-control" required autocomplete="off">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Password :-</label>
                <div class="col-md-6">
                  <input type="password" name="password" id="password" value="" class="form-control" autocomplete="off">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Email :-</label>
                <div class="col-md-6">
                  <input type="text" name="email" id="email" value="<?php echo $row['email'];?>" class="form-control" required autocomplete="off">
                </div>
              </div>                 

              <div class="form-group">
                <div class="col-md-9 col-md-offset-3">
                  <button type="submit" name="submit" class="btn btn-primary">Save</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php");?>