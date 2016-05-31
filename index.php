<?php
require_once('calculator/hospital_costs.php');
require_once('questions/radio_question.php');

session_start();
if($_SERVER['REQUEST_METHOD'] != "POST") {
require_once('header_maink2.php');
}
else {
	$uniqueID = '';
	if(!isset($_COOKIE['uniqueID'])){
	$uniqueID = uniqid(mt_rand(),true);
	setcookie('uniqueID', $uniqueID, time()+60*60*24*365,'/');}
	else {$uniqueID = $_COOKIE['uniqueID'];}
}

require_once('../common/setPageViewCounter.php');

//require_once('../common/header_commonk2b.php');
?>
<?php
if($_SERVER['REQUEST_METHOD'] != "POST") {
?>
<img id="titlelogo" src="/common/images/health-calculators.png"/>
<div id="space"></div>
<?php
} // end if($_SERVER['REQUEST_METHOD'] != "POST") {
?>
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
//$alc3 = '';
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
$htension1 = '';
$noDisease1 = '';
$immobile1 = '';
$immobile2 = '';
$pCode1 = '';
$household_income = '';
$home_ownership = '';
$marital_status = '';
$dementia = '';
$cancer_hc = '';

$eventtitle='';
$eventdate='';

$calc_le='1';
$calc_beddays='';
$calc_fhc = '';

if($_GET["fhc"] == "1") 
	$calc_fhc = '1';

if (isset ( $_GET ["le"] ) && isset ( $_GET ["hosp"] ) ) {
	if ($_GET ["le"] == "1") $calc_le='1';
	else $calc_le='';
	
	if ($_GET ["hosp"] == "1") $calc_beddays='1';
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
	//$alc3 = $_POST["alc3"];
	$alc4 = $_POST["alc4"];
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
	$htension1 = $_POST["htension1"];
	$noDisease1 = $_POST["noDisease1"];
	$immobile1 = $_POST["immobile1"];
	$immobile2 = $_POST["immobile2"];
	$pCode1 = $_POST["pCode1"];

	$eventtitle = $_POST["eventtitle"];
	$eventdate = $_POST["eventdate"];
	
	$calc_le = $_POST["calc_le"];
	$calc_beddays = $_POST["calc_beddays"];
	$calc_fhc = $_POST['calc_fhc'];

	$household_income = $_POST['household-income'];
	$home_ownership = $_POST['home-ownership'];
	$marital_status = $_POST['marital-status'];
	$dementia = $_POST['dementia1'];
	$cancer_hc = $_POST['cancer1'];
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
				echo 'Test';
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
					//$alc3 = $row->alc3;
					$alc4 = $row->alc4;
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
					$htension1 = $row->htension1;
					$noDisease1 = $row->noDisease1;
					$immobile1 = $row->immobile1;
					$immobile2 = $row->immobile2;
					//$pCode1 = $row->pCode1;
					
					$eventtitle = $row->eventtitle;
					$eventdate = $row->eventdate;
					
					$calc_le = $row->calc_le;
					$calc_beddays = $row->calc_beddays;

					if(!$_GET["fhc"])
						$calc_fhc = $row->calc_fhc;

					$household_income = $row->household_income;
					$home_ownership = $row->home_ownership;
					$marital_status = $row->marital_status;
					$dementia = $row->dementia;
					$cancer_hc = $row->cancer;
				}
			} //end else for  if (!$db_selected) {
	
		} //end else for  if (!$connect) { 
		
	} // end else of if(!isset($_SESSION['sessionID'])){
} // end else (not postback)

?>
<?php

if($_SERVER['REQUEST_METHOD'] != "POST") {

?>
<!--
<div id="intro"> 
Introduction text to go here. 
 </div>
 -->

<script language=javascript SRC="js/bmi.js">
</script>

<form name="myForm" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ; ?>" onSubmit="return validateForm()">
	<div id="calculate0" class="sectionContainer"> 
	<div id="calculate" style="border: solid 1px #CFC593;">
    <fieldset>
		<legend>What would you like us to calculate for you?</legend>
		<!-- <input type="checkbox" name="calc_le" id="calc-1" value="1" checked=true> -->
		<input type="checkbox" name="calc_le" id="calc-1" value="1" <?php echo ($calc_le =='1') ? 'checked' : '' ?> />
			<label for="calc-1">Life Expectancy</label>

		<!-- Checkbox for the future hospital costs calculation -->
		<input type="checkbox" name="calc_fhc" id="calc-3" value="1" <?php echo ($calc_fhc == '1') ? 'checked' : '' ?> />
		<label for="calc-3" valign=center>Future Health Care Costs</label>
		<!-- Checkbox for the future hospital costs calculation -->

			<br />

			<legend>If you live in Canada, provide your postal code to see the effect of air pollution on your health.</legend>
	</fieldset>
	</div>
	</div>
	
	
	<div id="section1" class="sectionContainer">
	<div id="header"> ABOUT YOU </div>

 	<div id="textboxes"> Age: &nbsp;&nbsp;&nbsp;
		<input type = "tel" name = "age" id = "age" size = "5" maxlength="3" onblur = "showTeaser(document.getElementById('age').value, document.getElementsByName('sex') )" title="The age must be a number between 20 and 79" value="<?php echo ($age_s !='') ? $age_s : '' ?>" />
		 <span id="info"> (between 20 and 79 years) </span> 
    </div>

	<div id="gender"></div>
    	<fieldset>
		<legend>Sex</legend>
		<input type="radio" name="sex" id="sex-1" value="1" class="sex1" <?php echo ($sex_s =='1') ? 'checked' : '' ?>  onClick="showTeaser(document.getElementById('age').value, document.getElementsByName('sex') )" />
		<label for="sex-1">Male</label>
		<input type="radio" name="sex" id="sex-2" value="2" class="sex1" <?php echo ($sex_s =='2') ? 'checked' : '' ?> onClick="showTeaser(document.getElementById('age').value, document.getElementsByName('sex') )" />
		<label for="sex-2">Female</label>
	</fieldset>
	</div>
	
	
	<div id="measurements"></div>
	<div id="textboxes">
		<table border=0>
		<tr>
		<td>
			Height: 
		</td>
		<td>
			<input type=tel name="height1" size=5 maxlength="3" onkeyup="conv(3)" class='innerc resform' value="<?php echo ($height1 !='') ? $height1 : '' ?>" /> 
		 
			<span id="info"> cm </span>
			&nbsp; or &nbsp; 
			<select name="height2" onChange="conv(1)" >
			<option name=feet value="1" <?php echo ($height2 =='1') ? 'selected="selected"' : '' ?> >1'</option>
			<option name=feet value="2" <?php echo ($height2 =='2') ? 'selected="selected"' : '' ?> >2'</option>
			<option name=feet value="3" <?php echo ($height2 =='3') ? 'selected="selected"' : '' ?> >3'</option>
			<option name=feet value="4" <?php echo ($height2 =='4') ? 'selected="selected"' : '' ?> >4'</option>
			<option name=feet value="5" <?php echo ($height2 =='5') ? 'selected="selected"' : '' ?> >5'</option>
			<option name=feet value="6" <?php echo ($height2 =='6') ? 'selected="selected"' : '' ?> >6'</option>
			<option name=feet value="7" <?php echo ($height2 =='7') ? 'selected="selected"' : '' ?> >7'</option>
			</select>
			<select name="height3" onChange="conv(2)">
			<option name=inches value="0" <?php echo ($height3 =='0') ? 'selected="selected"' : '' ?> >0"</option>
			<option name=inches value="1" <?php echo ($height3 =='1') ? 'selected="selected"' : '' ?> >1"</option>
			<option name=inches value="2" <?php echo ($height3 =='2') ? 'selected="selected"' : '' ?> >2"</option>
			<option name=inches value="3" <?php echo ($height3 =='3') ? 'selected="selected"' : '' ?> >3"</option>
			<option name=inches value="4" <?php echo ($height3 =='4') ? 'selected="selected"' : '' ?> >4"</option>
			<option name=inches value="5" <?php echo ($height3 =='5') ? 'selected="selected"' : '' ?> >5"</option>
			<option name=inches value="6" <?php echo ($height3 =='6') ? 'selected="selected"' : '' ?> >6"</option>
			<option name=inches value="7" <?php echo ($height3 =='7') ? 'selected="selected"' : '' ?> >7"</option>
			<option name=inches value="8" <?php echo ($height3 =='8') ? 'selected="selected"' : '' ?> >8"</option>
			<option name=inches value="9" <?php echo ($height3 =='9') ? 'selected="selected"' : '' ?> >9"</option>
			<option name=inches value="10" <?php echo ($height3 =='10') ? 'selected="selected"' : '' ?> >10"</option>
			<option name=inches value="11" <?php echo ($height3 =='11') ? 'selected="selected"' : '' ?> >11"</option>
			</select>
			
		</td>
		</tr>
		
		<tr>
		<td>
			Weight:
		</td>
		<td>
			<input type = "tel" name = "weight1" id = "weight1" size = "5" maxlength="5" title="" value="<?php echo ($weight1 !='') ? $weight1 : '' ?>" onkeyup="convWeight(1)" class='innerc resform' />
			<span id="info"> kg </span>
			&nbsp; or &nbsp;
			<input type = "tel" name = "weight2" id = "weight2" size = "5" maxlength="5" title="" value="<?php echo ($weight2 !='') ? $weight2 : '' ?>" onkeyup="convWeight(2)" class='innerc resform' /> <span id="info"> pounds </span>
		</td>
		<tr>
		
		</table>
	</div>
	
<!--
	<div id="teaser">
		<span id="txtTeaser"></span>
    </div>
-->

	<p />
	<div id="section2" class="sectionContainer">
	<div id="header"> HEALTH BEHAVIOURS </div>

    <div id="q1"></div>
		<div id="scroll1"></div>
		<div id="header3"> Smoking Status </div>	
		<fieldset>
			<legend>What is your smoking status?</legend> 
			
			<input type="radio" name="smk1" id="smoke-1" value="1" class="quitsmokefunc" <?php echo ($smk1 =='1') ? 'checked' : '' ?>  />
			<label for="smoke-1">Current heavy smoker (a pack or more a day)</label>
			
			<input type="radio" name="smk1" id="smoke-2" value="2" class="quitsmokefunc" <?php echo ($smk1 =='2') ? 'checked' : '' ?>  />
			<label for="smoke-2">Current light smoker (less than a pack a day)</label>
			
			<input type="radio" name="smk1" id="smoke-3" value="3" class="quitsmokefunc" <?php echo ($smk1 =='3') ? 'checked' : '' ?>  />
			<label for="smoke-3">Former heavy smoker (a pack or more a day)</label>
			
			<input type="radio" name="smk1" id="smoke-4" value="4" class="quitsmokefunc" <?php echo ($smk1 =='4') ? 'checked' : '' ?>  />
			<label for="smoke-4">Former light smoker (less than a pack a day)</label>
			
			<input type="radio" name="smk1" id="smoke-5" value="5" class="quitsmokefunc" <?php echo ($smk1 =='5') ? 'checked' : '' ?>  />
			<label for="smoke-5">Never smoker</label>
			
		</fieldset>
		
	<div id="q2"></div>
	<div id="smokeyears" class="formset">
		<br />
	<fieldset>
			<legend>How long ago since you quit smoking?</legend> 			
				<div id="textboxes"> 
		<input type = "tel" name = "smk2" id = "quitYears" size = "5" maxlength="3" title="Not more than your age" value="<?php echo ($smk2 !='') ? $smk2 : '' ?>" />
		 <span id="info"> (years) </span> 
    </div>		
	</fieldset>
	</div>
	
	<p />
	<div id="q3"></div>
		<div id="scroll2"></div>
		<div id="header3"> Alcohol Consumption </div>
		<fieldset>
			<legend>How often do you consume alcoholic beverages?</legend> 
			
			<input type="radio" name="alc1" id="alc1-1" value="1" class="alcfunc" <?php echo ($alc1 =='1') ? 'checked' : '' ?>  />
			<label for="alc1-1">1 or more times per month</label>
			
			<input type="radio" name="alc1" id="alc1-2" value="2" class="alcfunc" <?php echo ($alc1 =='2') ? 'checked' : '' ?>  />
			<label for="alc1-2">Less than once per month</label>
			
			<input type="radio" name="alc1" id="alc1-3" value="3" class="alcfunc" <?php echo ($alc1 =='3') ? 'checked' : '' ?>  />
			<label for="alc1-3">None at all in previous year</label>

	</fieldset>
	
	<div id="alcoholmore" class="formset">
	<p />
	<div id="q4"></div>
		<fieldset>
			<legend>How many drinks did you have in the past week?</legend> 			
				<div id="textboxes"> 
		<input type = "tel" name = "alc2" id = "alc2" size = "4" maxlength="3" title="Number of drinks in past week" value="<?php echo ($alc2 !='') ? $alc2 : '' ?>" />
		 <span id="info"> </span> 
    </div>		
	</fieldset>
	
	<!--
	<p />
	<div id="q5"></div>
	<fieldset>
			<legend>What is the most number of drinks you had on any one day in the past week?</legend> 			
				<div id="textboxes"> 
		<input type = "number" name = "alc3" id = "alc3" size = "4" maxlength="3" title="Maximum number of daily drinks in past week" value="<?php //echo ($alc3 !='') ? $alc3 : '' ?>" />
		 <span id="info"> </span> 
    </div>		
	</fieldset>
	-->
	
	<div id="alcoholmore2" class="formset">
	<p />
	<div id="q6"></div>
	<fieldset>
			<legend>On a typical week, do you have 5 or more drinks on one occasion?</legend> 
			
			<input type="radio" name="alc4" id="alc4-1" value="1" <?php echo ($alc4 =='1') ? 'checked' : '' ?>  />
			<label for="alc4-1">Yes</label>
			
			<input type="radio" name="alc4" id="alc4-2" value="2" <?php echo ($alc4 =='2') ? 'checked' : '' ?>  />
			<label for="alc4-2">No</label>
	</fieldset>
	</div>
	</div>
	
	<p />
	<div id="q7"></div>
	<div id="header3"> Fruit & Vegetable Diet </div>
	<fieldset>
			<legend>How many times did you have each of the following in the past week?</legend> 
		</fieldset>
	<div id="textboxes">
		<table border=0>
		<tr>
		<td>
			Fruit juice: 
		</td>
		<td>
			<input type = "tel" name = "diet1" id = "diet1" size =5 maxlength="4" title="" value="<?php echo ($diet1 !='') ? $diet1 : '' ?>" />
			<span id="info">  </span>
		</td>
		</tr>
		
		<tr>
		<td>
			Fruit:
		</td>
		<td>
			<input type = "tel" name = "diet2" id = "diet2" size = "5" maxlength="4" title="" value="<?php echo ($diet2 !='') ? $diet2 : '' ?>" />
			<span id="info">  </span>
		</td>
		<tr>
		
		<tr>
		<td>
			Salad:
		</td>
		<td>
			<input type = "tel" name = "diet3" id = "diet3" size = "5" maxlength="4" title="" value="<?php echo ($diet3 !='') ? $diet3 : '' ?>" />
			<span id="info">  </span>
		</td>
		<tr>
		
		<tr>
		<td>
			Potatoes:
		</td>
		<td>
			<input type = "tel" name = "diet4" id = "diet4" size = "5" maxlength="4" title="" value="<?php echo ($diet4 !='') ? $diet4 : '' ?>" />
			<span id="info">  </span>
		</td>
		<tr>
		
		<tr>
		<td>
			Carrots:
		</td>
		<td>
			<input type = "tel" name = "diet5" id = "diet5" size = "5" maxlength="4" title="" value="<?php echo ($diet5 !='') ? $diet5 : '' ?>" />
			<span id="info">  </span>
		</td>
		<tr>
		
		</table>
	</div>
	
	<p />
	<fieldset>
			<legend>How many servings of other vegetables did you have the past week? (Excluding Potatoes, Carrots, and Salad)</legend> 
				<div id="textboxes"> 
		<input type = "tel" name = "diet6" id = "diet6" size = "5" maxlength="4" title="Other Vegetable Consumption" value="<?php echo ($diet6 !='') ? $diet6 : '' ?>" />
		 <span id="info">  </span> 
    </div>		
	</fieldset>
	
	<p />
	<div id="header3"> Leisure Physical Activity </div>
	<fieldset>
			<legend>In the past week, how much time did you spend doing vigorous-intensity physical activity (e.g., running)?</legend> 
				<div id="textboxes"> 
		<input type = "tel" name = "pa1" id = "pa1" size = "4" maxlength="4" title="0.0 to 35.0" value="<?php echo ($pa1 !='') ? $pa1 : '' ?>" />
		 <span id="info"> hours </span> 
    </div>		
	</fieldset>
	
	<p />
	<fieldset>
			<legend>In the past week, how much time did you spend doing moderate-intensity physical activity (e.g. rollerblading) or sports that are vigorous but not continuous intensity (e.g., ice hockey, soccer, basketball, volleyball)?</legend> 
				<div id="textboxes"> 
		<input type = "tel" name = "pa2" id = "pa2" size = "4" maxlength="4" title="0 to 35" value="<?php echo ($pa2 !='') ? $pa2 : '' ?>" />
		 <span id="info"> hours </span> 
    </div>		
	</fieldset>
	
	<p />
	<fieldset>
			<legend>In the past week, how much time did you spend doing light-intensity physical activities? (e.g., walking, cycling, gardening, exercise class, golfing, bowling, skating, fishing, baseball, tennis)</legend> 
				<div id="textboxes"> 
		<input type = "tel" name = "pa3" id = "pa3" size = "4" maxlength="4" title="0 to 35" value="<?php echo ($pa3 !='') ? $pa3 : '' ?>" />
		 <span id="info"> hours </span> 
    </div>		
	</fieldset>
	
	
	<p />
	<div id="scroll5"></div>
	<div id="header3"> Self-Perceived Stress </div>
	<div id="stress"></div>
		<fieldset>
			<legend>In the past year, would you say that most days were:</legend> 
			
			<input type="radio" name="str1" id="stress-1" value="1" <?php echo ($str1 =='1') ? 'checked' : '' ?>  />
			<label for="stress-1">At most, a bit stressful</label>
			
			<input type="radio" name="str1" id="stress-2" value="2" <?php echo ($str1 =='2') ? 'checked' : '' ?>  />
			<label for="stress-2">Quite a bit or extremely stressful</label>
	</fieldset>
	
	<p />
	<p /><br />
	<div id="section3" class="sectionContainer">
	<div id="scroll6"></div>
	<div id="header"> SOCIODEMOGRAPHIC </div>
	
	<div id="country"></div>
		<fieldset>
			<legend>In which country do you live?</legend> 
			
			<input type="radio" name="country1" id="country1-1" value="1" class="countryfunc" <?php echo ($country1 =='1') ? 'checked' : '' ?>  />
			<label for="country1-1">Canada</label>
			
			<input type="radio" name="country1" id="country1-2" value="999" class="countryfunc" <?php echo ($country1 =='999') ? 'checked' : '' ?>  />
			<label for="country1-2">Other country</label>
	</fieldset>
	
	<div id="canadamore" class="formset">
	<p />
	<div id="textboxes"> Postal Code (Optional): &nbsp;<input type = "text" name = "pCode1" id = "postal" size = "7" maxlength="7" onblur = "checkPostal(this)" title="e.g. K1Y 4E9" value="<?php echo ($pCode1 !='') ? $pCode1 : '' ?>" /> <span id="info"> (e.g. K1Y 4E9) Postal code is used to adjust for geographic variations including pollution levels 
	 <img width="48" height="36" src="/common/images/pollution.png"/> </span>
	</div> 
	
	<div id="immigrated"></div>
		<div id="scroll7"></div>
		<fieldset>
			<legend>Did you immigrate to Canada?</legend> 
			
			<input type="radio" name="imm1" id="imm1-1" value="1" class="immfunc" <?php echo ($imm1 =='1') ? 'checked' : '' ?>  />
			<label for="imm1-1">Yes</label>
			
			<input type="radio" name="imm1" id="imm1-2" value="2" class="immfunc" <?php echo ($imm1 =='2') ? 'checked' : '' ?>  />
			<label for="imm1-2">No</label>
	</fieldset>
	
	<div id="immigratemore" class="formset">
	<fieldset>
			<legend>How long ago did you immigrate to Canada?</legend> 
				<div id="textboxes"> 
		<input type = "tel" name = "imm2" id = "imm2" size = "4" maxlength="4" title="" value="<?php echo ($imm2 !='') ? $imm2 : '' ?>" />
		 <span id="info"> years </span> 
    </div>		
	</fieldset>
	</div>
	
	</div>
	
	<div id="othercountrymore" class="formset">
	<p />
	<div id="ses1"></div>
		<div id="scroll8"></div>
		<fieldset>
			<legend>What is the level of social support and wealth in your neighbourhood compare to the rest of your country</legend> 
			
			<input type="radio" name="ses1" id="ses1-1" value="1" <?php echo ($ses1 =='1') ? 'checked' : '' ?>  />
			<label for="ses1-1">Above average</label>
			
			<input type="radio" name="ses1" id="ses1-2" value="2" <?php echo ($ses1 =='2') ? 'checked' : '' ?>  />
			<label for="ses1-2">Average</label>
			
			<input type="radio" name="ses1" id="ses1-3" value="3" <?php echo ($ses1 =='3') ? 'checked' : '' ?>  />
			<label for="ses1-3">Below average</label>
	</fieldset>
	</div>
	
	<p />
	<div id="education"></div>
		<div id="scroll9"></div>
		<fieldset>
			<legend>What is your education level?</legend> 
			
			<input type="radio" name="education1" id="education1-1" value="1" <?php echo ($education1 =='1') ? 'checked' : '' ?>  />
			<label for="education1-1">Less than high school</label>
			
			<input type="radio" name="education1" id="education1-2" value="2" <?php echo ($education1 =='2') ? 'checked' : '' ?>  />
			<label for="education1-2">High school graduate</label>
			
			<input type="radio" name="education1" id="education1-3" value="3" <?php echo ($education1 =='3') ? 'checked' : '' ?>  />
			<label for="education1-3">Post secondary graduate</label>
	</fieldset>
	<p />

	<?php
		//household income question 
		$householdIncomeChoiceLabels = array('0 to 29,999', '30,000 to 79,999', '80,000 or more');
		$householdIncomeChoiceValues = array('0-to-29-999', '30-000-to-79-999', '80-000-or-more');
		$householdIncomeQuestionId = 'household-income';
		$householdIncomeQuestionText = 'What best describes your household income?';
		echo getRadioQuestion($householdIncomeQuestionId, $householdIncomeQuestionText, $householdIncomeChoiceLabels, $householdIncomeChoiceValues, $household_income);
	?>

	<?php
		//home ownership question
		$homeOwnershipChoiceLabels = array('Yes', 'No');
		$homeOwnershipChoiceValues = array('yes', 'no');
		$homeOwnershipQuestionId = 'home-ownership';
		$homeOwnershipQuestionText = 'Is your residence owned by a household member?';
		echo getRadioQuestion($homeOwnershipQuestionId, $homeOwnershipQuestionText, $homeOwnershipChoiceLabels, $homeOwnershipChoiceValues, $home_ownership);
	?>

	<?php
		//martial status question
		$martialStatusChoiceLabels = array('Widowed / Separated / Single', 'Married / Common-Law');
		$martialStatusChoiceValues = array('widowed---separated---single', 'married---common-law-');
		$martialStatusQuestionId = 'marital-status';
		$martialStatusQuestionText = 'What is your marital status?';
		echo getRadioQuestion($martialStatusQuestionId, $martialStatusQuestionText, $martialStatusChoiceLabels, $martialStatusChoiceValues, $marital_status);
	?>
	
	<br />
	<div id="section3" class="sectionContainer">
	<div id="header"> DISEASES AND IMMOBILITY </div>
	<div id="diseases"></div>
		<div id="scroll10"></div>
		<fieldset>
			<legend>Which of the following Conditions do you have?</legend> 

			<input type="checkbox" name="diabetes1" id="diabetes1" value="1" class="disease" <?php echo ($diabetes1 =='1') ? 'checked' : '' ?> />
			<label for="diabetes1">Diabetes</label>
			
			<input type="checkbox" name="hDisease1" id="hDisease1" value="1" class="disease" <?php echo ($hDisease1 =='1') ? 'checked' : '' ?> />
			<label for="hDisease1">Heart disease</label>
			
			<input type="checkbox" name="stroke1" id="stroke1" value="1" class="disease"<?php echo ($stroke1 =='1') ? 'checked' : '' ?> />
			<label for="stroke1">Previous stroke</label>
			
			<input type="checkbox" name="htension1" id="htension1" value="1" class="disease"<?php echo ($htension1 =='1') ? 'checked' : '' ?> />
			<label for="htension1">Hypertension</label>

			<input type="checkbox" name="dementia1" id="dementia1" value="1" class="disease" <?php echo ($dementia == '1') ? 'checked' : '' ?> />
			<label for="dementia1">Dementia</label>
             
			<input type="checkbox" name="cancer1" id="cancer1" value="1" class="disease" <?php echo ($cancer_hc =='1') ? 'checked' : '' ?> />
			<label for="cancer1">Cancer</label>
			
			<input type="checkbox" name="noDisease1" id="noDisease1" value="1" class="noDisfunc" <?php echo ($noDisease1 =='1') ? 'checked' : '' ?> />
			<label for="noDisease1">None of the above</label>
	</fieldset>

	<p />
	<div id="mobility"></div>
		<div id="scroll11"></div>
		<fieldset>
			<legend>Does illness limit the kind of activity you can do at home, school, work, or leisure?</legend> 
			
			<input type="radio" name="immobile1" id="Immobile1-1" value="1" class="fragilityfunc" <?php echo ($immobile1 =='1') ? 'checked' : '' ?>  />
			<label for="Immobile1-1">Yes </label>
			
			<input type="radio" name="immobile1" id="Immobile1-2" value="2" class="fragilityfunc" <?php echo ($immobile1 =='2') ? 'checked' : '' ?>  />
			<label for="Immobile1-2">No </label>
			
	</fieldset>
	
	<p />
	<div id="scroll11b"></div>
	 <div id="morefragility2" class="formset">
		<fieldset>
			<legend>Because of illness, do you need the help when performing basic tasks? (e.g., running errands, household chores, personal care, etc.)</legend> 
			
			<input type="radio" name="immobile2" id="Immobile2-1" value="1" <?php echo ($immobile2 =='1') ? 'checked' : '' ?>  />
			<label for="Immobile2-1">Yes </label>
			
			<input type="radio" name="immobile2" id="Immobile2-2" value="2" <?php echo ($immobile2 =='2') ? 'checked' : '' ?>  />
			<label for="Immobile2-2">No</label>
		</fieldset>
	 </div>
	
    
	</div>
	<div id="header"> Will you live to see it? (Optional) </div>
	<div id="textboxes">
		<span id="info"> Provide this information if you would like to see the probability of living until a future event of your choice. <br />
			For example, will I live to see <br />
			<i>
			&nbsp;&nbsp;&nbsp; - the Toronto Maple Leafs win the Stanley Cup? <br />
			&nbsp;&nbsp;&nbsp; - my grandchild get married? <br />
			&nbsp;&nbsp;&nbsp; - my grandchild's graduation? <br />
			&nbsp;&nbsp;&nbsp; - my retirement? <br />
			&nbsp;&nbsp;&nbsp; - my 100th birthday?

			</i>
		</span>
	</div>
	<div id="textboxes"> Event name: &nbsp;<input type = "text" name = "eventtitle" id = "eventtitle" size = "50" maxlength="50" title="e.g. Your child's or grandchild's wedding" value="<?php echo ($eventtitle !='') ? stripslashes($eventtitle) : '' ?>" /> <span id="info"> (e.g. Your child's or grandchild's wedding) </span>
	</div> 
	<div id="textboxes"> Event year: &nbsp;&nbsp;&nbsp;<input type = "tel" name = "eventdate" id = "eventdate" size = "4" maxlength="4" title="e.g. 2030" value="<?php echo ($eventdate !='') ? $eventdate : '' ?>" /> <span id="info"> (YYYY - e.g. 2019) </span>
	</div> 
	<br />

<!--
<p><input type="submit" value="Calculate" class="submit" onclick = "checkAge(age" >
</p>
-->
<p><input type="submit" value="CALCULATE" class="button orange" onclick = "checkAge(age" >
</p>


</form>



<script type = "text/javascript">
<!--
function checkAgePause(age) {

    age.value = age.value.replace(/^\s+/,"") // strip leading spaces


    mystring = age.value;
    if (mystring.match(/^\d+$/ ) && mystring >=20 && mystring <=79) { //regex
	return true;
    }
    else
    {
        alert("The age must be a number between: 20 and 79");
	age.focus();
	return false;
    }


}

function checkPostalPause(postal) {

    postal.value = postal.value.replace(/^\s+/,"") // strip leading spaces


    mystring = postal.value;
    if (mystring.match(/^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxy]{1}\d{1}[A-Za-z]{1} *\d{1}[A-Za-z]{1}\d{1}$/ ) ) { //regex
	return true;
    }
    else
    {
        alert("The postal code must be a valid Canadian postal code in the format A#A #A#. Example K1Y 4E9");
	postal.focus();
    }

}


function validateForm(){

ErrorText= "";
var age=document.forms["myForm"]["age"].value.replace(/^\s+/,""); // strip leading spaces
if (age==null || age=="") {ErrorText= "\nPlease enter your age";}

else if (age.match(/^\d+$/ ) && age >=20 && age <= 79) { //regex
	ErrorText="";
    }
    else { ErrorText+= "\nThe age must be a number between 20 and 79";}

if ( (document.forms["myForm"].sex[0].checked == false) && (document.forms["myForm"].sex[1].checked == false) ) {ErrorText+= "\nPlease select your gender";}


var postal = document.forms["myForm"]["postal"].value.replace(/^\s+/,"") // strip leading spaces

    if ( ( postal ==null || postal =="") || (postal.match(/^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxyUu]{1}\d{1}[A-Za-z]{1} *\d{1}[A-Za-z]{1}\d{1}$/ ) ) ){ //regex
	ErrorText=ErrorText;
    }
    else
    {
        ErrorText+="\nThe postal code must be a valid Canadian postal code in the format A#A #A#. Example K1Y 4E9";
    }

var smk2=document.forms["myForm"]["quitYears"].value.replace(/^\s+/,""); // strip leading spaces
if (smk2 !=null && smk2 !='' && (!smk2.match(/^\d+$/) || smk2 <0 )) { ErrorText+="\nYears quit smoking must be a number";}

var alc2=document.forms["myForm"]["alc2"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2 !=null && alc2 !='' && (!alc2.match(/^\d+$/) || alc2 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

/*
var alc2b1=document.forms["myForm"]["alc2b1"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b1 !=null && alc2b1 !='' && (!alc2b1.match(/^\d+$/) || alc2b1 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

var alc2b2=document.forms["myForm"]["alc2b2"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b2 !=null && alc2b2 !='' && (!alc2b2.match(/^\d+$/) || alc2b2 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

var alc2b3=document.forms["myForm"]["alc2b3"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b3 !=null && alc2b3 !='' && (!alc2b3.match(/^\d+$/) || alc2b3 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

var alc2b4=document.forms["myForm"]["alc2b4"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b4 !=null && alc2b4 !='' && (!alc2b4.match(/^\d+$/) || alc2b4 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

var alc2b5=document.forms["myForm"]["alc2b5"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b5 !=null && alc2b5 !='' && (!alc2b5.match(/^\d+$/) || alc2b5 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

var alc2b6=document.forms["myForm"]["alc2b6"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b6 !=null && alc2b6 !='' && (!alc2b6.match(/^\d+$/) || alc2b6 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}

var alc2b7=document.forms["myForm"]["alc2b7"].value.replace(/^\s+/,""); // strip leading spaces
if (alc2b7 !=null && alc2b7 !='' && (!alc2b7.match(/^\d+$/) || alc2b7 <0 )) { ErrorText+="\nAlcohol drinks must be numbers";}
*/

var diet1=document.forms["myForm"]["diet1"].value.replace(/^\s+/,""); // strip leading spaces
if (diet1 !=null && diet1 !='' && (!diet1.match(/^\d+$/) || diet1 <0 )) { ErrorText+="\nFruit juice must be a number";}

var diet2=document.forms["myForm"]["diet2"].value.replace(/^\s+/,""); // strip leading spaces
if (diet2 !=null && diet2 !='' && (!diet2.match(/^\d+$/) || diet2 <0 )) { ErrorText+="\nFruit must be a number";}

var diet3=document.forms["myForm"]["diet3"].value.replace(/^\s+/,""); // strip leading spaces
if (diet3 !=null && diet3 !='' && (!diet3.match(/^\d+$/) || diet3 <0 )) { ErrorText+="\nSalad must be a number";}

var diet4=document.forms["myForm"]["diet4"].value.replace(/^\s+/,""); // strip leading spaces
if (diet4 !=null && diet4 !='' && (!diet4.match(/^\d+$/) || diet4 <0 )) { ErrorText+="\nPotatoes must be a number";}

var diet5=document.forms["myForm"]["diet5"].value.replace(/^\s+/,""); // strip leading spaces
if (diet5 !=null && diet5 !='' && (!diet5.match(/^\d+$/) || diet5 <0 )) { ErrorText+="\nCarrots must be a number";}

var diet6=document.forms["myForm"]["diet6"].value.replace(/^\s+/,""); // strip leading spaces
if (diet6 !=null && diet6 !='' && (!diet6.match(/^\d+$/) || diet6 <0 )) { ErrorText+="\nOther vegetables must be a number";}
		
var pa1=document.forms["myForm"]["pa1"].value.replace(/^\s+/,""); // strip leading spaces
if (pa1 !=null && pa1 !='' && (!pa1.match(/^\d+$/) || pa1 <0 )) { ErrorText+="\nAnswers for physical activity must be numbers";}

var pa2=document.forms["myForm"]["pa2"].value.replace(/^\s+/,""); // strip leading spaces
if (pa2 !=null && pa2 !='' && (!pa2.match(/^\d+$/) || pa2 <0 )) { ErrorText+="\nAnswers for physical activity must be numbers";}

var pa3=document.forms["myForm"]["pa3"].value.replace(/^\s+/,""); // strip leading spaces
if (pa3 !=null && pa3 !='' && (!pa3.match(/^\d+$/) || pa3 <0 )) { ErrorText+="\nAnswers for physical activity must be numbers";}

var eventdate=document.forms["myForm"]["eventdate"].value.replace(/^\s+/,""); // strip leading spaces
if (eventdate !=null && eventdate !='' && (!eventdate.match(/^\d+$/) || !eventdate.match(/^[2-9][0-9]{3}$/) || eventdate <2015)) { ErrorText+="\nPlease provide a valid event year";}
	
if (ErrorText!= "") {
alert(ErrorText);
return false;
}
if (ErrorText= "") { return true}


}



-->


</script>




<p /><br />



<?php

} // end for the if($_SERVER['REQUEST_METHOD'] != "POST") 

?>
<?php


/******************************************************************************
Create function for calculating life expectancy
For inputs need the following since considering cases when not answered (Non-response) or only age and sex answered
	will provide averages when missing;
1. age
2. sex
3. For smoking need 6 inputs: 
		currentLightSmk, currentHeavySmk, 
		formerLightSmk, formerHeavySmk, formerLightSmkQuitYears, formerHeavySmkQuitYears 
4. Alcohol need 4 inputs
5. Diet 1 input
6. Physical activity: 1 input
7. Immigration need 2 inputs:
		i) probability of being immigrant: if answered then 1 or 0 else proportion. If > 0 then immigrant.
		ii) years since immigrated		
8. House income 2 inputs: houseIncL, houseIncM -- they are either 1,0 or proportions
9. Diseases: 4 (one for each disease)
10. Event_year
******************************************************************************/
function calculate($sex, $age, $currentLightSmk, $currentHeavySmk, $formerLightSmk, $formerHeavySmk, $formerLightSmkYearsSinceQuit, $formerHeavySmkYearsSinceQuit, $alcH, /*$alcR, $alcL, $alcN,*/ $alcM, $diet, $PA, $imm, $yearsSinceImmigrated, $eduNoHSGrad, $eduHSGrad, $hDisease, $stroke, $hypertension, $cancer, $diabetes, $event_year, $bmiHigh, $bmiAbove35, $stressHigh, $fragile, $restricted, $depMod, $depHigh, $LHINInjuryRate, $calc_le, $calc_beddays, $eventtitle, $PM25MeanCenteredExposure, $O3MeanCenteredExposure, $NO2MeanCenteredExposure, $PM25FifthPerCenteredExposure, $O3FifthPerCenteredExposure, $NO2FifthPerCenteredExposure, $stroke_score, $stroke_lkupValue) {

//male model
if ($sex == 1) {
	// Helica-M
	$baseline = 0.0000372902906433195; // 0.0000317705; // need this value for new algorithm
	$ageBeta = 0.08314;
	$ageSplineBeta = 0.03286;
	$lightSmkBeta = 0.90032;
	$heavySmkBeta = 1.03950;
	$PABeta = -0.70311;
	$dietBeta = -0.03441;
	$alcHBeta = 0.05088; //heavy
	//$alcRBeta = 0.17793; //regular - now reference -> see below
	$alcMBeta = -0.19270; // new non-reference category of moderate
	//$alcLBeta = 0.1772; //none or light -- now reference
	//$alcNBeta = 0.28379; //none -- now combined with light drinker as above
	//$houseIncLBeta = 0.53863; //low income -- income not used anymore but replaced with education
	//$houseIncMBeta = 0.28799; //moderate -- income not used anymore but replaced with education

	$depHighBeta = 0.22010; // high deprivation - new 
	$depModBeta = 0.06906; // moderate deprivation - new
	
	$eduNoHSGradBeta = 0.18571; // new
	$eduHSGradBeta = 0.08632; // new
	
	$imm15Beta = -0.98263; //years in Canada 0-15
	$imm30Beta = -0.40429; //years in Canada 16-30
	$imm45Beta = -0.11708; //years in Canada 31-45
	$hDiseaseBeta = 0.37945; //heart disease
	$strokeBeta = 0.22416; 
	$cancerBeta = 4.40894;
	$diabetesBeta = 1.98474;
	$cancAgeBeta = -0.04978; //cancer*age interaction -- row age
	$diabAgeBeta = -0.02097; //diabetes*age interaction -- row age -- confirm to make sure this negative as Richard had positive in appendix 6
	$smokeConstant=15; 
	
	$bmiHighBeta = 0.03048; // new
	
	// air pollution
	$PM25Beta = 0.006625654; // new
	$O3Beta = 0.003091342; // new
	$NO2Beta = 0.006000419; // new
	
	
	// Helica-H Logistic
	$Logis_BaselineBeta = 3.5890990166;
	$Logis_Agecat40to49Beta = -0.297526472;
	$Logis_Agecat50to54Beta = -0.64760596;
	$Logis_Agecat55to59Beta = -0.94051777;
	$Logis_Agecat60to64Beta = -1.199440032;
	$Logis_Agecat65to69Beta = -1.476356149;
	$Logis_Agecat70to74Beta = -1.65999984;
	$Logis_Agecat75to79Beta = -1.777489361;
	$Logis_LightSmkBeta = -0.166863245;
	$Logis_HeavySmkBeta = -0.340177565;
	$Logis_LightFormerSmkBeta = -0.118854013;
	$Logis_HeavyFormerSmkBeta = -0.244608332;
	$Logis_InactiveBeta = -0.029576927;
	$Logis_BadDietBeta = -0.025640868;
	$Logis_AlcHeavyBeta = -0.025909638;
	$Logis_AlcNoneBeta = -0.121701712;
	$Logis_BMIHighBeta = -0.118499335;
	$Logis_StressHighBeta = -0.138909067;
	/*
	$Logis_HIncLowBeta = -0.114012189; // change from Logis_HIncLowBeta to EduLow
	$Logis_HIncModBeta = -0.077951724; // change from Logis_HIncModBeta to EduMod
	*/
	$Logis_eduNoHSGradBeta = -0.114012189;
	$Logis_eduHSGradBeta = -0.077951724;
	
	$Logis_Imm15Beta = 0.3276550398;
	$Logis_Imm30Beta = 0.1789216993;
	$Logis_Imm45Beta = 0.0965246765;
	$Logis_HdiseaseBeta = -0.607356297;
	$Logis_StrokeBeta = -0.320925249;
	$Logis_CancerBeta = -0.313752088;
	$Logis_DiabetesBeta = -0.349448874;
	$Logis_FragileBeta = -0.747851423;
	$Logis_RestrictedBeta = -0.379366236;
	$Logis_DepModBeta = 0.0472643759;
	$Logis_DepHighBeta = 0.012547919;
	$Logis_LHINInjuryRateBeta = -0.248065307;


	// Helica-H Count
	$Count_BaselineBeta = -5.492170526;
	$Count_Agecat40to49Beta = 0.2718495847;
	$Count_Agecat50to54Beta = 0.1345762326;
	$Count_Agecat55to59Beta = 0.3491800616;
	$Count_Agecat60to64Beta = 0.3510375442;
	$Count_Agecat65to69Beta = 0.3784773297;
	$Count_Agecat70to74Beta = 0.6369203047;
	$Count_Agecat75to79Beta = 0.8288018176;
	$Count_LightSmkBeta = 0.1788053915;
	$Count_HeavySmkBeta = 0.3831365758;
	$Count_LightFormerSmkBeta = 0.0479244222;
	$Count_HeavyFormerSmkBeta = -0.069513048;
	$Count_InactiveBeta = 0.1165788632;
	$Count_BadDietBeta = 0.1644418399;
	$Count_AlcHeavyBeta = 0.1202277218;
	$Count_AlcNoneBeta = 0.2027534259;
	$Count_BMIHighBeta = 0.0620475257;
	$Count_StressHighBeta = -0.010703721;
	/*
	$Count_HIncLowBeta = 0.1336793821; // change from Logis_HIncLowBeta to EduLow
	$Count_HIncModBeta = 0.1650187782; // change from Logis_HIncModBeta to EduMod
	*/
	$Logis_eduNoHSGradBeta = 0.1336793821;
	$Logis_eduHSGradBeta = -0.1650187782;
	
	$Count_Imm15Beta = -0.074392002;
	$Count_Imm30Beta = 0.0801180713;
	$Count_Imm45Beta = 0.0017928302;
	$Count_HdiseaseBeta = -0.094811607;
	$Count_StrokeBeta = 0.0923974299;
	$Count_CancerBeta = 0.0939539818;
	$Count_DiabetesBeta = 0.260984516;
	$Count_FragileBeta = 0.4858465646;
	$Count_RestrictedBeta = 0.1394577175;
	$Count_DepModBeta = 0.1980552063;
	$Count_DepHighBeta = 0.3673897412;
	$Count_LHINInjuryRateBeta = 0.2015686429;
	
	
	// SPoRT -- Stroke
	$sport_ageBeta = 0.104670237;
	$sport_AgeSplineBeta	= -0.031788441;
	$sport_scaleBeta	= 0.116236685;
	$sport_hypertension1Beta	= 0.312286315;
	//$sport_hypertension9Beta = -0.222726888;
	$sport_diabetesBeta = 0.253685637;
	$sport_heartDiseaseBeta = 0.309118218;
	$sport_surveycycle1Beta = 0.234017812;
	$sport_surveycycle2Beta = 0.03219116;
	
	$sport_ageMean = 47.9;
	$sport_AgeSplineMean = 1.6;
	$sport_scaleMean = 3.6;
	$sport_hypertension1Mean = 0.1484;
	//$sport_hypertension9Mean = 0;
	$sport_diabetesMean = 0.0591;
	$sport_heartDiseaseMean = 0.0735;
	$sport_surveycycle1Mean = 0;
	$sport_surveycycle2Mean = 0;
	
}

//female model
else if ($sex == 2) {
	
	// Helica-M
	$baseline = 0.0000238107907417682; //0.0000211843; // need this value for new algorithm 
	$ageBeta = 0.08965;
	$ageSplineBeta = 0.02831;
	$lightSmkBeta = 0.82551;
	$heavySmkBeta = 1.21862;
	$PABeta = -0.83081;
	$dietBeta = -0.03994;
	$alcHBeta = 0.08790; //heavy
	//$alcRBeta = 0.xxxx; //regular - now reference -> see below
	$alcMBeta = -0.19508; // new non-reference category of moderate
	//$alcLBeta = 0.xxxx; //none or light -- now reference
	//$alcNBeta = 0.xxxx; //none -- now combined with light drinker as above
	//$houseIncLBeta = 0.xxxxx; //low income -- income not used anymore but replaced with education
	//$houseIncMBeta = 0.xxxxx; //moderate -- income not used anymore but replaced with education

	$depHighBeta = 0.16378; // high deprivation - new 
	$depModBeta = 0.01309; // moderate deprivation - new
	
	$eduNoHSGradBeta = 0.12842; // new
	$eduHSGradBeta = 0.04381; // new
	
	$imm15Beta = -0.61324; //years in Canada 0-15
	$imm30Beta = -0.26705; //years in Canada 16-30
	$imm45Beta = -0.19508; //years in Canada 31-45
	$hDiseaseBeta = 0.33153; //heart disease
	$strokeBeta = 0.23761; 
	$cancerBeta = 3.90863;
	$diabetesBeta = 0.95916;
	$cancAgeBeta = -0.04245; //cancer*age interaction -- row age
	$diabAgeBeta = -0.00699; //diabetes*age interaction -- row age -- confirm to make sure this negative as Richard had positive in appendix 6
	$smokeConstant=26; 
	
	$bmiHighBeta = 0.02981; // new
	
	// air pollution
	$PM25Beta = 0.006625654; // new
	$O3Beta = 0.003091342; // new
	$NO2Beta = 0.006000419; // new

	
	// Helica-H Logistic
	$Logis_BaselineBeta = 2.9541701789;
	$Logis_Agecat40to49Beta = 0.1527754913;
	$Logis_Agecat50to54Beta = -0.012477809;
	$Logis_Agecat55to59Beta = -0.114493456;
	$Logis_Agecat60to64Beta = -0.32190516;
	$Logis_Agecat65to69Beta = -0.479605822;
	$Logis_Agecat70to74Beta = -0.635149716;
	$Logis_Agecat75to79Beta = -0.872328416;
	$Logis_LightSmkBeta = -0.129481517;
	$Logis_HeavySmkBeta = -0.295468675;
	$Logis_LightFormerSmkBeta = -0.081577224;
	$Logis_HeavyFormerSmkBeta = -0.164551309;
	$Logis_InactiveBeta = -0.036953272;
	$Logis_BadDietBeta = -0.034543824;
	$Logis_AlcHeavyBeta = -0.005662705;
	$Logis_AlcNoneBeta = -0.128683328;
	$Logis_BMIHighBeta = -0.349573703;
	$Logis_StressHighBeta = -0.104256475;
	
	/*
	$Logis_HIncLowBeta = -0.22215;
	$Logis_HIncModBeta = -0.14152;
	*/
	$Logis_eduNoHSGradBeta = -0.125307514;
	$Logis_eduHSGradBeta = -0.011302099;
	
	$Logis_Imm15Beta = 0.2714501124;
	$Logis_Imm30Beta = 0.07866342;
	$Logis_Imm45Beta = 0.1178230304;
	$Logis_HdiseaseBeta = -0.448374674;
	$Logis_StrokeBeta = -0.506645283;
	$Logis_CancerBeta = -0.432333705;
	$Logis_DiabetesBeta = -0.330266183;
	$Logis_FragileBeta = -0.727439748;
	$Logis_RestrictedBeta = -0.386878474;
	$Logis_DepModBeta = -0.055362618;
	$Logis_DepHighBeta = -0.141446783;
	$Logis_LHINInjuryRateBeta = -0.321235467;

	// Helica-H Count
	$Count_BaselineBeta = -5.697914606;
	$Count_Agecat40to49Beta = 0.4112943442;
	$Count_Agecat50to54Beta = 0.5350954973;
	$Count_Agecat55to59Beta = 0.5971272073;
	$Count_Agecat60to64Beta = 0.7104280207;
	$Count_Agecat65to69Beta = 0.9488494348;
	$Count_Agecat70to74Beta = 1.0078751787;
	$Count_Agecat75to79Beta = 1.1830741153;
	$Count_LightSmkBeta = 0.1155342448;
	$Count_HeavySmkBeta = 0.2527787937;
	$Count_LightFormerSmkBeta = -0.08194054;
	$Count_HeavyFormerSmkBeta = 0.0983001953;
	$Count_InactiveBeta = 0.066214927;
	$Count_BadDietBeta = 0.1345847682;
	$Count_AlcHeavyBeta = -0.107910531;
	$Count_AlcNoneBeta = 0.1713316872;
	$Count_BMIHighBeta = 0.0823229569;
	$Count_StressHighBeta = 0.0091298989;
	
	/*
	$Count_HIncLowBeta = 0.33067;
	$Count_HIncModBeta = 0.0125;
	*/
	$Count_eduNoHSGradBeta = -0.045367591;
	$Count_eduHSGradBeta = -0.029009076;
	
	$Count_Imm15Beta = 0.260831272;
	$Count_Imm30Beta = -0.28362215;
	$Count_Imm45Beta = -0.027054176;
	$Count_HdiseaseBeta = 0.1195312683;
	$Count_StrokeBeta = 0.1248157608;
	$Count_CancerBeta = -0.048563578;
	$Count_DiabetesBeta = 0.3378140284;
	$Count_FragileBeta = 0.4544700688;
	$Count_RestrictedBeta = 0.0663093554;
	$Count_DepModBeta = 0.2460521202;
	$Count_DepHighBeta = 0.1987166647;
	$Count_LHINInjuryRateBeta = -0.004853677;
	
	
	// SPoRT -- Stroke
	$sport_ageBeta = 0.100956753;
	$sport_AgeSplineBeta	= 0; //confirm no spline for females
	$sport_scaleBeta	= 0.13674374;
	$sport_hypertension1Beta	= 0.327899452;
	//$sport_hypertension9Beta = 0.424085992;
	$sport_diabetesBeta = 0.554893226;
	$sport_heartDiseaseBeta = 0.367223254;
	$sport_surveycycle1Beta = 0.168818888;
	$sport_surveycycle2Beta = 0.046985871;
	
	$sport_ageMean = 49.6;
	$sport_AgeSplineMean = 0;
	$sport_scaleMean = 4.2;
	$sport_hypertension1Mean = 0.185;
	//$sport_hypertension9Mean = 0;
	$sport_diabetesMean = 0.0511;
	$sport_heartDiseaseMean = 0.03651;
	$sport_surveycycle1Mean = 0;
	$sport_surveycycle2Mean = 0;

}



/* for conditions on calculating scores when calculating life lost due to smoking, alc, diet, inactivity */

if ($alcN !=1) $alcN_forScore=0; 
else $alcN_forScore=1;

if ($PA < 3) $PA_forScore=3; 
else $PA_forScore=$PA;


// Helica-H
if ($PA < 1.5) $inactive = 1;
else $inactive = 0;

if ($diet < 4) $badDiet = 1;
else $badDiet = 0;

/*
if ($deprivation == 2) $depMod = 1;
else $depMod = 0;

if ($deprivation == 3) $depHigh = 1;
else $depHigh = 0;
*/

//if ($injury == 0) $LHINInjuryRate = 0;





$diet_forScore=10;

/* define static score parts of exponential life equations */
$score1 = $currentLightSmk*$lightSmkBeta + $currentHeavySmk*$heavySmkBeta + 
			$alcH*$alcHBeta + $alcM*$alcMBeta +
			/*$bmiHigh*$bmiHighBeta + */
			$bmiAbove35*$bmiHighBeta +
			$diet*$dietBeta + (log10($PA+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta +
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta +
			$depMod*$depModBeta + $depHigh*$depHighBeta;
			
			
 
//for years lost due to smoking
$score1_smoke = $currentLightSmk*$lightSmkBeta*0 + $currentHeavySmk*$heavySmkBeta*0 + 
			$alcH*$alcHBeta + $alcM*$alcMBeta +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta +
			$diet*$dietBeta + (log10($PA+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta +
			$depMod*$depModBeta + $depHigh*$depHighBeta;
			
//for years lost due to alcohol
//if ($alcN == 1) $alcN_forScore=1;
//else $alcN_forScore=0;

$score1_alcohol = $currentLightSmk*$lightSmkBeta + $currentHeavySmk*$heavySmkBeta + 
			$alcH*$alcHBeta*0 + $alcM*$alcMBeta*1 +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta +
			$diet*$dietBeta + (log10($PA+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta + 
			$depMod*$depModBeta + $depHigh*$depHighBeta;

//for years lost due to diet
$score1_diet = $currentLightSmk*$lightSmkBeta + $currentHeavySmk*$heavySmkBeta + 
			$alcH*$alcHBeta + $alcM*$alcMBeta +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta + 
			10*$dietBeta + (log10($PA+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta +
			$depMod*$depModBeta + $depHigh*$depHighBeta;

//for years lost due to PA
//if ($PA < 3) $PA_forScore=3; 
//else $PA_forScore=$PA;

$score1_PA = $currentLightSmk*$lightSmkBeta + $currentHeavySmk*$heavySmkBeta + 
			$alcH*$alcHBeta + $alcM*$alcMBeta +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta + 
			$diet*$dietBeta + (log10($PA_forScore+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta +
			$depMod*$depModBeta + $depHigh*$depHighBeta;

			
//for years lost due to air pollution
$score1_pollution = $currentLightSmk*$lightSmkBeta + $currentHeavySmk*$heavySmkBeta + 
			$alcH*$alcHBeta + $alcM*$alcMBeta +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta +
			$diet*$dietBeta + (log10($PA+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25FifthPerCenteredExposure*$PM25Beta + $O3FifthPerCenteredExposure*$O3Beta + $NO2FifthPerCenteredExposure*$NO2Beta +
			$depMod*$depModBeta + $depHigh*$depHighBeta;
			
//for years lost due to neighbourhood (deprivation)
$score1_deprivation = $currentLightSmk*$lightSmkBeta + $currentHeavySmk*$heavySmkBeta + 
			$alcH*$alcHBeta + $alcM*$alcMBeta +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta +
			$diet*$dietBeta + (log10($PA+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta +
			$depMod*$depModBeta*0 + $depHigh*$depHighBeta*0;

			
//for years lost due to all factors
$score1_all = $currentLightSmk*$lightSmkBeta*0 + $currentHeavySmk*$heavySmkBeta*0 + 
			$alcH*$alcHBeta*0 + $alcM*$alcMBeta*1 +
			/*$bmiHigh*$bmiHighBeta +*/
			$bmiAbove35*$bmiHighBeta +
			10*$dietBeta + (log10($PA_forScore+1))*$PABeta + 
			$eduNoHSGrad*$eduNoHSGradBeta + $eduHSGrad*$eduHSGradBeta + 
			$hDisease*$hDiseaseBeta + $stroke*$strokeBeta + $cancer*$cancerBeta + $diabetes*$diabetesBeta +
			$PM25MeanCenteredExposure*$PM25Beta + $O3MeanCenteredExposure*$O3Beta + $NO2MeanCenteredExposure*$NO2Beta +
			$depMod*$depModBeta + $depHigh*$depHighBeta;

			
			
			
// Helica-H 
$scoreH_logistic = $Logis_BaselineBeta + $currentLightSmk*$Logis_LightSmkBeta +  $currentHeavySmk*$Logis_HeavySmkBeta + 
			$formerLightSmk*$Logis_LightFormerSmkBeta + $formerHeavySmk*$Logis_HeavyFormerSmkBeta + $inactive*$Logis_InactiveBeta + 
			$badDiet*$Logis_BadDietBeta + $alcH*$Logis_AlcHeavyBeta + $alcN*$Logis_AlcNoneBeta + $bmiHigh*$Logis_BMIHighBeta + 
			$stressHigh*$Logis_StressHighBeta + 
			/*$houseIncL*$Logis_HIncLowBeta + $houseIncM*$Logis_HIncModBeta + */
			$eduNoHSGrad*$Logis_eduNoHSGradBeta + $eduHSGrad*$Logis_eduHSGradBeta +
			$hDisease*$Logis_HdiseaseBeta + $stroke*$Logis_StrokeBeta + $cancer*$Logis_CancerBeta + $diabetes*$Logis_DiabetesBeta + 
			$fragile*$Logis_FragileBeta + $restricted*$Logis_RestrictedBeta + $depMod*$Logis_DepModBeta + $depHigh*$Logis_DepHighBeta + 
			$LHINInjuryRate*$Logis_LHINInjuryRateBeta;
			
$scoreH_count = log(365) + $Count_BaselineBeta + $currentLightSmk*$Count_LightSmkBeta +  $currentHeavySmk*$Count_HeavySmkBeta + 
			$formerLightSmk*$Count_LightFormerSmkBeta + $formerHeavySmk*$Count_HeavyFormerSmkBeta + $inactive*$Count_InactiveBeta + 
			$badDiet*$Count_BadDietBeta + $alcH*$Count_AlcHeavyBeta + $alcN*$Count_AlcNoneBeta + $bmiHigh*$Count_BMIHighBeta + 
			$stressHigh*$Count_StressHighBeta + 
			/*$houseIncL*$Count_HIncLowBeta + $houseIncM*$Count_HIncModBeta + */
			$eduNoHSGrad*$Count_eduNoHSGradBeta + $eduHSGrad*$Count_eduHSGradBeta +
			$hDisease*$Count_HdiseaseBeta + $stroke*$Count_StrokeBeta + $cancer*$Count_CancerBeta + $diabetes*$Count_DiabetesBeta + 
			$fragile*$Count_FragileBeta + $restricted*$Count_RestrictedBeta + $depMod*$Count_DepModBeta + $depHigh*$Count_DepHighBeta + 
			$LHINInjuryRate*$Count_LHINInjuryRateBeta;

			
	//echo "<br /> scoreH_logistic: $scoreH_logistic , scoreH_count: $scoreH_count <br />";
			
if ($age < 20) $i = 20;
else $i=$age;

//$i=$age;

// for probability of living to age 75 and future date
$prob_liveto75 = 0;
$prob_see_event = 0;
$thisyear = date("Y");
$futureyears = $age + ($event_year - $thisyear);

// x5-year 10-year probability of death
//$prob_death5year = 0;
$prob_death10year = 0;

$prob_alive = 1;
$final = 0;

//Helica-H
$finalBedDays = 0;
$adjustedBedDays=0;

/* for scores - life lost due to smoking, etc. */
$prob_alive_smoke = 1; $final_smoke = 0;
$prob_alive_alcohol = 1; $final_alcohol = 0;
$prob_alive_diet = 1; $final_diet = 0;
$prob_alive_PA = 1; $final_PA = 0;
$prob_alive_pollution = 1; $final_pollution = 0;
$prob_alive_deprivation = 1; $final_deprivation = 0;
$prob_alive_all = 1; $final_all = 0;


// start Kasim testing
	$_SESSION['baselineTesting']  = $baseline;
// end Kasim testing

while($i<=120) {
	
	// start MPoRT
		if ($i > 99) $iMPoRT = 99;
		else $iMPoRT = $i;
	// end MPoRT
	
	
	if ($sex == '1') {
		if ($i > 65) {$age2 = $i - 65;}
		else $age2=0;
		
		// start MPoRT - adjust baseline (instead of looking using formula)
			$adjbaseline = 0.000000000000021551081381583600*pow($iMPoRT,6) - 0.000000000008486447609888860000*pow($iMPoRT,5) + 0.000000001364399999667450000000*pow($iMPoRT,4) - 0.000000114481138673466000000000*pow($iMPoRT,3) + 0.000005288182339610380000000000*pow($iMPoRT,2) - 0.000127814512267739000000000000*$iMPoRT + 0.001304104388221730000000000000;
			
			$baseline=$adjbaseline;
		// end MPoRT
		
	}
	else if ($sex == '2') {
		if ($i > 80) {$age2 = $i - 80;}
		else $age2=0;
		
		// start MPoRT - adjust baseline (instead of looking using formula)
			$adjbaseline = 0.000000000000298122211138552000*pow($iMPoRT,5) + 0.000000000094450531059653000000*pow($iMPoRT,4) - 0.000000011553325235826000000000*pow($iMPoRT,3) + 0.000000688676124502489000000000*pow($iMPoRT,2) - 0.000020192895421023900000000000*$iMPoRT + 0.000256321798081950000000000000;
			
			$baseline=$adjbaseline;
		// end MPoRT
	}
	
	// start Kasim testing
		$_SESSION['adjbaselineTesting']  = $baseline;
	// end Kasim testing
	
	//Helica-H
	// initialize
	$Agecat40to49 = 0;
	$Agecat50to54 = 0;
	$Agecat55to59 = 0;
	$Agecat60to64 = 0;
	$Agecat65to69 = 0;
	$Agecat70to74 = 0;
	$Agecat75to79 = 0;
	//set values
	if ($i >= 40 && $i <=49) {$Agecat40to49 = 1;}
	else if ($i >= 50 && $i <=54) {$Agecat50to54 = 1;}
	else if ($i >= 55 && $i <=59) {$Agecat55to59 = 1;}
	else if ($i >= 60 && $i <=64) {$Agecat60to64 = 1;}
	else if ($i >= 65 && $i <=69) {$Agecat65to69 = 1;}
	else if ($i >= 70 && $i <=74) {$Agecat70to74 = 1;}
	//else if ($i >= 75 && $i <=79) {$Agecat75to79 = 1;}
	else if ($i >= 75 && $i <=120) {$Agecat75to79 = 1;} // quick fix to avoid having issue of having reference (20-39) for virtual age 80 and up.
	//end Helica-H
	
	
	
	// for time dependent variables calculate new numbers
	//$yearsSinceQuit_time = $i - ($age-$yearsSinceQuit); // years since quit
	$formerLightSmkYearsSinceQuit_time = $i - ($age-$formerLightSmkYearsSinceQuit); // years since quit - former light smoker
	$formerHeavySmkYearsSinceQuit_time = $i - ($age-$formerHeavySmkYearsSinceQuit); // years since quit - former heavy smoker
	$yearsSinceImmigrated_time = $i - ($age-$yearsSinceImmigrated); // years since immigrated to Canada
	
	// deal with smoking that is time dependent (for former smokers decay function)
	if ($formerLightSmk > 0) { // former light smoker
		$formerLightSmk_time =  exp(-1*($formerLightSmkYearsSinceQuit_time)/$smokeConstant); 
	}
	else $formerLightSmk_time = 0;
	
	if ($formerHeavySmk > 0) { // former heavy smoker
		$formerHeavySmk_time = exp(-1*($formerHeavySmkYearsSinceQuit_time)/$smokeConstant); 
	}
	else $formerHeavySmk_time = 0;
	
	// adjust for the proportion of former quit smokers
	$formerLightSmk_time = $formerLightSmk_time * $formerLightSmk;
	$formerHeavySmk_time = $formerHeavySmk_time * $formerHeavySmk;

	// deal with immigration that is time dependent	
	
	// initialize
	$imm15_time=0;
	$imm30_time=0;
	$imm45_time=0;

	if ($imm > 0) { // immigrant
		if ($yearsSinceImmigrated_time >=0 && $yearsSinceImmigrated_time <= 15) {
			//$imm15_time=1;
			$imm15_time=$imm;
			$imm30_time=0;
			$imm45_time=0;
		}
		else if ($yearsSinceImmigrated_time >=16 && $yearsSinceImmigrated_time <= 30) {
			$imm15_time=0;
			//$imm30_time=1;
			$imm30_time=$imm;
			$imm45_time=0;
		}
		else if ($yearsSinceImmigrated_time >=31 && $yearsSinceImmigrated_time <= 45) {
			$imm15_time=0;
			$imm30_time=0;
			//$imm45_time=1;
			$imm45_time=$imm;
		}
		else {
			$imm15_time=0;
			$imm30_time=0;
			$imm45_time=0;
		}
	}
	
	
	
	//$k = $base + $i*$age_p1 + $age2*$age_p2;
	// $prob_death = $base_hazard * exp($k);	
	// add time dependent components to the score
	$score1_withtime = $score1 + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta + $formerHeavySmk_time*$heavySmkBeta 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;
	

	$score1_smoke_withtime = $score1_smoke + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta*0 + $formerHeavySmk_time*$heavySmkBeta*0 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;

	$score1_alcohol_withtime = $score1_alcohol + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta + $formerHeavySmk_time*$heavySmkBeta 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;
	
	$score1_diet_withtime = $score1_diet + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta + $formerHeavySmk_time*$heavySmkBeta 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;		

	$score1_PA_withtime = $score1_PA + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta + $formerHeavySmk_time*$heavySmkBeta 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;		

	$score1_pollution_withtime = $score1_pollution + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta + $formerHeavySmk_time*$heavySmkBeta 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;		
	
	$score1_deprivation_withtime = $score1_deprivation + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta + $formerHeavySmk_time*$heavySmkBeta 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;	
	
	$score1_all_withtime = $score1_all + 
						$i*$ageBeta + $age2*$ageSplineBeta + 
						$formerLightSmk_time*$lightSmkBeta*0 + $formerHeavySmk_time*$heavySmkBeta*0 
					+ $imm15_time*$imm15Beta + $imm30_time*$imm30Beta + $imm45_time*$imm45Beta
					+ $cancer*$cancAgeBeta*$i + $diabetes*$diabAgeBeta*$i;		
		
		
	// Helica-H
	$scoreH_logistic_withtime = $scoreH_logistic + $Agecat40to49*$Logis_Agecat40to49Beta + $Agecat50to54*$Logis_Agecat50to54Beta  + 
			$Agecat55to59*$Logis_Agecat55to59Beta + $Agecat60to64*$Logis_Agecat60to64Beta + $Agecat65to69*$Logis_Agecat65to69Beta + 
			$Agecat70to74*$Logis_Agecat70to74Beta + $Agecat75to79*$Logis_Agecat75to79Beta + $imm15_time*$Logis_Imm15Beta + 
			$imm30_time*$Logis_Imm30Beta + $imm45_time*$Logis_Imm45Beta;
			
	$scoreH_count_withtime = $scoreH_count + $Agecat40to49*$Count_Agecat40to49Beta + $Agecat50to54*$Count_Agecat50to54Beta  + 
			$Agecat55to59*$Count_Agecat55to59Beta + $Agecat60to64*$Count_Agecat60to64Beta + $Agecat65to69*$Count_Agecat65to69Beta + 
			$Agecat70to74*$Count_Agecat70to74Beta + $Agecat75to79*$Count_Agecat75to79Beta + $imm15_time*$Count_Imm15Beta + 
			$imm30_time*$Count_Imm30Beta + $imm45_time*$Count_Imm45Beta;
			
	//echo "<br /> scoreH_logistic_withtime: $scoreH_logistic_withtime , 	scoreH_count_withtime: $scoreH_count_withtime <br />";	
	
	$prob_death = $baseline * exp($score1_withtime); // for general life expectancy
	$prob_death_smoke = $baseline * exp($score1_smoke_withtime); // life years lost due to smoking
	$prob_death_alcohol = $baseline * exp($score1_alcohol_withtime); // life years lost due to alcohol
	$prob_death_diet = $baseline * exp($score1_diet_withtime); // life years lost due to diet
	$prob_death_PA = $baseline * exp($score1_PA_withtime); // life years lost due to diet
	$prob_death_pollution = $baseline * exp($score1_pollution_withtime); // life years lost due to pollution
	$prob_death_deprivation = $baseline * exp($score1_deprivation_withtime); // life years lost due to neighbourhood (deprivation)
	$prob_death_all = $baseline * exp($score1_all_withtime); // life years lost due to all risk factors
	
	
	
	/*********** for general life expectancy ************/
		
	/************** NEW CODE *****************/
	if ($prob_death > 1) {
		$prob_death = 1;
		//$prob_cdeath = $prob_alive;
	}
	
	$numDeaths = $prob_alive*$prob_death;
	if ($prob_alive >= $numDeaths) {
		$endProb = $prob_alive - $numDeaths;
	}
	else $endProb = $prob_alive;
	
	$death_weighted = $i*$numDeaths; // age standardized deaths
	$final = $final + $death_weighted;
	
	
	// Health Age, will take this from session in the results.php to find age - 2014-07-16
	if ($i == $age) {
		$_SESSION['probdeath']=$prob_death;
	}
	
	// x5-year 10-year probability of death
	/*
	if ($i == ($age + 5) ) {
		$_SESSION['prob_death5year']=round($prob_death*100,1);
	}
	*/
	if ($i == ($age + 10) ) {
		$_SESSION['prob_death10year']=round($prob_death*100,1);
	}
	
	
	// Helica-H
	$pEvent = 1 - ( 1 / (1+exp(-$scoreH_logistic_withtime)) ); // PEvent= Probability of Having One or More Bed Days= 1 - 1/(1+exp(- Logistic Score))
	$pBedDaysGivenEvent = exp($scoreH_count_withtime); // PBedDays|Event= Bed Days Given Probability of Having One or More Bed Days = exp(Count Score)
	
	$expectedBedDays = $pEvent * $pBedDaysGivenEvent; // Expected Bed Days
	
	/*
	if ($i < 80) $adjustedBedDays = $expectedBedDays*$prob_alive;
	else $adjustedBedDays = 0;
	*/
	$adjustedBedDays = $expectedBedDays*$prob_alive;
	
	$finalBedDays = $finalBedDays + $adjustedBedDays; 
	
	// Bed days in the next 10 years -- this is similar to 10-year probability of being alive
	if ($i == ($age + 10) ) {
		$_SESSION['bedDays10year']=round($finalBedDays,1);
	}
	
	/*
	echo "<br /> $i, PEvent= Probability of Having One or More Bed Days= 1 - 1/(1+exp(- Logistic Score)) = $pEvent <br />";
	echo "Logistic Score = $scoreH_logistic_withtime <br />";
	echo "PBedDays|Event= Bed Days Given Probability of Having One or More Bed Days = exp(Count Score) = $pBedDaysGivenEvent <br  />";
	echo "Count Score = $scoreH_count_withtime <br />";
	echo "<b>Total bed days: $finalBedDays </b><br />";
	*/
	
	/*
	echo "<br /> pEvent: $pEvent <br />";
	echo "<br /> pBedDaysGivenEvent: $pBedDaysGivenEvent <br />";
	
	echo "<br /> expectedBedDays: $expectedBedDays <br />";
	echo "<br /> prob_alive: $prob_alive <br />";
	echo "<br /> endProb: $endProb <br />";
	echo "<br /> adjustedBedDays: $adjustedBedDays <br />";
	echo "<br />$i - finalBedDays: $finalBedDays <br />";
	*/
	
	/*********** for general life years lost due to smoking ************/
	/*
	if ($prob_death_smoke > 1) {
		$prob_death_smoke = 1;
		$prob_cdeath_smoke = $prob_alive_smoke;
	}
	
	if ( ($i == 120 && $prob_alive_smoke > 0) || ( ($prob_alive_smoke * $prob_death_smoke) > $prob_alive_smoke) ) {$prob_cdeath_smoke = $prob_alive_smoke;}
	else $prob_cdeath_smoke = $prob_alive_smoke * $prob_death_smoke;
	
	if ($prob_cdeath_smoke < $prob_alive_smoke) {
		$prob_alive_smoke = $prob_alive_smoke - $prob_cdeath_smoke;
	}
	else $prob_alive_smoke = 0;
	
	if ($prob_alive_smoke == 0) {$prob_cdeath_smoke=0;}
	$death_weighted_smoke = $i * $prob_cdeath_smoke;
	
	$final_smoke = $final_smoke + $death_weighted_smoke;
	*/
	
	if ($prob_death_smoke > 1) {
		$prob_death_smoke = 1;
		//$prob_cdeath_smoke = $prob_alive_smoke;
	}
	
	$numDeaths_smoke = $prob_alive_smoke*$prob_death_smoke;
	if ($prob_alive_smoke >= $numDeaths_smoke) {
		$endProb_smoke = $prob_alive_smoke - $numDeaths_smoke;
	}
	else $endProb_smoke = $prob_alive_smoke;
	
	$death_weighted_smoke = $i*$numDeaths_smoke; // age standardized deaths
	$final_smoke = $final_smoke + $death_weighted_smoke;
	
	//start of next row (for i+1)
	$prob_alive_smoke = $endProb_smoke;

	
	
	
	/*********** for general life years lost due to alcohol ************/
	/*
	if ($prob_death_alcohol > 1) {
		$prob_death_alcohol = 1;
		$prob_cdeath_alcohol = $prob_alive_alcohol;
	}
	
	if ( ($i == 120 && $prob_alive_alcohol > 0) || ( ($prob_alive_alcohol * $prob_death_alcohol) > $prob_alive_alcohol) ) {$prob_cdeath_alcohol = $prob_alive_alcohol;}
	else $prob_cdeath_alcohol = $prob_alive_alcohol * $prob_death_alcohol;
	
	if ($prob_cdeath_alcohol < $prob_alive_alcohol) {
		$prob_alive_alcohol = $prob_alive_alcohol - $prob_cdeath_alcohol;
	}
	else $prob_alive_alcohol = 0;
	
	if ($prob_alive_alcohol == 0) {$prob_cdeath_alcohol=0;}
	$death_weighted_alcohol = $i * $prob_cdeath_alcohol;
	
	$final_alcohol = $final_alcohol + $death_weighted_alcohol;	
	*/
	
	
	if ($prob_death_alcohol > 1) {
		$prob_death_alcohol = 1;
		//$prob_cdeath_alcohol = $prob_alive_alcohol;
	}
	
	$numDeaths_alcohol = $prob_alive_alcohol*$prob_death_alcohol;
	if ($prob_alive_alcohol >= $numDeaths_alcohol) {
		$endProb_alcohol = $prob_alive_alcohol - $numDeaths_alcohol;
	}
	else $endProb_alcohol = $prob_alive_alcohol;
	
	$death_weighted_alcohol = $i*$numDeaths_alcohol; // age standardized deaths
	$final_alcohol = $final_alcohol + $death_weighted_alcohol;
	
	//start of next row (for i+1)
	$prob_alive_alcohol = $endProb_alcohol;
	
	
	/*********** for general life years lost due to diet ************/
	/*
	if ($prob_death_diet > 1) {
		$prob_death_diet = 1;
		$prob_cdeath_diet = $prob_alive_diet;
	}
	
	if ( ($i == 120 && $prob_alive_diet > 0) || ( ($prob_alive_diet * $prob_death_diet) > $prob_alive_diet) ) {$prob_cdeath_diet = $prob_alive_diet;}
	else $prob_cdeath_diet = $prob_alive_diet * $prob_death_diet;
	
	if ($prob_cdeath_diet < $prob_alive_diet) {
		$prob_alive_diet = $prob_alive_diet - $prob_cdeath_diet;
	}
	else $prob_alive_diet = 0;
	
	if ($prob_alive_diet == 0) {$prob_cdeath_diet=0;}
	$death_weighted_diet = $i * $prob_cdeath_diet;
	
	$final_diet = $final_diet + $death_weighted_diet;
	*/
	
	if ($prob_death_diet > 1) {
		$prob_death_diet = 1;
		//$prob_cdeath_diet = $prob_alive_diet;
	}
	
	$numDeaths_diet = $prob_alive_diet*$prob_death_diet;
	if ($prob_alive_diet >= $numDeaths_diet) {
		$endProb_diet = $prob_alive_diet - $numDeaths_diet;
	}
	else $endProb_diet = $prob_alive_diet;
	
	$death_weighted_diet = $i*$numDeaths_diet; // age standardized deaths
	$final_diet = $final_diet + $death_weighted_diet;
	
	//start of next row (for i+1)
	$prob_alive_diet = $endProb_diet;


	/*********** for general life years lost due to Physical inactivity ************/
	/*
	if ($prob_death_PA > 1) {
		$prob_death_PA = 1;
		$prob_cdeath_PA = $prob_alive_PA;
	}
	
	if ( ($i == 120 && $prob_alive_PA > 0) || ( ($prob_alive_PA * $prob_death_PA) > $prob_alive_PA) ) {$prob_cdeath_PA = $prob_alive_PA;}
	else $prob_cdeath_PA = $prob_alive_PA * $prob_death_PA;
	
	if ($prob_cdeath_PA < $prob_alive_PA) {
		$prob_alive_PA = $prob_alive_PA - $prob_cdeath_PA;
	}
	else $prob_alive_PA = 0;
	
	if ($prob_alive_PA == 0) {$prob_cdeath_PA=0;}
	$death_weighted_PA = $i * $prob_cdeath_PA;
	
	$final_PA = $final_PA + $death_weighted_PA;
	*/

	if ($prob_death_PA > 1) {
		$prob_death_PA = 1;
		//$prob_cdeath_PA = $prob_alive_PA;
	}
	
	$numDeaths_PA = $prob_alive_PA*$prob_death_PA;
	if ($prob_alive_PA >= $numDeaths_PA) {
		$endProb_PA = $prob_alive_PA - $numDeaths_PA;
	}
	else $endProb_PA = $prob_alive_PA;
	
	$death_weighted_PA = $i*$numDeaths_PA; // age standardized deaths
	$final_PA = $final_PA + $death_weighted_PA;
	
	//start of next row (for i+1)
	$prob_alive_PA = $endProb_PA;
	
	
	/*********** for general life years lost due to air pollution ************/
	if ($prob_death_pollution > 1) {
		$prob_death_pollution = 1;
		//$prob_cdeath_pollution = $prob_alive_pollution;
	}
	
	$numDeaths_pollution = $prob_alive_pollution*$prob_death_pollution;
	if ($prob_alive_pollution >= $numDeaths_pollution) {
		$endProb_pollution = $prob_alive_pollution - $numDeaths_pollution;
	}
	else $endProb_pollution = $prob_alive_pollution;
	
	$death_weighted_pollution = $i*$numDeaths_pollution; // age standardized deaths
	$final_pollution = $final_pollution + $death_weighted_pollution;
	
	//start of next row (for i+1)
	$prob_alive_pollution = $endProb_pollution;
	
	
	/*********** for general life years lost due to neighbourhood (deprivation) ************/
	if ($prob_death_deprivation > 1) {
		$prob_death_deprivation = 1;
		//$prob_cdeath_deprivation = $prob_alive_deprivation;
	}
	
	$numDeaths_deprivation = $prob_alive_deprivation*$prob_death_deprivation;
	if ($prob_alive_deprivation >= $numDeaths_deprivation) {
		$endProb_deprivation = $prob_alive_deprivation - $numDeaths_deprivation;
	}
	else $endProb_deprivation = $prob_alive_deprivation;
	
	$death_weighted_deprivation = $i*$numDeaths_deprivation; // age standardized deaths
	$final_deprivation = $final_deprivation + $death_weighted_deprivation;
	
	//start of next row (for i+1)
	$prob_alive_deprivation = $endProb_deprivation;
	
	
	/*********** for general life years lost due to all risk factors ************/
	/*
	if ($prob_death_all > 1) {
		$prob_death_all = 1;
		$prob_cdeath_all = $prob_alive_all;
	}
	
	if ( ($i == 120 && $prob_alive_all > 0) || ( ($prob_alive_all * $prob_death_all) > $prob_alive_all) ) {$prob_cdeath_all = $prob_alive_all;}
	else $prob_cdeath_all = $prob_alive_all * $prob_death_all;
	
	if ($prob_cdeath_all < $prob_alive_all) {
		$prob_alive_all = $prob_alive_all - $prob_cdeath_all;
	}
	else $prob_alive_all = 0;
	
	if ($prob_alive_all == 0) {$prob_cdeath_all=0;}
	$death_weighted_all = $i * $prob_cdeath_all;
	
	$final_all = $final_all + $death_weighted_all;	
	*/
	
	if ($prob_death_all > 1) {
		$prob_death_all = 1;
		//$prob_cdeath_all = $prob_alive_all;
	}
	
	$numDeaths_all = $prob_alive_all*$prob_death_all;
	if ($prob_alive_all >= $numDeaths_all) {
		$endProb_all = $prob_alive_all - $numDeaths_all;
	}
	else $endProb_all = $prob_alive_all;
	
	$death_weighted_all = $i*$numDeaths_all; // age standardized deaths
	$final_all = $final_all + $death_weighted_all;
	
	//start of next row (for i+1)
	$prob_alive_all = $endProb_all;
	

	// probability of living to 75 and future date / event	
	//living to 75
	if ($age >= 75) $prob_liveto75=1; // already made it
	else if ($i == 75) {
			$prob_liveto75 = $prob_alive;
	}
		
	
	//living to year specified
	if ($event_year != NULL) {
		if ($thisyear >= $event_year) $prob_see_event=1; // already made it or pass it
		else if ($event_year >= $thisyear && $i == $futureyears) {
			//if ($i == $futureyears ) {
				$prob_see_event = $prob_alive;
			//}
		}
		
	}
	
	//echo "...prob alive: $prob_alive ...";
	$prob_alive = $endProb;

	$i++;
	
} //end for 20 to 120 loop


/* largest modifiable risk factor */
//$largest_RF = max($final_smoke, $final_alcohol, $final_diet, $final_PA);

// put values in array so to sort by values and label by keys (to know which is the largest risk factor)
$largest_RF_array = array();
$largest_RF_array[1] = $final_smoke;
$largest_RF_array[2] = $final_alcohol;
$largest_RF_array[3] = $final_diet;
$largest_RF_array[4] = $final_PA;
$largest_RF_array[5] = $final_pollution;
$largest_RF_array[6] = $final_deprivation;

//sort array
arsort($largest_RF_array);

$largest_RF = array_shift(array_keys($largest_RF_array));

// no risk factors
if ( ($final_smoke-$final) < 0.1 && ($final_alcohol-$final) < 0.1 && ($final_diet-$final) < 0.1 && ($final_PA-$final) < 0.1 && ($final_pollution-$final) < 0.1 && ($final_deprivation-$final) < 0.1) {
	$largest_RF=0;
}


$final_le=$calc_le;
$final_hosp=$calc_beddays;
$final_leage=round($final,1);
$final_beddays=round($finalBedDays,1);
$final_largestrisk=$largest_RF;
$final_yldt_smoke = round(($final_smoke - $final),1);
$final_yldt_alcohol = round(($final_alcohol - $final),1);

//$final_yldt_diet = round(($final_diet - $final),1);
// if diet is >= 8 then years lost due to diet should be 0
if ($diet >=8) $final_yldt_diet=0; 
else $final_yldt_diet = round(($final_diet - $final),1);

// $final_yldt_PA = round(($final_PA - $final),1);
// if PA >= 3 then years lost due to PA should be 0
if ($PA >= 3) $final_yldt_PA=0;
else $final_yldt_PA = round(($final_PA - $final),1);


//$pollutionTest = round(($final_pollution - $final),1); //use only for testing
//$_SESSION['pollutionTest']=$pollutionTest;

if (($final_pollution - $final) < 0) $final_yldt_pollution=0; // make sure no loss of life years due to pollution!! -- since using averages could have better ones so deal with them
else $final_yldt_pollution = round(($final_pollution - $final),1);

$final_yldt_deprivation = round(($final_deprivation - $final),1);

$final_prob_liveto75 = round($prob_liveto75*100,1);
$final_eventyear=$event_year;
$final_eventprob=round($prob_see_event*100,1);
$final_eventtitle=$eventtitle;

if ($event_year !='') $final_lts=1;
else $final_lts=0;

$_SESSION['le']=$final_le;
$_SESSION['hosp']=$final_hosp;
$_SESSION['lts']=$final_lts;
$_SESSION['leage']=$final_leage;
$_SESSION['beddays']=$final_beddays;
$_SESSION['largestrisk']=$final_largestrisk;
$_SESSION['yldtsmoke']=$final_yldt_smoke;
$_SESSION['yldtalc']=$final_yldt_alcohol;
$_SESSION['yldtdiet']=$final_yldt_diet;
$_SESSION['yldtpa']=$final_yldt_PA;
$_SESSION['yldtpollution']=$final_yldt_pollution;
$_SESSION['yldtdeprivation']=$final_yldt_deprivation;

$_SESSION['prob_liveto75']=$final_prob_liveto75;
$_SESSION['eventyear']=$final_eventyear;
$_SESSION['eventprob']=$final_eventprob;
$_SESSION['eventtitle']=$final_eventtitle;


// air pollution -- testing
$_SESSION['PM25MeanCenteredExposure']=$PM25MeanCenteredExposure;
$_SESSION['O3MeanCenteredExposure']=$O3MeanCenteredExposure;
$_SESSION['NO2MeanCenteredExposure']=$NO2MeanCenteredExposure;
$_SESSION['PM25FifthPerCenteredExposure']=$PM25FifthPerCenteredExposure;
$_SESSION['O3FifthPerCenteredExposure']=$O3FifthPerCenteredExposure;
$_SESSION['NO2FifthPerCenteredExposure']=$NO2FifthPerCenteredExposure;
$_SESSION['PM25Beta']=$PM25Beta;
$_SESSION['O3Beta']=$O3Beta;
$_SESSION['NO2Beta']=$NO2Beta;



// SPoRT - Stroke

//for TESTING
$_SESSION['stroke_score']=$stroke_score;

// Stroke - prep for calculations	
$ageSplineValue = 0;
if ($age < 65) $ageSplineValue=0; 
else $ageSplineValue = $age - 65;

//$hypertensionValue = 0; // we don't have this on web so using 0
if ($_POST["htension1"] == 1) $hypertensionValue=1; else $hypertensionValue=0;
if ($_POST["diabetes1"] == 1) $diabetesValue=1; else $diabetesValue=0;
if ($_POST["hDisease1"] == 1) $hDiseaseValue=1; else $hDiseaseValue=0;
$surveycycle1Value = 1;
$surveycycle2Value = 0;


$sumOfProducts = ($age-$sport_ageMean)*$sport_ageBeta + ($ageSplineValue-$sport_AgeSplineMean)*$sport_AgeSplineBeta + ($stroke_score-$sport_scaleMean)*$sport_scaleBeta 
					+ ($hypertensionValue-$sport_hypertension1Mean)*$sport_hypertension1Beta + ($diabetesValue-$sport_diabetesMean)*$sport_diabetesBeta
					+ ($hDiseaseValue-$sport_heartDiseaseMean)*$sport_heartDiseaseBeta + ($surveycycle1Value-$sport_surveycycle1Mean)*$sport_surveycycle1Beta
					+ ($surveycycle2Value-$sport_surveycycle2Mean)*$sport_surveycycle2Beta;

$stroke_risk = 1 - exp(-exp($sumOfProducts)*$stroke_lkupValue);

$_SESSION['stroke_risk']=$stroke_risk;


/*
header( "Location: /life/results.php?le=$final_le&hosp=$final_hosp&lts=$final_lts&leage=$final_leage&beddays=$final_beddays&largestrisk=$final_largestrisk&yldtsmoke=$final_yldt_smoke&yldtalc=$final_yldt_alcohol&yldtdiet=$final_yldt_diet&yldtpa=$final_yldt_PA&eventyear=$final_eventyear&eventprob=$final_eventprob&eventtitle=$final_eventtitle" ) ;
*/

//TODO kasim change to results.php uncomment
header( "Location: /life/resultsmport.php" ) ;

} // end function calculate











if($_SERVER['REQUEST_METHOD'] == "POST")
{	
// server side validation
$error = "";
if ( !isset($_POST['sex']) || !($_POST['sex'] == '1' || $_POST['sex'] == '2') ) {
	$error = "Please select your gender";
}
if (!isset($_POST['age']) || $_POST['age'] < 20 or $_POST['age'] > 79) {
	$error = $error . "<br />" . "The age must be a number between 20 and 79";
}

if ($error != "") {
	//echo "<span id=\"error\">" . $error . "</span>";
}
else {	
	//Create agesexid based on age and sex
	$agesexid = 9; // initialize to 40-49 year old female
	if ($sex_s == 1) { // male
		if ($age >= 20 && $age <= 29) $agesexid=1;
		else if ($age >= 30 && $age <= 39) $agesexid=2;
		else if ($age >= 40 && $age <= 49) $agesexid=3;
		else if ($age >= 50 && $age <= 59) $agesexid=4;
		else if ($age >= 60 && $age <= 69) $agesexid=5;
		else if ($age >= 70 && $age <= 79) $agesexid=6;
	}
	else if ($sex_s == 2) { // female
		if ($age >= 20 && $age <= 29) $agesexid=7;
		else if ($age >= 30 && $age <= 39) $agesexid=8;
		else if ($age >= 40 && $age <= 49) $agesexid=9;
		else if ($age >= 50 && $age <= 59) $agesexid=10;
		else if ($age >= 60 && $age <= 69) $agesexid=11;
		else if ($age >= 70 && $age <= 79) $agesexid=12;
	}
	
	//1st initialize (if all blank don't query database):
	//set flag if any one of the variables is missing - to access db
	
	//if ($immobile1 == 888 || $pCode1 == 888 || $imm1 == 888 || $imm2==888 || $str1==888 || $bmi == 888 || $hIncome1 == 888 || $diabetes1 == 888 //|| $cancer1 == 888 || $hDisease1 == 888 || $stroke1 == 888) {
	//	$queryDB=1;
	//}
		
	// initialize variables that are going into the algorithm
	//$sex=$sex; $age=$age;
	$currentLightSmk=0; $currentHeavySmk=0; $formerLightSmk=0; 
	$formerHeavySmk=0; $formerLightSmkYearsSinceQuit=0; $formerHeavySmkYearsSinceQuit=0; 
	/*$alcH=0; $alcR=0; $alcL=0; $alcN=0; */
	$alcH=0; $alcM=0;
	$diet=0; $PA=0; $imm=0; $yearsSinceImmigrated=0; 
	$houseIncL=0; $houseIncM=0; $hDisease=0; $stroke=0; $cancer=0; $diabetes=0; 
	$stressHigh=0; $bmiHigh=0; $bmiAbove35=0; $fragile=0; $restricted=0; $depMod=0; $depHigh=0; $LHINInjuryRate=0;
	//$event_year;
	
	
	/*############################# CASES WHERE ALL INPUTS ARE PROVIDED - NO NEED TO GO TO DB ############################# */
	// query is blank so all inputs provided and no need to query db (i.e. no 888 (or 999 that were provided in place of 888))
	//if ($query_final == "select a.agesexid, a.sex, a.agecat, 1 from agesex_lkup as a where a.agesexid=$agesexid;") {
		//echo "NOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO DBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB";

		// smoking
		if ($smk1 == 1) { // current heavy smoker
			$currentLightSmk=0; $currentHeavySmk=1; $formerLightSmk=0; $formerHeavySmk=0; 
				$formerLightSmkYearsSinceQuit=0; $formerHeavySmkYearsSinceQuit=0; 
		}
		else if ($smk1 == 2) { // current light smoker
			$currentLightSmk=1; $currentHeavySmk=0; $formerLightSmk=0; $formerHeavySmk=0; 
				$formerLightSmkYearsSinceQuit=0; $formerHeavySmkYearsSinceQuit=0; 
		}
		else if ($smk1 == 5) { // non-smoker
			$currentLightSmk=0; $currentHeavySmk=0; $formerLightSmk=0; $formerHeavySmk=0; 
				$formerLightSmkYearsSinceQuit=0; $formerHeavySmkYearsSinceQuit=0; 
		}
		else if ($smk1 == 3 || $smk1 == 4) { // former smoker
			$currentLightSmk=0; $currentHeavySmk=0; 
			if ($smk1 == 3) { //former heavy smoker
				$formerLightSmk=0; $formerHeavySmk=1; 
					$formerLightSmkYearsSinceQuit=0; $formerHeavySmkYearsSinceQuit=$smk2; 
			}
			if ($smk1 == 4) { //former light smoker
				$formerLightSmk=1; $formerHeavySmk=0; 
					$formerLightSmkYearsSinceQuit=$smk2; $formerHeavySmkYearsSinceQuit=0; 
			}
			
		} // end former smoker
		
		
		// alcohol 
		// $alcH, $alcR, $alcL, $alcN
		
		if ($alc1==3) {
			//$alcH=0; $alcR=0; $alcL=0; $alcN=1;
			$alcH=0; $alcM=0;
		}

		else {
			if ($sex_s==1) { // male		
				if ($alc1==1) { 

					if ($alc2 > 21) {$alcH=1; $alcM=0;}
					else if ($alc2 >= 4) {$alcH=0; $alcM=1;}
					else if ($alc2 >= 0) {$alcH=0; $alcM=0;}
					
					//binge 
					//if ($alc3 >= 5 || $alc4==1) {$alcH=1; $alcM=0;} //we removed alc3 so comment out
					if ($alc4==1) {$alcH=1; $alcM=0;}
				}
				else if ($alc1==2) {
					if ($alc2 > 21) {$alcH=1; $alcM=0;}
					else if ($alc2 >= 4) {$alcH=0; $alcM=1;}
					else if ($alc2 >= 0) {$alcH=0; $alcM=0;}
					
					//binge 
					//if ($alc3 >= 5) {$alcH=1; $alcM=0;} // we removed alc3 so comment out
				}
				
				//if ($alc3 > 4 || $alc4==5) {$alcH=1; $alcR=0; $alcL=0; $alcN=0;}
				//if ($alc3 > 4) {$alcH=1; $alcR=0; $alcL=0; $alcN=0;}
				
			}
				
			if ($sex_s==2) { // female		
				if ($alc1==1) { 

					if ($alc2 > 14) {$alcH=1; $alcM=0;}
					else if ($alc2 >= 3) {$alcH=0; $alcM=1;}
					else if ($alc2 >= 0) {$alcH=0; $alcM=0;}
					
					//binge 
					//if ($alc3 >= 5 || $alc4==1) {$alcH=1; $alcM=0;} // removed alc3 so comment out
					if ($alc4==1) {$alcH=1; $alcM=0;}
				}
				else if ($alc1==2) {
					if ($alc2 > 14) {$alcH=1; $alcM=0;}
					else if ($alc2 >= 3) {$alcH=0; $alcM=1;}
					else if ($alc2 >= 0) {$alcH=0; $alcM=0;}
					
					//binge 
					//if ($alc3 >= 5) {$alcH=1; $alcM=0;} // removed alc3 so comment out
				}
				
				//if ($alc3 > 4 || $alc4==5) {$alcH=1; $alcR=0; $alcL=0; $alcN=0;}
				//if ($alc3 > 4) {$alcH=1; $alcR=0; $alcL=0; $alcN=0;}
				
			}
		}
		
		

		// DIET
		// special cases
		//if ($diet1 <= 1) $diet1juiceOver=0;
		//else $diet1juiceOver=$diet1-1;
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
		
		/*
		$fvtotal=$diet2 + $diet3 + $diet4 + $diet5 + $diet6;
		//if ($fvtotal > 8) $fvtotal=8;
		$fvtotal=$fvtotal/7; //new code to convert for daily
		if ($fvtotal > 8) $fvtotal=8;
		
		// deduct 2 points each for high potato consumption, no carrot and high juice		
		//$crudeDietScore = $fvtotal + (-2*$diet4potHigh) + (-2*$diet5noCarrot) + (-2*$diet1juiceOver) + 2;
		
		//$crudeDietScore = $fvtotal + (-2*$diet4potHigh) + (-2*$diet5noCarrot) + (-2*$diet1juiceOver) + 2;
		
		// final diet score
		if ($crudeDietScore <= 0) $diet=0; 
		else $diet=$crudeDietScore;
		*/
		
		//echo "<br /> fvtotal: $fvtotal <br />";
		//echo "<br /> diet: $diet <br />";
				
		
		// PA - Physical Activity
		//$PA = $PA1;
		$PA = ($pa1*9.5 + $pa2*5.5 + $pa3*3.5) / 7;
		//$PA = ($pa1*9.5 + $pa2*5.5 + $pa3*3.5) / 30;
		$_SESSION['pa_METS']=$PA; // put in session for report page
		
		if ($PA > 10) $PA = 10;
		
		//echo "PA before send: $PA <br />";
		
		// Stress
		if ($str1 == 2) {
			$stressHigh = 1;
		}
		
		//BMI -> Math.round((wt/(h*h))*100)/100;
		$bmi = round(($weight1/($height1/100*$height1/100))*100)/100;
		
		//if ($bmi > 30) $bmiHigh = 1; // old
		if ($bmi > 35) $bmiHigh = 1;
		
		//if ($bmi > 35) $bmiAbove35 = 1; // old binary version
		if ($bmi > 35) {
			$bmiAbove35 = $bmi - 35;
		}
		
		// country -- put in session so in report page display results only for Canadians
		if ($country1 == 1) {$_SESSION['country']=1;}
		else {$_SESSION['country']=0;}
		
		// Immigration
		if ($imm1 == 2) { // non immigrant
			$imm = 0;
			$yearsSinceImmigrated = 0;
		}
		else if ($imm1 == 1) { // immigrant
			$imm = 1;
			$yearsSinceImmigrated = $imm2;
		}
		
		// household income -- replaced with education
		/*
		if ($hIncome1 == 1) { // < 30k
			$houseIncL=1; $houseIncM=0; 
		}
		else if ($hIncome1 == 2) { // 30k-79k
			$houseIncL=0; $houseIncM=1; 
		}
		else if ($hIncome1 == 3) { // 80k+
			$houseIncL=0; $houseIncM=0; 
		}
		*/
		if ($education1 == 1) { // Less than high school
			$eduNoHSGrad=1; $eduHSGrad=0; 
		}
		else if ($education1 == 2) { // High school or some post-secondary education
			$eduNoHSGrad=0; $eduHSGrad=1;  
		}
		else if ($education1 == 3) { // Post-secondary (community college or university)
			$eduNoHSGrad=0; $eduHSGrad=0;  
		}
		
		
		// diabetes
		if ($diabetes1 == 1) { // has diabetes
			$diabetes=1;
		}
		/*
		else if ($diabetes1 == 2) { // no diabetes
			$diabetes=0;
		}
		*/
		
		// cancer
		if ($cancer1 == 1) { // has cancer
			$cancer=1;
		}
		/*
		else if ($cancer1 == 2) { // no cancer
			$cancer=0;
		}
		*/
		
		// heart disease
		if ($hDisease1 == 1) { // has heart disease
			$hDisease=1;
		}
		/*
		else if ($hDisease1 == 2) { // no heart disease
			$hDisease=0;
		}
		*/
		
		// stroke
		if ($stroke1 == 1) { // has stroke
			$stroke=1;
		}
		/*
		else if ($stroke1 == 2) { // no stroke
			$stroke=0;
		}
		*/
		
		// hypertension
		if ($htension1 == 1) { // has hypertension
			$hypertension=1;
		}
		
		/*
		if ($immobile1 == 1) {
			$fragile=1;
		}
		if ($immobile1 == 2) {
			$restricted=1;
		}
		*/
		if ($immobile1 == 1) {
			$fragile=1;
		}
		if ($immobile2 == 1) {
			$restricted=1;
		}
		
		//Postal code stuff - dep index etc
		$postal2 = str_replace (" ", "", $pCode1);
		$postal2 = strtoupper($postal2);

		/* define province */
		$firstpostal = substr($postal2,0,1);
		 if ($firstpostal=='A') $prov='NL';
		 else if ($firstpostal=='B') $prov='NS';
		 else if ($firstpostal=='C') $prov='PE';
		 else if ($firstpostal=='E') $prov='NB';
		 else if ($firstpostal=='G' || $firstpostal=='H' || $firstpostal=='J') $prov='QC';
		 else if ($firstpostal=='K' || $firstpostal=='L' || $firstpostal=='M' || $firstpostal=='N' || $firstpostal=='P') $prov='ON';
		 else if ($firstpostal=='R') $prov='MB';
		 else if ($firstpostal=='S') $prov='SK';
		 else if ($firstpostal=='T') $prov='AB';
		 else if ($firstpostal=='V') $prov='BC';
		 else if ($firstpostal=='X') $prov='NT';
		 else if ($firstpostal=='Y') $prov='YT';
		 else $prov='NA';


		// initialize
		$injury = 0; 
		$rural = 0; 
		$depindex = 0; 
		$lhin = 0;
		
		// pollution
		$PM25 = 0;
		$O3 = 0;
		$NO2 = 0;
		

		// database
		//include 'riskconfig.php';
		if ($pCode1 !="" && $country1==1) {
			
			//$_SESSION['pCodeProvided'] = 1;
			
			//$connect = @mysql_connect ($host, $user, $pass);
			if (!$connect) { 
				$injury = 0; 
				$rural = 0; 
				$depindex = 0; 
				$lhin = 0; 
			} 

			else {
				// select db
				//$db_selected = mysql_select_db($database,$connect);
				if (!$db_selected) {
					$injury = 0; 
					$rural = 0; 
					$depindex = 0; 
					$lhin = 0;
				}

				else {
					//$query = "SELECT * FROM `postal_lookup_v2` WHERE `postal` = '" . mysql_real_escape_string ( $postal2 ) . "'";
					$query = "SELECT injury, rural, depindex, lhin, PM25, O3, NO2  FROM `postal_lookup_v3` WHERE `postal` = '" . mysql_real_escape_string ( $postal2 ) . "'";
					 
					$result = mysql_query($query) or die(mysql_error()); 
					$row = mysql_fetch_object($result);

					if(mysql_num_rows($result)==0){ 
					 $injury = 0; 
					 $rural = 0; 
					 $depindex = 0; 
					 $lhin = 0;
					 
					 $_SESSION['pCodeProvided'] = 2; // postal code not in db
					}
					else {
					 $injury = $row->injury; 
					 $rural = $row->rural; 
					 $depindex = $row->depindex; 
					 $_SESSION['depindex']=$depindex; // put in session for report page
					 
					 $lhin = $row->lhin;
					 
					 //pollution
					 $PM25 = $row->PM25; // put in session for report page
					 $O3 = $row->O3; // put in session for report page
					 $NO2 = $row->NO2; // put in session for report page
					 
					 
					 if ($PM25 == 0 || $O3 == 0 || $NO2 == 0) {
						$_SESSION['pCodeProvided'] = 3; // we don't have pollution data for this postal code
					 }
					 else {
						 $_SESSION['PM25']= $PM25; // put in session for report page
						 $_SESSION['O3']= $O3; // put in session for report page
						 $_SESSION['NO2']= $NO2; // put in session for report page
						 
						 $_SESSION['pCodeProvided'] = 1; // postal code in db and we have data
					 }
					}

				} //end else for  if (!$db_selected) {

			} //end else for  if (!$connect) { 

		} //database
		else {
			$_SESSION['pCodeProvided'] = 0;
		}
		
		if ($depindex == 2) $depMod = 1;
		if ($depindex == 3) $depHigh = 1;
		$LHINInjuryRate=$injury;
		
		
		// AIR POLLUTION
		// For missing postal code and other countries, calculate LE using mean Canadian air pollution expsoures... i.e. use mean canadian values and CENTER EXPOSURE
		// means
		$PM25mean = 8.9; //8.6;
		$O3mean = 39.6; //39.0;
		$NO2mean = 11.6; //10.4;

		
		// x5th percentiles as references -- actually 25th percentiles (were 5th before)
		$PM25FifthPercentile = 6.0; // 3.9; // actually 25th percentiles (were 5th before)
		$O3FifthPercentile = 34.3; // 30.1; // actually 25th percentiles (were 5th before)
		$NO2FifthPercentile = 6.0; //3.5; // actually 25th percentiles (were 5th before)
		
		
		if ($PM25 == 0) $PM25 = $PM25mean;
		if ($O3 == 0) $O3 = $O3mean;
		if ($NO2 == 0) $NO2 = $NO2mean;
		
		// center the exposure on the mean
		$PM25MeanCenteredExposure = $PM25 - $PM25mean;
		$O3MeanCenteredExposure	= $O3 - $O3mean;
		$NO2MeanCenteredExposure = $NO2 - $NO2mean;
		
		// center the 5th percnetile exposures (the references)
		$PM25FifthPerCenteredExposure = $PM25FifthPercentile - $PM25mean; //$PM25 - $PM25FifthPercentile;
		$O3FifthPerCenteredExposure	= $O3FifthPercentile - $O3mean; //$O3 - $O3FifthPercentile;
		$NO2FifthPerCenteredExposure = $NO2FifthPercentile - $NO2mean; //$NO2 - $NO2FifthPercentile;
	
	//} // end if statement for not going to db as have all the inputs



	/*############################# CASES WHERE SOME INPUTS ARE MISSING - NEED TO GO TO DB GRAB AVGS ############################# */
	//there was at least one non responses so go to database to grab averages -- will always go to db since need at least cancer lookup
	//if ($query_final != "select a.agesexid, a.sex, a.agecat, 1 from agesex_lkup as a where a.agesexid=$agesexid;") {
	//if ($queryDB != 1) {
		//echo "DBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB";
		// connect to database and grab latest data 
		//include 'riskconfig.php';	
		//$connect = @mysql_connect ($host, $user, $pass);
		//$db_selected = mysql_select($database,$connect);
		if ($connect && $db_selected) { // go and grab averages from db
			/* To fix the following issue/catch added the following two lines:
			#1104 - The SELECT would examine more than MAX_JOIN_SIZE rows; check your WHERE and use SET SQL_BIG_SELECTS=1 or 
			SET MAX_JOIN_SIZE=# if the SELECT is okay
			 */
			$options = "SET SESSION SQL_BIG_SELECTS = 1;"; // this and below line to bypass shared hosting setting on long query
			$setoptions = mysql_query($options) or die(mysql_error()); // this and below line to bypass shared hosting setting on long query
			
			$query_final = "select * from helica_h_missing_lkup_v2 as a where a.agesexid=$agesexid;";
			$result = mysql_query($query_final) or die(mysql_error()); 
			$row = mysql_fetch_object($result);
	
			// create error flag for not being able to fetch data
			if (mysql_num_rows($result)==0) $fetch_error=1; 
			else $fetch_error=0;

			/*
			if (!mysql_num_rows($result)==0) { 
				$food01 = $row->food01content;
				$food02 = $row->food02content;
				echo "The food1: $food01 <br />";
				echo "The food2: $food02 <br />";
			}
			*/
		} // end grab averages from db
		
		//$agecat = $row->agecat;
		//$currentLightSmk_lu = $row->currentLightSmk_lu;
		//$formerLightSmk_lu = $row->formerLightSmk_lu;
		//echo "--- agecat: $agecat and <br /> currentLightSmk: $currentLightSmk_lu <br /> formerLightSmk: $formerLightSmk_lu"; 
		
		
		
		// DNYNAMICALLY RETRIEVE or SET VALUES FOR SMOKING 
		if ($smk1 == '') {
			$currentLightSmk=0; 
			$currentHeavySmk=1; 
			$formerLightSmk=0; 
			$formerHeavySmk=0; 
			$formerLightSmkYearsSinceQuit=0; 
			$formerHeavySmkYearsSinceQuit=0; 	
		}
		else if ($smk1 == 3 && $smk2=='') {
			$currentLightSmk=0;
			$currentHeavySmk=0;
			$formerLightSmk=0;
			$formerHeavySmk=1;
			$formerLightSmkYearsSinceQuit=0;
			$formerHeavySmkYearsSinceQuit=1; 
		}
		else if ($smk1 == 4 && $smk2=='') {
			$currentLightSmk=0;
			$currentHeavySmk=0;
			$formerLightSmk=1;
			$formerHeavySmk=0;
			$formerLightSmkYearsSinceQuit=1; 
			$formerHeavySmkYearsSinceQuit=0;
		}
		
		// DNYNAMICALLY RETRIEVE OR SET VALUES FOR ALCOHOL
		if ($alc1 == '') {
			$alcH=1; 
			$alcR=0; 
			$alcL=0; 
			$alcN=0;
		}
		
		
		// DNYNAMICALLY RETRIEVE OR SET VALUES FOR DIET
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
		else $diet=$crudeDietScore;

		if ($pa1 == '' && $pa2 == '' && $pa3 == '' ) {
			//$PA = $row->PA_lu; 
			$PA = 0; 
		}
		
		// Stress
		if ($str1 == '') {
			$stressHigh = 1;
		}
		
		if ($weight1 == '' || $weight1 == 0 || $height1 == '' || $height1 == 0) {
			$bmiHigh=$row->BMIHighHosp_lu;
			$bmiAbove35=$row->BMIHighMortality_lu; //KASIM: done --We need to replace lookup var once Richard has the lookup value
		}
		
		// dynamically read inputs
		if ($imm1 == '') {
			$imm=$row->ImmStatus_lu; 
			//$yearsSinceImmigrated=$row->ImmYear_lu;  
			$yearsSinceImmigrated=0; //since issues with blank and recalculate repopulated with 0... so just set to 0 instead of lookup
		}
		else if ($imm1 == 1 && $imm2=='') {
			$imm=1; 
			//$yearsSinceImmigrated=$row->ImmYear_lu; 
			$yearsSinceImmigrated=0; //since issues with blank and recalculate repopulated with 0... so just set to 0 instead of lookup
		}
		
		// dynamically read inputs
		/*
		if ($hIncome1 == '') {
			$houseIncL = $row->HIncLow_lu;
			$houseIncM = $row->HIncMod_lu;
		}
		*/
		if ($education1 == '') {
			$eduNoHSGrad = $row->EduLow_lu; //KASIM: done --need to replace income lookup with education once Richard makes it
			$eduHSGrad = $row->EduMod_lu; //KASIM: done --need to replace income lookup with education once Richard makes it
		}
		
		if ($diabetes1 == '') {
			$diabetes = $row->Diabetes_lu;
		}
		
		if ($cancer1 == '') {
			$cancer = $row->Cancer_lu;
		}
	
		if ($hDisease1 == '') {
			$hDisease = $row->Hdisease_lu;
		}
		
		if ($stroke1 == '') {
			$stroke = $row->Stroke_lu;
		}
		
		/*
		if ($immobile1 == '') {
			$fragile=$row->Fragile_lu;
			//$restricted=$row->Restricted_lu;
		}
		if ($immobile2 == '') {
			//$fragile=$row->Fragile_lu;
			$restricted=$row->Restricted_lu;
		}
		*/
		
		if ($immobile1 == '' && $immobile2 == '') {
			$fragile=$row->BothMobilityQNotAns_Fragile_lu;
			$restricted=$row->BothMobilityQNotAns_Restricted_lu;
		}
		if ($immobile1 != '' && $immobile2 == '') {
			$fragile=$row->SecondMobilityQNotAns_Fragile_lu;
			$restricted=$row->SecondMobilityQNotAns_Restricted_lu;
		}
		
		//Postal code stuff - dep index etc
		if ($pCode1 == '') {
			$depMod = $row->DepMod_lu;
			$depHigh = $row->DepHigh_lu;
			$LHINInjuryRate=0;
			//echo "missing postal, depMod: $depMod , depHigh: $depHigh <br />";
			
		}
		
	//} //end else -- i.e. the else to start going to db and grab averages -- comment out as always going to db for at least cancer value
	
	
	// SPoRT -- Stroke
	$stroke_score = 0;
	
	if ($sex_s==1) { // male
		//smoking
		if ($smk1 == 1)  $stroke_score = $stroke_score + 3; // current heavy smoker
		else if ($smk1 == 2)  $stroke_score = $stroke_score + 2; // current heavy smoker
		else if ($smk1 == 3 || $smk1 == 4) $stroke_score = $stroke_score + 1; // former smoker
		else if ($smk1 == 5) $stroke_score = $stroke_score + 0; // non-smoker
		
		//alcohol
		if ($alc1 == 1 && $alc2 > 21 && $alc3 == 1) $stroke_score = $stroke_score + 1; // Heavy drinker //Note: it might be an OR for alc2 and alc3
		else if ($alc1 == 1 &&  $alc2 >= 5 && $alc2 <=21) $stroke_score = $stroke_score + 0; // Moderate Drinker
		else if ($alc1 == 1 &&  $alc2 > 0 && $alc2 <=4) $stroke_score = $stroke_score + 0; // Light Drinker
		else if ($alc1 == 2) $stroke_score = $stroke_score + 0; //Occasional Drinker
		else if ($alc1 == 3) $stroke_score = $stroke_score + 1; //Non Drinker
		
		//diet
		/*
		if (7*($diet5 + $diet6) < 7) $stroke_score = $stroke_score + 2; //Poor Diet
		else if  (7*($diet5 + $diet6) >= 7 && 7*($diet5 + $diet6) < 14) $stroke_score = $stroke_score + 1; //Fair  Diet:
		else if (7*($diet5 + $diet6) >= 14) $stroke_score = $stroke_score +	0; //Adequate diet:
		*/
		if (($diet5 + $diet6) < 7) $stroke_score = $stroke_score + 2; //Poor Diet
		else if (($diet5 + $diet6) >= 7 && ($diet5 + $diet6) < 14) $stroke_score = $stroke_score + 1; //Fair  Diet:
		else if (($diet5 + $diet6) >= 14) $stroke_score = $stroke_score +	0; //Adequate diet:
		
		// PA
		/*
		if ($pa1 < 1.5)  $stroke_score = $stroke_score + 2;
		else if ($pa1 >= 1.5 && $pa1 < 3)  $stroke_score = $stroke_score + 1;
		else if ($pa1 >= 3) $stroke_score = $stroke_score + 0;
		*/
		if ($PA < 1.5)  $stroke_score = $stroke_score + 2;
		else if ($PA >= 1.5 && $PA < 3)  $stroke_score = $stroke_score + 1;
		else if ($PA >= 3) $stroke_score = $stroke_score + 0;

		//stress
		if ($str1 == 2) $stroke_score = $stroke_score + 1;
		else if ($str1 == 1) $stroke_score = $stroke_score + 0;

	} // end male
	else if ($sex_s==2) { // female
	
		//smoking
		if ($smk1 == 1)  $stroke_score = $stroke_score + 4; // current heavy smoker
		else if ($smk1 == 2)  $stroke_score = $stroke_score + 3; // current heavy smoker
		else if ($smk1 == 3 || $smk1 == 4) $stroke_score = $stroke_score + 1; // former smoker
		else if ($smk1 == 5) $stroke_score = $stroke_score + 0; // non-smoker
		
		//alcohol
		if ($alc1 == 1 && $alc2 > 14 && $alc3 == 1) $stroke_score = $stroke_score + 2; // Heavy drinker //Note: it might be an OR for alc2 and alc3
		else if ($alc1 == 1 &&  $alc2 >= 3 && $alc2 <=14) $stroke_score = $stroke_score + 0; // Moderate Drinker
		else if ($alc1 == 1 &&  $alc2 > 0 && $alc2 <=2) $stroke_score = $stroke_score + 1; // Light Drinker
		else if ($alc1 == 2) $stroke_score = $stroke_score + 1; //Occasional Drinker
		else if ($alc1 == 3) $stroke_score = $stroke_score + 2; //Non Drinker
		
		//diet
		if (7*($diet5 + $diet6) < 7) $stroke_score = $stroke_score + 2; //Poor Diet
		else if  (7*($diet5 + $diet6) >= 7 && 7*($diet5 + $diet6) < 14) $stroke_score = $stroke_score + 1; //Fair  Diet:
		else if (7*($diet5 + $diet6) >= 14) $stroke_score = $stroke_score +	0; //Adequate diet:
		
		// PA
		if ($pa1 < 1.5)  $stroke_score = $stroke_score + 1;
		else if ($pa1 >= 1.5 && $pa1 < 3)  $stroke_score = $stroke_score + 0;
		else if ($pa1 >= 3) $stroke_score = $stroke_score + 0;

		//stress
		if ($str1 == 2) $stroke_score = $stroke_score + 2;
		else if ($str1 == 1) $stroke_score = $stroke_score + 0;
		
	} //end female
	
	//grab the lookup value for stroke
	$stroke_lkupValue = 0;
	if ($connect && $db_selected) { // go and grab averages from db		
		$query_stroke = "select * from sport_lkup as a where a.sex=$sex_s and a.age=$age_s;";
		$result_stroke = mysql_query($query_stroke) or die(mysql_error()); 
		$row_stroke = mysql_fetch_object($result_stroke);

		// create error flag for not being able to fetch data
		if (mysql_num_rows($result_stroke)==0) $fetch_error=1; 
		else $fetch_error=0;

		if (!mysql_num_rows($result_stroke)==0) { 
			$stroke_lkupValue = $row_stroke->value_lu;
		}
	} // end grab lookup value
	
	

	
		
		
	
	
	
	
	
	
	// call the calculate function
	/*
	calculate($sex, $age, $currentLightSmk, $currentHeavySmk, $formerLightSmk, 
	$formerHeavySmk, $formerLightSmkYearsSinceQuit, $formerHeavySmkYearsSinceQuit, 
	$alcH, $alcR, $alcL, $alcN, $diet, $PA, $imm, $yearsSinceImmigrated, 
	$houseIncL, $houseIncM, $hDisease, $stroke, $cancer, $diabetes, $event_year);
	*/
	
	/*
	echo "<br /> alc1: $alc1 , alc2: $alc2 , alc3: $alc3 , alc4: $alc4 <br />"; 
	echo "<br />BEFORE PASSING:: alcH: $alcH, alcR: $alcR, alcL: $alcL, alcN: $alcN, <br />";
	*/
	
	/*
	calculate($sex_s, $age_s, $currentLightSmk, $currentHeavySmk, $formerLightSmk, $formerHeavySmk, $formerLightSmkYearsSinceQuit, $formerHeavySmkYearsSinceQuit, $alcH, $alcR, $alcL, $alcN, $diet, $PA, $imm, $yearsSinceImmigrated, $eduNoHSGrad, $eduHSGrad, $hDisease, $stroke, $cancer, $diabetes, $eventdate, $bmiHigh, $stressHigh, $fragile, $restricted, $depMod, $depHigh, $LHINInjuryRate, $calc_le, $calc_beddays,  $eventtitle);
	*/
	
	calculate($sex_s, $age_s, $currentLightSmk, $currentHeavySmk, $formerLightSmk, $formerHeavySmk, $formerLightSmkYearsSinceQuit, $formerHeavySmkYearsSinceQuit, $alcH, /*$alcR, $alcL, $alcN,*/ $alcM, $diet, $PA, $imm, $yearsSinceImmigrated, $eduNoHSGrad, $eduHSGrad, $hDisease, $stroke, $hypertension, $cancer, $diabetes, $eventdate /*$event_year*/, $bmiHigh, $bmiAbove35, $stressHigh, $fragile, $restricted, $depMod, $depHigh, $LHINInjuryRate, $calc_le, $calc_beddays, $eventtitle, $PM25MeanCenteredExposure, $O3MeanCenteredExposure, $NO2MeanCenteredExposure, $PM25FifthPerCenteredExposure, $O3FifthPerCenteredExposure, $NO2FifthPerCenteredExposure, $stroke_score, $stroke_lkupValue);
		
	// insert user data into database
	// record info
	// $ip = $_SERVER['REMOTE_ADDR'];
	// 
	// yulric costs
    if($immobile1 == NULL)
        $fragility = NULL;
    elseif ($immobile1 == '1') {
        if($immobile2 == '2') {
            $fragility = '1';
        }
        else {
            $fragility = '2';
        }
    }
    else {
        $fragility = '3';
    }
    
	$calculatedCosts = calculateHospitalCost($sex_s, intval($age_s), $smk1, $alc1, $alc2, $alc4, $diet, $PA, $imm1, $education1, $str1, $household_income, $home_ownership, $marital_status, $bmi, $noDisease1, $htension1, $diabetes1, $hDisease1, $cancer_hc, $stroke1, $dementia, $fragility, $healthCostMaleBetas, $healthCostFemaleBetas, $country1);
	//echo json_encode($calculatedCosts);
	
	
	//$connect = @mysql_connect ($host, $user, $pass);

	if (!$connect) { 
	} 

	else {
	
	// select db
	//$db_selected = mysql_select_db($database,$connect);
	if (!$db_selected) {
	}

	else {
	//if ($connect && $db_selected) {
	$sessionID = $_SESSION['sessionID'];
		
			$query4insert= "INSERT INTO helica_h2 (uniqueID, sessionID, age, sex, height1, height2, height3, weight1, weight2, smk1, smk2, alc1, alc2, alc4, diet1, diet2, diet3, diet4, diet5, diet6, pa1, pa2, pa3, str1, country1, imm1, imm2, ses1, education1, diabetes1, hDisease1, stroke1, htension1, noDisease1, immobile1, immobile2, depindex, lhin, prov, eventtitle, eventdate, calc_le, calc_beddays, household_income, home_ownership, marital_status, cancer, dementia, calc_fhc)
							 VALUES ('$uniqueID', '$sessionID', '" . mysql_real_escape_string ($age_s) . "', '$sex_s', '" . mysql_real_escape_string ($height1) . "', '$height2', 	
							 '$height3', '" . mysql_real_escape_string ($weight1) . "', '" . mysql_real_escape_string ($weight2) .  "', 
							 '$smk1', '" . mysql_real_escape_string ($smk2) . "', '$alc1', '" . mysql_real_escape_string ($alc2) . "', '$alc4', '" . mysql_real_escape_string ($diet1) . "', '" . mysql_real_escape_string ($diet2) . "', '" . mysql_real_escape_string ($diet3) . "', '" . mysql_real_escape_string ($diet4) . "', '" . mysql_real_escape_string ($diet5) . "', '" . mysql_real_escape_string ($diet6) . "', '" . mysql_real_escape_string ($pa1) . "', '" . mysql_real_escape_string ($pa2) . "', '" . mysql_real_escape_string ($pa3) . "', '$str1', '$country1', '$imm1', '" . mysql_real_escape_string ($imm2) . "', '$ses1', '$education1', '$diabetes1', '$hDisease1', '$stroke1', '$htension1', '$noDisease1', '$immobile1', '$immobile2', '$depindex', '$lhin', '$prov', '" . mysql_real_escape_string ($eventtitle) . "', '" . mysql_real_escape_string ($eventdate) . "', '$calc_le','$calc_beddays', '$household_income', '$home_ownership', '$marital_status', '$cancer_hc', '$dementia', '$calc_fhc')";
	
			$result4insert = mysql_query($query4insert) or die("Sorry unable to connect to db " . mysql_error());
				if (!$result4insert) {
				}
				else {
				}
				
			
	} //end else for  if (!$db_selected) {

	} //end else for  if (!$connect) { 
	//} // end for connect and db select
	
			
}
}

mysql_close($connect); 
		
?>

<?php
if($_SERVER['REQUEST_METHOD'] != "POST") {
?>

</div>
<p /> <br />

<?php
} //end the if($_SERVER['REQUEST_METHOD'] != "POST") (just a few lines above this)
?>

<?php 
if($_SERVER['REQUEST_METHOD'] != "POST") {
	include_once("../common/socialmedia2.php");
}
?>

<?php
if($_SERVER['REQUEST_METHOD'] != "POST") {
?>

</div>
<br />
<br />

<?php
} //end the if($_SERVER['REQUEST_METHOD'] != "POST") (just a few lines above)
?>


<?php
if($_SERVER['REQUEST_METHOD'] != "POST") {
require_once('../common/footer_commonk.php');
}
?>

<?php
	require_once('hide_health_care_costs.php');
?>