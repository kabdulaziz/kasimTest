<?php
$above = "above";
$below = "below";

session_start();
//if($_SERVER['REQUEST_METHOD'] != "POST") {
require_once('header_maink2.php');
//}
//require_once('../common/header_commonk2bk.php');

require_once('../common/setPageViewCounter.php');



// decimal to fraction
function standardizeRate($n, $tolerance = 1.e-6) {
    $h1=$n*1000;

    return "$h1 in 1000";
}

?>


<script>

			var sessionID = <?php echo "'" . $_SESSION['sessionID'] . "'"; ?>;
			
			// set counter -- element is name of item you want to add the counter as. e.g., bigLifeButton
			function setCounter(sessionID,element)
			{
			
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			/*xmlhttp.onreadystatechange=function()
			
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				document.getElementById("txtTeaser").innerHTML=xmlhttp.responseText;
				}
			  }
			  */
			xmlhttp.open("GET","setCounter.php?sessionID="+sessionID+"&element="+element,true);
			xmlhttp.send();
			
			}
			// end teaser
			
</script>


<?php
/* 
// #### DATA PERSISTENCY #### 
// If post back DO NOT go to database to get the latest data;
// but if first time page loads go to database and grab latest info
*/


/*
session_start();
$sessionID = '';
if(!isset($_SESSION['sessionID'])){
$sessionID = uniqid(mt_rand(),true);
$_SESSION['sessionID'] = $sessionID;}
else {$sessionID = $_SESSION['sessionID'];}
*/

// initialize variables for session
$healthage='';
$leage=$_SESSION["leage"];

$age_s='';
$sex_s='';

$height1 = '';
$height2  = '';
$height3  = '';
$weight1 = '';
$weight2 = '';
$smk1='';
$smk2='';
$alc1 = '';
$alc2 = '';
$alc3 = '';
$alc4 = '';

$diet1 = '';
$diet2 = '';
$diet3 = '';
$diet4 = '';
$diet5 = '';
$diet6 = '';

$pa1 = '';
$pa2 = '';
$pa3 = '';
$str1 = '';
$imm1 = '';
$imm2 = '';

$country1 = '';
$ses1 = '';
$education1 = '';

$diabetes1 = '';
$hDisease1 = '';
$stroke1 = '';
$noDisease1 = '';
$immobile1 = '';
$immobile2 = '';
$pCode1 = '';

$eventtitle='';
$eventdate='';

$calc_le='1';
$calc_beddays='';

$household_income = '';
$home_ownership = '';
$marital_status = '';
$dementia = '';
$cancer = '';

if (isset ( $_SESSION ["le"] ) && isset ( $_SESSION["hosp"] ) ) {
	if ($_SESSION ["le"] == "1") $calc_le='1';
	else $calc_le='';
	
	if ($_SESSION ["hosp"] == "1") $calc_beddays='1';
	else $calc_beddays='';
}

/*
if (isset ( $_GET ["le"] ) && $_GET ["le"] == "1") {
	$calc_le='1';
}
*/

/*
//session_start();
if(isset($_SESSION['sessionID'])){
	$age_s = $_SESSION['age_s'];
	$sex_s = $_SESSION['sex_s'];
}
*/

//$age = $_POST["age"];

// connect to db
include '../common/riskconfig.php';
$connect = @mysql_connect ($host, $user, $pass);
if ($connect) { 
	$db_selected = mysql_select_db($database,$connect);
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$sessionID = $_SESSION['sessionID'];
	$_SESSION['age_s'] = $_POST["age"];
	$_SESSION['sex_s'] = $_POST["sex"];
	$_SESSION['pCode1'] = $_POST["pCode1"];
	
	$age_s = $_POST["age"];
	$sex_s = $_POST["sex"];
	
	$height1 = $_POST["height1"];
	$height2 = $_POST["height2"];
	$height3 = $_POST["height3"];
	$weight1 = $_POST["weight1"];
	$weight2 = $_POST["weight2"];
	$smk1 = $_POST["smk1"];
	$smk2 = $_POST["smk2"];
	$alc1 = $_POST["alc1"];
	$alc2 = $_POST["alc2"];
	$alc3 = $_POST["alc3"];
	$alc4 = $_POST["alc4"];
	/*
	$alc1 = $_POST["alc1"];
	$alc2a = $_POST["alc2a"];
	$alc2b1 = $_POST["alc2b1"];
	$alc2b2 = $_POST["alc2b2"];
	$alc2b3 = $_POST["alc2b3"];
	$alc2b4 = $_POST["alc2b4"];
	$alc2b5 = $_POST["alc2b5"];
	$alc2b6 = $_POST["alc2b6"];
	$alc2b7 = $_POST["alc2b7"];
	$alc3 = $_POST["alc3"];
	*/
	$diet1 = $_POST["diet1"];
	$diet2 = $_POST["diet2"];
	$diet3 = $_POST["diet3"];
	$diet4 = $_POST["diet4"];
	$diet5 = $_POST["diet5"];
	$diet6 = $_POST["diet6"];
	$pa1 = $_POST["pa1"];
	$pa2 = $_POST["pa2"];
	$pa3 = $_POST["pa3"];
	$str1 = $_POST["str1"];
	$imm1 = $_POST["imm1"];
	$imm2 = $_POST["imm2"];
	
	$country1 = $_POST["country1"];
	$ses1 = $_POST["ses1"];
	$education1 = $_POST["education1"];
	
	$diabetes1 = $_POST["diabetes1"];
	$hDisease1 = $_POST["hDisease1"];
	$stroke1 = $_POST["stroke1"];
	$noDisease1 = $_POST["noDisease1"];
	$immobile1 = $_POST["immobile1"];
    $immobile2 = $_POST["immobile2"];
	$pCode1 = $_POST["pCode1"];

	$eventtitle = $_POST["eventtitle"];
	$eventdate = $_POST["eventdate"];
	
	$calc_le = $_POST["calc_le"];
	$calc_beddays = $_POST["calc_beddays"];

	$household_income = $_POST['household-income'];
	$home_ownership = $_POST['home-ownership'];
	$marital_status = $_POST['marital-status'];
	$dementia = $_POST['dementia1'];
	$cancer = $_POST['cancer'];
    $htension1 = $_POST['htension1'];
}
else { // not a postback
	if(!isset($_SESSION['sessionID'])){
		$sessionID = uniqid(mt_rand(),true);
		$_SESSION['sessionID'] = $sessionID;
		
	}
	else {
		$sessionID = $_SESSION['sessionID'];
		$age_s = $_SESSION['age_s'];
		$sex_s = $_SESSION['sex_s'];
		$pCode1 = $_SESSION['pCode1'];
		
		// connect to database and grab latest data 
		//include 'riskconfig.php';
		
		//$connect = @mysql_connect ($host, $user, $pass);
	
		if (!$connect) { 
		} 
		
		else {
	
			// select db
			//$db_selected = mysql_select($database,$connect);
			if (!$db_selected) {
			}
	
			else {
				$query = "SELECT * FROM `helica_h2` WHERE `sessionID` = '" . mysql_real_escape_string ( $sessionID ) . "' ORDER BY dtime DESC LIMIT 0,1";
				 
				$result = mysql_query($query) or die("SOMETHING WENT WRONG " . mysql_error()); 
				$row = mysql_fetch_object($result);
				
				if (!mysql_num_rows($result)==0){ 
					$height1 = $row->height1;
					$height2 = $row->height2;
					$height3 = $row->height3;
					$weight1 = $row->weight1;
					$weight2 = $row->weight2;
					$smk1 = $row->smk1;
					$smk2 = $row->smk2;
					$alc1 = $row->alc1;
					$alc2 = $row->alc2;
					$alc3 = $row->alc3;
					$alc4 = $row->alc4;
					/*
					$alc1 = $row->alc1;
					$alc2a = $row->alc2a;
					$alc2b1 = $row->alc2b1;
					$alc2b2 = $row->alc2b2;
					$alc2b3 = $row->alc2b3;
					$alc2b4 = $row->alc2b4;
					$alc2b5 = $row->alc2b5;
					$alc2b6 = $row->alc2b6;
					$alc2b7 = $row->alc2b7;
					$alc3 = $row->alc3;
					*/
					$diet1 = $row->diet1;
					$diet2 = $row->diet2;
					$diet3 = $row->diet3;
					$diet4 = $row->diet4;
					$diet5 = $row->diet5;
					$diet6 = $row->diet6;
					$pa1 = $row->pa1;
					$pa2 = $row->pa2;
					$pa3 = $row->pa3;
					$str1 = $row->str1;
					$imm1 = $row->imm1;
					$imm2 = $row->imm2;
					
					$country1 = $row->country1;
					$ses1 = $row->ses1;
					$education1 = $row->education1;
					$diabetes1 = $row->diabetes1;
					$hDisease1 = $row->hDisease1;
					$stroke1 = $row->stroke1;
					$noDisease1 = $row->noDisease1;
					$immobile1 = $row->immobile1;
                    $immobile2 = $row->immobile2;
					//$pCode1 = $row->pCode1;
					
					$eventtitle = $row->eventtitle;
					$eventdate = $row->eventdate;
					
					$calc_le = $row->calc_le;
					$calc_beddays = $row->calc_beddays;
					$calc_fhc = $row->calc_fhc;
					$household_income = $row->household_income;
					$home_ownership = $row->home_ownership;
					$marital_status = $row->marital_status;
					$dementia = $row->dementia;
					$cancer = $row->cancer;
                    $htension1 = $row->htension1;	
				}
			
			//Get healthage	
			/*
			$queryhealthage = "SELECT MAX(`age`) as age FROM `healthagev2_lkup` WHERE `deathRate` <  '". mysql_real_escape_string($_SESSION['probdeath']) ."' AND `sex` = '". mysql_real_escape_string($_SESSION['sex_s']) . "'; ";
			
			$healthage = 0;
			$resulthealthage = mysql_query($queryhealthage) or die("SOMETHING WENT WRONG"); 
			$rowhealthage = mysql_fetch_object($resulthealthage);
				
				if (!mysql_num_rows($resulthealthage)==0){ 
					$healthage = $rowhealthage->age;
					if (!($healthage>19 &&  $healthage<101)) $healthage=20;
				}
			*/
			//Start of opening lookup table for healthage
			$queryhealthage = "SELECT MAX(`age`) as age FROM `healthagev2_lkup` WHERE `deathRate` <  '". mysql_real_escape_string($_SESSION['probdeath']) ."' AND `sex` = '". mysql_real_escape_string($_SESSION['sex_s']) . "'; ";
			
			$healthage = 0;
			$resulthealthage = mysql_query($queryhealthage) or die("SOMETHING WENT WRONG " . mysql_error()); 
			$rowhealthage = mysql_fetch_object($resulthealthage);	
				if (!mysql_num_rows($resulthealthage)==0){ 
					$healthage = $rowhealthage->age;
					if (!($healthage>19 &&  $healthage<101)) $healthage=20;
				}
			//End of opening lookup table for healthage
			
			//start -- calculate vascular age -- sport algorithm
			$queryVascularAge = "SELECT MAX(`age`) as age FROM `sport_lkup` WHERE `fiveYearAvg` <  '". mysql_real_escape_string($_SESSION['stroke_risk']) ."' AND `sex` = '". mysql_real_escape_string($_SESSION['sex_s']) . "'; ";
			

			//$queryVascularAge = "SELECT max(age) as age FROM sport_lkup;";
			//$queryVascularAge = "SELECT * FROM `sport_lkup` WHERE `fiveYearAvg` <  '". mysql_real_escape_string($_SESSION['stroke_risk']) ."' AND `sex` = '". mysql_real_escape_string($_SESSION['sex_s']) . "' ORDER BY age DESC LIMIT 0,1; ";
			
			$vascularAge = 0;
			$resultvascularAge = mysql_query($queryVascularAge) or die("SOMETHING WENT WRONG queryVascularAge" . mysql_error()); 

			$rowVascularAge = mysql_fetch_object($resultvascularAge);	
				if (!mysql_num_rows($resultvascularAge)==0){ ;
					$vascularAge = $rowVascularAge->age;
					if (!($vascularAge>19 &&  $vascularAge<101)) $vascularAge=20;
				}
				//echo "vascular age: $vascularAge -- " . $rowvascularAge->age; 
				//echo "risk:" . $_SESSION['stroke_risk'];
			//End of opening lookup table for vascularAge
			
			
			
			//Start of opening lookup table for average values of detailed results
			$queryDetailed = "SELECT * FROM `detailedResult_lkup` WHERE `age` ='" . mysql_real_escape_string ( $age_s ) . "' AND `sex` = '". mysql_real_escape_string($sex_s) . "'; ";
				$resultDetailed = mysql_query($queryDetailed) or die("SOMETHING WENT WRONG " . mysql_error()); 
				$row = mysql_fetch_object($resultDetailed);
				
				
				if (!mysql_num_rows($resultDetailed)==0){ 
					$metWk = $row->metWk;
					$minWk = $row->minWk;
					$dietScore = $row->dietScore;
					$fvWk = $row->fvWk;
					$alcWk = $row->alcWk;
					$alcMax = $row->alcMax;
					$bmiWk = $row->bmiWk;
					$leAvg = $row->leAvg;
					$haAvg = $row->haAvg;
					$smokAvg = $row->smokAvg;
					$alcAvg = $row->alcAvg;
					$dietAvg = $row->dietAvg;
					$paAvg = $row->paAvg;
					$death5Avg = $row->death5Avg;
					$prob75Avg = $row->prob75Avg;
					$hospAvg = $row->hospAvg;
					$hosp5Avg = $row->hosp5Avg;
					$sepAvg = $row->sepAvg;
					$bmiAvg = $row->bmiAvg;
					$lifeExpAvg_canada = $row->lifeExpAvg_canada;
				}
			//End of opening lookup table for average values of detailed results
			
			/* If the postal code is a valid Canadian one, set this to 1 so that the averages are shown */
			$pc= 0;
			//$pc = 1 if (pCode1==valid)
			
			//Start of calculations for the value of the 'You' column, and the 'Score' column
			$paYou=round(($pa1+$pa2)*60);
			
			$paScore='';
			if ($paYou<120) $paScore="Low";
			else if ($paYou<240) $paScore="Moderate";
			else $paScore="High";
			
			$fvYou=round(($diet1+$diet2+$diet3+$diet4+$diet5+$diet6)/7);
			
			$fvScore='';
			
			if ($fvYou<5) $fvScore="Low";
			else if ($fvYou<8) $fvScore="Moderate";
			else $fvScore="High";
			
			$smokeYou="Current";
			switch ($smk1)	{
				case 1: case 2: $smokeYou= "Current";
					break;
				case 3: case 4: $smokeYou="Former";
					break;
				case 5: $smokeYou= "Non- <br /> Smoker";
					break;
			}
			
			$alcRecWeek='';
			$alcRecDay='';
			if ($sex_s==1)	{
				if ($alc2<=15) $alcRecWeek="Within Recommendation";
				else $alcRecWeek="Excess";
				//if ($alc3<=3) $alcRecDay="Within Recommendation";
				//else $alcRecDay="Excess";
			} else {
				if ($alc2<=10) $alcRecWeek="Within Recommendation";
				else $alcRecWeek="Excess";
				//if ($alc3<=2) $alcRecDay="Within Recommendation";
				//else $alcRecDay="Excess";
			}
			
			if ($diet1 <= 7) $diet1juiceOver=0;
			else $diet1juiceOver=$diet1-7;
		
			if ($sex_s==1) { // male
				//if ($diet4 >= (7/7) ) $diet4potHigh=1;
				if ($diet4 >= 7 ) $diet4potHigh=1;
				else $diet4potHigh=0;
			}
			else if ($sex_s==2) { // female
				//if ($diet4 >= (5/7) ) $diet4potHigh=1;
				if ($diet4 >= 5 ) $diet4potHigh=1;
				else $diet4potHigh=0;
			}
		
			if ($diet5 == 0) $diet5noCarrot=1;
			else $diet5noCarrot=0;
			
			if ($diet1 == '') {
			$diet1juiceOver=1;
		}
		
		if ($diet2 == '') {
			$diet2=0;
		}	
		
		if ($diet3 == '') {
			$diet3=0;
		}	
		
		if ($diet4 == '') {
			$diet4potHigh=1;
			$diet4=0;
		}
		
		if ($diet5 == '') {
			$diet5noCarrot=1;
			$diet5=0; 
		}
		
		if ($diet6 == '') {
			$diet6=0;
		}
			
		$fvtotal=$diet2 + $diet3 + $diet4 + $diet5 + $diet6;
		$fvtotal = $fvtotal/7; //new code for conversion to daily
		if ($fvtotal > 8) $fvtotal=8;
		
		// deduct 2 points each for high potato consumption, no carrot and high juice		
		$crudeDietScore = $fvtotal + (-2*$diet1juiceOver) + (-2*$diet4potHigh) + (-2*$diet5noCarrot) + 2;
		
		// final diet score
		if ($crudeDietScore <= 0) $diet=0; 
		else $diet=round($crudeDietScore);
		
		
		$bmi=round(($weight1/(($height1/100)*($height1/100))),1);
		
			$bmiScore='';
			if ($bmi<18.5) $bmiScore="Underweight";
			else if ($bmi<=24.9) $bmiScore="Normal";
			else if ($bmi<=30) $bmiScore="Overweight";
			else if ($bmi<= 35) $bmiScore="Obese Class I";
			else if ($bmi<=40) $bmiScore="Obese Class II";
			else $bmiScore="Obese Class III";

			$metWeek=round(($_SESSION['pa_METS']*7), 1);
			
			$metScore='';
			if ($_SESSION['pa_METS']<1.5) $metScore="Low";
			else if ($_SESSION['pa_METS']<3) $metScore="Moderate";
			else $metScore="High";
		//End of calculations for the value of the 'You' column, and the 'Score' col

			

			
				
			} //end else for  if (!$db_selected) {
	
		} //end else for  if (!$connect) { 
		
	} // end else of if(!isset($_SESSION['sessionID'])){
} // end else (not postback)

?>

<?php
	require_once('calculator/hospital_costs.php');
    $hcMets = ($pa1*9.5 + $pa2*5.5 + $pa3*3.5) / 7;
    if ($hcMets > 10) $hcMets = 10;
    
    $fragility = NULL;
    
    if($immobile1 == NULL)
        $fragility = NULL;
    elseif ($immobile1 == '1') {
        if($immobile2 == '1') {
            $fragility = '1';
        }
        else {
            $fragility = '2';
        }
    }
    elseif ($immobile1 == '2'){
        $fragility = '3';
    }

	$calculatedCosts = calculateHospitalCost($sex_s, intval($age_s), $smk1, $alc1, $alc2, $alc4, $crudeDietScore, $hcMets, $imm1, $education1, $str1, $household_income, $home_ownership, $marital_status, $bmi, $noDisease1, $htension1, $diabetes1, $hDisease1, $cancer, $stroke1, $dementia, $fragility, $healthCostMaleBetas, $healthCostFemaleBetas, $country1);
?>

<img id="titlelogo" src="/common/images/my-report.png"/>
<div id="space"></div>
<div id="sectionResults" class="sectionContainer">	
	<div id="about">
	<span>
		<br>
		<!-- Life Expectancy section -->
		
		<?php echo '<html><div id="ResultHeader">My Results</div></html>' ?>
		<div id="section1" class="sectionContainer" style="border: solid 1px #c0c0c0; margin-top: 0;">
		<h1><?php if (($_SESSION['le'])=='1') echo 'Life Expectancy '?><font class="emphase" size='6em'> <?php if(($_SESSION['le'])=='1') echo $_SESSION['leage']?> </font> <?php if (($_SESSION['le'])=='1') echo 'years' ?>
		<span class="no-print"><?php if (($_SESSION['le'])=='1') echo '<img 
        type="image" class= "one" src="/common/images/info.png" 
        data-toggle="tooltip" data-placement="right" title="Estimated from your completed responses and population averages for missing data" />' ?>
		</span>

		<?php 
			if($calc_fhc == "1")
				require_once('hospital_costs_results.php');
		?>
		
		<?php if (($_SESSION['le'])=='1') echo '<h1>Largest risk is ' ?> <font class="emphase" size='6em'> <?php if (($_SESSION['le'])=='1') switch($_SESSION['largestrisk'])	{
			case 1:	echo "Smoking";
				break;
			case 2: echo "Alcohol";
				break;
			case 3: echo "Diet";
				break;
			case 4: echo "Physical Inactivity";
				break;
			case 5: echo "Air Pollution";
				break;
			case 6: echo "Neighbourhood Deprivation";
				break;
			case 0:
				echo "None";
				break;
		}?> </font>
		<span class="no-print"><?php if ($_SESSION['le']=='1') echo '<img 
        type="image" class= "one" src="/common/images/info.png" 
        data-toggle="tooltip" data-placement="right" title="Based on your modifiable risk factors: smoking status, alcohol consumption, diet, and physical activity as well as the area you live in" />' ?></h1>
		
		
			<div id="lifeDetails" style="margin:0; margin-left:20px;">
				<?php if (($_SESSION['le'])=='1' && $_SESSION['largestrisk']!='0') echo '
			
					<h2><a id="displayText" href="javascript:toggle();" style="text-decoration:none; color:#DB5806; font-size:1.0em;" onclick="setCounter(sessionID,\'seeDetails1\');">Other health behaviour risks &#9654</a></h2>
					<div id="toggleText" style="display: none; margin:0;">
						<h1> 
						Loss of: <br />
						<font size=\'5em\'>'.$_SESSION['yldtsmoke'].'</font> years due to <font size=\'5em\'> Smoking </font><br>
						<font size=\'5em\'>'. $_SESSION['yldtalc'].'</font> years due to <font size=\'5em\'> Alcohol </font><br>
						<font size=\'5em\'>'. $_SESSION['yldtdiet'].'</font> years due to <font size=\'5em\'> Diet </font><br> 
						<font size=\'5em\'>'.$_SESSION['yldtpa'] .'</font> years due to <font size=\'5em\'> Physical Inactivity </font><br>						
						</h1>
					</div>';
				?>

					<h2><a id="displayTextAirPollution" href="javascript:toggleAirPollution();" style="text-decoration:none; color:#DB5806; font-size:1.0em;" onclick="setCounter(sessionID,\'seeDetailsAirPol\');">Neighbourhood risk factors &#9654</a></h2>
					<div id="toggleTextAirPollution" style="display: none; margin:0;">
					
						<?php if ($_SESSION['pCodeProvided']=='1') echo '
								<h1> 
								Loss of: <br />
								<font size=\'5em\'> '. $_SESSION['yldtpollution'] . ' </font> years due to <font size=\'5em\'> Air Pollution </font> 
									<span class="no-print"> <html><input type="image" class= "one" src="/common/images/info.png" onclick=window.open("http://www.ec.gc.ca/cas-aqhi/default.asp?lang=En&n=3E3FDF68-1") ></html></span>
									<img width="48" height="36" src="/common/images/pollution.png"/>
									<br>
								</h1>

								<div id="airPollutionNumbers" style="margin-left: 10px; font-size: 1.5em;">
									<table cellspacing=5 cellpadding=5>
									<tr>
										<td><b>Based on your postal code</b></td>
										<td><b>Compared to the Canadian average</b></td>
									</tr>
									<tr>
										<td> <b>' . round($_SESSION['NO2']) . '</b> ppb  Nitrogen dioxide (NO<sup>2</sup>)</td>
										<td>' . ( round($_SESSION['NO2'],1) > 11.6 ? '<font color=red>above</font>': '<font color=green>below</font>') . '</td>
									</tr>
									<tr>
										<td> <b>' . round($_SESSION['PM25']) . '</b> &#181;g/m<sup>3</sup> Particulate matter (PM2.5)</td>
										<td>' . ( round($_SESSION['PM25'],1) > 8.9 ? '<font color=red>above</font>': '<font color=green>below</font>') . '</td>
										
									</tr>
									<tr>
										<td> <b>' . round($_SESSION['O3']) . '</b> ppb  Ozone (O<sup>3</sup>) </td>
										<td>' . ( round($_SESSION['O3'],1) > 39.6 ? '<font color=red>above</font>': '<font color=green>below</font>') . '</td>
									</tr>
									
									</table>
								</div>
							
								<h1>
									<font size=\'5em\'> ' . $_SESSION['yldtdeprivation'] . ' </font> years due to <font size=\'5em\'> Neighbourhood Deprivation </font><br>
								</h1>
								
														
						
							</div>';

							else if ($_SESSION['pCodeProvided']=='2') echo '<h1>Sorry we do not have your postal code on file, please try a different postal code</h1>';
							else if ($_SESSION['pCodeProvided']=='3') echo '<h1>Sorry we do not have pollution data for your postal code</h1>';
							
							else echo '<h1>Please provide a valid Canadian postal code to obtain neighbourhood/air pollution information</h1>';
						
						?>
			</div>
		
		<?php
		// calculate health age 2nd method using lookup
		// healthageV2=age +/- (LE-averageLE) for age and sex
		$healthageV2 = $age_s - ($_SESSION['leage'] - $lifeExpAvg_canada);
		?>
		
		
		<h1><?php if (($_SESSION["le"])=='1') echo "Health age "?><font size= '6em' class="emphase"> <?php if (($_SESSION["le"])=='1') echo $healthageV2?> </font> <?php if (($_SESSION["le"])=='1') echo " years"?>
		<span class="no-print"><?php if (($_SESSION["le"])=='1') echo '<img 
        type="image" class= "one" src="/common/images/info.png" 
        data-toggle="tooltip" data-placement="right" title="Health age compares your life expectancy to the life expectancy of the average Canadian of your age and sex. The difference is added or subtracted from your current age" />' ?>
		</span><?php if (($_SESSION["le"])=='1') echo "\n<br />"?>	</h1>
		
		<!--
		<font size=2em color=red>
		<?php echo  "<br />For Testing<br />";?>
		<?php echo  "age: " . $age_s . "<br />";?>
		<?php echo  "LE: " . $_SESSION['leage'] . "<br />";?>
		<?php echo  "average LE: " . $lifeExpAvg_canada . "<br />";?>
		<?php echo  "old method healthAge:" . $healthage . "<br />";?>
		</font>
		-->
		
		<!-- Hospital Use Section -->
		<?php
		/*
		<h1><font size= '6em' class="emphase"> <?php if (($_SESSION["hosp"])=='1') echo $_SESSION["beddays"]?> </font> <?php if (($_SESSION["hosp"])=='1') echo "Days spent in the hospital"?>
		<span class="no-print"><?php if (($_SESSION["hosp"])=='1') echo '<html><input type="image" class= "one" src="/common/images/info.png" onclick="alert_3()"></html>' ?>
		</span><?php if (($_SESSION["hosp"])=='1') echo "\n<br />"?>					
		</h1>
		*/
		?>


		<!-- Optional 'will you live to see it?' Section-->
		<h1><?php if(($_SESSION['le'])=='1' && ($_SESSION["lts"])=='1') 	{
		echo "Your chances of seeing your event "; 
		echo '<html><font class="emphase" size="6em">'.$_SESSION['eventtitle'].'</font></html>'; 
		echo " in "; 
		echo '<html><font class="emphase" size="6em">'.$_SESSION['eventyear'].'</font></html>';
		echo " are ";
		echo '<html><font class="emphase" size="6em">'.$_SESSION["eventprob"];
		echo "%".'</font></html>';
	}?></h1>
	
	</div> <!-- end sectionContainer -->
	
	
	<!-- Beginning of Form to show a detailed report -->
	<?php if(  $_SESSION['str']=='1' ) 	{ //start if stroke selected to show results for stroke / SPoRT ?>
	
		<?php echo '<html><div id="ResultHeader"> Disease Risks </div></html>' ?>
		<div id="section1" class="sectionContainer" style="border: solid 1px #c0c0c0; margin-top: 0;">
		
		<?php
		// prepare sport (stroke risk) for display -- convert to percentage and if < 0.5% just say <0.5%
			$stroke_risk_pct = round($_SESSION['stroke_risk'] * 100,2);
			if ($stroke_risk_pct < 0.5) $stroke_risk_pct2 = '< 0.5';
			else $stroke_risk_pct2 = $stroke_risk_pct;
			
		?>
		
		<h1>5-year risk of stroke is <font class="emphase" size='6em'> <?php echo $stroke_risk_pct2 . "%"; ?> </font> </h1>
		<h1>Vascular age is <font class="emphase" size='6em'> <?php echo $vascularAge; ?> </font> years </h1>
		<!--
		<h1>FOR TESTING: 5-year risk of stroke: <?php echo $_SESSION['stroke_risk']; ?></h1>
		<h1>FOR TESTING: SPoRT scale: <?php echo $_SESSION['stroke_score']; ?></h1>
		-->
	
	</div> <!-- end sectionContainer -->
	<?php } //end if stroke selected to show results for stroke ?>
	
	
	<!-- Beginning of Form to show a detailed report -->
	<?php echo '<html><div id="ResultHeader"> See how big your life really is </div></html>' ?>
	<div id="section1" class="sectionContainer" style="border: solid 1px #c0c0c0; margin-top: 0; text-align:center;">
	<div id="inner"><?php include_once("BigLifeTest.php")?></div>
	</div> <!-- end sectionContainer -->
	
	<?php echo '<html><div id="ResultHeader">Details</div></html>' ?>
	<div id="section1" class="sectionContainer" style="border: solid 1px #c0c0c0; margin-top: 0;">
	
	<h2><a id="displayCompareToOthers" href="javascript:toggle2a();" style="text-decoration:none; color:#DB5806; font-size:1.3em;" onclick="setCounter(sessionID,'seeDetails2a');">Compare me to others &#9654</a></h2>
		<div id="toggleCompareToOthers" style="display: none">
			
		
	<?php	
	
	//if ($_SESSION['country'] == 1) { // if Canadian display otherwise don't display
	echo '<br><br>
	<div class="page-break"></div>
	<table cellpadding=5 cellspacing=0 style="text-decoration:none; font-size:1.3em;">';
	echo '<tr>
		<th style="border-top: 1px solid #000; border-bottom: 1px solid #000;"> Risk Factors </th>
		<th style="border-top: 1px solid #000; border-bottom: 1px solid #000;"> You </th>
		<th style="border-top: 1px solid #000; border-bottom: 1px solid #000;"> Average<sup>*</sup></th>
		<th style="border-top: 1px solid #000; border-bottom: 1px solid #000;"> Target </th>
	</tr>';
	echo '<tr>
			<th id="title">Physical Activity</th>
			<th id="blank"></th>
			<th id="blank"></th>';
		echo	'<th id="titleEnd"/>
		</tr>';
	 echo '<tr>
		<td> Total physical <br/>activity <br /> (METS/week)</td>
		<td> '.$metWeek.' </td>
		<td>'.$metWk.' </td>
		<td> --- </td>
	</tr>';
	 echo '<tr>
		<td> Moderate to <br /> vigorous activity <br /> (Minutes/week)</td>
		<td>'; echo $paYou.' </td>
		<td> '.$minWk.'  </td>
		<td> At least 150 </td>
	</tr>';
	 echo '<tr>
			<th id="title">Fruits and <br /> vegetables</th>
			<th id="blank"></th>
			<th id="blank"></th>';
		 echo	'<th id="titleEnd"/>
		</tr>';
	 echo '<tr>
		<td> BigLife diet score</td>
		<td>'.$diet.'</td>
		<td> '.$dietScore.' </td>
		<td> --- </td>
	</tr>';
	 echo '<tr>
		<td> Fruits and <br /> vegetables <br /> (servings/day) </td>
		<td>';  echo $fvYou.' </td>
		<td> '.$fvWk.' </td>
		<td>'; if ($age_s>50) { if ($sex_s==2) echo '7 </td>'; else echo '7 </td>';} else { if ($sex_s==2) echo '7-8 </td>'; else echo '8-10 </td>';}
	 echo '</tr>';
	 echo '<tr>
		<th id="title">Smoking</th>
		<td>'.$smokeYou.'</td>
		<td>'; if ($sex_s==1 && $age_s>59) echo 'Former'; else echo 'Non- <br /> Smoker'; echo '</td>
		<td> Quit or don\'t start </td>
	</tr>';
	
	 echo '<tr>
		<th id="title">Alcohol (weekly)</th>
		<td>'.$alc2.'</td>
		<td>'.$alcWk.' </td>
		<td>'; if ($sex_s==2) echo 'Max 10/week </td>'; else echo 'Max 15/week </td>';
	 echo '</tr>';
	/*
	if (isset($_GET["choicealc"])) echo '<tr>
		<td> Most per day</td>
		<td>'.$alc3.'</td>
		<td>'.$alcRecDay.'</td>
		<td> '.$alcMax.' </td>
		<td>'; if ($sex_s==2 && (isset($_GET["choicealc"]))) echo 'No more than 2 drinks per day </td>'; else if (isset($_GET["choicealc"])) echo 'No more than 3 drinks per day </td>';
	*/
	 //echo '<tr>';
	
	 echo '<tr>
		<th id="title" style="border-bottom: 1px solid #000;"> Body Mass Index</td>
		<td style="border-bottom: 1px solid #000;">'.$bmi.'</td>
		<td style="border-bottom: 1px solid #000;"> '.$bmiWk.' </td>
		<td style="border-bottom: 1px solid #000;"> 18.5 to 24.9 </td>
	</tr>';
	echo '</table>';
	echo '<br><sup>*</sup> The average Canadian for your age and sex. See <a href=/common/faq.php>FAQ</a> on <b>projectbiglife.ca</b> for target recommendations.';
	//} // end if ($_SESSION['country'] == 1) {
	?>
	</div>
	
	
	<h2><a id="displayForHCprovider" href="javascript:toggle2b();" style="text-decoration:none; color:#DB5806; font-size:1.3em;" onclick="setCounter(sessionID,'seeDetails2b');">For my healthcare provider &#9654</a></h2>
		<div id="toggleForHCprovider" style="display: none">
			<?php
			echo '<table cellpadding=5 style="text-decoration:none; font-size:1.3em;">';
			echo '<tr>';
			echo '<td>10-year probability of death:</td>';
			echo '<td>' . $_SESSION['prob_death10year'] . "% or " . standardizeRate($_SESSION['prob_death10year']/100) . '</td></tr>';
			
			echo '<tr>';
			echo '<td>Probability of living until 75 years:</td>';
			echo '<td>' . $_SESSION['prob_liveto75'] . '% </td></tr>';
			
			//yulric removed this
			//echo '<tr>';
			//echo '<td>Hospital bed-days in the next 10 years:</td>';
			//echo '<td>' . $_SESSION['bedDays10year'] . '</td></tr>';
			
			echo '<tr>';
			echo '<td>Neighbourhood support:</td>';
			echo '<td>';
				
				if ($_SESSION["depindex"]==1) echo "High";
					else if ($_SESSION["depindex"]==2) echo "Mid";
					else echo "Low";
			echo '</td></tr>';
			
			echo '</table>'; 
			
			?>
		</div>
		
		
	<h2><a id="displayText2" href="javascript:toggle2();" style="text-decoration:none; color:#DB5806; font-size:1.3em;" onclick="setCounter(sessionID,'seeDetails2c');">Create/print custom report &#9654</a></h2>
	<div id="toggleText2" style="display: none">
	
	<form action="detailedReport.php" method="GET" target="_blank">
		<legend>Select the values you wish to display on your detailed report:</legend>
		<big>Name:</big> <input type="text" id="name" name="name"><label for="name">(Optional)</label>
		<ul class="customReportList">
		<li><input type="checkbox" id="choicele" name="choicele" value="1" checked><label for="choicele">Life Expectancy</label></li>
		<li><input type="checkbox" id="choiceha" name="choiceha" value="1" checked><label for="choiceha">Health Age</label></li>
		<li><input type="checkbox" id="choicesmok" name="choicesmok" value="1" checked><label for="choicesmok">Years lost due to Smoking</label></li>
		<li><input type="checkbox" id="choicealc" name="choicealc" value="1" checked><label for="choicealc">Years lost due to Alcohol</label></li>
		<li><input type="checkbox" id="choicediet" name="choicediet" value="1" checked><label for="choicediet">Years lost due to Diet</label></li>
		<li><input type="checkbox" id="choicepa" name="choicepa" value="1" checked><label for="choicepa">Years lost due to Physical Inactivity</label></li>
		<li><input type="checkbox" id="choice5d" name="choice5d" value="1" checked><label for="choice5d">10-year probability of death</label></li>
		<li><input type="checkbox" id="choice75" name="choice75" value="1" checked><label for="choice75">Probability of living until 75 years</label></li>
		<li><input type="checkbox" id="choicehosp" name="choicehosp" value="1" checked><label for="choicehosp">Lifetime days spent in Hospital</label></li>
		<li><input type="checkbox" id="choice5h" name="choice5h" value="1" checked><label for="choice5h">10-year probability of being hospitalized</label></li>
		<li><input type="checkbox" id="choicesd" name="choicesd" value="1" checked><label for="choicesd">Neighbourhood Support</label></li>
		<li><input type="checkbox" id="choicebmi" name="choicebmi" value="1" checked><label for="choicebmi">Body Mass Index</label></li>
		</ul>
		<br>
		<div id=clear style="clear: both;"></div>
		<input type="submit" value="DETAILED REPORT" class="button orange">
	</form>
	</div>
	
	</div> <!-- end sectionContainer -->
	
	<!--
	<?php //echo '<html><div id="ResultHeader">Help improve Project Big Life</div></html>' ?>
	<div id="section1" class="sectionContainer" style="border: solid 1px #c0c0c0; margin-top:0;">
		<table border=0 width=100%>
		<tr>
		<td width=100%>
		<h1>We’ve calculated how you can live longer, now take one of those extra minutes to provide us feedback by answering a few questions.</h1>
		<div id="inner">
		
		
		<center>
		<a href="http://www.surveygizmo.com/s3/1998393/projectbiglife?sguid=<?php //echo preg_replace("/[^A-Za-z0-9 ]/", "", $_SESSION['sessionID']) . "&lts=" . $_SESSION['lts'] . "&ageHealthAgeDiff=" . ($healthageV2 - $age_s) ; ?>" target="_blank" onclick="setCounter(sessionID,'survey');" class="button orange"><b>I’LL PROVIDE FEEDBACK		
		</b></a>
		</center>
		<br />
		Project Big Life is created by researchers and is free of ads and free for you to use.<br />
		</div>
		
		
		</td>
		<td width=75 align="right" valign="top">
			<img src="../common/images/Report150.png" border=0 width=90 height=90>
		</td>
		</tr>
		</table>
	</div> 
	-->
	<!-- end sectionContainer -->
	
	
	<!-- Recalculate and Privacy Button -->
	<div class="no-print">
		<br><br><font size='3em'><dfn>See how changes to your answers affect your results:</dfn></font><br><br>
		<a href="/life" onclick="setCounter(sessionID,'recalculate');" class="button orange"><b>RECALCULATE</b></a> 
		<!-- <a href="#" onclick="setCounter(sessionID,'print1');window.print();return false;" class="no-print button orange"><b>PRINT</b></a> -->
		<br><br><br>
		<center><h2><a href="/common/privacy.php"><b>PRIVACY</b></a> </h2></center>
	<br></div>
	
</div>
<p /> <br />

<?php include_once("../common/socialmedia2.php") ?>

<br />
<br />
</div>
<script>
	<!-- Function to have a section of text hide or show (detailed risks part) -->
	function toggle() {
		var ele = document.getElementById("toggleText");
		var text = document.getElementById("displayText");
		if(ele.style.display == "block") {
    		ele.style.display = "none";
			text.innerHTML = "Other health behaviour risks &#9654";
  		}
		else {
			ele.style.display = "block";
			text.innerHTML = "Other health behaviour risks &#9660";
		}
	}
	
	function toggleAirPollution() {
		var ele = document.getElementById("toggleTextAirPollution");
		var text = document.getElementById("displayTextAirPollution");
		if(ele.style.display == "block") {
    		ele.style.display = "none";
			text.innerHTML = "Neighbourhood risk factors &#9654";
  		}
		else {
			ele.style.display = "block";
			text.innerHTML = "Neighbourhood risk factors &#9660";
		}
	}
	
	
	<!-- Function to have a section of text hide or show (detailed risks part) -->
	function toggle2a() {
		var ele = document.getElementById("toggleCompareToOthers");
		var text = document.getElementById("displayCompareToOthers");
		if(ele.style.display == "block") {
    		ele.style.display = "none";
			text.innerHTML = "Compare me to others &#9654";
  		}
		else {
			ele.style.display = "block";
			text.innerHTML = "Compare me to others &#9660";
		}
	}
	<!-- Function to have a section of text hide or show (detailed risks part) -->
	function toggle2b() {
		var ele = document.getElementById("toggleForHCprovider");
		var text = document.getElementById("displayForHCprovider");
		if(ele.style.display == "block") {
    		ele.style.display = "none";
			text.innerHTML = "For my healthcare provider &#9654";
  		}
		else {
			ele.style.display = "block";
			text.innerHTML = "For my healthcare provider &#9660";
		}
	}
	
	<!-- Function to have a section of text hide or show (detailed report page part) -->
	function toggle2() {
		var ele = document.getElementById("toggleText2");
		var text = document.getElementById("displayText2");
		if(ele.style.display == "block") {
    		ele.style.display = "none";
			text.innerHTML = "Create/print custom report &#9654";
  		}
		else {
			ele.style.display = "block";
			text.innerHTML = "Create/print custom report &#9660";
		}
		}
	function toggle2() {
		var ele = document.getElementById("toggleText2");
		var text = document.getElementById("displayText2");
		if(ele.style.display == "block") {
    		ele.style.display = "none";
			text.innerHTML = "Create/print custom report &#9654";
  		}
		else {
			ele.style.display = "block";
			text.innerHTML = "Create/print custom report &#9660";
		}
		}
		
	<!-- Life Expectancy info button -->
	function alert_1()	{
		alert("Estimated from your completed responses and population averages for missing data.");
	}
	<!-- Largest Risk info button -->
	function alert_2()	{
		alert("Based on your modifiable risk factors: smoking status, alcohol consumption, diet, and physical activity as well as the area you live in.");
	}
	<!-- Hospital Bed Days info button -->
	function alert_3()	{
		alert("Does not include hospitalizations in the last year of life or pregnancy related hospitalizations.");
	}
	<!-- Health Age info button -->
	function alert_4()	{
		alert("Health age compares your life expectancy to the life expectancy of the average Canadian of your age and sex. The difference is added to or subtracted from your current age.");
	}
	<!-- Pollution info button -->
	function alert_5()	{
		alert("Visit the Environment Canada <a href=http://www.ec.gc.ca/cas-aqhi/default.asp?lang=En&n=3E3FDF68-1 target=_blank>website</a> for more information on air pollution in your community and how it affects your health.");
	}
	
	
</script>

<script type='text/javascript'>
    $(document).ready(function(e) {
        $('[data-toggle="tooltip"]').tooltip();

        var isHealthCareCostsDetailHidden = true;
        $('#healthcare-costs-detail-trigger').click(function() {
            if(isHealthCareCostsDetailHidden) {
                $('#healthcare-costs-detail-trigger').text('Healthcare costs breakdown \u25BC');
                $('#health-care-costs-detail').show();
            }
            else {
                $('#healthcare-costs-detail-trigger').text('Healthcare costs creakdown \u25B6');
                $('#health-care-costs-detail').hide();
            }

            isHealthCareCostsDetailHidden = !isHealthCareCostsDetailHidden;
        });
    });
</script> 
<?php require_once('../common/footer_commonk.php'); ?>

