<?php
    require_once 'login.php';
    $newCat = false;
    $entryset = false;
    $linkset = false;
    $problemset = false;
    $updateset = false;
    $deleted = false;
    $fail = "";
    try 
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    if (isset($_POST['delete']) && isset($_POST['probselect']))
    {
        $name = get_post($pdo, 'probselect');
        $link = fix_string(get_post($pdo, 'linkupdateentry'));
        $query = "SELECT problemID from problem where problemname=" . $name;
        $result = $pdo->query($query);
        while ($row = $result->fetch())
        {
            $ID = htmlspecialchars($row['problemID']);
        }
        $query = "DELETE FROM problem WHERE problemID=$ID;";
        $result = $pdo->query($query);
        $deleted = true;
    }
    
    if (isset($_POST['update']) && isset($_POST['probselect']) && isset($_POST['updateentry']))
    {
        $name = get_post($pdo, 'probselect');
        $problem = fix_string(get_post($pdo, 'updateentry'));
        $problemset = true;
        $updateset = true;
    }

    if (isset($_POST['linkupdate']) && isset($_POST['probselect']) && isset($_POST['linkupdateentry']))
    {
        $name = fix_string(get_post($pdo, 'probselect'));
        $link = fix_string(get_post($pdo, 'linkupdateentry'));
        $ID = get_ID($name, $pdo);
        $linkset = true;
        $updateset = true;
    }

    if (isset($_POST['catupdate']) && isset($_POST['probselect']) && isset($_POST['catentry'])){
        $updateset = true;
        $name = fix_string(get_post($pdo, 'probselect'));
        $category = fix_string(get_post($pdo, 'catentry'));
        $ID = get_ID($name, $pdo);
        $count = check_category($category, $pdo);
        echo"<script>alert($count)</script>";
        if ($count > 0){
            $categoryID = get_catID($category, $pdo);
            try {
                $pdo->beginTransaction();
                $query = "UPDATE problem SET categoryID= " . $categoryID . " WHERE problemID=$ID;" ;
                $result = $pdo->exec($query);
                $pdo->commit();
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            die($e->getMessage());
    }
    }
    else {
        $fail .= "Category not found in database<br>";
    }
}

    if (isset($_POST['catentry']) && isset($_POST['probentry']) && isset($_POST['linkentry']))
    {
        $entryset = true;
        $category = fix_string(get_post($pdo, 'catentry'));
        $problem = fix_string(get_post($pdo, 'probentry'));
        $link = fix_string(get_post($pdo, 'linkentry'));
        $count = check_category($category, $pdo);
        if ($count <= 0) {
            $fail .= "Category not found in database<br>";
        }
        else {
        $categoryID = get_catID($category, $pdo); 
    }
        
       
}
    if($entryset) {
        $fail .= validate_link($link, $pdo);
        $fail .= validate_name($problem);
    }
    if($linkset) {
        $fail .= validate_link($link, $pdo);
    }
    if($problemset) {
        $fail .= validate_name($problem);
    }

    if($entryset && $fail == "") {
        try {

            $pdo->beginTransaction();
            $query = "INSERT INTO problem (problemname, categoryID, link) VALUES (" . $problem . "," . $categoryID . "," . $link . "); ";
            $result = $pdo->exec($query);
            $pdo->commit();
        } 
        catch (PDOException $e) {
            $pdo->rollBack();
            die($e->getMessage());
    }
    }
    if($problemset && $fail == "") {
        try {
            $pdo->beginTransaction();
            $ID = get_ID($name, $pdo);
            $query = "UPDATE problem SET problemname= " . $problem . " WHERE problemID=$ID;";
            $result = $pdo->exec($query);
            $pdo->commit();
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            die($e->getMessage());
    }
}
    if($linkset && $fail == "") {
        try {
            $pdo->beginTransaction();
            $query = "START TRANSACTION; UPDATE problem SET link= " . $link . " WHERE problemID=$ID;" ;
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
    return htmlentities ($string);
  }

  function get_ID($name, $pdo) {
    $query = "SELECT problemID from problem where problemname=" . $name;
        $result = $pdo->query($query);
        while ($row = $result->fetch())
        {
            $ID = htmlspecialchars($row['problemID']);
        }
        return $ID;
  }
  function get_catID($name, $pdo) {
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
        <title>Programming Problems</title>
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
            #idSection {
                color : #ff6600;
                font-size: 30px;
                font-weight: lighter;
                font-family: fantasy;
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
        cursor: pointer;
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
    <link href="select.css" rel="stylesheet">
    <form name = "Probeditor" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class = "side">
            
    <pre><center><h1 id="idHeader"><img align ="left" src="mcLogo.png">PROBLEM EDITOR<img align= "right" src="MCProgLogo.png"></h1></center>
    </pre></div>
    <div class= "navbar">
        <a href="problems.php">BACK TO PROBLEMS</a>
    </div>

    <script>
        function dupe(id, hidden, needid = false) {
            var elem = document.getElementById(id);
            var newelem = document.getElementById(hidden);
            var clone = elem.cloneNode(true);
            if (needid) {
                clone.id = 'clone';
            }
            newelem.after(clone);
        }

       
    </script>
    
    <center><h1 id="idHeader">ADD A PROBLEM:</h1>
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
    <div class="input-group"><input type='text' required maxlength='255' size='50' name='probentry'><span class="highlight"></span><span class="bar"></span><label>PROBLEM NAME</label></div>
    <div class="input-group"><input type='text' required maxlength='255' size='50' name='linkentry'><span class="highlight"></span><span class="bar"></span><label>LINK</label></div>
    <?php 
    $categories = array();
    $cattoprob = array();
    $catname = array();
    $query = "SELECT * from category ORDER BY categoryID;";
    $result = $pdo->query($query);
    while ($row = $result->fetch())
    {
        $r0 = htmlspecialchars($row['categoryname']);
        $r1 = htmlspecialchars($row['categoryID']);
        
        $categories[$r0] = $r1;
        $catname[] = $r0;
    }
    echo"<center><div class='input-group'><input type='text' required list ='catentry' maxlength='255' size='50' name='catentry' id = 'catlist' ><span class='highlight'></span><span class='bar'></span><label>CATEGORY</label><br><br>";
    echo "<datalist id='catentry'>";
    foreach ($catname as $value) {
        echo "<option value='$value'>";
    }
    echo "</datalist>"
    ?>
    
    
    <div class="input-group"><button type='submit' name='submit' class='big-button' value='SUBMIT'>SUBMIT</button>
    </pre>
    <h1 id="idHeader">EDIT A PROBLEM:</h1></center>
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
    if ($deleted) {
        echo "<h1 id='idSuccess'>Deleted Successfully!</h1>";
        echo"<script>alert('Deleted from database successfully')</script>";
    }
   $query = "SELECT * FROM problem ORDER BY problemname";
    $result = $pdo->query($query);
    echo "<form action = '' method='post'>";
    echo"<center><div class='input-group'><input type='text' required list ='probselect' maxlength='255' size='50' name='probselect' id = 'problist' ><span class='highlight'></span><span class='bar'></span><label>PROBLEM TO DELETE</label><br><br>";
    echo "<input type='hidden' name='update' value='yes'>";
    echo "<datalist id = 'probselect'>";

    while ($row = $result->fetch())
    {
        $r0 = htmlspecialchars($row['problemname']);
        echo "<option value='$r0'></option>";
    }
echo <<<_END
    </datalist>
    <form action = '' method='post'>
    <input type='hidden' name='delete' value='yes'>
    <button class='delete-button' type='submit' onclick="return confirm('Are you sure! want to delete?')" value='DELETE CATEGORY'>DELETE</button></form>
    <form action = '' method='post'><br>
    <input type='hidden' name='update' id='update' value='yes'>
    <script>dupe('problist', 'update')</script><label>CURRENT PROBLEM NAME</label>
    <div class='input-group'><input type='text' required maxlength='255' size='50' name='updateentry'><span class='highlight'></span><span class='bar'></span><label>NEW PROBLEM NAME</label><br><br>
    <button type='submit' class='button' value='UPDATE PROBLEM NAME'>UPDATE NAME</button></form>
    <form action = '' method='post'><br>
    <input type='hidden' name='linkupdate' id='linkupdate' value='yes'>
    <script>dupe('problist', 'linkupdate')</script><label>PROBLEM NAME </label>
    <div class='input-group'><input type='text' required maxlength='255' size='50' name='linkupdateentry'><span class='highlight'></span><span class='bar'></span><label>NEW LINK</label><br><br>
    <button type='submit' class='button' value='UPDATE LINK'>UPDATE LINK</button></form>
    <form action = '' method='post'><br>
    <input type='hidden' name='catupdate' id='catupdate' value='yes'>
    <script>dupe('problist', 'catupdate', true)</script><label>PROBLEM NAME </label><br><br>
    <input type='hidden'id='format'>
    <script>dupe('catlist', 'format')</script><label>NEW CATEGORY</label><br><br><br>
    <button type='submit' class='button' value='UPDATE CATEGORY'>UPDATE CATEGORY</button></form></center>
    </form>
_END;


    function get_post($pdo, $var)
    {
        return $pdo->quote($_POST[$var]);
    } 

    function validate_link($field, $pdo) 
    {
    $field = str_replace(' ', '', $field);
    $query = "SELECT count(link) as count FROM problem WHERE link = $field";
    $fixedurl = filter_var($field, FILTER_SANITIZE_URL);
    $result = $pdo->query($query);
    while ($row = $result->fetch())
    {
        $val = htmlspecialchars($row['count']);
    }
    if (fix_string($field) == "") {return "No link was entered<br>";}
    else if (strlen($field) > 500) {
      return "Link must no more than 500 characters<br>";
    }
    else if ($val != 0)
    {
        return "Link already in database<br>";
    }
    else if (is_url($field === false)) { return "Link is not valid<br>";}
    return "";
    }
    
    
    function validate_name($field) 
    {
    $field = str_replace(' ', '', $field);
    if (fix_string($field) == "") {return "No Problem was entered<br>";}
    else if (strlen($field) > 255) {
      return "Problem name must no more than 255 characters<br>";
    }
    return "";
    }
    function is_url($uri){
        if(preg_match( '/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$uri)){
          return true;
        }
        else{
            return false;
        }
    }
    function check_category($field, $pdo) {
        $query = "SELECT count(categoryname) as count FROM category WHERE categoryname =" . $field;
        $result = $pdo->query($query);
        while ($row = $result->fetch())
        {
            $val = htmlspecialchars($row['count']);
        }
        return $val;
    }
?>
   

    </body>
</html>