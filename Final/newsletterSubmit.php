<?php 
    require_once 'Page.php';
    if(check_access_role(ROLE_ADMIN,true))
    {

        if(!empty($_POST['addnws']) && !empty($_POST['inputText']))
        {
            if($news_add = $mysqli->prepare("INSERT INTO `newsletter` (`id`, `date`, `text`, `autor`) VALUES (NULL, CURRENT_TIMESTAMP, ?, ?)"))
              {

                  $news_add->bind_param("si",htmlspecialchars($_POST['inputText']),$_SESSION['ID']);
                  if($news_add->execute())
                  {
                    $news_id = $news_add->insert_id;
                    if ($result = $mysqli->query("SELECT Mail FROM users WHERE newsletter=1;")) {

                        while($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            if($mail_add = $mysqli->prepare("INSERT INTO `mailer` (`ID`, `type`, `user`, `mail`, `sent`) VALUES (NULL, '2', ?, ?, '0');"))
                              {
                                  $mail_add->bind_param("ds",($news_id),($row['Mail']));
                                  if($mail_add->execute())
                                  {
                                  }
                              }
                              $mail_add->close();
                        }
                    }
                  }
                }
        }
?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Čo je nové?</h3>
        </div>

        <form role="form" method="post">
            <div class="box-body">
                <div class="form-group">
                    <textarea maxlength="1000" name="inputText" id="inputText" style="width: 100%;" rows="10"></textarea>
                </div>
            </div>

            <div class="box-footer">
                <button id="submitButton" type="submit" name="addnws" value="addnws" class="btn btn-primary">Pridať novinku</button>     
            </div>
        </form>
    </div>
<?php } ?>