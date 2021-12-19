<!-- http://localhost/HelfertyEricAsst1/Asst1Main.php -->
<?php

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj=new mysqli($Host, $UserName, $Password,$Database,$Port);
    // if the connection and authentication are successful, 
    // the error number is 0
    if ($mysqlObj->connect_errno != 0) 
    {
     echo "<p>Connection failed. Unable to open database $Database. 
          Error: ". $mysqlObj->connect_error . "</p>";
     // stop executing the php script
     exit;
    }
    return ($mysqlObj);
}

function WriteHeaders($Heading="Welcome",$TitleBar="MySite")
{
    echo"
        <!doctype html>
        <html lang= \"en\">
        <head>
            <meta charset = \"UTF-8\">
            <title>$TitleBar</title>\n
        </head>
        <body>\n
        <h1>$Heading</h1>\n
        <link rel =\"stylesheet\" type = \"text/css\" href=\"Asst1Style.css\"/>
        ";
}

function DisplayLabel($prompt)
{
    echo "<label>" . $prompt . "</label>";
}

function DisplayTextbox($Name,  $Placeholder ="Enter Here", $Size=20)
{
    echo "<input type = text name = \"$Name\" size = $Size 
                        placeholder = \"$Placeholder\">";
}

function DisplayContactInfo()
{
    echo "Comments? Questions? Contact me here: ";
    echo "<a href = \"mailto:ehelferty27@student.sl.on.ca\">ehelferty27@student.sl.on.ca</a>";
}

function DisplayImage($FileName, $Alt, $Height, $Width)
{
    echo "<img src = \"$FileName\" height = \"$Height\" 
                width = \"$Width\" alt = \"$Alt\"/>";
}

function DisplayButton($Name, $FileName, $Alt)
{
    if($FileName=="")
    {
        echo"<button type = Submit name = \"$Name\">$Name</button>";
    }
    else
    {
        echo "<button type = Submit name = \"$Name\">"; 
        DisplayImage($FileName, $Alt, 40, 100);
        echo "</button>";
    }
}

function WriteFooters()
{
    echo "<div class = \"footer\">";
    DisplayContactInfo();
    echo"</body>\n";
    echo"</html>\n";
    echo "</div>";
}


?>