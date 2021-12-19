<!-- http://localhost/HelfertyEricAsst1/Asst1Main.php -->
<?php
require_once("Asst1Include.php");

//main
date_default_timezone_set('America/Toronto');
$mysqlObj;
$TableName = "BroadwayShows";
CreateConnection();
echo"<div class = header>";
WriteHeaders("Broadway Ticket Booth", "Eric Helferty Assignment 1");
echo "</div>";

if(isset($_POST['f_CreateTable']))
    CreateTableForm();
else if(isset($_POST['f_AddRecord']))
    AddRecordForm();
else if(isset($_POST['f_SaveRecord']))
    AddRecordToTable($mysqlObj, $TableName, $_POST["f_showName"], $_POST["f_performanceDate"],
                    $_POST["f_performanceTime"], $_POST["f_noTickets"], $_POST["f_price"]);
else if(isset($_POST['f_ShowData']))
     ShowDataForm();
else
     DisplayMainForm();


CloseConnection();
WriteFooters();
//end main

function CreateConnection()
{
    global $mysqlObj;
    $mysqlObj = CreateConnectionObject();
}

function CloseConnection()
{
    global $mysqlObj;
    $mysqlObj->close();
}

function DisplayMainForm()
{
    echo "<form action = ? method = post>";
    echo "<div class=\"buttonsContainer\">";
    DisplayButton("f_CreateTable","Buttons/CreateTableBttn.png","Create Table");
    DisplayButton("f_AddRecord","Buttons/AddRecordBttn.png","Add Record");
    DisplayButton("f_ShowData","Buttons/ShowDataBttn.png","Show Data");
    echo "</div>";  
    echo "</form>";
}

function CreateTableForm()
{
    echo "<div class = \"alignCenter\">";
    echo "<form action=? method=post>";
    global $mysqlObj;
    global $TableName;
    $stmt = $mysqlObj->prepare("Drop table If Exists $TableName");
    $stmt->execute();
    $field1 = "showName varchar(50)";
    $field2 = "performanceDateAndTime datetime";
    $field3 = "nbrTickets int";
    $field4 = "ticketPrice decimal(5,2)";
    $SQLStatement = "Create Table $TableName($field1, $field2, $field3, $field4)";
	$stmt = $mysqlObj->prepare($SQLStatement);
	$CreateResult = $stmt->execute();
	if ($CreateResult) 
		echo "<p>Table \"$TableName\" Created.</p>";
	else
        echo "Unable to create table $TableName" . $stmt->error;
    $stmt->close();
    
    DisplayButton("f_Home", "Buttons/HomeBttn.png", "Home");
    
    echo "</form></div>";
    
}

function AddRecordForm()
{
    global $mysqlObj;
    global $TableName;

    echo "<form action=? method=post>
    <h2>Ticket Purchase Details</h2>
    <div class = \"orderFormContainer\">";

    echo "<div class = \"DataPair\">";
    DisplayLabel("Show Name: ");
    DisplayTextbox("f_showName");
    echo "</div>";

    $currentDate=date('Y-m-d');
    echo "<div class = \"DataPair\">";
    DisplayLabel("Performance Date: ");
    echo" <input type=date name=\"f_performanceDate\" value=\"$currentDate\">";
    echo "</div>";

    $currentTime=date('h:i');
    echo "<div class = \"DataPair\">";
    DisplayLabel("Performance Time: ");
    echo" <input type=time name=\"f_performanceTime\" value=\"$currentTime\">";
    echo "</div>";

    echo "<div class = \"DataPair\">";
    DisplayLabel("Number of Tickets: ");
    echo" <input type=number name=\"f_noTickets\" min=\"1\" max=\"10\" value=\"2\">";
    echo "</div>";

    echo "<div class = \"DataPair\">";
    DisplayLabel("Ticket Price: ");
    echo"<input type=\"radio\" name=\"f_price\" id=\"100\" value= \"100.00\" checked>";
    DisplayLabel("$100");
    echo"<input type=\"radio\" name=\"f_price\" id=\"150\" value= \"150.00\">";
    DisplayLabel("$150");
    echo"<input type=\"radio\" name=\"f_price\" id=\"200\" value= \"200.00\">";
    DisplayLabel("$200"); 
    echo "</div>";
     
    DisplayButton("f_SaveRecord", "Buttons/SaveRecord.png", "Save Record");
    DisplayButton("f_Home", "Buttons/HomeBttn.png", "Home");
    echo"</div>";
    
    echo "</form>";
      
}

function AddRecordToTable(&$mysqlObj, $TableName, $ShowName, $ShowDate,
                            $ShowTime, $NbrTickets, $TicketPrice)
{
    echo "<form action =? method = post>";
    $query = "INSERT INTO BroadwayShows 
        (showName, performanceDateAndTime, nbrTickets, ticketPrice) VALUES (?,?,?,?)";
    $stmt = $mysqlObj->prepare($query);
    if($stmt == false)
    {
        echo "Prepare failed on query $query". $mysqlObj->error;
        exit;
    }
    $DateTime = $ShowDate . " " . $ShowTime;
    $BindSuccess = $stmt->bind_param("ssid", $ShowName, $DateTime, $NbrTickets, $TicketPrice);
    if($BindSuccess)
        $success=$stmt->execute();
    else
        echo"Bind Failed: " . $stmt->error;
    
    if($success)
        echo"<p>Record Successfully added to $TableName.</p>";
    else
        echo "<p>Unable to add record to $TableName.</p>";
    $stmt->close();
    
    DisplayButton("f_Home", "Buttons/HomeBttn.png", "Home");
    echo "</form>";
}

function ShowDataForm()
{
    global $mysqlObj;
    global $TableName;
    echo "<form action = ? method=post>
    <h2>Ticket Sales</h2>";

    $SelectStmt = "Select showName, performanceDateAndTime, nbrTickets, 
                        ticketPrice from $TableName order by ticketPrice";
    $stmt = $mysqlObj->prepare($SelectStmt);
    $stmt->execute(); 
	$stmt->bind_result($ShowNameField, $ShowDateTimeField, $NbrTicketsField, $TicketPriceField);
    
    echo"<table border =2>
         <tr>
            <th>Performance Name</th>
            <th>Performance Date/Time</th>
            <th>Number of Tickets</th>
            <th>Price Per Ticket</th>
         </tr>";
	while ($stmt->fetch())
     echo "<tr>
                <td>" . $ShowNameField ."</td>
                <td> ".  $ShowDateTimeField . "</td>
                <td> " . $NbrTicketsField . "</td>
                <td> " . $TicketPriceField . "</td>
            </tr>";
    echo"</table>";

    echo "<div class = \"alignCenter\"";
    echo "<p>" . $stmt->num_rows . " bookings to date.</p>";
    
    $stmt->close();
   
    DisplayButton("f_Home", "Buttons/HomeBttn.png", "Home");
    echo "</div>";
    echo"</form>";
}
?>