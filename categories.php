<?php
    require_once 'login.php';
    $fail = "";
    $newCat = "";
    $updateset = false;
    $entryset = false;
    try 
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    
    if (isset($_POST['update']) && isset($_POST['catselect']) && isset($_POST['updateentry']))
    {
        $updateset = true;
        $name = fix_string(get_post($pdo, 'catselect'));
        $cat =  fix_string(get_post($pdo, 'updateentry'));
    }

    if (isset($_POST['catentry']))
    {
        $entryset = true;
        $category = fix_string(get_post($pdo, 'catentry'));
    } 
    
    if ($entryset)
    {
        $fail .= validate_name($category, $pdo);
    }
    
    if ($updateset)
    {
        $fail .= validate_name($cat, $pdo);
    }

  if ($entryset && $fail == "") {
    $query = "INSERT INTO category (categoryname) VALUES (" . $category . ");";
    $result = $pdo->query($query);
  }

  if ($updateset && $fail == "") {
    echo "<script>alert('I hate php!')</script>";
    try {
        $pdo->beginTransaction();
        $ID = get_ID($name, $pdo);
        $query = "UPDATE category SET categoryname= " . $cat . " WHERE categoryID=$ID;";
        $result = $pdo->exec($query);
        $pdo->commit();
    }
    catch (PDOException $e) {
        $pdo->rollBack();
        die($e->getMessage());
}
  }

  function fix_string($string)
  {
    $string = stripslashes($string);
    return htmlentities($string);
  }

  function get_ID($name, $pdo) {
    $query = "SELECT categoryID from category where categoryname=" . $name;
        $result = $pdo->query($query);
        while ($row = $result->fetch())
        {
            $ID = htmlspecialchars($row['categoryID']);
        }
        return $ID;
  }

?>
<html>
<head>
        <title>Programming Categories</title>
        <script src="https://kit.fontawesome.com/29ba4da398.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/x-icon" href="mcLogo.png">
    <style>
        #idHeader {
                color : #ff6600;
                font-size: 45px;
                font-weight: lighter;
                font-family: fantasy;
            }
            #idCategories {
                color : #5B0F1B;
                background-color: #ff6600;
                width: 25%;
                font-size: 30px;
                font-weight: lighter;
                font-family: fantasy;
            }
            #idFail {
            color : red;
            font-size: 20px;
            font-weight: lighter;
            font-family: fantasy, Arial, Helvetica, sans-serif;
        }
        #idSuccess {
            color : green;
            font-size: 20px;
            font-weight: lighter;
            font-family: fantasy, Arial, Helvetica, sans-serif;
        }
            * {
                box-sizing: border-box;
            }
            body {
            margin: 0;
            
            }
            .side {
                -ms-flex: 30%; /* IE10 */
                flex: 30%;
                background-color: #5B0F1B;
                padding: 4px;
                }
        .navbar {
            overflow: hidden;
            background-color: #ff6600;
            cursor: pointer;
            }
        .navbar a {
        float: left;
        display: block;
        color: white;
        text-align: center;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 25px;
        padding: 4px 20px;
        text-decoration: none;
        }

        .navbar a:hover {
        background-color: #5B0F1B;
        color: #ff6600;
        }

    </style>
    </head>
    <body>
    <noscript>
        <meta http-equiv="refresh" content="0; URL=ProgTeam.html" />
    </noscript>
    <link rel="stylesheet" href="input.css">
    <form name = "Categories" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class = "side">
            <img src="mcLogo.png">
            <img align= "right" src="MCProgLogo.png">
            <center><h1 id="idHeader">CATEGORIES</h1></center>
        </div>
    <div class= "navbar">
        <a href="problems.php">BACK TO PROBLEMS</a>
    </div>
    <center><h1 id="idHeader">ADD A CATEGORY:</h1></center>
    <center>
    <?php
        if ($entryset && $fail != "") {
            echo "<h1 id='idFail'>$fail</h1>";
            echo"<script>alert('Failed to enter into database')</script>";
        }
        else if ($entryset) {
            echo "<h1 id='idSuccess'>Sucessfully added to database!</h1>";
            echo"<script>alert('Entered into database successfully')</script>";
        }
    ?>
    </center>
    <center> <div class="input-group"><input type='text' required maxlength='255' size='50' name='catentry'><span class="highlight"></span><span class="bar"></span><label>CATEGORY NAME</label></div>
    <button type='submit' class='big-button' name='submit' value='SUBMIT'>SUBMIT</button></center>
    <center><h1 id="idHeader">EDIT A CATEGORY:</h1></center>
    </form>
<?php  
    if ($updateset && $fail != "") {
        echo "<center><h1 id='idFail'>$fail</h1></center>";
        echo"<script>alert('Failed to update database')</script>";
    }
    else if ($updateset) {
        echo "<h1 id='idSuccess'>Sucessfully updated database!</h1>";
        echo"<script>alert('Updated database successfully')</script>";
    }
    $query = "SELECT * FROM category";
    $result = $pdo->query($query);
    echo "<form action = '' method='post'>";
    echo"<center><div class='input-group'><input type='text' required list ='catselect' maxlength='255' size='50' name='catselect'><span class='highlight'></span><span class='bar'></span><label>OLD CATEGORY NAME</label><br><br>";
    echo "<input type='hidden' name='update' value='yes'>";
    echo "<datalist id = 'catselect'>";
    while ($row = $result->fetch())
    {
        $r0 = htmlspecialchars($row['categoryname']);

        echo "<option value='$r0'></option>";
    }
    echo "</datalist><br><br>";
    echo"<center><div class='input-group'><input type='text' required maxlength='255' size='50' name='updateentry'><span class='highlight'></span><span class='bar'></span><label>NEW CATEGORY NAME</label><br><br>";
    echo "<button type='submit' class='big-button' value='UPDATE CATEGORY'>UPDATE</button></center></form>";
  
    function get_post($pdo, $var)
    {
        return $pdo->quote($_POST[$var]);
    } 
    function validate_name($field, $pdo) 
    {
    $query = "SELECT count(categoryname) as count FROM category WHERE categoryname = $field";
    $result = $pdo->query($query);
    while ($row = $result->fetch())
    {
        $val = htmlspecialchars($row['count']);
    }
    if ($field == "") {return "No Category was entered<br>";}
    else if (strlen($field) > 255) {
      return "Category name must no more than 255 characters<br>";
    }
    else if ($val != 0)
    {
        return "Category name already in database<br>";
    }
    return "";
    }
?>

    </body>
</html>