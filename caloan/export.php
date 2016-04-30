<?php
$date1 = mysql_escape_string($_GET['date1']);
$date2 = mysql_escape_string($_GET['date2']);
/*******EDIT LINES 3-8*******/
if(isset($_GET['exot'])){
    $DB_Server = "localhost"; //MySQL Server    
    $DB_Username = "root"; //MySQL Username     
    $DB_Password = "";             //MySQL Password     
    $DB_DBName = "testnew";         //MySQL Database Name  
    $DB_TBLName = "overtime"; //MySQL Table Name   
    $filename = "Format";         //File Name
    /*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
    //create MySQL connection   
    $sql = "Select account_id,dateofot,IF(approvedothrs is not null, 1, 0) as ot,approvedothrs from $DB_TBLName where datehr BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin')";
    $Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
    //select database   
    $Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
    //execute query 
    $result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
    $file_ending = ".xls";
    //header info for browser
    header("Content-Type: application/xls");    
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
if(isset($_GET['exob'])){
    /*******EDIT LINES 3-8*******/
    $DB_Server = "localhost"; //MySQL Server    
    $DB_Username = "root"; //MySQL Username     
    $DB_Password = "";             //MySQL Password     
    $DB_DBName = "testnew";         //MySQL Database Name  
    $DB_TBLName = "officialbusiness"; //MySQL Table Name   
    $filename = "portalOB-" . date("Y-m-d");         //File Name
    /*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
    //create MySQL connection   
    $sql = "Select account_id,DATE_FORMAT(obdatereq, '%m-%d-%Y'),obtimein,IF(obtimein is not null, 1, 0) as ot from $DB_TBLName where datehr BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin') and obtimein is not null and obtimein != ''";
    $sql2 = "Select account_id,DATE_FORMAT(obdatereq, '%m-%d-%Y'),obtimeout,IF(obtimeout is not null, 2, 0) as ot from $DB_TBLName where datehr BETWEEN '$date1' and '$date2' and (state = 'CheckedHR' or state = 'AAdmin') and obtimeout is not null and obtimeout != ''";
    $Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
    //select database   
    $Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
    //execute query 
    $result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
    $result2 = @mysql_query($sql2,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
    $file_ending = ".txt";
    //header info for browser
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=$filename.txt");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
    /*******Start of Formatting for Excel*******/   
    //define separator (defines columns in excel & tabs in word)
    $sep = "\t"; //tabbed character
    //start of printing column names as names of MySQL fields
    echo 'ChronoNo' . "\t";
    echo 'Date' . "\t";
    echo 'Time' . "\t";
    echo 'InOutType' . "\t";

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