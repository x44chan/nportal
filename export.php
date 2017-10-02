<?php
if(isset($_GET['date1'])){
    $date1 = mysql_escape_string($_GET['date1']);
    $date2 = mysql_escape_string($_GET['date2']);
}
$DB_Server = "127.0.0.1"; //MySQL Server    
$DB_Username = "root"; //MySQL Username     
$DB_Password = "";             //MySQL Password     
$DB_DBName = "testnew";         //MySQL Database Name  

include('savelogs.php');
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
        savelogs("Export Overtime", "For the Cutoff " . date("M j, Y", strtotime($date1)) . ' to ' . date("M j, Y", strtotime($date2)));
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
    $sql2 = "Select phoenix_chrono,obdatereq,obtimeout,IF(obtimeout is not null, 2, 0) as ot,nxtday from officialbusiness,login where officialbusiness.account_id = login.account_id and obdatereq BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin') and obtimeout is not null and obtimeout != '' ORDER BY obdatereq asc";
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
                if($j == 4){
                    continue;
                }
                if(!isset($row[$j])){
                    $schema_insert .= "NULL".$sep;
                }
                elseif ($row[$j] != ""){
                    if($j == 1){
                        if($row[4] == 1){
                            $row[$j] = date("m-d-Y",strtotime("+1 day", strtotime($row[$j])));
                        }else{
                            $row[$j] = date("m-d-Y",strtotime($row[$j]));
                        }
                    }
                    if(stristr($row[$j], ':') == true){
                        $row[$j] = date("H:i:s",strtotime($row[$j]));
                    }
                    $schema_insert .= "$row[$j]" . $sep;
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
        savelogs("Export Official Business", "For the Cutoff " . date("M j, Y", strtotime($date1)) . ' to ' . date("M j, Y", strtotime($date2)));
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
            $sql1 = "SELECT * FROM nleave,login where nleave.account_id = login.account_id and (state = 'AAdmin') and (dateofleavfr BETWEEN '$date1' and '$date2' or dateofleavto BETWEEN '$date1' and '$date2') and leapay = 'wthpay' ORDER BY datefile ASC";
            $sql2 = "SELECT * FROM overtime,login where overtime.account_id = login.account_id and (state = 'AAdmin' or state = 'CheckedHR') and dateofot BETWEEN '$date1' and '$date2' ORDER BY datefile ASC";
            $result1 = $conn->query($sql1);
            $result2 = $conn->query($sql2);
            if($result1->num_rows > 0 || $result2->num_rows > 0){
                savelogs("Export Leave and Overtime", "For the Cutoff " . date("M j, Y", strtotime($date1)) . ' to ' . date("M j, Y", strtotime($date2)));
                while($row1 = $result2->fetch_assoc()){              
                    echo '<tr>';
                    echo '<td>' . $row1['phoenix_empid'] . '</td>';
                    echo '<td>' . $row1['dateofot'] . '</td>';
                    if(stristr($row1['officialworksched'], 'Restday') == true){
                    	$type = '17';
                    }elseif(stristr($row1['officialworksched'], 'Special N-W Holliday') == true){
                    	$type = '16';
                    }elseif(stristr($row1['officialworksched'], 'Legal Holliday') == true){
                    	$type = '15';
                    }elseif(stristr($row1['officialworksched'], 'Oncall') == true){
                        $type = '4';
                    }else{
                    	$type = '1';
                    }
                    echo '<td>'.$type.'</td>';
                    if(stristr($row1['approvedothrs'], ':30') == true){
                        $row1['approvedothrs'] = str_replace(':30', '.5', $row1['approvedothrs']);
                    }elseif(stristr($row1['approvedothrs'], ':00') == true){
                        $row1['approvedothrs'] = str_replace(':00', '', $row1['approvedothrs']);
                    }elseif(stristr($row1['approvedothrs'], ':0') == true){
                        $row1['approvedothrs'] = str_replace(':0', '', $row1['approvedothrs']);
                    }
                    echo '<td>' . $row1['approvedothrs'] . '</td>';
                    echo '<td></td><td></td><td></td><td></td>';
                    echo '</tr>';
                }
                while($row1 = $result1->fetch_assoc()){               
                    $i = 0;
                    while($row1['dateofleavfr'] <= $row1['dateofleavto'] && $row1['numdays'] >= 1){
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
                        if($row1['leapay'] == 'wthoutpay'){
                            echo '<td>25</td>';
                        }elseif($row1['typeoflea'] == 'Vacation Leave'){
                            echo '<td>21</td>';  
                        }elseif($row1['typeoflea'] == 'Others'){
                            echo '<td>21</td>';
                        }elseif($row1['typeoflea'] == 'Sick Leave'){
                            echo '<td>22</td>';
                        }elseif($row1['typeoflea'] == 'Paternity Leave'){
                            echo '<td>101</td>';
                        }elseif($row1['typeoflea'] == 'Solo Parent Leave'){
                            echo '<td>103</td>';
                        }
                        /*if($row1['leapay'] == 'wthoutpay'){
                            echo '<td>0</td>';
                        }else*/
                        if(strtoupper($row1['position']) == 'SERVICE TECHNICIAN'){
                            echo '<td>8</td>';
                        }else{
                            echo '<td>8</td>';
                        }
                        echo '<td></td><td></td><td></td><td></td>';
                        echo '</tr>';
                        $i++;
                    }
                    if($row1['numdays'] < 1){
                        echo '<tr>';
                        echo '<td>' . $row1['phoenix_empid'] . '</td>';
                        echo '<td>' . $row1['dateofleavfr'] . '</td>';
                        if($row1['leapay'] == 'wthoutpay'){
                            echo '<td>25</td>';
                        }elseif($row1['typeoflea'] == 'Vacation Leave'){
                            echo '<td>21</td>';  
                        }elseif($row1['typeoflea'] == 'Others'){
                            echo '<td>21</td>';
                        }elseif($row1['typeoflea'] == 'Sick Leave'){
                            echo '<td>22</td>';
                        }elseif($row1['typeoflea'] == 'Paternity Leave'){
                            echo '<td>101</td>';
                        }elseif($row1['typeoflea'] == 'Solo Parent Leave'){
                            echo '<td>103</td>';
                        }
                       
                        echo '<td>'. number_format(8*$row1['numdays'],2).'</td>';
                        
                        echo '<td></td><td></td><td></td><td></td>';
                        echo '</tr>';
                    }
                }
            }
        ?>
    </table>
<?php  
    }
?>
<?php
    if(isset($_GET['201']) && ($_SESSION['level'] == 'Admin')){
        // The function header by sending raw excel
        header("Content-type: application/vnd-ms-excel");
         
        // Defines the name of the export file "codelution-export.xls"
        header("Content-Disposition: attachment; filename=201 Files Portal (".date("Y-m-d H/i/s A").").xls");
?>
    <style type="text/css">
        tr {
            padding: 10px !important;
            text-align: center;
        }
        td {
            padding: 10px !important;
            text-align: center;
        }
        th {
            padding: 10px !important;
            text-align: center;
        }
    </style>
    <table border="1">
        <thead>
            <tr>
                <th>Employee No.</th>
                <th>Chrono No.</th>
                <th>Full Name</th>
                <th>Date Hired</th>
                <th>Employment Status</th>
                <th>Status Date</th>
                <th>Service Period</th>
                <th>Current Position</th>
                <th>Department</th>
                <th>Civil Status</th>
                <th>Mobile No.</th>
                <th>Address</th>
                <th>Blood Type</th>
                <th>Birthday</th>
                <th>Age</th>
                <th>Salary Category</th>
                <th>Salary Per Day</th>
                <th>Salary Per Month</th>
                <th>Allowed Loan</th>
                <th>Allowed C.A.</th>
                <th>V.L.</th>
                <th>S.L.</th>
                <th>SSS #</th>
                <th>TIN #</th>
                <th>PHILHEALTH #</th>
                <th>PAGIBIG</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include 'conf.php';
                $sql1 = "SELECT *,TIMESTAMPDIFF(YEAR, ebday, CURDATE()) AS age,TIMESTAMPDIFF(DAY, edatehired, CURDATE()) AS serviceper from `login` where level != 'Admin' and fname is not null and account_id NOT IN (47,48,37) and (active = '1' or active IS NULL) order by phoenix_empid ASC";
                $result1 = $conn->query($sql1);
                if($result1->num_rows > 0){
                    while($row1 = $result1->fetch_object()){
                        $limit = 0;
                        $ca = '1,500';
                        $vacleave = ' - ';
                        $sleave = ' - ';
                        if($row1->empcatergory == 'Regular'){
                            $ca = '3,000';
                            $leaveexec = "SELECT * FROM `nleave_bal` where account_id = '$row1->account_id' and CURDATE() BETWEEN startdate and enddate and state = 'AAdmin'";
                            $datalea = $conn->query($leaveexec)->fetch_assoc();
                            $sleave = $datalea['sleave'];
                            $vacleave = $datalea['vleave'];
                            $categdate = $row1->regdate;
                            if(date("Y-m-d") <= date("Y-m-d",strtotime('+1 years', strtotime($row1->regdate)))){
                                $limit = ($row1->salary * .4);
                            }elseif(date("Y-m-d") > date("Y-m-d",strtotime('+1 years', strtotime($row1->regdate))) && date("Y-m-d") <= date("Y-m-d",strtotime('+2 years', strtotime($row1->regdate)))){
                                $limit = ($row1->salary * .6);
                            }elseif(date("Y-m-d") > date("Y-m-d",strtotime('+2 years', strtotime($row1->regdate))) && date("Y-m-d") <= date("Y-m-d",strtotime('+4 years', strtotime($row1->regdate)))){
                                $limit = ($row1->salary * .7);
                            }elseif(date("Y-m-d") > date("Y-m-d",strtotime('+4 years', strtotime($row1->regdate)))){
                                $limit = ($row1->salary * .9);
                            }            
                        }elseif($row1->empcatergory == 'Contractual'){
                            $categdate = $row1->contractdate;
                        }elseif($row1->empcatergory == 'Probationary'){
                            $categdate = $row1->probidate;
                        }elseif($row1->empcatergory == ""){
                            $categdate = 'No Category';
                            $row1->empcatergory = 'No Category';
                            $row1->payment = 'No Salary Category';
                        }         
                        echo '<tr>';
                        echo    '<td>' . $row1->phoenix_empid . '</td>';
                        echo    '<td>' . $row1->phoenix_chrono . '</td>';
                        echo    '<td>' . $row1->fname . ' ' . $row1->mname . ', ' . $row1->lname . '</td>';
                        echo    '<td>' . $row1->edatehired . '</td>';
                        echo    '<td>' . $row1->empcatergory . '</td>';
                        echo    '<td>' . $categdate . '</td>';
                        echo    '<td>' . number_format($row1->serviceper/365, 2) . '</td>';
                        echo    '<td>' . $row1->position . '</td>';
                        echo    '<td>' . $row1->department . '</td>';
                        echo    '<td>' . $row1->ecstatus . '</td>';
                        echo    '<td>' . strval($row1->econt) . '</td>';
                        echo    '<td>' . $row1->eaddress . '</td>';
                        echo    '<td>' . $row1->eblood . '</td>';
                        echo    '<td>' . $row1->ebday . '</td>';
                        echo    '<td>' . $row1->age . '</td>'; 
                        echo    '<td>' . $row1->payment . '</td>';
                        if($row1->salary > 0){
                            echo    '<td>' . number_format($row1->salary/26, 2) . '</td>';
                            echo    '<td>' . number_format($row1->salary, 2) . '</td>';
                        }else{
                            echo    '<td> No Salary </td>';
                            echo    '<td> No Salary </td>';
                        }
                        echo    '<td>' . number_format($limit) . '</td>';
                        echo    '<td>' . $ca . '</td>';
                        echo    '<td>' . $vacleave . '</td>';
                        echo    '<td>' . $sleave . '</td>';                      
                        echo    '<td>' . $row1->esss . '</td>';
                        echo    '<td>' . $row1->etin . '</td>';
                        echo    '<td>' . $row1->ephilhealth . '</td>';
                        echo    '<td>' . $row1->epagibig . '</td>';
                        echo '</tr>';
                    }
                }
            ?>
        </tbody>
    </table>
<?php  
    }
?>