<?php
$date1 = mysql_escape_string($_GET['date1']);
$date2 = mysql_escape_string($_GET['date2']);
$DB_Server = "127.0.0.1"; //MySQL Server    
$DB_Username = "root"; //MySQL Username     
$DB_Password = "";             //MySQL Password     
$DB_DBName = "testnew";         //MySQL Database Name  
/*******EDIT LINES 3-8*******/
session_start();
if(isset($_GET['exot']) && ($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR')){
    $DB_TBLName = "overtime"; //MySQL Table Name   
    $filename = "Format";         //File Name
    /*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
    //create MySQL connection   
    $sql = "Select login.phoenix_empid,dateofot,IF(approvedothrs is not null, 1, 0) as ot,approvedothrs from overtime,login where overtime.account_id = login.account_id and dateofot BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin') ORDER BY dateofot ASC";
    $Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
    //select database   
    $Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
    //execute query 
    $result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
    $file_ending = ".xls";
    //header info for browser
    header("Content-type: application/vnd-ms-excel");    
    header("Content-Disposition: attachment; filename=$filename.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    /*******Start of Formatting for Excel*******/   
    //define separator (defines columns in excel & tabs in word)
    $sep = "\t"; //tabbed character
    //start of printing column names as names of MySQL fields
    echo 'EmployeeId' . "\t";
    echo 'Date' . "\t";
    echo 'AttendanceType' . "\t";
    echo 'Hours' . "\t";
    echo 'JobCode' . "\t";
    echo 'SubCategory' . "\t";
    echo 'Rate' . "\t";
    echo 'EmployeeName' . "\t";

    print("\n");    
    //end of printing column names  
    //start while loop to get data
        while($row = mysql_fetch_row($result))
        {
            $schema_insert = "";
            for($j=0; $j<mysql_num_fields($result);$j++)
            {
                if(!isset($row[$j])){
                    $schema_insert .= "NULL".$sep;
                }
                elseif ($row[$j] != ""){
                	if(stristr($row[$j], ':30') == true){
                		$row[$j] = str_replace(':30', '.5', $row[$j]);
                	}elseif(stristr($row[$j], ':00') == true){
                		$row[$j] = str_replace(':00', '', $row[$j]);
                	}elseif(stristr($row[$j], ':0') == true){
                		$row[$j] = str_replace(':0', '', $row[$j]);
                	}
                    $schema_insert .= "$row[$j]".$sep;
                }
                else{
                    $schema_insert .= "".$sep;
                }
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
            $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
        }   
    }
?>
<?php
if(isset($_GET['exob'])  && ($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR')){
    /*******EDIT LINES 3-8*******/
    $DB_TBLName = "officialbusiness"; //MySQL Table Name   
    $filename = "portalOB-" . date("Y-m-d");         //File Name
    /*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
    //create MySQL connection   
    $sql = "Select phoenix_chrono,DATE_FORMAT(obdatereq, '%m-%d-%Y'),obtimein,IF(obtimein is not null, 1, 0) as ot from officialbusiness,login where officialbusiness.account_id = login.account_id and obdatereq BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin') and obtimein is not null and obtimein != '' ORDER BY obdatereq asc";
    $sql2 = "Select phoenix_chrono,DATE_FORMAT(obdatereq, '%m-%d-%Y'),obtimeout,IF(obtimeout is not null, 2, 0) as ot from officialbusiness,login where officialbusiness.account_id = login.account_id and obdatereq BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin') and obtimeout is not null and obtimeout != '' ORDER BY obdatereq asc";
    $Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
    //select database   
    $Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
    //execute query 
    $result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
    $result2 = @mysql_query($sql2,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
    $file_ending = ".txt";
    //header info for browser
    header("Content-type: application/vnd-ms-excel");    
    header("Content-Disposition: attachment; filename=$filename.txt");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    /*******Start of Formatting for Excel*******/   
    //define separator (defines columns in excel & tabs in word)
    $sep = "\t"; //tabbed character
    //start of printing column names as names of MySQL fields

    //end of printing column names  
    //start while loop to get data
        while($row = mysql_fetch_row($result))
        {
            $schema_insert = "";
            for($j=0; $j<mysql_num_fields($result);$j++)
            {
                if($row[3] == ""){
                    continue;
                }
                if(!isset($row[$j])){
                    $schema_insert .= "NULL".$sep;
                }
                elseif ($row[$j] != ""){
                    if(stristr($row[$j], ':') == true){
                        $row[$j] = date("H:i:s",strtotime($row[$j]));
                    }
                    $schema_insert .= "$row[$j]".$sep;
                }
                else{
                    $schema_insert .= " ".$sep;
                }
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
            $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
        } 
        while($row = mysql_fetch_row($result2))
        {
            $schema_insert = "";
            for($j=0; $j<mysql_num_fields($result2);$j++)
            {
                if($row[3] == ""){
                    continue;
                }
                if(!isset($row[$j])){
                    $schema_insert .= "NULL".$sep;
                }
                elseif ($row[$j] != ""){
                    if(stristr($row[$j], ':') == true){
                        $row[$j] = date("H:i:s",strtotime($row[$j]));
                    }
                    $schema_insert .= "$row[$j]".$sep;
                }
                else{
                    $schema_insert .= "".$sep;
                }
            }
            $schema_insert = str_replace($sep."$", "", $schema_insert);
            $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
        }  
    }
?>
<?php
    if(isset($_GET['exlea']) && ($_SESSION['level'] == 'ACC' || $_SESSION['level'] == 'HR')){
        // The function header by sending raw excel
        header("Content-type: application/vnd-ms-excel");
         
        // Defines the name of the export file "codelution-export.xls"
        header("Content-Disposition: attachment; filename=Format.xls");
?>
    <table border="1">
        <tr>
            <th>EmployeeId</th>
            <th>Date</th>
            <th>AttendanceType</th>
            <th>Hours</th>
            <th>JobCode</th>
            <th>SubCategory</th>
            <th>Rate</th>
            <th>EmployeeName</th>
        </tr>
        <?php
            include 'conf.php';
            $sql1 = "SELECT * FROM nleave,login where nleave.account_id = login.account_id and (state = 'AAdmin' or state = 'CheckedHR' or state = 'CLea' or state = 'ReqCLea' or state = 'ReqCLeaHR') and (dateofleavfr BETWEEN '$date1' and '$date2' or dateofleavto BETWEEN '$date1' and '$date2') and typeoflea != 'Sick Leave' ORDER BY datefile ASC";
            $sql2 = "SELECT * FROM nleave,login where nleave.account_id = login.account_id and (state = 'AAdmin' or state = 'CheckedHR' or state = 'CLea' or state = 'ReqCLea' or state = 'ReqCLeaHR') and (datehr BETWEEN '$date1' and '$date2') and typeoflea = 'Sick Leave' ORDER BY datefile ASC";
            $result1 = $conn->query($sql1);
            $result2 = $conn->query($sql2);
            if($result1->num_rows > 0 || $result2->num_rows > 0){
                while($row1 = $result1->fetch_assoc()){               
                    $i = 0;
                    while($row1['dateofleavfr'] <= $row1['dateofleavto']){
                        if($i >= 1){
                            $plus = 1;
                        }else{
                            $plus = 0;
                        }
                        if($i == $row1['numdays']){
                            break;
                        }
                        $row1['dateofleavfr'] = date("Y-m-d", strtotime("+" . $plus . " days", strtotime($row1['dateofleavfr'])));
                        if(date("D", strtotime($row1['dateofleavfr'])) == 'Sun'){
                            $row1['dateofleavfr'] = date("Y-m-d", strtotime("+1 days", strtotime($row1['dateofleavfr'])));
                        }
                        echo '<tr>';
                        echo '<td>' . $row1['phoenix_empid'] . '</td>';
                        echo '<td>' . $row1['dateofleavfr'] . '</td>';
                        if($row1['typeoflea'] == 'Vacation Leave'){
                            echo '<td>21</td>';  
                        }elseif($row1['typeoflea'] == 'Others'){
                            echo '<td>24</td>';
                        }elseif($row1['typeoflea'] == 'Sick Leave'){
                            echo '<td>22</td>';
                        }
                        if(strtoupper($row1['position']) == 'SERVICE TECHNICIAN'){
                            echo '<td>8</td>';
                        }else{
                            echo '<td>9</td>';
                        }
                        $i++;
                    }
                }
                while($row1 = $result2->fetch_assoc()){              
                    $i = 0;
                    while($row1['dateofleavfr'] <= $row1['dateofleavto']){
                        if($i >= 1){
                            $plus = 1;
                        }else{
                            $plus = 0;
                        }
                        if($i == $row1['numdays']){
                            break;
                        }
                        $row1['dateofleavfr'] = date("Y-m-d", strtotime("+" . $plus . " days", strtotime($row1['dateofleavfr'])));
                        if(date("D", strtotime($row1['dateofleavfr'])) == 'Sun'){
                            $row1['dateofleavfr'] = date("Y-m-d", strtotime("+2 days", strtotime($row1['dateofleavfr'])));
                        }
                        echo '<tr>';
                        echo '<td>' . $row1['phoenix_empid'] . '</td>';
                        echo '<td>' . $row1['dateofleavfr'] . '</td>';
                        if($row1['typeoflea'] == 'Vacation Leave'){
                            echo '<td>21</td>';  
                        }elseif($row1['typeoflea'] == 'Others'){
                            echo '<td>24</td>';
                        }elseif($row1['typeoflea'] == 'Sick Leave'){
                            echo '<td>22</td>';
                        }
                        if(strtoupper($row1['position']) == 'SERVICE TECHNICIAN'){
                            echo '<td>8</td>';
                        }else{
                            echo '<td>9</td>';
                        }
                        $i++;
                    }   
                }
            }
        ?>
    </table>
<?php  
    }
?>