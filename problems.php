<?php
    require_once 'login.php';
    try 
    {
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch (PDOException $e)
    {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
?>
<html>
<head>
        <title>Programming Problems</title>
        <script src="https://kit.fontawesome.com/29ba4da398.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/x-icon" href="mcLogo.png">
    <style>
        #searchinput {
        background-image: url('search.png'); 
        background-position: 10px 12px; 
        background-repeat: no-repeat; 
        width: 50%; 
        font-size: 16px;
        padding: 12px 20px 12px 40px; 
        border: 1px solid #ddd; 
        }

        #idHeader {
                color : #ff6600;
                font-size: 45px;
                font-weight: lighter;
                font-family: fantasy, sans-serif;
            }
        #idCategories {
                color : #5B0F1B;
                background-color: #ff6600;
                border-radius: 15px;
                width: fit-content;
                font-size: 30px;
                font-weight: lighter;
                font-family: fantasy, sans-serif;
                tab-size: 8;
            }
        .button {
            background-color: #5B0F1B;
            color : #ff6600;
            width: 50px;
            font-size: 30px;
            padding: 10px;
            cursor: pointer;
            border-radius: 30%;
            -webkit-font-smoothing: antialiased;
        }
        .button:hover {
	        opacity: 0.85;
        }
        .button:active {
	        box-shadow: inset 0 3px 4px hsla(0, 0%, 0%, 0.2);
        }
                
            * {
  box-sizing: border-box;
}
            body {
            margin: 0;
            }
            .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        font-weight: lighter;
        font-family: 'Franklin Gothic Medium', sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        text-align: center;
        margin-left: auto;
  margin-right: auto;

    }
    .styled-table thead tr {
    background-color: #000080;
    color: #ffffff;
}

.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: thin solid #dddddd;
}
.styled-table a {
    text-decoration: none;
}

.styled-table a:hover {
    text-decoration: underline;
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
    <body style="background-color: #e8e9f3">
    <noscript>
        <meta http-equiv="refresh" content="0; URL=ProgTeam.html" />
    </noscript>
    <div class = "side">
        <pre>
            <center><h1 id="idHeader"><img align = "left" src="mcLogo.png">PROBLEMS<img align= "right" src="MCProgLogo.png"></h1></center>
    </pre></div>
    <div class= "navbar">
        <a href="ProgTeam.html">MAIN PAGE</a>    
        <a href="categories.php">EDIT CATEGORIES</a>
        <a href="probedit.php">EDIT PROBLEMS</a>
    </div>

    <script>
        current = 0
        function moveCurrent(direction, length) {
            var catarr = JSON.parse(JSON.stringify(categories))
            if (direction == 'left') {
                if (current == 0) {
                    current = length - 1
                }
                else {
                    current--
                }
            }
            else {
                if (current == length - 1) {
                    current = 0
                }
                else {
                    current++
                }
        }
        
        document.getElementById("current").value = current
        document.getElementById("catLabel").innerHTML = catarr[ids[current]]
        document.querySelectorAll('table').forEach(e => e.remove());
        tableGeneration();
        
    } 
    function tableGeneration() {
        var body = document.getElementsByTagName('body')[0];
        var tbl = document.createElement('table');
        tbl.setAttribute('class', 'styled-table')
        tbl.setAttribute('id', 'probtable')
        var tbdy = document.createElement('tbody');
        var probarr = JSON.parse(JSON.stringify(problems))
        var linkarr = JSON.parse(JSON.stringify(links))
        l = probarr[ids[current]].length
        var thead = document.createElement('thead');
        var tr = document.createElement('tr');
        var td1 = document.createElement('td')
        td1.appendChild(document.createTextNode("PROBLEM NAME"));
        tr.appendChild(td1)
        var td2 = document.createElement('td')
        td2.appendChild(document.createTextNode("LINK"));
        tr.appendChild(td2)
        thead.appendChild(tr);
        var tbdy = document.createElement('tbody');
    for (var i = 0; i < l; i++) {
        var tr = document.createElement('tr');
        for (var j = 0; j < 2; j++) {
        currentprob = probarr[ids[current]][i]
        if (j == 0) {
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(currentprob));
            tr.appendChild(td)
        }
        else {
            var td = document.createElement('td');
            var a = document.createElement('a')
            a.href = linkarr[currentprob]
            a.target = "_blank"
            a.innerHTML = linkarr[currentprob]
            td.appendChild(a)
            tr.appendChild(td)
        }
        }
        tbdy.appendChild(tr);
        }
        tbl.appendChild(thead);
        tbl.appendChild(tbdy);
        body.appendChild(tbl)
    }      
    function search() {
  // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchinput");
        table = document.getElementById("probtable");
        filter = input.value.toUpperCase();
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }
        }
        }
    </script>
    
    <input type="hidden" name="current" id="current" value="0"><br>
    <center><input type="text" id="searchinput" onkeyup="search()" placeholder="Search for problem.."></center>
<?php
    
    $query = "SELECT * from category ORDER BY categoryID;";
    $result = $pdo->query($query);
    $current = $_REQUEST["current"];
    $categories = array();
    $cattoprob = array();
    $ids = array();
    while ($row = $result->fetch())
    {
        $r0 = htmlspecialchars($row['categoryname']);
        $r1 = htmlspecialchars($row['categoryID']);
        
        $categories[$r1] = $r0;
        $ids[] = $r1;
    }
    $length = sizeof($categories);
echo <<<_END
    <center>
    <h2 id = 'idCategories'> <input type='submit' class='button' value='<' onClick="moveCurrent('left', $length)">
    <label id= "catLabel">$categories[$current]</label>
    <input type='submit' class='button' value='>' onClick="moveCurrent('right', $length)"></h2>
    </center>
    <script>length=$length</script>
_END;

    $query = "SELECT * from problem ORDER BY categoryID;";
    $result = $pdo->query($query);
    $probtolink = array();
    
    while ($row = $result->fetch())
    {
        $r0 = htmlspecialchars($row['problemname']);
        $r1 = htmlspecialchars($row['link']);
        $r2 = htmlspecialchars($row['categoryID']);
        if (isset($cattoprob[$r2])) {
            array_push($cattoprob[$r2], $r0);
        } 
        else {
            $cattoprob[$r2] = array();
            array_push($cattoprob[$r2], $r0);
        }
        $probtolink[$r0] = $r1;

    }
    
    function get_post($pdo, $var)
    {
        return $pdo->quote($_POST[$var]);
    } 
    function in_array_multi(
        mixed $value, 
        array $arr, 
        bool $strict = false) 
    {
        foreach ($arr as $i) {
            if (
                ($strict ? $i === $value : $i == $value) ||
                (is_array($i) && in_array_multi($value, $i, $strict))
            ) {
                return true;
            }
        }
        return false;
    }

?>
    <center>
    <script>
        var categories = <?php echo json_encode($categories);?>;
        var problems = <?php echo json_encode($cattoprob);?>;
        var links = <?php echo json_encode($probtolink);?>;
        var ids = <?php echo json_encode($ids);?>;
        tableGeneration();
    </script>
    </center>

    </body>
</html>