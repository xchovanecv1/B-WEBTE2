<?php 
  if(!empty($_POST['chngnws']))
    {

        if($addrchng = $mysqli->prepare("UPDATE users SET newsletter=? WHERE ID= ?;")) {

            $vals = 0;
                if($_POST['checkboxVal'] == 'on')
                {
                    $vals = 1;
                }
              $addrchng->bind_param("ii",$vals, $_SESSION['ID']);
              if($addrchng->execute())
              {

                load_user_data($mysqli,$_SESSION['ID']);
                add_message("","Zmena newsletter nastavenia prebehla úspešne");
              } else {
                add_message("error","Počas zmeny newslettera nastal problém!");
              }
              $addrchng->close();

         }
    }
?>
    <div class="box box-primary active">
        <div class="box-header with-border">
            <h3 class="box-title">Newsletter</h3>
        </div>

        <form role="form" method="post">
            <div class="box-body active">
                <?php
                    $counter = 0;
                    if ($result = $mysqli->query("SELECT date, text FROM newsletter ORDER BY date DESC LIMIT 3;")) {

                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        print "<div class=\"form-group\"><label for=\"newsletter1\">";
                        print $row['date'];
                        print "</label><div id=\"newsletter";
                        print $counter;
                        print "\">";
                        print $row['text'];
                        print "</div></div>";

                    $counter++;
                    }
                    /* free result set */
                    $result->close();
                    }
                ?>
            </div>

            <div class="box-footer">
                <div class="checkbox">
                    <label>

                        <?php

                            if($_SESSION['newsletter'] == 1){
                                    echo '<input id="newsletterCheckbox" name="checkboxVal" type="checkbox" checked> Chcem dostávať newsletter.';
                                    } else{
                                    echo '<input id="newsletterCheckbox" name="checkboxVal" type="checkbox"> Chcem dostávať newsletter.';
                                }

                        ?>
                        <button id="submitButton" name="chngnws" value="chng" type="submit" class="btn btn-primary">Zmeniť</button>

                        <script>
                            if($("#newsletterCheckbox").is(':checked') == true){
                                val = "1";
                                $("#boxBody").css("display","block");
                            }
                            else{
                                val = "0";
                                $("#boxBody").css("display","none");
                            }
                        </script>
                    </label>
                </div>    
            </div>
        </form>
    </div>